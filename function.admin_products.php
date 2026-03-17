<?php
if (!defined('CMS_VERSION')) exit;

$db = $this->GetDb();
$products_table = $this->products_table_name();
$categories_table = $this->categories_table_name();
$product_categories_table = $this->product_categories_table_name();

// --- Persistent filter (MAMS pattern) ---
$pref_key = 'invman_products_filter_category';

if (isset($params['filter_reset'])) {
    cms_userprefs::remove($pref_key);
    $filter_category_id = 0;
} elseif (isset($params['filter'])) {
    $filter_category_id = (int) xt_param::get_string($params, 'filter_category_id', 0);
    cms_userprefs::set($pref_key, (string) $filter_category_id);
} else {
    $saved = cms_userprefs::get($pref_key);
    $filter_category_id = ($saved !== false && $saved !== null && $saved !== '') ? (int) $saved : 0;
}

// Load all categories for the filter dropdown
$all_categories = $db->GetArray(
    "SELECT category_id, name FROM {$categories_table} ORDER BY name"
);
if (!is_array($all_categories)) {
    $all_categories = [];
}

// Build products query, optionally filtered by category
if ($filter_category_id > 0) {
    $products = $db->GetArray(
        "SELECT p.product_id, p.name, p.alias, p.sku, p.description, p.base_cost, p.created_at
         FROM {$products_table} p
         INNER JOIN {$product_categories_table} pc ON pc.product_id = p.product_id
         WHERE pc.category_id = ?
         ORDER BY p.name",
        [$filter_category_id]
    );
} else {
    $products = $db->GetArray(
        "SELECT product_id, name, alias, sku, description, base_cost, created_at
         FROM {$products_table}
         ORDER BY name"
    );
}
if (!is_array($products)) {
    $products = [];
}

$admintheme = cms_utils::get_theme_object();

foreach ($products as &$product) {
    $pid = (int) $product['product_id'];

    $edit_icon = $admintheme->DisplayImage('icons/system/edit.gif', $this->Lang('lnk_edit_product'), '', '', 'systemicon');
    $product['edit_link'] = $this->CreateLink($id, 'edit_product', $returnid, $edit_icon, ['product_id' => $pid]);

    $delete_icon = $admintheme->DisplayImage('icons/system/delete.gif', $this->Lang('lnk_delete_product'), '', '', 'systemicon');
    $product['delete_link'] = $this->CreateLink($id, 'delete_product', $returnid, $delete_icon, ['product_id' => $pid], '', false, false, 'class="invman_delete_product"');

    $copy_icon = $admintheme->DisplayImage('icons/system/copy.gif', $this->Lang('lnk_duplicate_product'), '', '', 'systemicon');
    $product['duplicate_link'] = $this->CreateLink($id, 'duplicate_product', $returnid, $copy_icon, ['product_id' => $pid]);
}
unset($product);

$add_icon = $admintheme->DisplayImage('icons/system/newobject.gif', $this->Lang('lnk_add_product'), '', '', 'systemicon');
$smarty->assign('addlink', $this->CreateLink($id, 'add_product', $returnid, $add_icon . ' ' . $this->Lang('lnk_add_product')));

// Filter form — posts back to defaultadmin; submit named "filter", reset is a plain URL
$smarty->assign('filter_form_start', $this->CreateFormStart($id, 'defaultadmin', $returnid));
$smarty->assign('filter_form_end', $this->CreateFormEnd());
$smarty->assign('filter_category_id', $filter_category_id);
$smarty->assign('all_categories', $all_categories);
$smarty->assign('has_categories', count($all_categories) > 0);
$smarty->assign('active_tab_hidden', $this->CreateInputHidden($id, 'active_tab', 'products'));
$smarty->assign('reset_filter_url', $this->create_url($id, 'defaultadmin', $returnid, ['active_tab' => 'products', 'filter_reset' => '1']));

$smarty->assign('title_existing_products', $this->Lang('title_existing_products'));
$smarty->assign('label_filter_category', $this->Lang('label_filter_category'));
$smarty->assign('lnk_filter', $this->Lang('lnk_filter'));
$smarty->assign('lnk_reset_filter', $this->Lang('lnk_reset_filter'));
$smarty->assign('col_name', $this->Lang('col_name'));
$smarty->assign('col_alias', $this->Lang('col_alias'));
$smarty->assign('col_sku', $this->Lang('col_sku'));
$smarty->assign('col_description', $this->Lang('col_description'));
$smarty->assign('col_base_cost', $this->Lang('col_base_cost'));
$smarty->assign('col_actions', $this->Lang('col_actions'));
$smarty->assign('text_no_products', $this->Lang('text_no_products'));
$smarty->assign('text_no_categories', $this->Lang('text_no_categories'));
$smarty->assign('areyousure_delete_product', $this->Lang('areyousure_delete_product'));
$smarty->assign('products', $products);

echo $this->ProcessTemplate('admin_products.tpl');
