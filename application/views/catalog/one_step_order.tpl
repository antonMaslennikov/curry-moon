<div class="one-steporder-form">
	<div class="small_title" style="margin: -30px 0 0 4px;">Заказ в один шаг</div>
	<div style="text-align:right;width:21px;margin-top:-30px;float:right;"><a href="#" onclick="tb_remove()"><img src="http://www.maryjane.ru/images2/icons/delete.png" alt="close" /></a></div>
	
	
	<div style="width: 390px; height: 350px;" class="" id="element_1">
		<div class="fastOrder marked" id="element_0">
		
		<div style="" class="BoxFastOrder">
		
			{* <div class="BoxFastOrderTitle">#{$basket->id}</div> *}
		
			<form id="fast-order" method="post" action="/ajax/catalog_one_step_order/">
				
				<input type="hidden" name="good_id" value="{$good_id}" />
				<input type="hidden" name="good_stock_id" value="{$good_stock_id}" />
				<input id="one_step_order_comment" type="hidden" />
				<input id="one_step_order_price" type="hidden" />
				
				
				    <ul class="form-list">
	                <li>
		    	        <div class="field name-fio">
				            <label class="required BoxFastOrderLabel" for="fio">ФИО<em>*</em></label>
				            <div class="input-box">
				                <input type="text" class="input-text validate-custom required" title="ФИО" value="{$USER->user_name}" name="name" id="fio" style="float:left" />
				                <div class="box-input-result" id="box-input-result-fio"></div>
				            </div>
				        </div>
	                </li>
					<li>
						<div class="field customer-phone">
							<label class="required BoxFastOrderLabel" for="phone1">Телефон для связи<em>*</em></label>
							<div>
							    <input type="text" value="" name="phone" id="phone_input" class="required inpText inp3Num input-text field2" size="9" style="float:left" />
								<div class="box-input-result" id="box-input-result-phone_input"></div>
							</div>
						</div>
					</li>
					<li>
	                    <div class="field">
	                        <label for="email_address " class="required BoxFastOrderLabel">Email{*<em>*</em>*} (не обязательно)</label>
	                        <div class="input-box">
	                            <input type="text" name="email" id="email" value="{$USER->user_email}" title="Email" class="{* required *} input-text validate-email" style="float:left" />
	                            <div class="box-input-result" id="box-input-result-email"></div>
	                        </div>
	                    </div>
	                </li>
	                <li>
	                    <div class="field">
	                        <label for="address" class="BoxFastOrderLabel">Адрес доставки</label>
	                        <div class="input-box">
	                            <textarea name="address" id="address" class="input-long-text" type="text"></textarea>
	                        </div>
	                    </div>
	                </li>
	                <li>
	                    <div class="field">
	                        <label for="comment" class="BoxFastOrderLabel">Комментарий к заказу</label>
	                        <div class="input-box">
	                        	<textarea name="user_comment" class="input-long-text"></textarea>
	                        </div>
	                    </div>
	                </li>
	                <li>
	                    <div class="field center" style="margin-top: 25px; width:295px;">
	                        <input type="submit"  name="submit" class="buttonSubmit" value="Оформить заказ" />
					        <span style="display:none;" class="please-wait">
					            <img class="v-middle" title="Отправление информации о заказе..." alt="Отправление информации о заказе..." src="http://www.proactiv.ru/skin/frontend/default/default/images/opc-ajax-loader.gif"> Отправление информации о заказе...				        </span>
	                    </div>
	                </li>
				</ul>
			</form>
			
		</div>
	</div>
	</div>
	
	{literal}
	<div>
	<script type="text/javascript">
	
	//$(document).ready(function(){
		var data = goodForm.getDesignData();
		if (data) {
			//var gsId   = goodForm.designData[goodForm.curCategory][goodForm.curGender].sizes[goodForm.curSize].colors[goodForm.curColor].id;
			var gsId   = data.sizes[goodForm.curSize].colors[goodForm.curColor].id;
			$('#one_step_order_comment').attr('name', 'comment['+gsId+']').val(goodForm.getDefaultCommens());
			$('#one_step_order_price').attr('name', 'price['+gsId+']').val(/[\d]+/.exec($('.price-order .current-price').text())[0]);
		}
	//});
	
function validateOneStepForm () {
	var check = true;
	
	// Валидация на пустые значения
	$('input.required').each(function (o) {
		if ($(this).val().length == 0) {
			$(this).addClass('red-input');
			$('#box-input-result-' + $(this).attr('id')).addClass('error');
			check = false;
		} else {
			$(this).removeClass('red-input');
			$('#box-input-result-' + $(this).attr('id')).removeClass('error').addClass('complite');
		}
	});
	
	var title = $('#phone_input').prev('label').text();
	if ($('#phone_input').val().length == 0 || $('#phone_input').val().length < 6 || $('#phone_input').val() == title) {
		$('#phone_input').addClass('red-input');
		$('#box-input-result-phone_input').addClass('error');
		check = false;
	}
	
	var int_ = $('#phone_input').val();
	var re = /^[0-9]*$/;
	if (!re.test(int_)) {
		$('#phone_input').addClass('red-input');
		$('#box-input-result-phone_input').addClass('error');
		check = false;
	}
	
	
	if ($('#email').hasClass('required') || $('#email').val().length > 0) {
		if (!isValidEmail($('#email').val())) {
			$('#email').addClass('red-input');
			check = false;
			$('#box-input-result-email').addClass('error');
		} else {
			$('#box-input-result-email').removeClass('error').addClass('complite');
		}
	}
	
	try {console.log('validating:'+check); } catch(e){}
	return check;
	
}
	
// валидация электронной почты на лету		
$('#email').blur(function(event) {
	if ($(this).hasClass('required') || $(this).val().length > 0) {
		if ($(this).val().length > 0) { $(this).removeClass('red-input'); $('#box-input-result-' + $(this).attr('id')).removeClass('error');
			if (!isValidEmail($(this).val())) {
				$(this).addClass('red-input');
				$('#box-input-result-' + $(this).attr('id')).addClass('error')
			} else {
				$('#box-input-result-' + $(this).attr('id')).addClass('complite');
			}
		}
	}
});

// Валидация телефона на лету
$('#phone_input').keyup(function(event) {
$(this).removeClass('red-input'); $('#box-input-result-' + $(this).attr('id')).removeClass('error');
var int_ = $(this).val(); var re = /^[0-9]*$/;
if ($(this).val().length > 0) {
	if (!re.test(int_)) { $(this).addClass('red-input'); $('#box-input-result-' + $(this).attr('id')).addClass('error')} else { /*$('#box-input-result-' + $(this).attr('id')).addClass('complite');*/ }
}}).blur(function(event) {
var int_ = $(this).val();
if (int_.length > 0) { var re = /^[0-9]*$/;
		if (!re.test(int_)) { $(this).addClass('red-input'); $('#box-input-result-' + $(this).attr('id')).addClass('error') } else { $('#box-input-result-' + $(this).attr('id')).addClass('complite');}
}});

$("#fio").blur(function(event){
	if ($(this).val().length > 0) { 
		$(this).removeClass('red-input');
		$('#box-input-result-' + $(this).attr('id')).removeClass('error');
		$('#box-input-result-' + $(this).attr('id')).addClass('complite');
	} else {
		$(this).addClass('red-input');
		$('#box-input-result-' + $(this).attr('id')).addClass('error');
	}
});

// Валидация перед сабмитом формы
$('.buttonSubmit').click( function () {return validateOneStepForm()});
$("form#fast-order").submit(function(){return validateOneStepForm()});
		
	</script>
	</div>
	<style type="text/css">
.one-steporder-form {float:left;}
.one-steporder-form * {margin:0; padding:0;}

#fast-order {float:left; width:325px;}
.gray-input {border-color:#C3C3C3;color:gray;}
.red-input {border-color : red !important; color:red !important;}
.box-input-result {background: url("/images/basket/input-marker.gif") no-repeat scroll 0 24px transparent; float: left; height: 24px; margin: 1px; width: 24px;}
.box-input-result.complite { background-position: 0 -24px;}
.box-input-result.error {background-position: 0 0; }
.BoxFastOrder { padding:20px 0 0 45px; float:left; width:325px; }
.BoxFastOrderTitle { font-size: 25px; padding-bottom: 10px; padding-top: 20px; }
.BoxFastOrderSubtitle { font-size: 13px; width: 300px;}
ol, ul { list-style: none outside none;}
.form-list li { clear: both;}
.field { padding-top:10px; }
.BoxFastOrderLabel { font-size:11px; margin-right:10px; text-align:right; width:140px; color:#000000;}
.fastOrder .field2 { width: 140px; }
.input-text, .input-long-text { border:1px solid #ABABAB; color: #666666; height:20px; line-height:20px; padding:3px; width: 280px; font-size:14px; font-weight:normal; }
.input-long-text {height:50px}
.center { text-align: center;}
.required em {color:red}
.buttonSubmit { background-color: #00A851; border: 1px solid #00A851; color: #FFFFFF; cursor: pointer; height:31px; padding:7px 15px; }
.buttonSubmit:hover {background-color: #019247 !important;}
	</style>
	{/literal}

</div>