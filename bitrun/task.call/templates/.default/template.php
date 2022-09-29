<?
if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();
/**
 * Bitrix vars
 *
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 * @global CMain $APPLICATION
 * @global CUser $USER
 */
 //echo"<pre>";print_r($arParams);echo"</pre>";
?>
<?CJSCore::Init(array("jquery"));?>

<!--Заказать звонок-->
<div id="form_wrapper_call">
<pre><?//print_r($_SESSION["CMPT_PARAMS"]);?></pre>
<div id="fhead">
	<h3><?=GetMessage("MFT_HEADER")?></h3>
  <span class="wr_close"><img src="/images/cls_btn.png" /></span>
</div>
   <div class="frm_place">
		<div class="m-err" id="f_COMMON"></div>
		<form action="<?=$componentPath?>/script/senddata.php" method="POST" id="call_ord">
			<?=bitrix_sessid_post()?>
			<div>
				<input type="text" name="USER[NAME]" id="name" placeholder="<?=GetMessage("MFT_NAME")?>" value="" maxlength="30" />
				<?if(empty($arParams["REQUIRED_FIELDS"]) || in_array("NAME", $arParams["REQUIRED_FIELDS"])):?><span class="mf-req">*</span><?endif?>
				<div id="f_NAME" class="m-err"></div>
			</div>
			<div>
				<input type="text" name="USER[PHONE]" id="phone" placeholder="<?=GetMessage("MFT_PHONE")?>" value="" maxlength="20" />
				<?if(empty($arParams["REQUIRED_FIELDS"]) || in_array("PHONE", $arParams["REQUIRED_FIELDS"])):?><span class="mf-req">*</span><?endif?>
				<?if(!empty($arParams["PHONE_FORMAT"])):?>
				<div class="exmpl"><?=GetMessage("MFT_PHONE_EXMPL_".$arParams["PHONE_FORMAT"]);?></div>
				<?endif;?>
				
				<div id="f_PHONE" class="m-err"></div>
			</div>
			<div>
				<input type="text" name="USER[TIME]" id="time" placeholder="<?=GetMessage("MFT_TIME")?>" value="" maxlength="10" />
				<?if(empty($arParams["REQUIRED_FIELDS"]) || in_array("TIMETOCALL", $arParams["REQUIRED_FIELDS"])):?><span class="mf-req">*</span><?endif?>
				<div id="f_TIME" class="m-err"></div>
			</div>
			<?if($arParams["USE_MESSAGE_FIELD"] == "Y"):?>
			<div>
				<textarea name="USER[MESS]" id="mess" placeholder="<?=GetMessage("MFT_MESSAGE")?>" maxlength="150"></textarea>
				<?if(empty($arParams["REQUIRED_FIELDS"]) || in_array("MESSAGE", $arParams["REQUIRED_FIELDS"])):?><span class="mf-req">*</span><?endif?>
				<div id="f_MESS" class="m-err"></div>
			</div>
			<?endif;?>
			<?if($arParams["USE_CAPTCHA"] == "Y"):?>
				<div class="mf-captcha">
					<div class="mf-text"><?=GetMessage("MFT_CAPTCHA")?></div>
					<input type="hidden" name="captcha_sid" id="captcha_sid" value="<?=$arResult["capCode"]?>">
					<div class="mf-captcha"><img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["capCode"]?>" width="180" height="40" alt="CAPTCHA"></div>
					<div class="mf-text"><?=GetMessage("MFT_CAPTCHA_CODE")?><span class="mf-req">*</span>
					<input type="text" name="captcha_word" id="captcha_word" size="30" maxlength="10" value="" />
					</div>
					<div id="f_CAPTCHA" class="m-err"></div>
				</div>
			<?endif;?>
			<div class="bsubm">
				<input type="submit" name="sord_call" id="sord_call" value="<?=GetMessage("MFT_SUBMIT")?>" />
			</div>
		</form>
   </div>
 <div id="fbott"></div>
</div>
<script>
$(function(){
	
	$('#call_ord').on('submit', function(e){
		var form = $(this);
		var url = form.attr('action');
		
		var data = {
			'USER[NAME]': $('#name').val(),
			'USER[PHONE]': $('#phone').val(),
			'USER[TIME]': $('#time').val(),
			<?if($arParams["USE_MESSAGE_FIELD"] == "Y"):?>
			'USER[MESS]': $('#mess').val(),
			<?endif;?>
			<?if($arParams["USE_CAPTCHA"] == "Y"):?>
			 'captcha_sid': $('#captcha_sid').val(),
			 'captcha_word': $('#captcha_word').val(),
			<?endif;?>
			'sessid': $('#sessid').val(),
			'form_type': form.attr('id'),		
		}
		
		$.post(url, data, function(res){
			if(res.ERROR){
				$.each(res.ERROR, function(i, val){
					if($.isArray(val)){
						$.each(val, function(ii, vval){
							$('#f_' + i).append('<span>' + vval + '</span>').css('color', '#f00');
						});
					}
					else{
						$('#f_' + i).text(val).css('color', '#f00');	
					}
					
				});
			}
			else{
				form.parent('.frm_place').empty().html('<span style="color:#74991a;">'+res.SUCCESS+'<span>');
				$('#call_ord input[type=text]').add('#call_ord textarea').val('');
			}
			<?if($arParams["USE_CAPTCHA"] == "Y"):?>
				$('input#cp_sid').val(res.captcha);
				$('img#cp_img').attr('src', '/bitrix/tools/captcha.php?captcha_sid='+res.captcha);
			<?endif;?>
		}, 'json');
		
		e.preventDefault();
		
	});
});

</script>