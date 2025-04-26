<?php
use Bitrix\Main\Localization\Loc,
    Bitrix\Main\EventManager,
    Bitrix\Main\ModuleManager,
	Bitrix\Main\Config\Option,
    Bitrix\Main\Application;

Loc::loadMessages(__FILE__);

class awz_acl extends CModule
{
	var $MODULE_ID = "awz.acl";
	var $MODULE_VERSION;
	var $MODULE_VERSION_DATE;
	var $MODULE_NAME;
	var $MODULE_DESCRIPTION;

    public function __construct()
	{
        $arModuleVersion = array();
        include(__DIR__.'/version.php');

        $dirs = explode('/',dirname(__DIR__ . '../'));
        $this->MODULE_ID = array_pop($dirs);
        unset($dirs);

        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];

        $this->MODULE_NAME = Loc::getMessage("AWZ_ACL_MODULE_NAME");
        $this->MODULE_DESCRIPTION = Loc::getMessage("AWZ_ACL_MODULE_DESCRIPTION");
		$this->PARTNER_NAME = Loc::getMessage("AWZ_PARTNER_NAME");
		$this->PARTNER_URI = "https://zahalski.dev/";

		return true;
	}

    function DoInstall()
    {
        global $APPLICATION, $step;

        $this->InstallFiles();
        $this->InstallDB();
        $this->checkOldInstallTables();
        $this->InstallEvents();
        $this->createAgents();

        ModuleManager::RegisterModule($this->MODULE_ID);

        $filePath = dirname(__DIR__ . '/../../').'/options.php';

        if(file_exists($filePath)){
            LocalRedirect('/bitrix/admin/settings.php?lang='.LANG.'&mid='.$this->MODULE_ID.'&mid_menu=1');
        }

        return true;
    }

    function DoUninstall()
    {
        global $APPLICATION, $step;
		$this->UnInstallDB();
		$this->UnInstallFiles();
		$this->UnInstallEvents();
		$this->deleteAgents();

		ModuleManager::UnRegisterModule($this->MODULE_ID);
		return true;
		
    }

    function InstallDB()
    {
        global $DB, $DBType, $APPLICATION;
        $connection = \Bitrix\Main\Application::getConnection();
        $this->errors = false;
        if(!$this->errors && !$DB->TableExists(implode('_', explode('.',$this->MODULE_ID)).'_permission')) {
            $this->errors = $DB->RunSQLBatch($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/" . $this->MODULE_ID . "/install/db/".$connection->getType()."/access.sql");
        }
        if (!$this->errors) {
            return true;
        } else {
            $APPLICATION->ThrowException(implode("", $this->errors));
            return $this->errors;
        }
    }

    function UnInstallDB()
    {
        global $DB, $DBType, $APPLICATION;
        $connection = \Bitrix\Main\Application::getConnection();
        $this->errors = false;
        if (!$this->errors) {
            $this->errors = $DB->RunSQLBatch($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/" . $this->MODULE_ID . "/install/db/" . $connection->getType() . "/unaccess.sql");
        }
        if (!$this->errors) {
            return true;
        }
        else {
            $APPLICATION->ThrowException(implode("", $this->errors));
            return $this->errors;
        }
    }

    function InstallEvents()
    {
        $eventManager = EventManager::getInstance();
        $eventManager->registerEventHandlerCompatible(
            'main', 'OnAfterUserUpdate',
            $this->MODULE_ID, '\\Awz\\Acl\\Access\\Handlers', 'OnAfterUserUpdate'
        );
        $eventManager->registerEventHandlerCompatible(
            'main', 'OnAfterUserAdd',
            $this->MODULE_ID, '\\Awz\\Acl\\Access\\Handlers', 'OnAfterUserUpdate'
        );
        return true;
    }

    function UnInstallEvents()
    {
        $eventManager = EventManager::getInstance();
        $eventManager->unRegisterEventHandler(
            'sale', 'OnAfterUserUpdate',
            $this->MODULE_ID, '\\Awz\\Acl\\Access\\Handlers', 'OnAfterUserUpdate'
        );
        $eventManager->unRegisterEventHandler(
            'sale', 'OnAfterUserAdd',
            $this->MODULE_ID, '\\Awz\\Acl\\Access\\Handlers', 'OnAfterUserUpdate'
        );
        return true;
    }

    function InstallFiles()
    {
        CopyDirFiles(
            $_SERVER['DOCUMENT_ROOT']."/bitrix/modules/".$this->MODULE_ID."/install/components/acl.config.permissions/",
            $_SERVER['DOCUMENT_ROOT']."/bitrix/components/awz/acl.config.permissions",
            true, true
        );
        return true;
    }

    function UnInstallFiles()
    {
        DeleteDirFilesEx("/bitrix/components/awz/acl.config.permissions");
        return true;
    }

    function createAgents() {
        return true;
    }

    function deleteAgents() {
        return true;
    }

    function checkOldInstallTables()
    {
        return true;
    }

}