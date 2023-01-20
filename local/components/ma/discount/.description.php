<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Localization\Loc;

$arComponentDescription = array(
	"NAME" => Loc::getMessage("MA_DISCOUNT.NAME"),
	"DESCRIPTION" => Loc::getMessage("MA_DISCOUNT.DESC"),
	"COMPLEX" => "N",
    "PATH" => [
        "ID" => 'test',
        "NAME" => Loc::getMessage("MA_DISCOUNT.PATH"),
    ],
);
