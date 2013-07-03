{section name=sec1 loop=$secteurs}<div class="category_unit_container">
<div class="category_unit icon_search_name_{$secteurs[sec1].ref}">
    <a href="#" class="text_black" onclick="winrani.update({$secteurs[sec1].id}); return false;" >{$secteurs[sec1].nom}</a> 
</div>
</div>
{/section}