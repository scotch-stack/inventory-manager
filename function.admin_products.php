<?php
if (!defined('CMS_VERSION')) exit;

$db = $this->GetDb();
$products_table = $this->products_table_name();
$categories_table = $this->categories_table_name();
$product_categories_table = $this->product_categories_table_name();

$products = $db->GetArray(
    "SELECT product_id, name, alias, sku, description, base_cost, created_at
     FROM {$products_table}
     ORDER BY name"
);
if (!is_array($products)) {
    $products = [];
}

$category_map_rows = $db->GetArray(
    "SELECT pc.product_id, c.name AS category_name
     FROM {$product_categories_table} pc
     INNER JOIN {$categories_table} c ON c.category_id = pc.category_id
     ORDER BY c.name"
);
if (!is_array($category_map_rows)) {
    $category_map_rows = [];
}

$product_categories = [];
foreach ($category_map_rows as $row) {
    $product_id = (int) $row['product_id'];
    if (!isset($product_categories[$product_id])) {
        $product_categories[$product_id] = [];
    }
    $product_categories[$product_id][] = (string) $row['category_name'];
}

foreach ($products as &$product) {
    $product_id = (int) $product['product_id'];
    $names = isset($product_categories[$product_id]) ? $product_categories[$product_id] : [];
    $product['category_names'] = implode(', ', $names);
}
unset($product);

$admintheme = cms_utils::get_theme_object();
$add_icon = $admintheme->DisplayImage('icons/system/newobject.gif', $this->Lang('lnk_add_product'), '', '', 'systemicon');
$smarty->assign('addlink', $this->CreateLink($id, 'add_product', $returnid, $add_icon . ' ' . $this->Lang('lnk_add_product')));

$smarty->assign('title_existing_products', $this->Lang('title_existing_products'));
$smarty->assign('col_name', $this->Lang('col_name'));
$smarty->assign('col_alias', $this->Lang('col_alias'));
$smarty->assign('col_sku', $this->Lang('col_sku'));
$smarty->assign('col_description', $this->Lang('col_description'));
$smarty->assign('col_base_cost', $this->Lang('col_base_cost'));
$smarty->assign('col_categories', $this->Lang('col_categories'));
$smarty->assign('text_no_products', $this->Lang('text_no_products'));
$smarty->assign('products', $products);

echo $this->ProcessTemplate('admin_products.tpl');
