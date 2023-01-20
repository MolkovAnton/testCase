<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Highloadblock\HighloadBlockLangTable;
use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Main\Loader;
use Bitrix\Main\Type\DateTime;

if (!Loader::includeModule('highloadblock')) {
    return;
}

$hlblock = HighloadBlockTable::getList([
    'filter' => ['NAME' => 'Discounts']
])->fetch()['ID'];
if ($hlblock > 0) {
    echo 'Уже создан';
    return;
}

$arTable = array(
    'NAME' => 'Discounts',
    'TABLE_NAME' => 'ma_discounts',
);

$addResult = HighloadBlockTable::add($arTable);

if ($addResult->isSuccess()) {
    HighloadBlockLangTable::add(array(
        'ID' => $addResult->getId(),
        'LID' => 'ru',
        'NAME' => 'Скидки'
    ));

    $obUserField = new \CUserTypeEntity;

    $arLangFields = array("EDIT_FORM_LABEL", "LIST_COLUMN_LABEL", "LIST_FILTER_LABEL");
    $arUserFields = array(
        array("NAME" => "UF_DATE", "TYPE" => "datetime", "MULTIPLE" => "N", "LANG_RU" => "Дата создания"),
        array("NAME" => "UF_CODE", "TYPE" => "string", "MULTIPLE" => "N", "LANG_RU" => "Код скидки"),
        array("NAME" => "UF_PERCENT", "TYPE" => "integer", "MULTIPLE" => "N", "LANG_RU" => "Процент скидки"),
        array("NAME" => "UF_USER", "TYPE" => "integer", "MULTIPLE" => "N", "LANG_RU" => "Пользователь"),
    );

    foreach ($arUserFields as $arUserField) {
        $arFields = array(
            'ENTITY_ID' => 'HLBLOCK_' . $addResult->getId(),
            'FIELD_NAME' => $arUserField["NAME"],
            'USER_TYPE_ID' => $arUserField["TYPE"],
            'XML_ID' => $arUserField["NAME"],
            'SORT' => '100',
            'MULTIPLE' => $arUserField["MULTIPLE"],
            'MANDATORY' => 'Y',
            'SHOW_FILTER' => 'E',
            'SHOW_IN_LIST' => 'Y',
            'EDIT_IN_LIST' => 'Y',
            'IS_SEARCHABLE' => 'Y',
            'SETTINGS' => array(
                'DEFAULT_VALUE' => '',
                'SIZE' => '60',
                'ROWS' => '1',
                'MIN_LENGTH' => '0',
                'MAX_LENGTH' => '0',
                'REGEXP' => '',
            ),
        );

        foreach ($arLangFields as $langField) {
            $arFields[$langField]["ru"] = $arUserField["LANG_RU"];
        }

        $obUserField->Add($arFields);
    }
    
    $import = [
        [
            'UF_DATE' => new DateTime(),
            'UF_CODE' => 'qwert',
            'UF_PERCENT' => 10,
            'UF_USER' => 10
        ],
        [
            'UF_DATE' => new DateTime(),
            'UF_CODE' => 'yuiop',
            'UF_PERCENT' => 15,
            'UF_USER' => 11
        ],
        [
            'UF_DATE' => new DateTime(),
            'UF_CODE' => 'asdfg',
            'UF_PERCENT' => 20,
            'UF_USER' => 12
        ],
        [
            'UF_DATE' => new DateTime(),
            'UF_CODE' => 'ghjkl',
            'UF_PERCENT' => 25,
            'UF_USER' => 13
        ],
    ];
    
    $entityClass = $addResult->getObject()->getEntityDataClass();
    foreach ($import as $entity) {
        $entityClass::add($entity);
    }
    
    echo $addResult->getId();
} else {
    print_r($addResult->getErrorMessages());
}