<?php
if (!defined('CMS_VERSION')) exit;

$db = $this->GetDb();
$categories_table = $this->categories_table_name();

$categories = $db->GetArray(
    "SELECT category_id, name, alias, description, created_at
     FROM {$categories_table}
     ORDER BY name"
);
if (!is_array($categories)) {
    $categories = [];
}

$admintheme = cms_utils::get_theme_object();
$add_icon = $admintheme->DisplayImage('icons/system/newobject.gif', $this->Lang('lnk_add_category'), '', '', 'systemicon');
$smarty->assign('addlink', $this->CreateLink($id, 'add_category', $returnid, $add_icon . ' ' . $this->Lang('lnk_add_category')));

$smarty->assign('title_existing_categories', $this->Lang('title_existing_categories'));
$smarty->assign('col_name', $this->Lang('col_name'));
$smarty->assign('col_alias', $this->Lang('col_alias'));
$smarty->assign('col_description', $this->Lang('col_description'));
$smarty->assign('text_no_categories', $this->Lang('text_no_categories'));
$smarty->assign('categories', $categories);

echo $this->ProcessTemplate('admin_categories.tpl');
