<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$flats = [];
foreach ($arItems as $key => $flat) {
    $flats[$flat['HOUSE_ID']][$flat['ACCOUNT_ID']] = [
        'ACCOUNT_ID' => $flat['ACCOUNT_ID'],
        'HOUSE_ID' => $flat['HOUSE_ID'],
        'FLAT_ABBR' => $flat['FLAT_ABBR'],
        'ADDRESS_FULL' => $flat['ADDRESS_FULL'],
        'DEBT_END_WITHOUT_CHARGES' => $flat['DEBT_END_WITHOUT_CHARGES'],
    ];
}

function houseList($list)
{
    $houses = [];
    foreach ($list as $house) {
        $houses[$house['HOUSE_ID']] = $house['STREET'] . ', ' . $house['HOUSE'];
    }
    return $houses;
}

function flatList($list)
{
    $flats = [];
    foreach ($list as $flat) {
        if (!array_key_exists($flat['HOUSE_ID'], $flats)) {
            $flats[$flat['HOUSE_ID']] = [0 => 'Все квартиры'];
        }
        $flats[$flat['HOUSE_ID']][$flat['ACCOUNT_ID']] = $flat['FLAT_ABBR'];
    }
    return $flats;
}

