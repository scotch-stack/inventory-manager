<h3>{$title_edit_category|escape}</h3>
{$form_start}
<input type="hidden" name="{$actionid}category_id" value="{$category_id|intval}">
<div class="pageoverflow">
  <p class="pagetext">{$label_category_name|escape}:</p>
  <p class="pageinput"><input type="text" name="{$actionid}name" value="{$category.name|escape}" maxlength="255" size="40"></p>
</div>
<div class="pageoverflow">
  <p class="pagetext">{$label_category_alias|escape}:</p>
  <p class="pageinput"><input type="text" name="{$actionid}alias" value="{$category.alias|escape}" maxlength="255" size="40"></p>
</div>
<div class="pageoverflow">
  <p class="pagetext">{$label_category_description|escape}:</p>
  <p class="pageinput"><textarea name="{$actionid}description" rows="5" cols="60">{$category.description|escape}</textarea></p>
</div>
<div class="pageoverflow">
  <p class="pagetext">&nbsp;</p>
  <p class="pageinput">
    <input type="submit" name="{$actionid}submit" value="{$submit_text|escape}">
    &nbsp;<input type="submit" name="{$actionid}cancel" value="{$cancel_text|escape}">
  </p>
</div>
{$form_end}
