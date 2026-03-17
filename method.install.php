<?php
if (!defined('CMS_VERSION')) exit;

$db = $this->GetDb();
$dict = NewDataDictionary($db);

$products_table = $this->products_table_name();
$categories_table = $this->categories_table_name();
$product_categories_table = $this->product_categories_table_name();

$this->CreatePermission('Manage Inventory Manager', 'Manage Inventory Manager');

$sqlarray = $dict->CreateTableSQL(
    $products_table,
    '
    product_id I KEY AUTO,
    name C(255) NOTNULL,
    alias C(255) NOTNULL,
    sku C(128) NOTNULL,
    description X2,
    base_cost N(10.2) NOTNULL DEFAULT 0.00,
    created_at I NOTNULL,
    updated_at I NOTNULL
    '
);
$dict->ExecuteSQLArray($sqlarray);

$sqlarray = $dict->CreateTableSQL(
    $categories_table,
    '
    category_id I KEY AUTO,
    name C(255) NOTNULL,
    alias C(255) NOTNULL,
    description X2,
    created_at I NOTNULL,
    updated_at I NOTNULL
    '
);
$dict->ExecuteSQLArray($sqlarray);

$sqlarray = $dict->CreateTableSQL(
    $product_categories_table,
    '
    product_category_id I KEY AUTO,
    product_id I NOTNULL,
    category_id I NOTNULL
    '
);
$dict->ExecuteSQLArray($sqlarray);

$dict->ExecuteSQLArray($dict->CreateIndexSQL('invman_products_alias_uq', $products_table, 'alias', ['UNIQUE']));
$dict->ExecuteSQLArray($dict->CreateIndexSQL('invman_products_sku_uq', $products_table, 'sku', ['UNIQUE']));
$dict->ExecuteSQLArray($dict->CreateIndexSQL('invman_categories_alias_uq', $categories_table, 'alias', ['UNIQUE']));
$dict->ExecuteSQLArray($dict->CreateIndexSQL('invman_prodcat_prod_idx', $product_categories_table, 'product_id'));
$dict->ExecuteSQLArray($dict->CreateIndexSQL('invman_prodcat_cat_idx', $product_categories_table, 'category_id'));
$dict->ExecuteSQLArray($dict->CreateIndexSQL('invman_prodcat_unique', $product_categories_table, 'product_id,category_id', ['UNIQUE']));

$this->Audit(0, $this->GetName(), $this->Lang('audit_installed'));
