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
    $this->Redirect($id, 'add_product', '', ['error_key' => 'error_csrf']);
    return;
}

$name = xt_param::get_string($params, 'name', '');
$alias_input = xt_param::get_empty_string($params, 'alias');
$sku = xt_param::get_string($params, 'sku', '');
$description = xt_param::get_empty_string($params, 'description');
$base_cost = xt_param::get_float($params, 'base_cost', 0.0);
$raw_category_ids = xt_param::get_string_array($params, 'category_ids');

if (trim((string) $name) === '') {
    $this->Redirect($id, 'add_product', '', ['error_key' => 'error_product_name_required']);
    return;
}

if (trim((string) $sku) === '') {
    $this->Redirect($id, 'add_product', '', ['error_key' => 'error_product_sku_required']);
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

$base_alias = InventoryManager::create_alias($alias_input);
$alias = $base_alias;
$counter = 2;

while ((int) $db->GetOne("SELECT COUNT(*) FROM {$products_table} WHERE alias = ?", [$alias]) > 0) {
    $alias = $base_alias . '-' . $counter;
    $counter++;
}

if ((int) $db->GetOne("SELECT COUNT(*) FROM {$products_table} WHERE sku = ?", [$sku]) > 0) {
    $this->Redirect($id, 'add_product', '', ['error_key' => 'error_product_sku_exists']);
    return;
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

$created = $db->Execute(
    "INSERT INTO {$products_table} (name, alias, sku, description, base_cost, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?)",
    [
        $name,
        $alias,
        $sku,
        (string) $description,
        $base_cost,
        time(),
        time()
    ]
);

if ($created === false) {
    $db->FailTrans();
}

$product_id = (int) $db->Insert_ID();
if ($product_id <= 0) {
    $db->FailTrans();
}

foreach ($valid_category_ids as $category_id) {
    $saved_map = $db->Execute(
        "INSERT INTO {$product_categories_table} (product_id, category_id) VALUES (?, ?)",
        [$product_id, (int) $category_id]
    );
    if ($saved_map === false) {
        $db->FailTrans();
    }
}

$db->CompleteTrans();

if ($db->HasFailedTrans()) {
    $this->Redirect($id, 'add_product', '', ['error_key' => 'error_save_failed']);
    return;
}

$this->Audit(0, $this->GetName(), sprintf($this->Lang('audit_product_created'), $name));

$this->Redirect($id, 'defaultadmin', '', [
    'active_tab' => 'products',
    'message_key' => 'msg_product_saved'
]);
