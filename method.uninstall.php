<?php
if (!defined('CMS_VERSION')) exit;

$db = $this->GetDb();
$dict = NewDataDictionary($db);

$dict->ExecuteSQLArray($dict->DropTableSQL($this->product_categories_table_name()));
$dict->ExecuteSQLArray($dict->DropTableSQL($this->products_table_name()));
$dict->ExecuteSQLArray($dict->DropTableSQL($this->categories_table_name()));

$this->RemovePermission('Manage Inventory Manager');
$this->Audit(0, $this->GetName(), $this->Lang('audit_uninstalled'));
