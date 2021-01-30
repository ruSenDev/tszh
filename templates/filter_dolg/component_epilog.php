<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if (is_set($_GET, "tszh_id") && intval($_GET["tszh_id"]) > 0)
{
	$msg = getMessage("TPL_ORG_TITLE", array("#TSZH_NAME#" => $arResult["TSZH"]["NAME"]));
	$APPLICATION->setTitle($msg);
	$APPLICATION->SetPageProperty("title", $msg);
}
?>
