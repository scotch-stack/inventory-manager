<h3>{$title_add_product|escape}</h3>
{$form_start}
<div class="pageoverflow">
  <p class="pagetext">{$label_product_name|escape}:</p>
  <p class="pageinput"><input type="text" name="{$actionid}name" value="" maxlength="255" size="40"></p>
</div>
<div class="pageoverflow">
  <p class="pagetext">{$label_product_alias|escape}:</p>
  <p class="pageinput"><input type="text" name="{$actionid}alias" value="" maxlength="255" size="40"></p>
</div>
<div class="pageoverflow">
  <p class="pagetext">{$label_product_sku|escape}:</p>
  <p class="pageinput"><input type="text" name="{$actionid}sku" value="" maxlength="128" size="30"></p>
</div>
<div class="pageoverflow">
  <p class="pagetext">{$label_product_description|escape}:</p>
  <p class="pageinput"><textarea name="{$actionid}description" rows="5" cols="60"></textarea></p>
</div>
<div class="pageoverflow">
  <p class="pagetext">{$label_product_base_cost|escape}:</p>
  <p class="pageinput"><input type="number" name="{$actionid}base_cost" value="0.00" min="0" step="0.01" size="12"></p>
</div>
<div class="pageoverflow">
  <p class="pagetext">{$label_product_categories|escape}:</p>
  <p class="pageinput">
    <select name="{$actionid}category_ids[]" multiple="multiple" size="6">
      {foreach from=$categories item=category}
        <option value="{$category.category_id|intval}">{$category.name|escape}</option>
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
