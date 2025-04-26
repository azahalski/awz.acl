<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Loader;
use Bitrix\Main\Application;
use Bitrix\Main\UI\Extension;
use Awz\Acl\Access\AccessController;

Loc::loadMessages(__FILE__);
global $APPLICATION;
$module_id = "awz.acl";
if(!Loader::includeModule($module_id)) return;
Extension::load('ui.sidepanel-content');
$request = Application::getInstance()->getContext()->getRequest();
$APPLICATION->SetTitle(Loc::getMessage('AWZ_ACL_OPT_TITLE'));

if($request->get('IFRAME_TYPE')==='SIDE_SLIDER'){
    require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
    require_once('lib/access/include/moduleright.php');
    CMain::finalActions();
    die();
}

if(!AccessController::isViewSettings())
    $APPLICATION->AuthForm(Loc::getMessage("ACCESS_DENIED"));
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");

if ($request->getRequestMethod()==='POST' && AccessController::isEditSettings() && $request->get('Update'))
{
    //Option::set($module_id, "LOAD_NBRB", $request->get("LOAD_NBRB")=='Y' ? 'Y' : 'N', "");
}

$aTabs = array();

$aTabs[] = array(
    "DIV" => "edit1",
    "TAB" => Loc::getMessage('AWZ_ACL_OPT_SECT1'),
    "ICON" => "vote_settings",
    "TITLE" => Loc::getMessage('AWZ_ACL_OPT_SECT1')
);

$saveUrl = $APPLICATION->GetCurPage(false).'?mid='.htmlspecialcharsbx($module_id).'&lang='.LANGUAGE_ID.'&mid_menu=1';
$tabControl = new CAdminTabControl("tabControl", $aTabs);
$tabControl->Begin();
?>
    <style>.adm-workarea option:checked {background-color: rgb(206, 206, 206);}</style>
    <form method="POST" action="<?=$saveUrl?>" id="FORMACTION">
        <?
        $tabControl->BeginNextTab();
        Extension::load("ui.alerts");
        ?>

        <tr>
            <td style="width:200px;"></td>
            <td>
                <div class="ui-alert ui-alert-primary">
                    <span class="ui-alert-message">
                        <?=Loc::getMessage('AWZ_ACL_OPT_SHOW_DESC')?>
                    </span>
                </div>
            </td>
        </tr>

        <?if(false){?>
            <tr>
                <td style="width:200px;"><?=Loc::getMessage('AWZ_CURRENCY_OPT_LOAD_NBRB_TITLE')?></td>
                <td>
                    <?$val = Option::get($module_id, "LOAD_NBRB", "N","");?>
                    <input type="checkbox" value="Y" name="LOAD_NBRB" <?if ($val=="Y") echo "checked";?>></td>
                </td>
            </tr>
        <?}?>
        <?
        $tabControl->Buttons();
        ?>
        <input <?if (!AccessController::isEditSettings()) echo "disabled" ?> type="submit" class="adm-btn-green" name="Update" value="<?=Loc::getMessage('AWZ_ACL_OPT_L_BTN_SAVE')?>" />
        <input type="hidden" name="Update" value="Y" />
        <?if(AccessController::isViewRight()){?>
            <button class="adm-header-btn adm-security-btn" onclick="BX.SidePanel.Instance.open('<?=$saveUrl?>');return false;">
                <?=Loc::getMessage('AWZ_ACL_OPT_SECT2')?>
            </button>
        <?}?>
        <?$tabControl->End();?>
    </form>
<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");