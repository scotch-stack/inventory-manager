<?php
if (!defined('CMS_VERSION')) exit;

switch ($oldversion) {
    case '0.1':
        $db = $this->GetDb();
        $dict = NewDataDictionary($db);
        $products_table = $this->products_table_name();
        $sqlarray = $dict->AddColumnSQL($products_table, 'base_cost N(10.2) NOTNULL DEFAULT 0.00');
        $dict->ExecuteSQLArray($sqlarray);
        // fall through
    default:
        break;
}

return true;
