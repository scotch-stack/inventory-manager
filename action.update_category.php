<?php
if (!defined('CMS_VERSION')) exit;

if (!$this->CheckPermission('Manage Inventory Manager')) {
    echo $this->ShowErrors($this->Lang('error_accessdenied'));
    return;
}

if (isset($params['cancel'])) {
    $this->Redirect($id, 'defaultadmin', $returnid, ['active_tab' => 'categories']);
    return;
}

if (!xt_utils::valid_form_csrf()) {
    $this->Redirect($id, 'defaultadmin', $returnid, ['active_tab' => 'categories', 'error_key' => 'error_csrf']);
    return;
}

$category_id = (int) xt_param::get_string($params, 'category_id', 0);
if ($category_id < 1) {
    $this->Redirect($id, 'defaultadmin', $returnid, ['active_tab' => 'categories']);
    return;
}

$name = xt_param::get_string($params, 'name', '');
$alias_input = xt_param::get_empty_string($params, 'alias');
$description = xt_param::get_empty_string($params, 'description');

if (trim((string) $name) === '') {
    $this->Redirect($id, 'edit_category', $returnid, ['category_id' => $category_id, 'error_key' => 'error_name_required']);
    return;
}

if ($alias_input === null || trim($alias_input) === '') {
    $alias_input = $name;
}

$db = $this->GetDb();
$categories_table = $this->categories_table_name();

$existing = $db->GetRow(
    "SELECT category_id FROM {$categories_table} WHERE category_id = ?",
    [$category_id]
);
if (!$existing) {
    $this->Redirect($id, 'defaultadmin', $returnid, ['active_tab' => 'categories']);
    return;
}

$base_alias = InventoryManager::create_alias($alias_input);
$alias = $base_alias;
$counter = 2;

while ((int) $db->GetOne(
    "SELECT COUNT(*) FROM {$categories_table} WHERE alias = ? AND category_id != ?",
    [$alias, $category_id]
) > 0) {
    $alias = $base_alias . '-' . $counter;
    $counter++;
}

$db->Execute(
    "UPDATE {$categories_table} SET name = ?, alias = ?, description = ?, updated_at = ? WHERE category_id = ?",
    [
        $name,
        $alias,
        (string) $description,
        time(),
        $category_id
    ]
);

$this->Audit(0, $this->GetName(), sprintf($this->Lang('audit_category_updated'), $name));

$this->Redirect($id, 'defaultadmin', $returnid, [
    'active_tab' => 'categories',
    'message_key' => 'msg_category_saved'
]);
