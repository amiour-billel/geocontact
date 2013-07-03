<?php
// require_once('../includes/FirePHPCore/FirePHP.class.php');
// $firephp = FirePHP::getInstance(true);
error_reporting('E_PARSE');
 if ($_REQUEST['format'] == 'xml') header('Content-Type: text/xml');  else header('Content-Type: application/json');
require_once '../includes/db.conf.php';
$dbhost  =$dsn['hostspec'];
$dbuser  =$dsn['username'];
$dbpass =$dsn['password'];
$dbname=$dsn['database'];

$dbconnect=mysql_connect($dbhost,$dbuser,$dbpass); 
if($dbconnect==FALSE)	die('Erreur de connexion au serveur!');
$dba=mysql_select_db($dbname);
// PREPARATION DE LA REQUETTE
if (!empty($_REQUEST['id']) && ($_REQUEST['id']!='')){
	$id = $_REQUEST['id'];
}
if (!empty($_REQUEST['keywords']) && ($_REQUEST['keywords']!='' )){
	$keywords =$_REQUEST['keywords'];
	$keywodarry = explode("-", $keywords);
}
if (!empty($_REQUEST['sec']) && ($_REQUEST['sec']!='' )){
	// $secteur_id = $_REQUEST['sec'];
	$secteur_alpha=$_REQUEST['sec'];
	$secteur_alpha = str_replace("_", " ", $secteur_alpha);

}
if (!empty($_REQUEST['cat']) && ($_REQUEST['cat']!='')){
	$categorie_id = $_REQUEST['cat'];
}
if (!empty($_REQUEST['wilaya'])&& ($_REQUEST['wilaya']!="" ) ){
	$wilaya = $_REQUEST['wilaya'];
}
if (!empty($_REQUEST['type']) && ($_REQUEST['type']!="" )){
// $firephp->log($_REQUEST['type']);
	switch ($_REQUEST['type']){
		case 'Store'		:	$evenement = 0;	break;
		case 'Evenement'	: 	$evenement = 1;	break;
		default : 	break;
	}
	// $firephp->log($evenement);
}
if (!empty($_REQUEST['limit']) ){
	$limitation = $_REQUEST['limit'];
}
if (!empty($_REQUEST['lon']) && !empty($_REQUEST['lat'])){
	$lon = $_REQUEST['lon'];
	$lat = $_REQUEST['lat'];
}
$radius=1;
if (!empty($_REQUEST['R']) ){
	 $radius = $_REQUEST['R'];
	
} 
  

$select=' SELECT stores.id, stores.date_maj, stores.date_insert, stores.adresse,stores.image,stores.created_by, stores.wilaya, stores.commune, secteurs.nom as secteur,secteurs.ref as ref, categories.categorie as categorie, stores.total_votes, stores.total_value, stores.raison, stores.lon, stores.lat, stores.description, stores.active,stores.tel,fax, stores.created_by,stores.evenement, stores.evt_name, stores.evt_info, stores.evt_begin,stores.evt_end ';
$from =' FROM  `stores` ';
$join="	LEFT OUTER JOIN  secteurs ON secteurs.id=stores.secteur
	LEFT OUTER JOIN   categories ON categories.id= stores.categorie ";
$where=" WHERE active=0 ";
$groupby="  ";
$ordreby=" ORDER BY `stores`.`raison` ASC  ";
// (isset($_GET['zoom']) && $_GET['zoom']=="bas")? $groupby.=",`parc`,`parc_dest` " : null;
$limit=" LIMIT 0 , 50 ";
if(!empty($id)) $where.=" AND stores.id=".$id."";

// if(isset($secteur_id)) $where.=" AND stores.secteur=".$secteur_id."";
if(isset($secteur_alpha)) $where.=" AND secteurs.nom LIKE '%".$secteur_alpha."%'";
if(isset($wilaya)) $where.=" AND stores.wilaya LIKE '%".$wilaya."%'";
if(!empty($evenement)) $where.=" AND stores.evenement = ".$evenement;
if(isset($categorie_id)) $where.=" AND stores.categorie=".$categorie_id."";
if(isset($keywodarry)){
	
	if (count($keywodarry) >= 1){
		for ($i=0;$i <count($keywodarry);$i++) 
			// $wherekeywods[$i].=" (stores.raison like '%$keywodarry[$i]%' OR stores.wilaya LIKE '%$keywodarry[$i]%' OR stores.commune LIKE '%$keywodarry[$i]%' )";
			$wherekeywods[$i].=" (stores.raison like '%$keywodarry[$i]%' OR  stores.evt_name like '%$keywodarry[$i]%') ";
		}
	}
	
$or_keyword = implode("OR", $wherekeywods);
if ($or_keyword!= "" )$where.=' AND ('.$or_keyword.')';
 // ajouter le rayon de l recherche

if(isset($lon) && isset($lat)  && isset($radius) ){
	$point="POINT($lon $lat)";
	$km = 0.009; 
	// $km = 1.609; 
	$center = "GeomFromText('".$point."')"; 
	$radius = $radius*$km; 
	$bbox = "CONCAT('POLYGON((', 
	X(".$center.") - ".$radius.", ' ', Y(".$center.") - ".$radius.", ',', 
	X(".$center.") + ".$radius.", ' ', Y(".$center.") - ".$radius.", ',', 
	X(".$center.") + ".$radius.", ' ', Y(".$center.") + ".$radius.", ',', 
	X(".$center.") - ".$radius.", ' ', Y(".$center.") + ".$radius.", ',', 
	X(".$center.") - ".$radius.", ' ', Y(".$center.") - ".$radius.", ' 
	))')"; 
	
	$select.=' ,AsText(stores.geom) AS geom, (SQRT(POW( ABS( stores.lon - X('.$center.')), 2) + POW( ABS(stores.lat - Y('.$center.')), 2 )))/0.009 AS distance ';
	$where.=' AND Intersects( GeomFromText(concat(\'POINT(\',stores.lon,\' \',stores.lat,\')\')), GeomFromText('.$bbox.') ) AND SQRT(POW( ABS( stores.lon - X('.$center.')), 2) + POW( ABS(stores.lat - Y('.$center.')), 2 )) < '.$radius.' ';
	// $ordreby.=' distance ';
	
	}
// SELECT id, ( 3959 * ACOS( COS( RADIANS( 37 ) ) * COS( RADIANS( lat ) ) * COS( RADIANS(  `long` ) - RADIANS( -122 ) ) + SIN( RADIANS( 37 ) ) * SIN( RADIANS(  `long` ) ) ) ) AS distance 
// FROM table 
// HAVING distance <(`table.radius`*1609.344) 
// ORDER BY distance 
// LIMIT 0 , 20;


$requette =$select.$from.$join.$where.$groupby.$ordreby.$limit;
// $firephp->log($requette);
// $firephp->log('point de centre',$center);
// $firephp->log('rayaon',$radius);
// $firephp->log('cadre de recherche',$bbox);


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
			$result->id = $row['id'];
			$result->titre = $row['raison'];
			$result ->raison = $row['raison'];
			$result->adresse =  $row['adresse'];	
			$result->wilaya = $row['wilaya'];
			$result->secteur = $row['secteur'];
			$result->categorie = $row['categorie'];
			$result->fax =  $row['fax'];
			$result->tel =  $row['tel'];
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
				$resutatjson[$i]['id']=$row['id'];
				$resutatjson[$i]['titre']=$row['raison'];
				$resutatjson[$i]['raison']= $row['raison'];
				$resutatjson[$i]['adresse']= $row['adresse'];
				$resutatjson[$i]['wilaya']= $row['wilaya'];
				$resutatjson[$i]['secteur']= $row['secteur'];
				$resutatjson[$i]['categorie']= $row['categorie'];
				$resutatjson[$i]['fax']=  $row['fax'];
				$resutatjson[$i]['tel']=  $row['tel'];
				$resutatjson[$i]['lon']=  (float) $row['lon'];
				$resutatjson[$i]['lat']=  (float)  $row['lat'];
				$resutatjson[$i]['description']=   $row['description'];
				$resutatjson[$i]['datemaj']=   $row['date_maj'];
				$resutatjson[$i]['ref']=   $row['ref'];
				$resutatjson[$i]['vote']=  htmlspecialchars (rating_bar($row['id'],''));
				$resutatjson[$i]['evenement']=   $row['evenement'];
				$resutatjson[$i]['evt_begin']=   $row['evt_begin'];
				$images = explode('#',$row['image']);
				$resutatjson[$i]['image']=   $images[0];
				$resutatjson[$i]['createdby']=  $row['created_by'];
				$resutatjson[$i]['evt_end']=   $row['evt_end'];
				$resutatjson[$i]['evt_name']=   $row['evt_name'];
				$resutatjson[$i]['evt_info']=   $row['evt_info'];
				$i++;				
			}
			echo json_encode($resutatjson);
}


?>