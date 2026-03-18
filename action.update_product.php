<?php
if (!defined('CMS_VERSION')) exit;

if (!$this->CheckPermission('Manage Inventory Manager')) {
    echo $this->ShowErrors($this->Lang('error_accessdenied'));
    return;
}

if (isset($params['cancel'])) {
    $this->Redirect($id, 'defaultadmin', $returnid, ['active_tab' => 'products']);
    return;
}

if (!xt_utils::valid_form_csrf()) {
    $this->Redirect($id, 'defaultadmin', $returnid, ['active_tab' => 'products', 'error_key' => 'error_csrf']);
    return;
}

$product_id = (int) xt_param::get_string($params, 'product_id', 0);
if ($product_id < 1) {
    $this->Redirect($id, 'defaultadmin', $returnid, ['active_tab' => 'products']);
    return;
}

$name = xt_param::get_string($params, 'name', '');
$alias_input = xt_param::get_empty_string($params, 'alias');
$sku = xt_param::get_string($params, 'sku', '');
$description = xt_param::get_empty_string($params, 'description');
$base_cost = xt_param::get_float($params, 'base_cost', 0.0);
$raw_category_ids = xt_param::get_string_array($params, 'category_ids');

if (trim((string) $name) === '') {
    $this->Redirect($id, 'edit_product', $returnid, ['product_id' => $product_id, 'error_key' => 'error_product_name_required']);
    return;
}

if (trim((string) $sku) === '') {
    $this->Redirect($id, 'edit_product', $returnid, ['product_id' => $product_id, 'error_key' => 'error_product_sku_required']);
    return;
}

if ($base_cost < 0) {
    $base_cost = 0.0;
}

if ($alias_input === null || trim($alias_input) === '') {
    $alias_input = $name;
}

$category_ids = [];
if (is_array($raw_category_ids)) {
    foreach ($raw_category_ids as $raw_category_id) {
        $category_id = (int) $raw_category_id;
        if ($category_id > 0) {
            $category_ids[$category_id] = $category_id;
        }
    }
}
$category_ids = array_values($category_ids);

$db = $this->GetDb();
$products_table = $this->products_table_name();
$categories_table = $this->categories_table_name();
$product_categories_table = $this->product_categories_table_name();

$existing = $db->GetRow(
    "SELECT product_id, alias FROM {$products_table} WHERE product_id = ?",
    [$product_id]
);
if (!$existing) {
    $this->Redirect($id, 'defaultadmin', $returnid, ['active_tab' => 'products']);
    return;
}

// Check SKU uniqueness (excluding this product)
if ((int) $db->GetOne("SELECT COUNT(*) FROM {$products_table} WHERE sku = ? AND product_id != ?", [$sku, $product_id]) > 0) {
    $this->Redirect($id, 'edit_product', $returnid, ['product_id' => $product_id, 'error_key' => 'error_product_sku_exists']);
    return;
}

$base_alias = InventoryManager::create_alias($alias_input);
$alias = $base_alias;
$counter = 2;
while ((int) $db->GetOne("SELECT COUNT(*) FROM {$products_table} WHERE alias = ? AND product_id != ?", [$alias, $product_id]) > 0) {
    $alias = $base_alias . '-' . $counter;
    $counter++;
}

$valid_category_ids = [];
if (!empty($category_ids)) {
    $placeholders = implode(',', array_fill(0, count($category_ids), '?'));
    $valid_category_ids = $db->GetCol(
        "SELECT category_id FROM {$categories_table} WHERE category_id IN ({$placeholders})",
        $category_ids
    );
    if (!is_array($valid_category_ids)) {
        $valid_category_ids = [];
    }
}

$db->StartTrans();

$db->Execute(
    "UPDATE {$products_table} SET name = ?, alias = ?, sku = ?, description = ?, base_cost = ?, updated_at = ? WHERE product_id = ?",
    [$name, $alias, $sku, (string) $description, $base_cost, time(), $product_id]
);

$db->Execute("DELETE FROM {$product_categories_table} WHERE product_id = ?", [$product_id]);

foreach ($valid_category_ids as $category_id) {
    $db->Execute(
        "INSERT INTO {$product_categories_table} (product_id, category_id) VALUES (?, ?)",
        [$product_id, (int) $category_id]
    );
}

$db->CompleteTrans();

if ($db->HasFailedTrans()) {
    $this->Redirect($id, 'edit_product', $returnid, ['product_id' => $product_id, 'error_key' => 'error_save_failed']);
    return;
}

$this->Audit(0, $this->GetName(), sprintf($this->Lang('audit_product_updated'), $name));

$this->Redirect($id, 'defaultadmin', $returnid, [
    'active_tab' => 'products',
    'message_key' => 'msg_product_saved'
]);
