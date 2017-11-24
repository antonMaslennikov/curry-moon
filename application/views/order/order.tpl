<link rel="stylesheet" href="/css/order/styles.css" type="text/css" media="all"/>
<script type="text/javascript" src="/js/jquery.cookie.min.js"></script>
<script type="text/javascript" src="/js/mj.polls.js"></script>

<div class="order-page">
	<!--div class="back2old">
		<center><a href="/basket2/" class="error">Вернуться к класической корзине за 4 шага</a></center>
	</div-->
	<div class="basket-top-box">
		<div class="b-contacts">
			<h3>Оформите заказ по телефону</h3>
			<div class="dtl">Москва..........<i class="tel">+7 (495) 229 - 30 - 73</i></div>
			<div class="dtl">Skype.............<a class="a_sky" href="skype:maryjane_ru?chat">maryjane_ru</a></div>
		</div>
		
		<!-- Шаги оформления -->
		<div class="basket-step {if $step == 1 || $step == 2} step_2 {elseif $step == 'confirm'} step_3 {/if}">
			<a href="/basket/" class="go-to-basket" title="Вернуться в корзину"></a>
			{if $step == 1}
				<a href="{if $step == confirm}#{else}/order/step/2/{/if}" class="go-to-confirm" id="go2step2"  title="Далее - оплата и доставка"></a>
			{elseif $step == 2}
				<a href="#" class="go-to-confirm" id="go2confirm"  title="Далее - подтвердить заказ"></a>
			{else}
				<a href="#" class="go-to-confirm" id="go2confirm"  title=""></a>
			{/if}
		</div>
		
		<ul class="b-basket-sum">
			<li class="li">Стоимость.....................<span class="sum" id="basket_sum_total">{$basket_sum} руб.</span></li>
			<li class="li">На вашем счету..............<span class="sum">{if $USER->authorized}{$USER->user_bonus}{else}0{/if} руб.</span></li>			
			<li class="li">Будет начисленно.........<span class="sum">{$bonusBack} руб.</span></li>
		</ul>
	</div>

	{if $error}
	<div class="error_order" style="">{$error.text}</div>
	{/if}


	{include file="$step_tpl"}

	<div class="empty_for_footer"></div>
</div>

{literal}
<script type="text/javascript">

// Скрипты для оформления заказа
$(document).ready(function(){
	
	{/literal}
	{* ($USER->user_id == 27278 || $USER->user_id == 6199 || $USER->user_id == 63250 || $USER->user_id == 86455) &&  *}
	{if $step != 'confirm'}
		if (typeof qBask != 'undefined' && typeof qBask.initCloseAlert == 'function')
			qBask.initCloseAlert();
			//$('a').bind('mousedown', function(){ window.onbeforeunload = null; return true; });
			
			$('a').each(function(){
				var s = $(this).attr('href');
				s = (typeof s == 'string'?s:'');
				if (s.substr(1,1) != '#') 
					$(this).bind('mousedown', function(){ window.onbeforeunload = null; return true; });
			});
			
			$('form').bind('submit', function(){ window.onbeforeunload = null; return true; });
			//$('.part-makingOrder form').submit(function(){ window.onbeforeunload = null; return true; });
	{/if}
	{literal}
	
	if ($.cookie('form_addr_opened') == 'true') {		
		$('.step1-form').show();
	}

	order.init();
		
	// При сабмите формы доставки и оплаты - копируем комментарии в основное поле комментария
	$("#delivery_payment_form").submit(function(){
		$("#comment_text").val(	$(".b-deliver input[type=radio]:checked").parent("div").next(".more_options_box").children('textarea.sub_comment').val() );
		return true;
	});
	
	$("#oldadresses tr").hover(
		function(){
			$("#oldadresses tr").removeClass('hover');
			$(this).addClass('hover');
		},
		function(){
			var tr = this;
			setTimeout(function() {$(tr).removeClass('hover');}, 1000);
		}
	).click(function(){
		$(this).children("td").children("input").attr("checked", "checked");
	});
	
	//клик переход шага
	$("#go2step2").click(function(){
		$("form#delivery_address").submit();
		return false;
	});
	
	$("#go2confirm").click(function(){
		$("form#delivery_payment_form").find('input:submit').click();
		return false;
	});
});

var order = {
	
	inputs: '#basket_fio, #basket_phone, #basket_skype, #basket_email, #basket_comm, #basket_city_search, #postal_code, #metro_time',
	validate_inpts: '#basket_fio, #basket_phone, #basket_email, #basket_comm, #basket_city_search, #postal_code',
	formDA: '#delivery_address',
	
	errorClass: 'error',
	validClass: 'complite',
	
	empty_input_css: {color:'#A9ABAB', fontStyle:'italic'},
	filled_inpt_css: {color:'#212121', fontStyle:'normal'},
	
	//Навешиваем на кнопки и поля все необходимые события
	init: function(){
		this.initAddressInputs();
		this.initSubmitAddress();
		this.initRadioDelivery();
		this.initPaymentLabel();
		this.initMyBobusSum();
		this.initPrevAddr();
		this.initAutoSave();
	},
	
	initRadioDelivery: function () {
		$(".b-deliver input[type=radio]").change(function(){ order.initMyBobusSum();
			order.showMoreOpt(this);
		});		
		order.showMoreOpt($(".b-deliver input[type=radio]:checked"));
	},
	
	// Нужно пересчитивать стоимость, учитываю галочку "Мои бонусы"
	initMyBobusSum: function () {
		
		if ($(".payment-type-radio").length > 0)
		{ 
			if ($(".payment-type-radio:checked:visible").length == 0)
				$(".payment-type-radio:visible:first").attr('checked', 'checked');
				
			var payment_price = parseFloat($('#'+$(".payment-type-radio:checked").val()+'-total-price').val());
			var delivery = $('.b-deliver input[type=radio]:checked').parent().find('.sub-price-bonuses').text();
			delivery = parseFloat(delivery);
			if (!isNaN(delivery)) payment_price += delivery;
			
			if ($("#my_bonuses").length == 1 && $("#my_bonuses").attr("checked")) {
				if (payment_price - $('#my_bonuses_value').val() < 0) {
					payment_price = 0;
				} else {
					payment_price -= $("#my_bonuses_value").val();
				}
			}
			//if (payment_price != undefined) {
			$('#basket_sum_total').text(payment_price+' руб.');
			//$('#'+$(".payment-type-radio:checked").val() + '-price').text(payment_price+' руб.');
			$('.sub-price').text(payment_price+' руб.');
			//}
			
			if (payment_price <= 300 || payment_price >= 10000) {
				$('input#payment_by_cards').attr('disabled', 'disabled');
				//$('input#payment_by_bank').attr('checked', 'checked');
			} else
				$('input#payment_by_cards').removeAttr('disabled');
		}		
	},
	
	// Востановить предыдущий введенный адрес, если вернулись на страницу по бекспейсу
	initPrevAddr: function (){
		var prev_addr = $.cookie("MJbasket_json");
		
		if (prev_addr != null && prev_addr.address != null) {
			prev_addr = eval('('+prev_addr+')');
			// Востанавливаем город, город айди, индекс
			if (prev_addr.address.postal_code != null) { $("#postal_code").val(prev_addr.address.postal_code); }
			if (prev_addr.address.city_name != null) { $("#basket_city_search").val(prev_addr.address.city_name);}
			if (prev_addr.address.city != null) { $("#basket_city").val(prev_addr.address.city); }
			if (prev_addr.address.city > 1) {
				$("#postal_code").css({visibility:'visible'}).show();
			}
		}
	},
	
	initPaymentLabel: function () {
		// Ховер на лейбле - показывает цену
		$(".b-payment .radio label.lbl-paymeth").hover(function(){
			$(this).addClass('selected');
		}, function(){
			$(this).removeClass('selected');
		}).click(function(){
			$(".b-payment .radio label.lbl-paymeth").removeClass('selected_b');
			$(this).addClass('selected_b');
		});
		
		// Если установлено какой-то радиобатон - то мы показываем цену
		$(".b-payment .radio input:checked").next("label").addClass('selected_b');
		// При клике на радиобатоне - устанавливаем цену на лейбле
		$(".b-payment .radio input").change(function(){
			
			$(".b-payment .radio label.lbl-paymeth").removeClass('selected_b');
			var checked = $(this).attr('checked');
			if (checked == "checked") {
				$(this).next('label').addClass('selected_b');
			} else {
				$(this).next('label').removeClass('selected_b');
			}
			order.initMyBobusSum();
		});
		// по ховеру на радиобатоне тоже подсвечивать цену
		$(".b-payment .radio input").hover(function(){
			$(this).next('label').addClass('selected');
		}, function(){
			$(this).next('label').removeClass('selected');
		});
		
	},
	
	// показываем дополнительные параметры доставки для разных способов
	showMoreOpt: function (obj_radio) {
		$(".more_options_box").hide();
		
		$(obj_radio).parent("div").next(".more_options_box").show();
		
		if ($(obj_radio).val() == 'ems') {
			$('.payment_by_cashon').show();
			$('input#payment_by_cashon').attr('checked', 'checked');
		}
		
		if ($(obj_radio).val() == 'dpd') {
			if ("{dpd_cashon_possible}" == "1") {
				$('.payment_by_cashon').show();
				$('input#payment_by_cashon').attr('checked', 'checked');
			} else {
				$('.payment_by_cashon').hide();
				$('input#payment_by_cards').attr('checked', 'checked');
			}
		}
		
		if ($(obj_radio).val() == 'post') {
			$('.payment_by_cashon').hide();
			$('input#payment_by_cashon').removeAttr('checked');
			$('input#payment_by_cards').attr('checked', 'checked');
		}
		
		if ($('#my_bonuses').attr('checked'))
			bbb = parseInt($('#my_bonuses_value').val());
		else
			bbb = 0;
		
		if (parseInt($('#creditcard-price').text()) - bbb <= 300) {
			$('input#payment_by_cards').attr('disabled', 'disabled');
			$('input#payment_by_bank').attr('checked', 'checked');
		} else
			$('input#payment_by_cards').removeAttr('disabled');
	},
	
	// Валидация перед тем как засабмитить форму адреса
	initSubmitAddress: function () {
		$(order.formDA).submit(function(){			
			return order.validateDAForm();
		});
		
		// Если самовывоз - отключать валидацию некоторых полей
		$("#basket_samovivoz").change(function(){
			if ($("#basket_samovivoz").attr("checked")) {
				order.validate_inpts = '#basket_fio, #basket_phone, #basket_email';
				$(".no_samovivoz").css({visibility:'hidden'});
				$("#postal_code").hide();
			} else {
				$(".no_samovivoz").css({visibility:'visible'});
				$("#postal_code").show();
				order.validate_inpts = '#basket_fio, #basket_phone, #basket_email, #basket_comm, #basket_city_search, #postal_code';
			}
		});
		$("#basket_samovivoz").attr("checked", false);		
	},
	
	// проверяем валидность всех полей на форме
	/*
	1) ну естественно все поля должны быть заполнены (кроме скайпа)
	2) мыло валидируется на похожесть на мыло, есть уже фунция isValidEmail в майн.жс
	3) телефон должен содержать только цифры
	4) при смене странцы очищается поле город
	5) после воода мыла - ажакс проверка на сервер на занятость по адресу /ajax/checkemail/?email=[ляляля]
	6) при постановке галки "самовывоз" поля справа и ниже (с адресом) прячуться (контент снизу не должен двигаться  при этом)
	7) можно здесь посмотреть коечто http://printshop.maryjane.ru/catalog/view/29997/ если нажат на "заказ в один шаг"
	9) важно важно, дефолтные значения стирать передоптавкой как-то можно чтобы я на строне сервера их анализировал?
	10) не только цвет с серого на чёрный менять нужно когда свои данные в поля вписываешь, но и наклонность тоже убирать
	*/
	validateDAForm: function(){
		var result = true;
		$(".box-input-result").removeClass(order.errorClass+' '+order.validClass); // Обнуляем предыдущие валидации
		$(".input_error").hide();
		
		$(order.validate_inpts).each(function(i, val){			
			result = (order.validateInput(val, true) && result);	// возвращаем логическое И от результата валидации текущего поля и предыдущих результатов
																	// Если не валидно хоть одно поле - то мы получим ЛОЖЬ 
		});
		// Убираем лишнее перед сабмитом
		if (result) {
			if ($("#basket_skype").val() == $("#basket_skype").prev("label").text()) { $("#basket_skype").val('');}
			if ($("#postal_code").val() ==  $("#postal_code").prev("label").text()) { $("#postal_code").val('');}
		}
		
		return result;		
	},
	
	// проверяем один инпут и выводим или нет ошибку
	// Для разных полей разные правила и даже повторяющиеся правила 
	validateInput: function (inputId, showErr) {
		
		if ($(inputId).css('display') == 'none') return true;
		
		showErr = (showErr)?true:false;
		var valid_result = true;
		
		switch ($(inputId).attr('id')) {			
			case 'basket_fio' : 
				valid_result = ($(inputId).val() != '' && $(inputId).val() != $(inputId).prev('label').text());				
				break;
				
			case 'basket_city_search' : 
				valid_result = ($(inputId).val() != '' && $(inputId).val() != $(inputId).prev('label').text());				
					// Прячем индекс если нужно
					if ($("#basket_city_search").val()=='Москва') {
						$("#postal_code").css({visibility:'hidden'});
						
					} else {
						$("#postal_code").css({visibility:'inherit'});
					}
					if ($("#basket_city_search").val()==$(inputId).prev('label').text()) {
						if ($("#basket_city").length>0)
						$("#basket_city_search").val($("#basket_city").attr('_value')).change();
						valid_result = true;
					}
				break;
				
			case 'basket_phone' :
					valid_result = ($(inputId).val() != '' && $(inputId).val() != $(inputId).prev('label').text() && $(inputId).val()*1>99999);
					
					if (!valid_result && $(inputId).val()*1<99999 && $(inputId).val()*1>1) { 
						$('#'+$(inputId).attr("id")+'_err').hide();
					} else if (!valid_result) {
						$('#'+$(inputId).attr("id")+'_err').show();
					}
				break;
				
			case 'basket_email' :
				valid_result = ($(inputId).val() != '' && $(inputId).val() != $(inputId).prev('label').text() && isValidEmail($(inputId).val()));
				if (!valid_result) { $('#'+$(inputId).attr("id")+'_err').show();} else {$('#'+$(inputId).attr("id")+'_err').hide();}
				//$.get("/ajax/checkemail/?email="+$("#basket_email").val(), function(r){alert(r);}); -- но если один и тот же пользователь - но хочет ввести новый адрес - то фигня				
				break;
				
			case 'postal_code' :
				if ($("#basket_city").val() != 1) {	// Если 1 - это Москва и я не проверяю ИНДЕКС города
					valid_result = ($(inputId).val() != '' && $(inputId).val() != $(inputId).prev('label').text() && $(inputId).val()*1>0);
				} else {
					valid_result = true;					
				}
				break;
				
			case 'basket_comm' :
				valid_result = ($(inputId).val() != '' && $(inputId).val() != $(inputId).prev('label').text());
				break;				
		}
		// Если надо - то показываем ошибки		
		$(inputId).next(".box-input-result").removeClass(order.errorClass).click(function(){}).css('cursor','default');
		if ($(inputId).val() == $(inputId).prev('label').text())
			$(inputId).css(order.empty_input_css);
		else
			$(inputId).css(order.filled_inpt_css);
		if (showErr) { 
			$(inputId).next(".box-input-result").removeClass(order.errorClass+', '+order.validClass).addClass( !valid_result?order.errorClass:order.validClass );
			$(inputId).next(".box-input-result."+order.errorClass).css('cursor','pointer').click(function(){
				var inputId = $(this);
				if ($(this).prev().hasClass('input'))
					inputId = $(this).prev();
				if ($(this).prev().prev().hasClass('input'))
					inputId = $(this).prev().prev();
				
				$(inputId).val('');
				var text = $(inputId).prev('label').text();
				if (text && text.length>0)
					$(inputId).val(text);
				$(inputId).css(order.empty_input_css);
			});
			// Показываем текстовые ошибки, если они есть
		}
			
		return valid_result;
	},
	
	// убирание и добавление вспомогательного текста в інпуты
	initAddressInputs: function (){
		
		$(order.inputs).focus(function(){
			if ($(this).val() == $(this).prev('label').text()) {
				$(this).val('').css(order.filled_inpt_css);
			}
		}).blur(function(){
			if ($(this).val() == '') {
				if ($(this).attr('id') == 'basket_city_search') {
					if ($('#basket_country').val() == '838')
						$(this).val($(this).prev('label').text()).css(order.empty_input_css);
				} else
					$(this).val($(this).prev('label').text()).css(order.empty_input_css);
			} 
			var input_id = $(this).attr("id");
			order.validateInput('#'+input_id, true);			
		});
		
		$("#basket_phone, #postal_code").keyup(function(){
			var input_id = $(this).attr("id");
			order.validateInput('#'+input_id, true);
		});
		
		// Прячем индекс если нужно
		if ($("#basket_city").val() == 1 || $("#basket_city_search").val()=='Москва') {
			$("#postal_code").css({visibility:'hidden'});
		} else {
			$("#postal_code").css({visibility:'inherit'});
		}
		
		// инициализация после загрузки корзины
		$(order.inputs).each(function(i, inpt){			
			if ($(this).val() == $(this).prev('label').text() || $(this).val() == '') {
				$(this).val($(this).prev('label').text()).css(order.empty_input_css);
			} else {
				$(this).css(order.filled_inpt_css);
			}
		});
	},
	
	// Открыть форму ввода новой формы адреса доставки
	openFormNewAddr: function () {
		$('.step1-form').slideToggle();
		//$('#submit-delivery-btn').hide();
		$.cookie('form_addr_opened', 'true');
	},
	
	//Автосохранение адреса
	initAutoSave: function(){
		$('#basket_fio, #basket_phone, #basket_skype, #basket_email, #basket_country, #basket_city_search, #basket_city, #postal_code, #basket_comm').bind('change',function(){
			//$('#oldadresses input[type=radio]:checked').val()
			$.get('/order/save_address/?field='+$(this).attr('name')+'&value='+$(this).val());
			//if ($(this).attr('id') == 'basket_city_search')
			//	$.get('/order/save_address/?field='+$('#basket_city').attr('name')+'&value='+$('#basket_city').val());
		});
	},
	
	editAddress: function(obj) {
		
		$(obj).next("input").attr("checked", "checked");
		
		$(".box-input-result").removeClass(order.errorClass+', '+order.validClass);
		
		$.get($(obj).attr("href"), function (response){
			response = eval('('+response+')');
			
			if (response.phone != undefined && response.phone != '' ) 			{$("#basket_phone").val(response.phone).css(order.filled_inpt_css); order.validateInput("#basket_phone", true);}
			if (response.skype != undefined && response.skype != '') 			{$("#basket_skype").val(response.skype).css(order.filled_inpt_css); order.validateInput("#basket_skype", true);}
			if (response.name != undefined &&  response.name !=  '') 			{$("#basket_fio").val(response.name).css(order.filled_inpt_css); 	order.validateInput("#basket_fio", true);}			
			if (response.country != undefined && response.country != '') 		{$("select#basket_country option[value="+response.country+"]").attr("selected", "selected").css(order.filled_inpt_css); }
			
			if (response.city != undefined && response.city != '') 				{$("#basket_city").val(response.city).css(order.filled_inpt_css); }
			if (response.city_name != undefined && response.city_name != '') 	{$("#basket_city_search").val(response.city_name); 					 order.validateInput("#basket_city_search", true);}
			
			if (response.city != undefined && response.city > 1) {	
				if (response.postal_code != undefined){$("#postal_code").val(response.postal_code).css(order.filled_inpt_css).css({visibility:'visible'}).show(); order.validateInput("#postal_code", true);}
			} else {
				$("#postal_code").hide();
			}
			if (response.address != undefined && response.address != '') 		{$("#basket_comm").val(response.address).css(order.filled_inpt_css); order.validateInput("#basket_comm", true); }
			
			$('.step1-form').slideDown();
		});
	}
	
}

</script>

{/literal}
