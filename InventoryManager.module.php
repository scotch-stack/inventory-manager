<?php
if (!defined('CMS_VERSION')) exit;

final class InventoryManager extends CMSMSExt
{
    public function __construct()
    {
        static $once = false;
        if ($once) {
            return;
        }
        $once = true;

        parent::__construct();

        global $CMS_INSTALL_PAGE;
        if (isset($CMS_INSTALL_PAGE)) {
            return;
        }
    }

    public function GetName()
    {
        return 'InventoryManager';
    }

    public function GetFriendlyName()
    {
        return $this->Lang('friendlyname');
    }

    public function GetVersion()
    {
        return '0.2';
    }

    public function GetHelp()
    {
        return $this->Lang('help');
    }

    public function GetAuthor()
    {
        return 'John Scotcher with ChatGPT Codex 5.3 and Claude Code';
    }

    public function GetAuthorEmail()
    {
        return '';
    }

    public function GetChangeLog()
    {
        return $this->Lang('changelog');
    }

    public function HasAdmin()
    {
        return true;
    }

    public function LazyLoadAdmin()
    {
        return false;
    }

    public function GetAdminSection()
    {
        return 'ecommerce';
    }

    public function GetAdminDescription()
    {
        return $this->Lang('moddescription');
    }

    public function VisibleToAdminUser()
    {
        return $this->CheckPermission('Manage Inventory Manager');
    }

    public function IsPluginModule()
    {
        return true;
    }

    public function AllowAutoInstall()
    {
        return false;
    }

    public function AllowAutoUpgrade()
    {
        return false;
    }

    public function GetDependencies()
    {
        return ['CMSMSExt' => '1.5.0'];
    }

    public function GetMinimumPhpVersion()
    {
        return '7.4.0';
    }

    public function MinimumCMSVersion()
    {
        return '2.2.14';
    }

    public function SetParameters()
    {
        $this->CreateParameter('product_id', -1, $this->Lang('param_product_id'));
        $this->CreateParameter('category_id', -1, $this->Lang('param_category_id'));
    }

    public function InitializeFrontend()
    {
        $this->RestrictUnknownParams();
        $this->RegisterModulePlugin();
    }

    public static function create_alias($value)
    {
        $value = html_entity_decode((string) $value, ENT_QUOTES, 'UTF-8');
        $value = strtolower(trim($value));
        $value = preg_replace('/[^a-z0-9]+/', '-', $value);
        $value = trim((string) $value, '-');

        if ($value === '') {
            $value = 'item-' . substr(md5(uniqid((string) mt_rand(), true)), 0, 10);
        }

        return $value;
    }

    public function products_table_name()
    {
        return cms_db_prefix() . 'module_inventorymanager_products';
    }

    public function categories_table_name()
    {
        return cms_db_prefix() . 'module_inventorymanager_categories';
    }

    public function product_categories_table_name()
    {
        return cms_db_prefix() . 'module_inventorymanager_product_categories';
    }
}
