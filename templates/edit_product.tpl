<h3>{$title_edit_product|escape}</h3>
{$form_start}
<input type="hidden" name="{$actionid}product_id" value="{$product_id|intval}">
<div class="pageoverflow">
  <p class="pagetext">{$label_product_name|escape}:</p>
  <p class="pageinput"><input type="text" name="{$actionid}name" value="{$product.name|escape}" maxlength="255" size="40"></p>
</div>
<div class="pageoverflow">
  <p class="pagetext">{$label_product_alias|escape}:</p>
  <p class="pageinput"><input type="text" name="{$actionid}alias" value="{$product.alias|escape}" maxlength="255" size="40"></p>
</div>
<div class="pageoverflow">
  <p class="pagetext">{$label_product_sku|escape}:</p>
  <p class="pageinput"><input type="text" name="{$actionid}sku" value="{$product.sku|escape}" maxlength="128" size="30"></p>
</div>
<div class="pageoverflow">
  <p class="pagetext">{$label_product_description|escape}:</p>
  <p class="pageinput"><textarea name="{$actionid}description" rows="5" cols="60">{$product.description|escape}</textarea></p>
</div>
<div class="pageoverflow">
  <p class="pagetext">{$label_product_base_cost|escape}:</p>
  <p class="pageinput"><input type="number" name="{$actionid}base_cost" value="{$product.base_cost|string_format:"%.2f"}" min="0" step="0.01" size="12"></p>
</div>
<div class="pageoverflow">
  <p class="pagetext">{$label_product_categories|escape}:</p>
  <p class="pageinput">
    <select name="{$actionid}category_ids[]" multiple="multiple" size="6">
      {foreach from=$categories item=category}
        <option value="{$category.category_id|intval}"{if $category.category_id|intval|in_array:$assigned_ids} selected="selected"{/if}>{$category.name|escape}</option>
      {/foreach}
    </select>
    {if $categories|@count eq 0}<br>{$text_no_categories|escape}{/if}
  </p>
</div>
<div class="pageoverflow">
  <p class="pagetext">&nbsp;</p>
  <p class="pageinput">
    <input type="submit" name="{$actionid}submit" value="{$submit_text|escape}">
    &nbsp;<input type="submit" name="{$actionid}cancel" value="{$cancel_text|escape}">
  </p>
</div>
{$form_end}
