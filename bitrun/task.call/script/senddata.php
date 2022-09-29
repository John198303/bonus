<?
	/**
	 * Экранирует элементы массива
	 * @param {array} $array Сам массив.
	 * @param {bool} $orig=false Возвращать ли оригинальные элементы с '~'.
	 * @return {array}
	 */
	function escapeArray($array, $orig=false){
		$res = false;
		foreach ($array as $k=>$v){
			if(is_array($v)){
				$o = ($orig)?true:false;
				$res[$k] = escapeArray($v, $o);
			} else {
				$res[$k] = htmlspecialcharsEx($v);
				if($orig) $res['~'.$k] = $v;
			}
		}
		return $res;
	}	
	
	// если скрипт вызван не через AJAX
	if($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest'){
		exit;
	}
	
	require ($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
	
	$escPOST = escapeArray($_POST);
	
	if($escPOST['form_type'] == 'call_ord'){
		
		$APPLICATION->IncludeComponent("mattweb:callback", ".default", array(
				"_POST" => $escPOST,
				"IBLOCK_TYPE" => $_SESSION["CMPT_PARAMS"]["IBLOCK_TYPE"],
				"IBLOCK_ID" => $_SESSION["CMPT_PARAMS"]["IBLOCK_ID"],
				"USE_CAPTCHA" => $_SESSION["CMPT_PARAMS"]["USE_CAPTCHA"],
				"OK_TEXT" => $_SESSION["CMPT_PARAMS"]["OK_TEXT"],
				"EMAIL_TO" => $_SESSION["CMPT_PARAMS"]["EMAIL_TO"],
				"USE_MESSAGE_FIELD" => $_SESSION["CMPT_PARAMS"]["USE_MESSAGE_FIELD"],
				"SAVE_FORM_DATA" => $_SESSION["CMPT_PARAMS"]["SAVE_FORM_DATA"],
				"FIELDS_INFO" => $_SESSION["CMPT_PARAMS"]["FIELDS_INFO"],				
				"REQUIRED_FIELDS" => $_SESSION["CMPT_PARAMS"]["REQUIRED_FIELDS"],
				"EVENT_MESSAGE_ID" => $_SESSION["CMPT_PARAMS"]["EVENT_MESSAGE_ID"],
				"PHONE_FORMAT" => $_SESSION["CMPT_PARAMS"]["PHONE_FORMAT"],
			),
			false);
	}
	else{
		die('error!');
	}

	require ($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_after.php");
?>