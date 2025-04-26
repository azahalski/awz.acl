# Пример создания ролевых прав доступа для модулей CMS Bitrix
<!-- ex3-start -->

## 1. Устанавливаем модуль [awz.admin](https://github.com/azahalski/awz.admin)

* поддерживаются только модули содержащие в названии директории точку, например, `awz.acl`

## 2. Генерируем права доступа

### 2.1. Переходим в генератор прав доступа и выбираем директорию с нашим модулем (awz.acl)

`Настройки` -> `AWZ: Конструктор списков` -> `Генератор прав доступа`

![](https://zahalski.dev/images/modules/awz.admin/right/001.png)

### 2.2. Добавляем разделы прав доступа

Например, `Кабинет дилера` код: `LK`

![](https://zahalski.dev/images/modules/awz.admin/right/008.png)

### 2.3. Добавляем правила прав доступа

| Параметр           | Пример                     | Описание                                                                              |
|--------------------|----------------------------|---------------------------------------------------------------------------------------|
| Константа          | DILER_LK_VIEW              | Большие латинские буквы                                                               |
| Значение           | 5                          | Любое уникальное в рамках модуля int                                                  |
| Правило            | lkview                     | Название класса с логикой проверки <br> будет сгенерирован в \lib\access\custom\rules |
| Название настройки | Просмотр главной кабинета  | Значение для языковой переменной                                                      |

![](https://zahalski.dev/images/modules/awz.admin/right/009.png)

### 2.4 Создаем роль и сохраняем права

![](https://zahalski.dev/images/modules/awz.admin/right/010.png)

## 3. проверка прав доступа

### Добавление дополнительной логики в класс доступа

/bitrix/modules/awz.acl/lib/access/custom/rules/lkview.php

Стандартный класс, сгенерированный модулем уже проверяет по первому добавленному (пункт 2.3)

```php
class Lkview extends \Bitrix\Main\Access\Rule\AbstractRule
{
    public function execute(AccessibleItem $item = null, $params = null): bool
    {
        if ($this->user->isAdmin() && !Helper::ADMIN_DECLINE)
        {
            return true;
        }
        if ($this->user->getPermission(PermissionDictionary::VIEW_EUR))
        {
            return true;
        }
        return false;
    }
}
```

Добавим проверку по ид элемента

```php
class Viewcurrency extends \Bitrix\Main\Access\Rule\AbstractRule
{
    public function execute(AccessibleItem $item = null, $params = null): bool
    {
        if ($this->user->isAdmin() && !Helper::ADMIN_DECLINE)
            return true;
        if ($this->user->getPermission(PermissionDictionary::VIEW_ALL))
            return true;
        if($item == 1 && $params == 'test') return true;
        return false;
    }
}
```

```php
use Awz\Acl\Access\AccessController;
use Awz\Acl\Access\Custom\ActionDictionary;
if(AccessController::can(0,ActionDictionary::ACTION_DILER_LK_VIEW, 1, "test"){
//проверка пройдена
}
if(!AccessController::can(0,ActionDictionary::ACTION_DILER_LK_VIEW, 1, "неизвестный параметр"){
//проверка не пройдена
}
```

### AccessController::can

| Параметр | Описание                                                                 |
|----------|--------------------------------------------------------------------------|
| userId   | Ид пользователя, если не задан то текущий                                |
| action   | Константа прав доступа, например ActionDictionary::ACTION_DILER_LK_VIEW  |
| itemId   | null или ид элемента для проверки прав                                   |
| params   | null или дополнительные параметры для проверки                           |

```php
use Awz\Acl\Access\AccessController;
use Awz\Acl\Access\Custom\ActionDictionary;
if(\Bitrix\Main\Loader::includeModule('awz.currency')){

	$res = \Awz\Currency\CursTable::getCurs(date('d.m.Y'));
	echo 'count all: '.count($res)."\n";
	foreach($res as $code=>$value){
        if(AccessController::can(0,ActionDictionary::ACTION_VIEW_ALL, false, $code)){
            echo $code.' - '.$value['AMOUNT']."\n";
        }
	}

}
### Результат выполнения команды
### count all: 4
### USD - 99.4215
```

### AccessController::isViewSettings

Проверяет есть ли доступ к редактированию настроек модуля

```php
use Bitrix\Main\Loader;
use Awz\Acl\Access\AccessController;
$module_id = "awz.acl";
Loader::includeModule($module_id);
if(!AccessController::isViewSettings())
    $APPLICATION->AuthForm(Loc::getMessage("ACCESS_DENIED"));
```

### AccessController::isViewRight

Проверяет есть ли доступ к редактированию прав доступа к модулю

<!-- ex3-end -->