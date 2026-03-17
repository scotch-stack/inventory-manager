<p>{$addlink}</p>

<h3>{$title_existing_categories|escape}</h3>
{if $categories|@count gt 0}
<table class="pagetable">
  <thead>
    <tr>
      <th>{$col_name|escape}</th>
      <th>{$col_alias|escape}</th>
      <th>{$col_description|escape}</th>
    </tr>
  </thead>
  <tbody>
    {foreach from=$categories item=category}
      <tr class="{cycle values='row1,row2'}">
        <td>{$category.name|escape}</td>
        <td>{$category.alias|escape}</td>
        <td>{$category.description|escape}</td>
      </tr>
    {/foreach}
  </tbody>
</table>
{else}
<p>{$text_no_categories|escape}</p>
{/if}
