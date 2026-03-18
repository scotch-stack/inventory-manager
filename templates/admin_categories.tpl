<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){
    $('a.invman_delete_category').click(function(ev){
        var self = $(this);
        ev.preventDefault();
        cms_confirm('{$areyousure_delete_category|escape:"javascript"}').done(function(){
            window.location = self.attr('href');
        });
    });
});
//]]>
</script>

<p>{$addlink}</p>

<h3>{$title_existing_categories|escape}</h3>
{if $categories|@count gt 0}
<table class="pagetable">
  <thead>
    <tr>
      <th>{$col_name|escape}</th>
      <th>{$col_alias|escape}</th>
      <th>{$col_description|escape}</th>
      <th class="pageicon">{$col_actions|escape}</th>
    </tr>
  </thead>
  <tbody>
    {foreach from=$categories item=category}
      <tr class="{cycle values='row1,row2'}">
        <td>{$category.name_link}</td>
        <td>{$category.alias|escape}</td>
        <td>{$category.description|escape}</td>
        <td class="pageicon" style="white-space:nowrap;">
          {$category.edit_link}&nbsp;{$category.delete_link}
        </td>
      </tr>
    {/foreach}
  </tbody>
</table>
{else}
<p>{$text_no_categories|escape}</p>
{/if}
