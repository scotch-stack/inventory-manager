<?php
if (!defined('CMS_VERSION')) exit;

$lang['friendlyname'] = 'Inventory Manager';
$lang['moddescription'] = 'Manage products and categories for inventory.';
$lang['help'] = 'Use Categories to define product groupings, then add Products and assign one or more categories.';
$lang['changelog'] = '0.2 - Separate add forms, base cost field.' . "\n" . '0.1 - Initial module with product and category creation.';

$lang['products_tab'] = 'Products';
$lang['categories_tab'] = 'Categories';
$lang['submit'] = 'Save';
$lang['cancel'] = 'Cancel';

$lang['title_add_product'] = 'Add Product';
$lang['title_existing_products'] = 'Existing Products';
$lang['title_add_category'] = 'Add Category';
$lang['title_existing_categories'] = 'Existing Categories';

$lang['lnk_add_product'] = 'Add Product';
$lang['lnk_add_category'] = 'Add Category';

$lang['label_product_name'] = 'Name';
$lang['label_product_alias'] = 'Alias';
$lang['label_product_sku'] = 'SKU';
$lang['label_product_description'] = 'Description';
$lang['label_product_base_cost'] = 'Base Cost';
$lang['label_product_categories'] = 'Categories';

$lang['label_category_name'] = 'Name';
$lang['label_category_alias'] = 'Alias';
$lang['label_category_description'] = 'Description';

$lang['col_name'] = 'Name';
$lang['col_alias'] = 'Alias';
$lang['col_sku'] = 'SKU';
$lang['col_description'] = 'Description';
$lang['col_base_cost'] = 'Base Cost';
$lang['col_categories'] = 'Categories';

$lang['text_no_products'] = 'No products have been created yet.';
$lang['text_no_categories'] = 'No categories have been created yet.';
$lang['frontend_placeholder'] = 'Inventory frontend output is not implemented in version 0.2.';

$lang['msg_product_saved'] = 'Product saved.';
$lang['msg_category_saved'] = 'Category saved.';

$lang['error_accessdenied'] = 'You do not have permission to manage inventory.';
$lang['error_csrf'] = 'Security token validation failed. Please try again.';
$lang['error_name_required'] = 'Name is required.';
$lang['error_product_name_required'] = 'Product name is required.';
$lang['error_product_sku_required'] = 'Product SKU is required.';
$lang['error_product_sku_exists'] = 'That SKU already exists.';
$lang['error_save_failed'] = 'Unable to save. Please try again.';

$lang['audit_installed'] = 'InventoryManager module installed.';
$lang['audit_uninstalled'] = 'InventoryManager module uninstalled.';
$lang['audit_product_created'] = 'Product created: %s';
$lang['audit_category_created'] = 'Category created: %s';

$lang['param_product_id'] = 'Product ID';
$lang['param_category_id'] = 'Category ID';
