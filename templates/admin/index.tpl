{section name=sec1 loop=$evt_recent}
 <div class="info_unit solid ">
		<div class="home_thumbnails_container">
		<a href="?content=map&st={$evt_recent[sec1].id}" target="_blank"><img alt="" src="css/images/pic.jpg" style="margin-bottom:0px;" class="home_thumbnails"></a>
		</div>
		<div class="newest_reviews_info">
		<a href="?content=map&st={$evt_recent[sec1].id}" target="_blank"class="text_blue"> {$evt_recent[sec1].evt_name}</a><br>
		<a href="" class="text_blue"> {$evt_recent[sec1].evt_info|truncate:10:"..."}</a><br>
		<div class="rating_icon">
			<a class="ratings_popup ajaxlink">
		
			</a>
		</div>
		</div>
		
		<div class="reviewed_by">
		<span class="small">Creéer par:</span> <a href="" class="text_orange">{$evt_recent[sec1].created_by}</a>
		</div>
    </div>
{/section}