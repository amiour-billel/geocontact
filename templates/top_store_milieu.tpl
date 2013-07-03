{if count($all_recherce)==0 }
<div> Pas de résultats </div>
{else}
{section name=sec1 loop=$all_recherce}
<div class="featured_inner_container">
	 <div class="home_thumbnails_container_featured">
    
    <a href="?content=map&st={$all_recherce[sec1].id}" target="_blank" title=""><img class="home_thumbnails_pow" src="css/images/pic.jpg" alt="store"></a>
    </div>
	<div class="featured_info">
		<a title="" href="?content=map&st={$all_recherce[sec1].id}" target="_blank" class="text_black">{$all_recherce[sec1].raison}</a>
		<p></p>
		<div class="rating_icon">
			<a class="ratings_popup ajaxlink">
				<!-- <img title="" alt="" src=""> -->
			</a>
	</div>
	</div>
</div>
{/section}
{/if}