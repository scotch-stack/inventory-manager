<?php
if (!defined('CMS_VERSION')) exit;

if (!$this->CheckPermission('Manage Inventory Manager')) {
    echo $this->ShowErrors($this->Lang('error_accessdenied'));
    return;
}

$error_key = xt_param::get_string($params, 'error_key', '');
if ($error_key !== '') {
    echo $this->ShowErrors($this->Lang($error_key));
}

$smarty->assign('form_start', $this->XTCreateFormStart($id, 'save_category', $returnid) . xt_utils::create_csrf_inputs());
$smarty->assign('form_end', $this->CreateFormEnd());
$smarty->assign('title_add_category', $this->Lang('title_add_category'));
$smarty->assign('label_category_name', $this->Lang('label_category_name'));
$smarty->assign('label_category_alias', $this->Lang('label_category_alias'));
$smarty->assign('label_category_description', $this->Lang('label_category_description'));
$smarty->assign('submit_text', $this->Lang('submit'));
$smarty->assign('cancel_text', $this->Lang('cancel'));

echo $this->ProcessTemplate('add_category.tpl');
