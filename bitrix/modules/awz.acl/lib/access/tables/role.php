<?php
namespace Awz\Acl\Access\Tables;

use Bitrix\Main\Access\Role\AccessRoleTable;

class RoleTable extends AccessRoleTable
{
    public static function getTableName()
    {
        return 'awz_acl_role';
    }
}