# Архитектура модуля
<!-- arch-start -->

## 1. Структура классов

### Awz\Acl\Access

| Класс            | Описание                                                      |
|------------------|---------------------------------------------------------------|
| Handlers         | Содержит обработчии (например расчет кодов прав)              |
| AccessController | Контроллер для проверки прав доступа в своих модулях          |

### Awz\Acl\Access\Tables

Таблицы в базе данных

| Класс             | Описание |
|-------------------|----------|
| PermissionTable   |          |
| RoleTable         |          |
| RoleRelationTable |          |

### Awz\Acl\Access\Entity

| Класс   | Описание |
|---------|----------|
| User    |          |

### Awz\Acl\Access\Component

| Класс                 | Описание |
|-----------------------|----------|
| ConfigPermissions     |          |

### Awz\Acl\Access\EntitySelectors

| Класс | Описание |
|-------|----------|
| Group |          |
| User  |          |

### Awz\Acl\Access\Model

| Класс     | Описание |
|-----------|----------|
| BaseModel |          |
| UserModel |          |

### Awz\Acl\Access\Permission

| Класс                | Описание |
|----------------------|----------|
| ActionDictionary     |          |
| PermissionDictionary |          |
| RoleDictionary       |          |
| RoleUtil             |          |
| RuleFactory          |          |

### Awz\Acl\Access\Permission\Rules

| Класс                  | Описание |
|------------------------|----------|
| RightEdit              |          |
| RightView              |          |
| SettEdit               |          |
| SettView               |          |

## 2. Структура - Классы для кастомизации

### Awz\Acl\Access\Custom

Справочники констант и логика для компонента управления прав

| Класс                     | Описание |
|---------------------------|----------|
| ActionDictionary          |          |
| ComponentConfig           |          |
| Helper                    |          |
| PermissionDictionary      |          |
| RoleDictionary            |          |

### Awz\Acl\Access\Custom\Rules

Содержатся сгенерированные классы прав

| Класс          | Описание |
|----------------|----------|
| Example        |          |

## 3. Структура - компонент сохранения прав

/bitrix/modules/awz.acl/install/components/module.config.permissions

## 4. Структура - таблицы базы данных

/bitrix/modules/awz.acl/install/db/mysql/access.sql<br>
/bitrix/modules/awz.acl/install/db/pgsql/access.sql<br>
/bitrix/modules/awz.acl/install/db/mysql/unaccess.sql
/bitrix/modules/awz.acl/install/db/pgsql/unaccess.sql

<!-- arch-end -->