<?php
if (!defined('CMS_VERSION')) exit;

if (!$this->CheckPermission('Manage Inventory Manager')) {
    echo $this->ShowErrors($this->Lang('error_accessdenied'));
    return;
}

$message_key = xt_param::get_string($params, 'message_key', '');
$error_key = xt_param::get_string($params, 'error_key', '');
$active_tab = xt_param::get_string($params, 'active_tab', 'products');
if ($active_tab !== 'categories') {
    $active_tab = 'products';
}

if ($message_key !== '') {
    echo $this->ShowMessage($this->Lang($message_key));
}

if ($error_key !== '') {
    echo $this->ShowErrors($this->Lang($error_key));
}

echo $this->StartTabHeaders();
echo $this->SetTabHeader('products', $this->Lang('products_tab'), $active_tab === 'products');
echo $this->SetTabHeader('categories', $this->Lang('categories_tab'), $active_tab === 'categories');
echo $this->EndTabHeaders();

echo $this->StartTabContent();

echo $this->StartTab('products', $params);
include __DIR__ . DIRECTORY_SEPARATOR . 'function.admin_products.php';
echo $this->EndTab();

echo $this->StartTab('categories', $params);
include __DIR__ . DIRECTORY_SEPARATOR . 'function.admin_categories.php';
echo $this->EndTab();

echo $this->EndTabContent();
