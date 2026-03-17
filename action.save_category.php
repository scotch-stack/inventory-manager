<?php
if (!defined('CMS_VERSION')) exit;

if (!$this->CheckPermission('Manage Inventory Manager')) {
    echo $this->ShowErrors($this->Lang('error_accessdenied'));
    return;
}

if (!xt_utils::valid_form_csrf()) {
    $this->Redirect($id, 'add_category', '', ['error_key' => 'error_csrf']);
    return;
}

$name = xt_param::get_string($params, 'name', '');
$alias_input = xt_param::get_empty_string($params, 'alias');
$description = xt_param::get_empty_string($params, 'description');

if (trim((string) $name) === '') {
    $this->Redirect($id, 'add_category', '', ['error_key' => 'error_name_required']);
    return;
}

if ($alias_input === null || trim($alias_input) === '') {
    $alias_input = $name;
}

$db = $this->GetDb();
$categories_table = $this->categories_table_name();

$base_alias = InventoryManager::create_alias($alias_input);
$alias = $base_alias;
$counter = 2;

while ((int) $db->GetOne("SELECT COUNT(*) FROM {$categories_table} WHERE alias = ?", [$alias]) > 0) {
    $alias = $base_alias . '-' . $counter;
    $counter++;
}

$saved = $db->Execute(
    "INSERT INTO {$categories_table} (name, alias, description, created_at, updated_at) VALUES (?, ?, ?, ?, ?)",
    [
        $name,
        $alias,
        (string) $description,
        time(),
        time()
    ]
);

if ($saved === false) {
    $this->Redirect($id, 'add_category', '', ['error_key' => 'error_save_failed']);
    return;
}

$this->Audit(0, $this->GetName(), sprintf($this->Lang('audit_category_created'), $name));

$this->Redirect($id, 'defaultadmin', '', [
    'active_tab' => 'categories',
    'message_key' => 'msg_category_saved'
]);
