<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

CModule::IncludeModule('crm');
global $USER;

$arSelect = Array("ID", "NAME","PROPERTY_70", "PROPERTY_69", "PROPERTY_71", "PROPERTY_72");
$arFilter = Array("IBLOCK_ID"=>16);
$res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
$id = [];
while($ob = $res->Fetch())
{

    $id[] = $ob;

}
$arResult['BLOCK'] = $id;




$this->IncludeComponentTemplate();
