<?php
if (!isset($gCms)) exit;

$db = $this->GetDb();
$products_table   = $this->products_table_name();
$categories_table = $this->categories_table_name();
$pc_table         = $this->product_categories_table_name();

// Determine template name
$template_name = (isset($params['template']) && trim($params['template']) !== '')
    ? trim($params['template'])
    : 'default_products';

// Build cache id incorporating all relevant params
$cache_id = '|invman|list|' . md5(serialize($params));

$tpl_ob = $smarty->CreateTemplate(
    'module_file_tpl:InventoryManager;' . $template_name . '.tpl',
    $cache_id, null, $smarty
);

if (!$tpl_ob->IsCached()) {

    // --- Resolve filter ---
    $filter_category_id = isset($params['category_id']) ? (int) $params['category_id'] : 0;
    $filter_category    = isset($params['category'])    ? trim($params['category'])    : '';

    if ($filter_category_id > 0) {
        $rows = $db->GetArray(
            "SELECT p.*
             FROM {$products_table} p
             INNER JOIN {$pc_table} pc ON pc.product_id = p.product_id
             WHERE pc.category_id = ?
             ORDER BY p.name",
            [$filter_category_id]
        );
        $category_name = (string) $db->GetOne(
            "SELECT name FROM {$categories_table} WHERE category_id = ?",
            [$filter_category_id]
        );
    } elseif ($filter_category !== '') {
        $rows = $db->GetArray(
            "SELECT p.*
             FROM {$products_table} p
             INNER JOIN {$pc_table} pc ON pc.product_id = p.product_id
             INNER JOIN {$categories_table} c ON c.category_id = pc.category_id
             WHERE c.name = ?
             ORDER BY p.name",
            [$filter_category]
        );
        $category_name = $filter_category;
    } else {
        $rows = $db->GetArray("SELECT * FROM {$products_table} ORDER BY name");
        $category_name = '';
    }

    if (!is_array($rows)) {
        $rows = [];
    }

    // --- Bulk-load category names for all returned products ---
    $product_ids = [];
    foreach ($rows as $row) {
        $product_ids[] = (int) $row['product_id'];
    }

    $cat_map = [];
    if (!empty($product_ids)) {
        $placeholders = implode(',', array_fill(0, count($product_ids), '?'));
        $cat_rows = $db->GetArray(
            "SELECT pc.product_id, c.name
             FROM {$pc_table} pc
             INNER JOIN {$categories_table} c ON c.category_id = pc.category_id
             WHERE pc.product_id IN ({$placeholders})
             ORDER BY c.name",
            $product_ids
        );
        if (is_array($cat_rows)) {
            foreach ($cat_rows as $cr) {
                $pid = (int) $cr['product_id'];
                if (!isset($cat_map[$pid])) {
                    $cat_map[$pid] = [];
                }
                $cat_map[$pid][] = $cr['name'];
            }
        }
    }

    // --- Build stdClass items ---
    $items = [];
    foreach ($rows as $row) {
        $pid = (int) $row['product_id'];
        $obj = new stdClass();
        $obj->id          = $pid;
        $obj->name        = $row['name'];
        $obj->alias       = $row['alias'];
        $obj->sku         = $row['sku'];
        $obj->description = $row['description'];
        $obj->base_cost   = number_format((float) $row['base_cost'], 2, '.', '');
        $obj->categories  = isset($cat_map[$pid]) ? implode(', ', $cat_map[$pid]) : '';
        $obj->detail_url  = $this->create_url($id, 'detail', $returnid, ['product_id' => $pid]);
        $obj->detail_link = $this->CreateLink($id, 'detail', $returnid, $row['name'],
                                              ['product_id' => $pid]);
        $items[] = $obj;
    }

    $tpl_ob->assign('items',         $items);
    $tpl_ob->assign('itemcount',     count($items));
    $tpl_ob->assign('category_name', $category_name);
}

$tpl_ob->display();
