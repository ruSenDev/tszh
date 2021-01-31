<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Page\Asset;

$this->setFrameMode(true);

Asset::getInstance()->addJs($componentPath . "/templates/filter_dolg/select2/select2.min.js");
Asset::getInstance()->addJs($componentPath . "/templates/filter_dolg/select2/ru.js");

$arItems = $arResult["ACCOUNTS"];
$arFields = $arResult["FIELDS"];
$arExcluded = $arResult["ACCOUNTS_EXCLUDED"];

if (!empty($_REQUEST['del_filter']) or empty($_REQUEST)) {
    $arResult['OPEN_FILTER'] = 0;
    unset($_REQUEST);
} else {
    $arResult['OPEN_FILTER'] = 1;
}

?>

<?if (count(houseList($arItems)) > 0):?>
<div class="filter-block">
    <div class="block-left"></div>
    <div class="block-right">
        <div class="div_search_button">
            <input type="button" value="<?= GetMessage('TPL_FILTER_BUTTON_SEARCH') ?>"
                   class="debt-button <? if ($arResult["OPEN_FILTER"]): ?>show<? endif ?>">
            <label></label>
        </div>
        <form action="<?= $APPLICATION->GetCurPageParam(); ?>" method="get">
            <div class="debt-list-filter <? if ($arResult["OPEN_FILTER"]): ?>show<? endif ?>">
                <div class="debt-filter-list">
                    <div class="filter-params">

                        <div class="number_title"><?= GetMessage('TPL_FILTER_HOUSES') ?></div>
                        <select name="select1" id="select1" class="form-control js-select2" onchange="if ((this.value !== '') && (this.value !== 'all')) document.getElementById('select2').removeAttribute('disabled'); else document.getElementById('select2').setAttribute('disabled','disabled');">
                            <? if ($_REQUEST['select1']): ?> selected<? endif ?>
                            <option></option>
                            <option value="all"<? if ($_REQUEST['select1'] == 'all'): ?> selected<? endif ?>>
                                <?= GetMessage('TPL_FILTER_ALL_HOUSES') ?>
                            </option>
                            <? foreach (houseList($arItems) as $key => $houses): ?>
                                <option <? if ($_REQUEST['select1'] == $key): ?> selected<? endif ?>
                                        value="<?= $key ?>"><?= $houses ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <?if ($USER->IsAuthorized()):?>
                        <div class="filter-params">
                            <div class="number_title"><?= GetMessage('TPL_FILTER_FLATS') ?></div>
                            <select name="select2" id="select2" class="form-control js-select2">
                                <option disabled value=""></option>
                                <? foreach (flatList($arItems) as $key => $debt): ?>
                                    <? foreach ($debt as $id => $flat): ?>
                                        <option <? if ($_REQUEST['select2'] == $id): ?> selected<? endif ?>
                                                data-value="<?= $key ?>"
                                                value="<?= $id ?>"><?= $flat ?></option>
                                    <? endforeach; ?>
                                <? endforeach; ?>
                            </select>
                        </div>
                    <?endif;?>
                </div>
                <div class="btn-filter-buttons">
                    <input type="submit" value="<?= GetMessage('TPL_FILTER_BUTTON_RESET') ?>" name="del_filter">
                    <div style="position: relative;"><input type="submit"
                                                            value="<?= GetMessage('TPL_FILTER_BUTTON_SEARCH') ?>"
                                                            class="link-theme-default" name="set_filter">
                        <label for="set_filter_input"></label>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<?endif;?>

<?
if (!function_exists('TszhDolgRenderList')) {
    function TszhDolgRenderList($arItems, $arFields, $arExcluded)
    {
        echo '<div class="overflowx_auto"><table class="data-table tszh-dolg-table"><thead><tr>';
        foreach ($arFields as $arField) {
            echo "<th>{$arField['TITLE']}</th>\n";
        }
        echo "</tr></thead>\n";
        foreach ($arItems as $arAccount) {
            if (in_array($arAccount["ACCOUNT_ID"], $arExcluded)) {
                continue;
            }
            echo "<tr>";

            if ((in_array($_REQUEST['select1'], $arAccount) and in_array($_REQUEST['select2'], $arAccount)) or $_REQUEST['select1'] == 'all') {

                foreach ($arFields as $arField) {
                    if ($arField["CODE"] == "FLAT") {
                        $arField["CODE"] = "FLAT_ABBR";
                    }
                    $value = $arAccount[$arField["CODE"]];
                    if (in_array($arField["CODE"], Array("DEBT_END", "DEBT_END_WITHOUT_CHARGES", "DEBT_BEG"))) {
                        $value = CTszhPublicHelper::FormatCurrency($value);
                        $class = ' class="cost"';
                    } else {
                        $class = '';
                    }
                    echo "<td$class>$value</td>\n";
                }
            } else {
                continue;
            }
            echo "</tr>";
        }

        echo '</table></div>';

    }
}

if (empty($_REQUEST['select1']) or $_REQUEST['select1'] == 'all') {

    if (!empty($arResult["TSZH_LINKS"])) {
        ?>
        <?= GetMessage("TPL_CHOOSE_TSZH") ?>:
        <ul>
        <? foreach ($arResult["TSZH_LINKS"] as $tszhID => $tszhName): ?>
            <li><a href="<?= $APPLICATION->GetCurPage() . "?tszh_id={$tszhID}" ?>"><?= $tszhName ?></a></li>
        <? endforeach ?>
        </ul><?
        return;
    }

    if (count($arResult["ACCOUNTS"]) <= 0) {
        if (empty($arResult["TSZH_LINKS"])) {
            ShowNote(GetMessage("TPL_NO_DEBTORS"));
        }
        return;
    }

    if ($arParams["DISPLAY_TOP_PAGER"]) {
        echo "<div>{$arResult['NAV_STRING']}</div>\n";
    }

    if (is_array($arResult['GROUPS'])) {
        $curTszhID = false;
        foreach ($arResult["GROUPS"] as $arItems) {
            if (!empty($arResult["TSZH_LINKS"]) && $arItems["TSZH_ID"] != $curTszhID) {
                echo '<h3>' . $arItems["TSZH_NAME"] . '</h3>';
                $curTszhID = $arItems["TSZH_ID"];
            }
            if (strlen($arItems['TITLE']) > 0) {
                echo '<h4>' . $arItems['TITLE'] . '</h4>';
            }
            TszhDolgRenderList($arItems['ITEMS'], $arResult["FIELDS"], $arResult["ACCOUNTS_EXCLUDED"]);
            if ($arItems['SUMMARY']) {
                ?>
                <p><?= $arItems["SUMMARY_TITLE"] ?>: <b><?= $arItems['SUMMARY'] ?></b></p>
                <?
            }
        }
    } else {
        TszhDolgRenderList($arResult["ACCOUNTS"], $arResult["FIELDS"], $arResult["ACCOUNTS_EXCLUDED"]);
    }

    if ($arParams["DISPLAY_BOTTOM_PAGER"]) {
        echo "<div>{$arResult['NAV_STRING']}</div>\n";
    }
} elseif ($_REQUEST['select1'] !== 'all') {
    $curTszhID = false;
    foreach ($arResult["GROUPS"] as $arItems) {
        if (!empty($arResult["TSZH_LINKS"]) && $arItems["TSZH_ID"] != $curTszhID) {
            echo '<h3>' . $arItems["TSZH_NAME"] . '</h3>';
            $curTszhID = $arItems["TSZH_ID"];
        }
        TszhDolgRenderList($arItems['ITEMS'], $arResult["FIELDS"], $arResult["ACCOUNTS_EXCLUDED"]);
    }
}
?>

