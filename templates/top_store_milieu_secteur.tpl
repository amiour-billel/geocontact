{section name=sec1 loop=$top_rech_secteur}
<div class="featured_inner_container">
	 <div class="home_thumbnails_container_featured">
    
    <a href="?content=map&st={$top_rech_secteur[sec1].id}" target="_blank" title=""><img class="home_thumbnails_pow" 
	src="{if $top_rech_secteur[sec1].image !=''}stores/profile/{$top_rech_secteur[sec1].created_by}/{$top_rech_secteur[sec1].id}/{$top_rech_secteur[sec1].image} {else}css/images/pic.jpg {/if} "  alt="store"></a>
    </div>
	<div class="featured_info">
		<a title="" href="?content=map&st={$top_rech_secteur[sec1].id}" target="_blank" class="text_black">{$top_rech_secteur[sec1].raison}</a>
		<p></p>
		<div class="rating_icon">
			<a class="ratings_popup ajaxlink">
				<!-- <img title="" alt="" src=""> -->
			</a>
	</div>
	</div>
</div>
{/section}