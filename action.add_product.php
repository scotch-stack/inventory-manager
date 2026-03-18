<?php
if (!defined('CMS_VERSION')) exit;

if (!$this->CheckPermission('Manage Inventory Manager')) {
    echo $this->ShowErrors($this->Lang('error_accessdenied'));
    return;
}

$error_key = xt_param::get_string($params, 'error_key', '');
if ($error_key !== '') {
    echo $this->ShowErrors($this->Lang($error_key));
}

$db = $this->GetDb();
$categories_table = $this->categories_table_name();

$categories = $db->GetArray("SELECT category_id, name FROM {$categories_table} ORDER BY name");
if (!is_array($categories)) {
    $categories = [];
}

$smarty->assign('form_start', $this->XTCreateFormStart($id, 'save_product', $returnid) . xt_utils::create_csrf_inputs());
$smarty->assign('form_end', $this->CreateFormEnd());
$smarty->assign('title_add_product', $this->Lang('title_add_product'));
$smarty->assign('label_product_name', $this->Lang('label_product_name'));
$smarty->assign('label_product_alias', $this->Lang('label_product_alias'));
$smarty->assign('label_product_sku', $this->Lang('label_product_sku'));
$smarty->assign('label_product_description', $this->Lang('label_product_description'));
$smarty->assign('label_product_base_cost', $this->Lang('label_product_base_cost'));
$smarty->assign('label_product_categories', $this->Lang('label_product_categories'));
$smarty->assign('submit_text', $this->Lang('submit'));
$smarty->assign('cancel_text', $this->Lang('cancel'));
$smarty->assign('text_no_categories', $this->Lang('text_no_categories'));
$smarty->assign('categories', $categories);

echo $this->ProcessTemplate('add_product.tpl');
