<h3>{$title_add_category|escape}</h3>
{$form_start}
<div class="pageoverflow">
  <p class="pagetext">{$label_category_name|escape}:</p>
  <p class="pageinput"><input type="text" name="{$actionid}name" value="" maxlength="255" size="40"></p>
</div>
<div class="pageoverflow">
  <p class="pagetext">{$label_category_alias|escape}:</p>
  <p class="pageinput"><input type="text" name="{$actionid}alias" value="" maxlength="255" size="40"></p>
</div>
<div class="pageoverflow">
  <p class="pagetext">{$label_category_description|escape}:</p>
  <p class="pageinput"><textarea name="{$actionid}description" rows="5" cols="60"></textarea></p>
</div>
<div class="pageoverflow">
  <p class="pagetext">&nbsp;</p>
  <p class="pageinput">
    <input type="submit" value="{$submit_text|escape}">
    &nbsp;{$cancel_link}
  </p>
</div>
{$form_end}
