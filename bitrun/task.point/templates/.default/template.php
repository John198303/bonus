<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
CJSCore::Init(array("jquery"));

echo "<PRE>";
print_r($arParams);
echo "</PRE>";
foreach ($arResult['BLOCK'] as $item)
{
    echo "<PRE>";
    print_r($item);
    echo "</PRE>";

}

?>

