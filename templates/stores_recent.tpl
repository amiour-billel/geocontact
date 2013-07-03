{section name=sec1 loop=$store_recent}
 <div class="info_unit solid ">
		<div class="home_thumbnails_container">
		
	<!--	<a href="?content=map&st={$store_recent[sec1].id}" target="_blank"><img alt="" src="css/images/pic.jpg" style="margin-bottom:0px;" class="home_thumbnails"></a> -->
		<a href="?content=map&st={$store_recent[sec1].id}" target="_blank"><img alt="" 
		src="{if $store_recent[sec1].image !=''}stores/profile/{$store_recent[sec1].created_by}/{$store_recent[sec1].id}/{$store_recent[sec1].image} {else}css/images/pic.jpg {/if} " 
		style="margin-bottom:0px;" class="home_thumbnails"></a>
		</div>
		<div class="newest_reviews_info">
		<a href="?content=map&st={$store_recent[sec1].id}" target="_blank"class="text_blue"> {$store_recent[sec1].raison}</a><br>
		<a href="" class="text_blue"> {$store_recent[sec1].description|truncate:10:"..."}</a><br>
		<div class="rating_icon">
			<a class="ratings_popup ajaxlink">
			
			</a>
		</div>
		</div>
		
		<div class="reviewed_by">
		<span class="small">Creéer par:</span> <a href="" class="text_orange">{$store_recent[sec1].created_by}</a>
		</div>
    </div>
{/section}