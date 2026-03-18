<?php
if (!defined('CMS_VERSION')) exit;

if (!$this->CheckPermission('Manage Inventory Manager')) {
    echo $this->ShowErrors($this->Lang('error_accessdenied'));
    return;
}

$category_id = (int) xt_param::get_string($params, 'category_id', 0);
if ($category_id < 1) {
    $this->Redirect($id, 'defaultadmin', $returnid, ['active_tab' => 'categories']);
    return;
}

$error_key = xt_param::get_string($params, 'error_key', '');
if ($error_key !== '') {
    echo $this->ShowErrors($this->Lang($error_key));
}

$db = $this->GetDb();
$categories_table = $this->categories_table_name();

$category = $db->GetRow(
    "SELECT * FROM {$categories_table} WHERE category_id = ?",
    [$category_id]
);

if (!$category) {
    $this->Redirect($id, 'defaultadmin', $returnid, ['active_tab' => 'categories']);
    return;
}

$smarty->assign('form_start', $this->XTCreateFormStart($id, 'update_category', $returnid) . xt_utils::create_csrf_inputs());
$smarty->assign('form_end', $this->CreateFormEnd());
$smarty->assign('category', $category);
$smarty->assign('category_id', $category_id);
$smarty->assign('title_edit_category', $this->Lang('title_edit_category'));
$smarty->assign('label_category_name', $this->Lang('label_category_name'));
$smarty->assign('label_category_alias', $this->Lang('label_category_alias'));
$smarty->assign('label_category_description', $this->Lang('label_category_description'));
$smarty->assign('submit_text', $this->Lang('submit'));
$smarty->assign('cancel_text', $this->Lang('cancel'));

echo $this->ProcessTemplate('edit_category.tpl');
