<?php
if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();
session_start();
/**
 * Bitrix vars
 *
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponent $this
 * @global CMain $APPLICATION
 * @global CUser $USER
 */
 
//удаляем параметры из сессии
if(isset($_SESSION["CMPT_PARAMS"])){
	unset($_SESSION["CMPT_PARAMS"]);
}

$arResult["PARAMS_HASH"] = md5(serialize($arParams).$this->GetTemplateName());

$arParams["USE_CAPTCHA"] = (($arParams["USE_CAPTCHA"] != "N" && !$USER->IsAuthorized()) ? "Y" : "N");

// ID почтового события
$arParams["EVENT_MESSAGE_ID"] = trim($arParams["EVENT_MESSAGE_ID"]);

$arParams["EMAIL_TO"] = trim($arParams["EMAIL_TO"]);
if($arParams["EMAIL_TO"] == '')
	$arParams["EMAIL_TO"] = COption::GetOptionString("main", "email_from");

$arParams["OK_TEXT"] = trim($arParams["OK_TEXT"]);
if($arParams["OK_TEXT"] == '')
	$arParams["OK_TEXT"] = GetMessage("MF_OK_MESSAGE");

	if(isset($arParams['_POST'])){
		//здесь производится отправка
		
		if(check_bitrix_sessid()){
			
			// собираем ошибки			
			if($arParams['_POST']['form_type'] == 'call_ord')
			{
				foreach($arParams['_POST']['USER'] as $key=>$fld_val){
					if(in_array($arParams['REQUIRED_FIELDS'], $key) && empty($fld_val)){
						$arResult["ERROR"][$key] = GetMessage('MF_REQ_FIELD').$arParams[$key]["NAME"].GetMessage('MF_REQ_FIELD_EMPTY');
					}
					
					if($key == 'PHONE' && !empty($arParams['PHONE_FORMAT'])){

						switch($arParams['PHONE_FORMAT']){
							case 'F1':
								$pattern = "/^\([0-9]{3}\) [0-9]{3}-[0-9]{2}-[0-9]{2}\$/";
							break;
							case 'F2':
								$pattern = "/^\([0-9]{3}\)[0-9]{3}-[0-9]{2}-[0-9]{2}\$/";
							break;
							case 'F3':
								$pattern = "/^[0-9]{3} [0-9]{3} [0-9]{2} [0-9]{2}\$/";
							break;
							case 'F4':
								$pattern = "/^[0-9]{3} [0-9]{7}\$/";
							break;
						}
						
						if(preg_match($pattern, $fld_val) == 0){
							$arResult["ERROR"][$key] = GetMessage('MF_REQ_INCORRECT_PHONE');	
						}
					}
				}
			} 
			
			if($arParams["USE_CAPTCHA"] == "Y")
			{
				include_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/classes/general/captcha.php");
				$captcha_code = $arParams['_POST']["captcha_sid"];
				$captcha_word = $arParams['_POST']["captcha_word"];
				$cpt = new CCaptcha();
				$captchaPass = COption::GetOptionString("main", "captcha_password", "");
				if (strlen($captcha_word) > 0 && strlen($captcha_code) > 0)
				{
					if (!$cpt->CheckCodeCrypt($captcha_word, $captcha_code, $captchaPass))
						$arResult["ERROR"]["CAPTCHA"] = GetMessage("MF_CAPTCHA_WRONG");
				}
				else
					$arResult["ERROR"]["CAPTCHA"] = GetMessage("MF_CAPTHCA_EMPTY");

			}
			
			if(!$arResult["ERROR"])
			{
				
				$arFields = Array(
					"AUTHOR" => $arParams["_POST"]["USER"]["NAME"],
					"AUTHOR_PHONE" => $arParams["_POST"]["USER"]["PHONE"],
					"TIME_TOCALL" => $arParams["_POST"]["USER"]["TIME"],
					"EMAIL_TO" => $arParams["EMAIL_TO"],
				);				
				
				if($arParams["USE_MESSAGE_FIELD"] == "Y")
				{
					$arFields["MESSAGE"] = $arParams["_POST"]["USER"]["MESS"];
				}
						
				CEvent::Send($arParams["EVENT_MESSAGE_ID"], SITE_ID, $arFields);
				
				$arResult["SUCCESS"] = $arParams["OK_TEXT"];
				
				if($arParams["SAVE_FORM_DATA"] == "Y" && !empty($arParams["IBLOCK_ID"])){
					if(CModule::IncludeModule('iblock')){
						$el = new CIBlockElement;
						
						$elname = GetMessage('MF_ORDER_FROM').htmlspecialcharsbx($arParams["_POST"]["USER"]["NAME"]);
						
						$props = Array();
						$props["VISITOR_FIO"] = htmlspecialcharsbx($arParams["_POST"]["USER"]["NAME"]);
						$props["VISITOR_PHONE"] = htmlspecialcharsbx($arParams["_POST"]["USER"]["PHONE"]);
						$props["TIME_TOCALL"] = htmlspecialcharsbx($arParams["_POST"]["USER"]["TIME"]);
						if($arParams["USE_MESSAGE_FIELD"] == "Y"){
							$props["VISITOR_MESSAGE"] = Array("VALUE" => Array ("TEXT" => htmlspecialcharsbx($arParams["_POST"]["USER"]["MESS"]), "TYPE" => "text"));
						}
						
						$arLoadOrderArray = Array(
							"IBLOCK_ID" => $arParams["IBLOCK_ID"],
							"PROPERTY_VALUES" => $props,
							"NAME" => $elname,
							"ACTIVE" => "Y"
						);
												
						if(!$el->Add($arLoadOrderArray)){
							$arResult["ERROR"]["COMMON"][] = GetMessage('MF_ERROR_MESSAGE').' '.$el->LAST_ERROR;							
						}
					}			
				}
			}		
		}
		else
		{
			$arResult["ERROR"]["COMMON"][] = GetMessage("MF_SESS_EXP"); 		
		}
		
		echo json_encode($arResult);			
		
	}
	else{
		// до отправки
		if(empty($arResult["ERROR_MESSAGE"]))
		{
			if($USER->IsAuthorized())
			{
				$arResult["AUTHOR_NAME"] = $USER->GetFormattedName(false);
				$arResult["AUTHOR_EMAIL"] = htmlspecialcharsbx($USER->GetEmail());
			}
			else
			{
				if(strlen($_SESSION["MF_NAME"]) > 0)
					$arResult["AUTHOR_NAME"] = htmlspecialcharsbx($_SESSION["MF_NAME"]);
				if(strlen($_SESSION["MF_EMAIL"]) > 0)
					$arResult["AUTHOR_EMAIL"] = htmlspecialcharsbx($_SESSION["MF_EMAIL"]);
			}
		}

		if($arParams["USE_CAPTCHA"] == "Y")
			$arResult["capCode"] =  htmlspecialcharsbx($APPLICATION->CaptchaGetCode());
		
		// сохраняем параметры в сессии
		$_SESSION["CMPT_PARAMS"] = $arParams;
		
		$this->IncludeComponentTemplate();	
	}