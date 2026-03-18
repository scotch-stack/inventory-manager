<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){
    $('a.invman_delete_product').click(function(ev){
        var self = $(this);
        ev.preventDefault();
        cms_confirm('{$areyousure_delete_product|escape:"javascript"}').done(function(){
            window.location = self.attr('href');
        });
    });
});
//]]>
</script>

<p>{$addlink}</p>

{* Category filter *}
{$filter_form_start}
{$active_tab_hidden}
<div class="pageoverflow">
  <p class="pagetext">{$label_filter_category|escape}:</p>
  <p class="pageinput">
    <select name="{$actionid}filter_category_id"{if not $has_categories} disabled="disabled"{/if}>
      <option value="0">{if $has_categories}&mdash; {$label_filter_category|escape} &mdash;{else}({$text_no_categories|escape}){/if}</option>
      {foreach from=$all_categories item=cat}
        <option value="{$cat.category_id|intval}"{if $filter_category_id eq $cat.category_id} selected="selected"{/if}>{$cat.name|escape}</option>
      {/foreach}
    </select>
    &nbsp;<input type="submit" name="{$actionid}filter" value="{$lnk_filter|escape}"{if not $has_categories} disabled="disabled"{/if}>
    {if $filter_category_id gt 0}&nbsp;<a href="{$reset_filter_url}">{$lnk_reset_filter|escape}</a>{/if}
  </p>
</div>
{$filter_form_end}

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
      <th class="pageicon">{$col_actions|escape}</th>
    </tr>
  </thead>
  <tbody>
    {foreach from=$products item=product}
      <tr class="{cycle values='row1,row2'}">
        <td>{$product.name_link}</td>
        <td>{$product.alias|escape}</td>
        <td>{$product.sku|escape}</td>
        <td>{$product.base_cost|string_format:"%.2f"|escape}</td>
        <td>{$product.description|escape}</td>
        <td class="pageicon" style="white-space:nowrap;">
          {$product.edit_link}&nbsp;{$product.duplicate_link}&nbsp;{$product.delete_link}
        </td>
      </tr>
    {/foreach}
  </tbody>
</table>
{else}
<p>{$text_no_products|escape}</p>
{/if}
