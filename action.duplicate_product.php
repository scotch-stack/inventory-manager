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
$categories_table = $this->categories_table_name();
$product_categories_table = $this->product_categories_table_name();

$source = $db->GetRow(
    "SELECT * FROM {$products_table} WHERE product_id = ?",
    [$product_id]
);

if (!$source) {
    $this->Redirect($id, 'defaultadmin', $returnid, ['active_tab' => 'products']);
    return;
}

$new_name = $source['name'] . ' (copy)';

$base_alias = InventoryManager::create_alias($source['alias'] . '-copy');
$alias = $base_alias;
$counter = 2;
while ((int) $db->GetOne("SELECT COUNT(*) FROM {$products_table} WHERE alias = ?", [$alias]) > 0) {
    $alias = $base_alias . '-' . $counter;
    $counter++;
}

$base_sku = $source['sku'] . '-copy';
$sku = $base_sku;
$sku_counter = 2;
while ((int) $db->GetOne("SELECT COUNT(*) FROM {$products_table} WHERE sku = ?", [$sku]) > 0) {
    $sku = $base_sku . '-' . $sku_counter;
    $sku_counter++;
}

$source_category_ids = $db->GetCol(
    "SELECT category_id FROM {$product_categories_table} WHERE product_id = ?",
    [$product_id]
);
if (!is_array($source_category_ids)) {
    $source_category_ids = [];
}

$db->StartTrans();

$db->Execute(
    "INSERT INTO {$products_table} (name, alias, sku, description, base_cost, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?)",
    [$new_name, $alias, $sku, $source['description'], $source['base_cost'], time(), time()]
);

$new_product_id = (int) $db->Insert_ID();
if ($new_product_id < 1) {
    $db->FailTrans();
}

foreach ($source_category_ids as $cat_id) {
    $db->Execute(
        "INSERT INTO {$product_categories_table} (product_id, category_id) VALUES (?, ?)",
        [$new_product_id, (int) $cat_id]
    );
}

$db->CompleteTrans();

if ($db->HasFailedTrans()) {
    $this->Redirect($id, 'defaultadmin', $returnid, ['active_tab' => 'products', 'error_key' => 'error_save_failed']);
    return;
}

$this->Audit(0, $this->GetName(), sprintf($this->Lang('audit_product_duplicated'), $source['name']));

$this->Redirect($id, 'edit_product', $returnid, ['product_id' => $new_product_id]);
