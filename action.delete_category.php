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

$db = $this->GetDb();
$categories_table = $this->categories_table_name();
$product_categories_table = $this->product_categories_table_name();

$category = $db->GetRow(
    "SELECT name FROM {$categories_table} WHERE category_id = ?",
    [$category_id]
);

if (!$category) {
    $this->Redirect($id, 'defaultadmin', $returnid, ['active_tab' => 'categories']);
    return;
}

$db->StartTrans();

$db->Execute(
    "DELETE FROM {$product_categories_table} WHERE category_id = ?",
    [$category_id]
);

$db->Execute(
    "DELETE FROM {$categories_table} WHERE category_id = ?",
    [$category_id]
);

$db->CompleteTrans();

if ($db->HasFailedTrans()) {
    $this->Redirect($id, 'defaultadmin', $returnid, ['active_tab' => 'categories', 'error_key' => 'error_save_failed']);
    return;
}

$this->Audit(0, $this->GetName(), sprintf($this->Lang('audit_category_deleted'), $category['name']));

$this->Redirect($id, 'defaultadmin', $returnid, ['active_tab' => 'categories']);
