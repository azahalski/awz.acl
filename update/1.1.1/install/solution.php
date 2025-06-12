<?php
$filePath = dirname(__DIR__) . DIRECTORY_SEPARATOR. 'options.php';
if(file_exists($filePath)){
    LocalRedirect('/bitrix/admin/settings.php?lang='.LANG.'&mid='.$this->MODULE_ID);
}