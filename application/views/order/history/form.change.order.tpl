<div class="wrap-change-bg"></div>

<div class="wrap-change">
	<a href="#" rel="nofollow" class="close-popup" title="Завершить"></a>
	<div class="change">
		<div class="goods-wrap clearfix">
			<div class="t-change">Обмен заказа</div>
			<form action="/orderhistory/return/" method="post">
				<a href="#" rel="nofollow" class="selectAll" style="display:none">Выбрать все</a>
				
				<div class="body"></div>
				
				<div class="summ-bonus-exchange clear">
					<label class="text">Укажите причину</label>
					<select class="cause" name="cause" required="required">
						<option value=""></option>
						<option value="4">Мало</option>
						<option value="5">Велико</option>
						<option value="2">Брак</option>
						<option value="3">Другое</option>
					</select>				
				</div>
				<textarea name="exchange_reasone" placeholder="Комментарий"></textarea>
				<div class="personal-account clear">
					<p class="personal-account-text">Будет начислено на ЛС
						<a href="/faq/group/10/view/203/" target="_blank" class="personal-account-tooltips" id="account-tooltips-green">
							<span class="personal-account-tooltips-green"></a>
						</a> 
					</p>
					<span class="sum" title="Выберите позицию для обмена">0</span>
					<div class="img-change">
						<input type="hidden" name="next" value="{$CURRENT_URL}">
						<input type="hidden" name="submit" value="true">
						<input type="submit" title="" value="">
					</div>
				</div>
				<div class="form-element-checkbox">
					<input type="checkbox" name="cashback">Я хочу вернуть деньги 
					<a href="/faq/group/10/view/203/" target="_blank" class="checkbox-tooltips-green" id="checkbox-tooltips"></a>
				</div>
			</form>		
		</div>
	</div>
</div>

<script>
	$(document).ready(function(){ 
		
		$('.wrap-change form').submit(function() {
			
			if ($('select[name=cause]').val() == 3 && $('textarea[name=exchange_reasone]').val().length == 0) {
				alert('Вы должны обязательно оставить коментарий к возврату	');
				return false;
			} 
			
			if ($('select[name=cause]').val().length == 0) {
				alert('Выберите причину возврата');
				return false;
			}
			
			
			trackUser('Order.v3','возврат', 'оформление возврата');//трек аналитика							

			var msg = $('.wrap-change form').serialize();
			var url = $('.wrap-change form').attr('action');
			
			$.ajax({
				type: 'POST',
				url: url,
				data: msg,
				success: function(data) {
					data = eval('(' + data + ')');
					
					if (data.status == 'ok') {
						
						$('.wrap-change, .wrap-change-bg').toggle();							
						$('.loadRefundExchangeHint').show();
						
						if ($('input[name=cashback]:checked').length == 1)
							tb_show("", "#TB_inline?width=600&amp;height=130&amp;inlineId=refundExchangeHint2");
						else
							tb_show("", "#TB_inline?width=600&amp;height=140&amp;inlineId=refundExchangeHint");
						
						$('.refundExchangeHint').hide();
						
						$('.refundExchangeHint').show();
						$('.ypravlenie_zakazom .otstupLeft').css("width",'9px');
						$('.ypravlenie_zakazom .trackingCode, .ypravlenie_zakazom .n-zakaz').hide();
						$('.ypravlenie_zakazom .ya-peredumal').show();
						$('.loadRefundExchangeHint').hide();
					} else {
						alert(data.message);
					}
				},
				error:  function(xhr, str){
					$('.loadRefundExchangeHint').hide();
					alert('Возникла ошибка на сервере, попробуйте немного позже');
					trackUser('Order.v3','возврат - Ошибки', 'возникла ошибка на сервере при оформлении в модульном окне');//трек аналитика										
				}									
			});
			return false;							
			
		});
		
	});
</script>

<div id="refundExchangeHint" style="display:none">
	<div class="refundExchangeHint" style="display:none">		
		<span>На Ваш счёт начислено <font class="prc">100</font> руб. Оформите новый заказ.</span>
		<br/>
		<a href="/orderhistory/return/cancel/" rel="nofollow">Я передумал!</a>		
	</div>
	<div class="loadRefundExchangeHint" style="display:none"></div>
</div>

<div id="refundExchangeHint2" style="display:none">
	<div class="refundExchangeHint" style="display:none">		
		<span>На Ваш счёт начислено <font class="prc">100</font> руб.<br />Вы можете <a href="/payback/">вывести их</a> удобным Вам способом.</span>
		<br/>
		<a href="/orderhistory/return/cancel/" rel="nofollow">Я передумал!</a>
	</div>
	<div class="loadRefundExchangeHint" style="display:none"></div>
</div>