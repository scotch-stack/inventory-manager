<?php
if (!defined('CMS_VERSION')) exit;

if (!$this->CheckPermission('Manage Inventory Manager')) {
    echo $this->ShowErrors($this->Lang('error_accessdenied'));
    return;
}

$product_id = (int) xt_param::get_string($params, 'product_id', 0);
if ($product_id < 1) {
    $this->Redirect($id, 'defaultadmin', $returnid, ['active_tab' => 'products']);
    return;
}

$error_key = xt_param::get_string($params, 'error_key', '');
if ($error_key !== '') {
    echo $this->ShowErrors($this->Lang($error_key));
}

$db = $this->GetDb();
$products_table = $this->products_table_name();
$categories_table = $this->categories_table_name();
$product_categories_table = $this->product_categories_table_name();

$product = $db->GetRow(
    "SELECT * FROM {$products_table} WHERE product_id = ?",
    [$product_id]
);

if (!$product) {
    $this->Redirect($id, 'defaultadmin', $returnid, ['active_tab' => 'products']);
    return;
}

$categories = $db->GetArray(
    "SELECT category_id, name FROM {$categories_table} ORDER BY name"
);
if (!is_array($categories)) {
    $categories = [];
}

$assigned_ids = $db->GetCol(
    "SELECT category_id FROM {$product_categories_table} WHERE product_id = ?",
    [$product_id]
);
if (!is_array($assigned_ids)) {
    $assigned_ids = [];
}
$assigned_ids = array_map('intval', $assigned_ids);

$smarty->assign('form_start', $this->XTCreateFormStart($id, 'update_product', $returnid) . xt_utils::create_csrf_inputs());
$smarty->assign('form_end', $this->CreateFormEnd());
$smarty->assign('product', $product);
$smarty->assign('product_id', $product_id);
$smarty->assign('categories', $categories);
$smarty->assign('assigned_ids', $assigned_ids);
$smarty->assign('title_edit_product', $this->Lang('title_edit_product'));
$smarty->assign('label_product_name', $this->Lang('label_product_name'));
$smarty->assign('label_product_alias', $this->Lang('label_product_alias'));
$smarty->assign('label_product_sku', $this->Lang('label_product_sku'));
$smarty->assign('label_product_description', $this->Lang('label_product_description'));
$smarty->assign('label_product_base_cost', $this->Lang('label_product_base_cost'));
$smarty->assign('label_product_categories', $this->Lang('label_product_categories'));
$smarty->assign('submit_text', $this->Lang('submit'));
$smarty->assign('cancel_link', $this->CreateLink($id, 'defaultadmin', $returnid, $this->Lang('cancel'), ['active_tab' => 'products']));
$smarty->assign('text_no_categories', $this->Lang('text_no_categories'));

echo $this->ProcessTemplate('edit_product.tpl');
