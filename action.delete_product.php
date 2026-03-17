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

$db = $this->GetDb();
$products_table = $this->products_table_name();
$product_categories_table = $this->product_categories_table_name();

$product = $db->GetRow(
    "SELECT product_id, name FROM {$products_table} WHERE product_id = ?",
    [$product_id]
);

if (!$product) {
    $this->Redirect($id, 'defaultadmin', $returnid, ['active_tab' => 'products']);
    return;
}

$db->StartTrans();
$db->Execute("DELETE FROM {$product_categories_table} WHERE product_id = ?", [$product_id]);
$db->Execute("DELETE FROM {$products_table} WHERE product_id = ?", [$product_id]);
$db->CompleteTrans();

if (!$db->HasFailedTrans()) {
    $this->Audit(0, $this->GetName(), sprintf($this->Lang('audit_product_deleted'), $product['name']));
}

$this->Redirect($id, 'defaultadmin', $returnid, ['active_tab' => 'products']);
