$(function(){	
	// закрываем блоки с формами
	$('span.wr_close').click(function(){
		$('#overlay').fadeOut(500);
		$(this).parents('#form_wrapper_call').hide();
	});	
});