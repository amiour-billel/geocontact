<?php
if ($_REQUEST['format'] == 'xml') header('Content-Type: text/xml');
require_once '../includes/db.conf.php';
$dbhost  =$dsn['hostspec'];
$dbuser  =$dsn['username'];
$dbpass =$dsn['password'];
$dbname=$dsn['database'];

$dbconnect=mysql_connect($dbhost,$dbuser,$dbpass); 
if($dbconnect==FALSE)	die('Erreur de connexion au serveur!');
$dba=mysql_select_db($dbname);
// PREPARATION DE LA REQUETTE
if (!empty($_REQUEST['keywords']) ){
	$keywords =$_REQUEST['keywords'];
	$keywodarry = explode("-", $keywords);
}
if (!empty($_REQUEST['sec']) ){
	$secteur_id = $_REQUEST['sec'];
}
if (!empty($_REQUEST['cat']) ){
	$categorie_id = $_REQUEST['cat'];
}
if (!empty($_REQUEST['lon']) && !empty($_REQUEST['lat'])){
	$lon = $_REQUEST['lon'];
	$lat = $_REQUEST['lat'];
}
if (!empty($_REQUEST['R']) ){
	$radius = $_REQUEST['R'];
} else $radius =2;
  

$select=' SELECT stores.id, stores.date_maj, stores.date_insert, stores.adresse, stores.wilaya, stores.commune, secteurs.nom as secteur, categories.categorie as categorie, stores.note, stores.counternote, stores.raison, stores.lon, stores.lat, stores.description, stores.active, stores.created_by  ';
$from =' FROM  `stores` ';
$join="	LEFT OUTER JOIN  secteurs ON secteurs.id=stores.secteur
	LEFT OUTER JOIN   categories ON categories.id= stores.categorie ";
$where=" WHERE active=0 ";
$groupby="  ";
$ordreby=" ORDER BY `stores`.`raison` ASC  ";
// (isset($_GET['zoom']) && $_GET['zoom']=="bas")? $groupby.=",`parc`,`parc_dest` " : null;
$limit=" LIMIT 0 , 100 ";

if(isset($secteur_id)) $where.=" AND stores.secteur=".$secteur_id."";
if(isset($categorie_id)) $where.=" AND stores.categorie=".$categorie_id."";
if(isset($keywodarry)){
	
	if (count($keywodarry) >= 1){
		for ($i=0;$i <count($keywodarry);$i++) 
			$wherekeywods[$i].=" (stores.raison like '%$keywodarry[$i]%' OR stores.wilaya LIKE '%$keywodarry[$i]%' OR stores.commune LIKE '%$keywodarry[$i]%' )";
		}
	}
	
$or_keyword = implode("OR", $wherekeywods);
if ($or_keyword!= "" )$where.=' AND ('.$or_keyword.')';
 // ajouter le ryon de l recherche
 
if(isset($lon) && isset($lat) ){
	$point="POINT($lon $lat)";
	$km = 0.009; 
	$center = "GeomFromText('".$point."')"; 
	$radius = $radius*$km; 
	$bbox = "CONCAT('POLYGON((', 
	X(".$center.") - ".$radius.", ' ', Y(".$center.") - ".$radius.", ',', 
	X(".$center.") + ".$radius.", ' ', Y(".$center.") - ".$radius.", ',', 
	X(".$center.") + ".$radius.", ' ', Y(".$center.") + ".$radius.", ',', 
	X(".$center.") - ".$radius.", ' ', Y(".$center.") + ".$radius.", ',', 
	X(".$center.") - ".$radius.", ' ', Y(".$center.") - ".$radius.", ' 
	))')"; 
	$select.=' ,AsText(stores.geom) AS geom, (SQRT(POW( ABS( X(stores.geom) - X({'.$center.'})), 2) + POW( ABS(Y(stores.geom) - Y({'.$center.'})), 2 )))/0.009 AS distance ';
	$where.=' AND Intersects( geom, GeomFromText('.$bbox.') ) AND SQRT(POW( ABS( X(stores.geom) - X({'.$center.'})), 2) + POW( ABS(Y(stores.geom) - Y({'.$center.'})), 2 )) < '.$radius.' ';
	// $ordreby.=' distance ';
	
	}

$requette =$select.$from.$join.$where.$groupby.$ordreby.$limit;

// INTEROGATION D LA BASE DE DONN2EE

$res=mysql_query($requette);


// RECUPERATION DES RESULTATS FORMAT XML
if (!$res) {
			if ($_REQUEST['format'] == 'xml'){
$xmlstr = <<<XML
	<resultsSet>
		<noresult></noresult>
	</resultsSet>
XML;
$xml = new SimpleXMLElement($xmlstr);
echo $xml->asXML();
} else {
		$resutatjson[0]='none';
		echo json_encode($resutatjson);
		}
			return false;
		}

if ($_REQUEST['format'] == 'xml')
{
	$xmlstr = <<<XML
		<resultsSet>		
		</resultsSet>
XML;
	$xml = new SimpleXMLElement($xmlstr);

while ($row =  mysql_fetch_assoc ($res))
	{
		
			$result = $xml->addChild('result');
			$result->titre = $row['raison'];
			$result ->raison = $row['raison'];
			$result->adresse =  $row['adresse'];	
			$result->wilaya = $row['wilaya'];
			$result->secteur = $row['secteur'];
			$result->categorie = $row['categorie'];
			$result->fax = '/';
			$result->tel = '/';
			$result->lon = $row['lon'];
			$result->lat= $row['lat'];
			$result->description= $row['description'];
			$result->datemaj= $row['date_maj'];
			
	}
	
	
echo $xml->asXML();


}elseif ($_GET['format'] == 'json'){
$i=0;
while ($row =  mysql_fetch_assoc ($res))
	{
				$resutatjson[$i]['titre']=$row['raison'];
				$resutatjson[$i]['raison']= $row['raison'];
				$resutatjson[$i]['adresse']= $row['adresse'];
				$resutatjson[$i]['wilaya']= $row['wilaya'];
				$resutatjson[$i]['secteur']= $row['secteur'];
				$resutatjson[$i]['categorie']= $row['categorie'];
				$resutatjson[$i]['fax']=  '/';
				$resutatjson[$i]['tel']=  '/';
				$resutatjson[$i]['lon']=  (float) $row['lon'];
				$resutatjson[$i]['lat']=  (float)  $row['lat'];
				$resutatjson[$i]['description']=   $row['description'];
				$resutatjson[$i]['datemaj']=   $row['date_maj'];
				$i++;				
			}
			echo json_encode($resutatjson);
}


?>