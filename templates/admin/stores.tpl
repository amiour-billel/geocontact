<form id="mainform" action="">
	<table border="0" width="100%" cellpadding="0" cellspacing="0" id="product-table">
				<tr>
					<th class="table-header-check"><a id="toggle-all" ></a> </th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">Raison</a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">Email</a></th>
					<th class="table-header-repeat line-left"><a href="">wilaya</a></th>
					<th class="table-header-repeat line-left"><a href="">Propriétaire</a></th>
					<th class="table-header-repeat line-left"><a href="">Site web</a></th>
					<th class="table-header-options line-left"><a href="">Options</a></th>
				</tr>

	{section name=str loop=$stores}
		<tr>
			<td><input  type="checkbox"/></td>
			<td>{$stores[str].raison}</td>
			<td><a href="">{$stores[str].email}</a></td>
			<td>{$stores[str].wilaya}</td>
			<td>{$stores[str].created_by}</td>
			<td><a href="">site</a></td>
			<td class="options-width">
			<a href="?content=admin&s=edit&id={$stores[str].id}" target="_blank" title="Edit" class="icon-1 info-tooltip"></a>
			<a href="?content=admin&s=del&id={$stores[str].id}" target="_blank"  title="Sup" class="icon-2 info-tooltip"></a>
			<a href="?content=admin&s=fav&id={$stores[str].id}" target="_blank"  title="Favoré" class="icon-3 info-tooltip"></a>
		<!--	<a href="?content=admin&s=pan&id={$stores[str].id}" target="_blank"  title="Pannnier" class="icon-4 info-tooltip"></a> -->
			<a href="?content=admin&s=val&id={$stores[str].id}" target="_blank" title="Valider" class="icon-5 info-tooltip"></a>
			</td>
		</tr>
	{/section}
	</table>
</form>