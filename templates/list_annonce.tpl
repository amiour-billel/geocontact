{section name=sec1 loop=$store_recent}
<div class="events_inner_container">
    <div class="home_thumbnails_container_events">
      <a href=""><img class="home_thumbnails_events" src="css/images/pic.jpg" alt=""></a>
    </div>
    <div class="events_info">
    <a class="text_orange" href="">{$store_recent[sec1].raison}</a><br>
       {$store_recent[sec1].description|truncate:3:"..."}<br>
   	</div>
</div>
{/section}