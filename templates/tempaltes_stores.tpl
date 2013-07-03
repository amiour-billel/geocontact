{section name=sec1 loop=$top_medecal}
<div class="popular_location">
	{if  $top_medecal[sec1].raison != ''}
<a title="{$top_medecal[sec1].raison}" href="?content=map&st={$top_medecal[sec1].id}" target="_blank"  style="font-size:12px;" class="">
		{html_entity_decode($top_medecal[sec1].raison)}
		</a>
{else}
<a title="{$top_medecal[sec1].raison}" href="?content=map&st={$top_medecal[sec1].id}"  target="_blank"  style="font-size:12px;" class="">
		Stores
</a>
{/if}	
</div>
{/section}



