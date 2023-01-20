<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Highloadblock\HighloadBlockTable,
	Bitrix\Main\Localization\Loc,
	Bitrix\Main\ORM\Fields\Relations\Reference,
	Bitrix\Highloadblock\HighloadBlockLangTable,
	Bitrix\Main\Loader;
	
if( !Loader::includeModule("highloadblock") ) {
    throw new \Exception(Loc::getMessage("MA_DISCOUNT.HLBLOCK_ERROR"));
}

$arTypesEx = [];
$blockRes = HighloadBlockTable::getList([
	'filter' => ['LANG.LID' => 'ru'],
	'runtime' => [
		new Reference('LANG', HighloadBlockLangTable::getEntity(), [
			'=this.ID' => 'ref.ID'
		]),
	],
	'select' => ['LANG_NAME' => 'LANG.NAME', 'NAME']
]);
while ($block = $blockRes->fetch()) {print_r($block);
	$arTypesEx[$block['NAME']] = $block['LANG_NAME'];
}

$arComponentParameters = [
	"PARAMETERS" => [
		"HLBLOCK_CODE" => [
			"NAME" => Loc::getMessage("MA_DISCOUNT.HLBLOCK"),
			"TYPE" => "LIST",
			"VALUES" => $arTypesEx,
		],
	]
];