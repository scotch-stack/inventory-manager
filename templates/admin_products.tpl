<p>{$addlink}</p>

<h3>{$title_existing_products|escape}</h3>
{if $products|@count gt 0}
<table class="pagetable">
  <thead>
    <tr>
      <th>{$col_name|escape}</th>
      <th>{$col_alias|escape}</th>
      <th>{$col_sku|escape}</th>
      <th>{$col_base_cost|escape}</th>
      <th>{$col_description|escape}</th>
      <th>{$col_categories|escape}</th>
    </tr>
  </thead>
  <tbody>
    {foreach from=$products item=product}
      <tr class="{cycle values='row1,row2'}">
        <td>{$product.name|escape}</td>
        <td>{$product.alias|escape}</td>
        <td>{$product.sku|escape}</td>
        <td>{$product.base_cost|string_format:"%.2f"|escape}</td>
        <td>{$product.description|escape}</td>
        <td>{if $product.category_names neq ''}{$product.category_names|escape}{else}-{/if}</td>
      </tr>
    {/foreach}
  </tbody>
</table>
{else}
<p>{$text_no_products|escape}</p>
{/if}
