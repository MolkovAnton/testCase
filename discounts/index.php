<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Скидки");
?><?$APPLICATION->IncludeComponent(
	"ma:discount",
	"",
	Array(
		"HLBLOCK_CODE" => "Discounts"
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>