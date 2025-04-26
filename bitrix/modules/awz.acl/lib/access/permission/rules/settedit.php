<?php
namespace Awz\Acl\Access\Permission\Rules;

use Bitrix\Main\Access\AccessibleItem;
use Awz\Acl\Access\Custom\PermissionDictionary;

class SettEdit extends \Bitrix\Main\Access\Rule\AbstractRule
{
    public function execute(AccessibleItem $item = null, $params = null): bool
    {
        if ($this->user->isAdmin())
        {
            return true;
        }
        if ($this->user->getPermission(PermissionDictionary::MODULE_SETT_EDIT))
        {
            return true;
        }
        return false;
    }
}