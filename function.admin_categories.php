<?php
if (!defined('CMS_VERSION')) exit;

$db = $this->GetDb();
$categories_table = $this->categories_table_name();

$error_key = xt_param::get_string($params, 'error_key', '');
if ($error_key !== '') {
    echo $this->ShowErrors($this->Lang($error_key));
}

$categories = $db->GetArray(
    "SELECT category_id, name, alias, description, created_at
     FROM {$categories_table}
     ORDER BY name"
);
if (!is_array($categories)) {
    $categories = [];
}

$admintheme = cms_utils::get_theme_object();

foreach ($categories as &$category) {
    $cid = (int) $category['category_id'];

    $category['name_link'] = $this->CreateLink($id, 'edit_category', $returnid, $category['name'], ['category_id' => $cid]);

    $edit_icon = $admintheme->DisplayImage('icons/system/edit.gif', $this->Lang('lnk_edit_category'), '', '', 'systemicon');
    $category['edit_link'] = $this->CreateLink($id, 'edit_category', $returnid, $edit_icon, ['category_id' => $cid]);

    $delete_icon = $admintheme->DisplayImage('icons/system/delete.gif', $this->Lang('lnk_delete_category'), '', '', 'systemicon');
    $category['delete_link'] = $this->CreateLink($id, 'delete_category', $returnid, $delete_icon, ['category_id' => $cid], '', false, false, 'class="invman_delete_category"');
}
unset($category);

$add_icon = $admintheme->DisplayImage('icons/system/newobject.gif', $this->Lang('lnk_add_category'), '', '', 'systemicon');
$smarty->assign('addlink', $this->CreateLink($id, 'add_category', $returnid, $add_icon . ' ' . $this->Lang('lnk_add_category')));

$smarty->assign('title_existing_categories', $this->Lang('title_existing_categories'));
$smarty->assign('col_name', $this->Lang('col_name'));
$smarty->assign('col_alias', $this->Lang('col_alias'));
$smarty->assign('col_description', $this->Lang('col_description'));
$smarty->assign('col_actions', $this->Lang('col_actions'));
$smarty->assign('text_no_categories', $this->Lang('text_no_categories'));
$smarty->assign('areyousure_delete_category', $this->Lang('areyousure_delete_category'));
$smarty->assign('categories', $categories);

echo $this->ProcessTemplate('admin_categories.tpl');
