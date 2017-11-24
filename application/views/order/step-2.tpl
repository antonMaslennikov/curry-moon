<!-- step-2.html -->

<script src="http://api-maps.yandex.ru/2.0-stable/?load=package.standard&lang=ru-RU" type="text/javascript"></script>

<div class="you_save">
	<center>За этот заказ вам вернется <strong>{$bonusBack} руб.</strong></center>
</div>

<div class="b-deliver-payment">
	
	<div class="address" style="margin-bottom:20px">
		<center>{$deliveryAddress}</center>
	</div>
	
	<form action="" method="post" id="delivery_payment_form">
		<div class="b-deliver">			
			{if $deliveryTypes.deliveryboy}
			<div class="radio">
				<input type="radio" name="data[delivery]" value="deliveryboy" id="delivery_kurer" {$deliveryTypes.deliveryboy.selected} />
				<label for="delivery_kurer">
					<span class="label"><b>Курьер</b> (На след. день)</span>
					<sup class="help"><a href="/faq/38/?TB_iframe=true&amp;height=500&amp;width=600" rel="nofollow" class="thickbox help">?</a></sup>
					<b class="sub-price-bonuses">{if $deliveryTypes.deliveryboy.cost == 0} бесплатно {else} {$deliveryTypes.deliveryboy.cost} руб.{/if}</b>
				</label>
			</div>
			
			<div class="select select_time more_options_box" style="display:none;">
				<span style="padding:0 0 0 29px">Удобное время</span><br /><br />
				<span style="float:left;padding:0px 0 0 29px;padding-top:3px">с</span>

				<select name="data[kurer_time][start]" id="deliver_kurer_time_s" style="margin-left:10px; width:80px;">
					<option value=""></option>
					{foreach from=$time item="h"}
					<option value="{$h}">{$h}:00</option>
					{/foreach}
				</select>

				<span style="float:left;padding-left:20px;padding-top:3px">до</span>
				
				<select name="data[kurer_time][end]" id="deliver_kurer_time_e" style="margin-left:10px; width:80px;">
					<option value=""></option>
					{foreach from=$time item="h"}
					<option value="{$h}">{$h}:00</option>
					{/foreach}
				</select>

				<label for="delivery_kurer_comment" class="comm-label">Комментарий:</label>
				<textarea name="data[comment_text_kurer]" id="delivery_kurer_comment" class="sub_comment"></textarea>
			</div>
			{/if}
		
			{if $deliveryTypes.user}
			<div class="radio check_samovivoz">
				<input type="radio" name="data[delivery]" id="deliver_samovivoz" value="user"  id="user" {$deliveryTypes.user.selected} />
				<label for="deliver_samovivoz">
					<span class="label"><b>Самовывоз</b> (м. Бауманская)</span>
					<sup class="help"><a href="/faq/37/?TB_iframe=true&amp;height=500&amp;width=600" rel="nofollow" class="thickbox help">?</a></sup>
					<b class="sub-price-bonuses">бесплатно</b>
				</label>
			</div>
			<div class="more_options_box samovivoz_comment" style="display:none;">
				<label for="delivery_samovivoz_comment" class="comm-label">Комментарий:</label>
				<textarea name="data[comment_text_samovivoz]" id="delivery_samovivoz_comment" class="sub_comment"></textarea>
			</div>
			{/if}
			
			{if $deliveryTypes.metro}
			<div class="radio select_metro">
				<input type="radio" name="data[delivery]" value="metro" id="deliver_metro" {$deliveryTypes.metro.selected} />
				<label for="deliver_metro">
					<span class="label"><b>В метро</b> (Кольцевая)</span>
					<sup class="help"><a href="/faq/131/?TB_iframe=true&amp;height=500&amp;width=600" rel="nofollow" class="thickbox help">?</a></sup>
					<b class="sub-price-bonuses">{if $deliveryTypes.metro.cost == 0} бесплатно {else} {$deliveryTypes.metro.cost} руб.{/if}</b>
				</label>
			</div>
			
			<div class="select sel_metro more_options_box" style="display:none;">
				<select name="data[select_metro]" id="select_metro">
					<option value="" class="first">Выберите метро</option>
					 
					<!-- BEGIN station -->
					<!-- option value="{$metro.station.metro_id}" {$metro.station.selected}>{$metro.station.metro_name}</option-->
					<!-- END station -->
					
					<option value="35">Белорусская</option>
					<option value="68">Новослободская</option>
					<option value="69">Проспект Мира</option>
					<option value="6">Комсомольская</option>
					<option value="46">Курская</option>
					<option value="72">Таганская</option>
					<option value="30">Павелецкая</option>
					<option value="74">Добрынинская</option>
					<option value="75">Октябрьская</option>
					<option value="13">Парк Культуры</option>
					<option value="42">Киевская</option>
					<option value="120">Баррикадная</option>
					
				</select>
				
				<div class="error-croshair" style="visibility:hidden;"></div>
				<div class="error-text" style="visibility:hidden;">Укажите метро</div>
				
				<div class="input good_time">
					<label for="metro_time" style="display:none">Удобное время</label>
					<input type="text" name="data[metro_time]" id="metro_time" value="Удобное время" />
				</div>
				
				<label for="delivery_metro_comment" class="comm-label">Комментарий:</label>
				<textarea name="data[comment_text_metro]" id="delivery_metro_comment" class="sub_comment"></textarea>
			</div>
			{/if}
			
			{if $deliveryTypes.IMlog_self}
			<div class="radio check_post">
				<input type="radio" name="data[delivery]" value="IMlog_self"  id="deliver_IMlog_self" {$deliveryTypes.IMlog_self.selected} />
				<label for="deliver_IMlog_self">
					<span class="label"><b>Самовывоз</b></span>
					{*<sup class="help"><a href="/faq/145/?TB_iframe=true&amp;height=500&amp;width=600" rel="nofollow" class="thickbox help">?</a></sup>*}
					<b class="sub-price-bonuses">{if $deliveryTypes.IMlog_self.cost == 0} бесплатно {else} {$deliveryTypes.IMlog_self.cost} руб.{/if}</b>
				</label>
				<div style="padding-left:28px;padding-top: 5px">Время доставки: {$IMlog_self_delivery_time}</div>
			</div>
			<div class="more_options_box" style="display:none; margin:-32px 0 10px;">

				<div style="width:297px;margin-top:25px;margin-left:26px">
					
					{if $IMlog_self_delivery_points|count > 1}
						<a href="/order/selfDeliveryMap/{$city.id}/IMlog_self/?width=600&height=508" class="thickbox dashed" style="display:block;width:103px;margin-bottom:5px">Выбрать на карте</a>
					{/if}
					
					<select class="sub_comment" name="IMlog_self_point" {if $IMlog_self_delivery_points|count <= 1}style="display:none"{/if}>
						<option value="">Выберите пункт</option>
						{foreach from=$IMlog_self_delivery_points name="IMlog_self_delivery_points" item="point" key="k"}
						<option value="{$point.id}" point_id="{$point.id}" address="{$point.address}">{$point.name}</option>
						{/foreach}
					</select>
					
					<div id="IMlog_self_point_address" style="margin-top:5px;{if $IMlog_self_delivery_points|count > 1}display:none{/if}">
						
						{if $IMlog_self_delivery_points|count == 1}
							{foreach from=$IMlog_self_delivery_points item="point" key="k"}
								<span>{$point.address}</span> 
								<a href="/order/selfDeliveryMap/{$city.id}/IMlog_self/{$point.id}/?width=600&height=608" class="thickbox dashed" style="margin-left:10px">как добраться</a></div>
							{/foreach}
						{else}
							<span></span> <a href="#" class="showonmap dashed" style="margin-left:10px">как добраться</a></div>
						{/if}
					
					<script>
						$(document).ready(function(){
							
							if ($('select[name=IMlog_self_point]').children(':selected').val().length > 0) 
								$('#IMlog_self_point_address').show().children('span').text($('select[name=IMlog_self_point]').children(':selected').attr('address'));
							
							$('select[name=IMlog_self_point]').change(function(){
								$('#IMlog_self_point_address').show().children('span').text($(this).children(':selected').attr('address'));
							})
							
							$('.showonmap').click(function(){
								tb_show('', '/order/selfDeliveryMap/{$city.id}/IMlog_self/' + $('select[name=IMlog_self_point]').children(':selected').attr('point_id') + '/?width=600&height=608');
								return false;
							});
						});
					</script>
					
				</div>
				
				<label for="delivery_dpd_comment" class="comm-label" style="padding-top:15px">Комментарий:</label>
				<textarea name="data[comment_text_IMlog_self]" id="delivery_IMlog_self_comment" class="sub_comment"></textarea>
			</div>
			{/if}
			
			{if $deliveryTypes.IMlog}
			<div class="radio check_post">
				<input type="radio" name="data[delivery]" value="IMlog"  id="deliver_IMlog" {$deliveryTypes.IMlog.selected} />
				<label for="deliver_IMlog">
					<span class="label"><b>Курьером IML</b></span>
					{*<sup class="help"><a href="/faq/145/?TB_iframe=true&amp;height=500&amp;width=600" rel="nofollow" class="thickbox help">?</a></sup>*}
					<b class="sub-price-bonuses">{if $deliveryTypes.IMlog.cost == 0} бесплатно {else} {$deliveryTypes.IMlog.cost} руб.{/if}</b>
				</label>
				<div style="padding-left:28px;padding-top: 5px">Время доставки: {$IMlog_delivery_time}</div>
			</div>
			<div class="more_options_box" style="display:none; margin:-32px 0 28px;">
				
				<label for="delivery_dpd_comment" class="comm-label">Комментарий:</label>
				<textarea name="data[comment_text_IMlog]" id="delivery_IMlog_comment" class="sub_comment"></textarea>
			</div>
			{/if}
			
			{if $deliveryTypes.post}
			<div class="radio check_post">
				<input type="radio" name="data[delivery]" value="post"  id="deliver_post" {$deliveryTypes.post.selected} />
				<label for="deliver_post">
					<span class="label"><b>Почта России</b></span>
                    <sup class="help"><a href="/faq/39/?TB_iframe=true&amp;height=500&amp;width=600" rel="nofollow" class="thickbox help">?</a></sup>
					<b class="sub-price-bonuses">{if $deliveryTypes.post.cost == 0} бесплатно {else} {$deliveryTypes.post.cost} руб.{/if}</b>
				</label>
				<div style="padding-left:28px;padding-top: 5px">Время доставки: 2 - 4 недели</div>
			</div>
			
			<div class="more_options_box" style="display:none; margin:-32px 0 28px;">
				
				<label for="delivery_post_comment" class="comm-label">Комментарий:</label>
				<textarea name="data[comment_text_post]" id="delivery_post_comment" class="sub_comment"></textarea>
			</div>
			{/if}
			
			{if $deliveryTypes.dpd}
			<div class="radio check_post">
				<input type="radio" name="data[delivery]" value="dpd"  id="deliver_dpd" {$deliveryTypes.dpd.selected} />
				<label for="deliver_dpd">
					<span class="label"><b>курьер DPD лично в руки</b></span>
					<sup class="help"><a href="/faq/145/?TB_iframe=true&amp;height=500&amp;width=600" rel="nofollow" class="thickbox help">?</a></sup>
					<b class="sub-price-bonuses">{if $deliveryTypes.dpd.cost == 0} бесплатно {else} {$deliveryTypes.dpd.cost} руб.{/if}</b>
				</label>
				<div style="padding-left:28px;padding-top: 5px">Время доставки: {$dpd_delivery_time} {if $dpd_delivery_time == 1}день{elseif $dpd_delivery_time >= 2 && $dpd_delivery_time <= 4}дня{else}дней{/if}</div>
			</div>
			<div class="more_options_box" style="display:none; margin:-32px 0 28px;">
				
				<label for="delivery_dpd_comment" class="comm-label">Комментарий:</label>
				<textarea name="data[comment_text_dpd]" id="delivery_dpd_comment" class="sub_comment"></textarea>
			</div>
			{/if}
			
			{if $deliveryTypes.baltick}
			<div class="radio check_post">
				<input type="radio" name="data[delivery]" value="baltick"  id="deliver_baltick" {$deliveryTypes.baltick.selected} />
				<label for="deliver_baltick">
					<span class="label"><b>Курьер до квартиры</b></span>
					{*<sup class="help"><a href="/faq/145/?TB_iframe=true&amp;height=500&amp;width=600" rel="nofollow" class="thickbox help">?</a></sup>*}
					<b class="sub-price-bonuses">{if $deliveryTypes.baltick.cost == 0} бесплатно {else} {$deliveryTypes.baltick.cost} руб.{/if}</b>
				</label>
				<div style="padding-left:28px;padding-top: 5px">Время доставки: {$baltick_delivery_time} {if $baltick_delivery_time == 1}день{elseif $baltick_delivery_time >= 2 && $baltick_delivery_time <= 4}дня{else}дней{/if}</div>
			</div>
			<div class="more_options_box" style="display:none; margin:-32px 0 28px;">
				
				<label for="delivery_dpd_comment" class="comm-label">Комментарий:</label>
				<textarea name="data[comment_text_baltick]" id="delivery_dpd_comment" class="sub_comment"></textarea>
			</div>
			{/if}
			
			{if $deliveryTypes.ems}
			{*
			<div class="radio check_post">
				<input type="radio" name="data[delivery]" value="ems"  id="deliver_ems" {$deliveryTypes.ems.selected} />
				<label for="deliver_ems">
					<span class="label"><b>EMS Почта России</b></span>
					<sup class="help"><a href="/faq/77/?TB_iframe=true&amp;height=500&amp;width=600" rel="nofollow" class="thickbox help">?</a></sup>
					<b class="sub-price-bonuses">{$deliveryTypes.ems.cost} руб.</b>
				</label>
			</div>
			<div class="more_options_box" style="display:none; margin:-32px 0 48px;">
				<label for="delivery_ems_comment" class="comm-label">Комментарий:</label>
				<textarea name="data[comment_text_ems]" id="delivery_ems_comment" class="sub_comment"></textarea>
			</div>
			*}
			{/if}
			
			<div class="checkbox chk_comment" style="display:none;">
				<!-- input type="checkbox" name="data[is_comment]" id="is_comment" checked="checked" /-->
				<label for="is_comment"><b>Комментарий</b></label>
			</div>
			<div class="commment_text" style="display:none;">
				<textarea name="data[comment_text]" id="comment_text"></textarea>
			</div>
			
		
		</div>
	
		<div class="b-payment">
			{if $USER->user_bonus > 0}
			<div class="checkbox my-bonuses">
			
				<input type="checkbox" name="data[my_bonuses]" id="my_bonuses" checked="checked" />
				<input type="hidden" name="my_bonuses" id="my_bonuses_value" value="{$USER->user_bonus}" />
				<label for="my_bonuses">
					<b>Мои бонусы</b>
					<b class="sub-price-bonuses">{$USER->user_bonus} руб.</b>
				</label>
				{literal}
				<script>
				
					function rebuildPaymentPrice() 
					{
						var checked = $('#my_bonuses').attr('checked');
						var delivery = $('.b-deliver input[type=radio]:checked').parent().find('.sub-price-bonuses').text();
						delivery = parseFloat(delivery);
						
						$('.payment-type-radio').each(function() {
							if (checked) {
								
								if (parseInt($('#my_bonuses_value').val()) > parseInt($('#' + $(this).val() + '-total-price').val()) + delivery) {
									//var p =  '0 руб.';
									$(this).parent().hide();
								} else {
									var p = ($('#' + $(this).val() + '-total-price').val() - $('#my_bonuses_value').val()) + ' руб.';
								}
							} else {
								var p = ($('#' + $(this).val() + '-total-price').val()) + ' руб.';
								$(this).parent().show();
							}

							$('#' + $(this).val() + '-price').text(p);
							
						});
					}
				
				
					$(document).ready(function(){
						rebuildPaymentPrice();
					});
				
					$('#my_bonuses').change(function() {						
						rebuildPaymentPrice();
						order.initMyBobusSum();
					});
				</script>
				{/literal}
			</div>
			{/if}
			
			
			{if $paymentTypes.CASH}
			<div class="radio payment_by_cash">
				<input type="radio" name="data[payment_method]" class="payment-type-radio" value="cash" id="payment_by_cash" {$paymentTypes.CASH.selected} />
				<label for="payment_by_cash" class="lbl-paymeth">
					<span class="label">Наличный расчёт</span>
					{*<sup class="help"><a href="/faq/112/?TB_iframe=true&amp;height=500&amp;width=600" rel="nofollow" class="thickbox help">?</a></sup>*}
					<b class="sub-price" id="cash-price">{$paymentTypes.CASH.total_without_bonuses} руб.</b>
					<input type="hidden" id="cash-total-price" value="{$paymentTypes.CASH.total}" />
				</label>
			</div>
			{/if}
			
			{if $paymentTypes.CASHON}
			<div class="radio payment_by_cashon">
				<input type="radio" name="data[payment_method]" class="payment-type-radio" value="cashon" id="payment_by_cashon" {$paymentTypes.CASHON.selected} />
				<label for="payment_by_cash" class="lbl-paymeth" title="Оплата при получении">
					<span class="label">Наложенный платёж</span>
					{*<sup class="help"><a href="/faq/112/?TB_iframe=true&amp;height=500&amp;width=600" rel="nofollow" class="thickbox help">?</a></sup>*}
					<b class="sub-price" id="cashon-price">{$paymentTypes.CASHON.total_without_bonuses} руб.</b>
					<input type="hidden" id="cashon-total-price" value="{$paymentTypes.CASHON.total}" />
				</label>
			</div>
			{/if}
			
			{if $paymentTypes.CREDITCARD}
			<div class="radio payment_by_cards">
				<input type="radio" name="data[payment_method]" class="payment-type-radio" value="creditcard" id="payment_by_cards" {$paymentTypes.CREDITCARD.selected} {if $paymentTypes.CREDITCARD.disabled}disabled=""{/if} />
				<label for="payment_by_cards" class="lbl-paymeth">
					<span class="label">Банковской картой</span>
					<sup class="help"><a href="/faq/88/?TB_iframe=true&amp;height=500&amp;width=600" rel="nofollow" class="thickbox help">?</a></sup>
					<b class="sub-price" id="creditcard-price">{$paymentTypes.CREDITCARD.total_without_bonuses} руб.</b>
					<input type="hidden" id="creditcard-total-price" value="{$paymentTypes.CREDITCARD.total}" />
				</label>
			</div>
			{/if}
			
			{if $paymentTypes.SBERBANK}
			<div class="radio payment_by_bank">
				<input type="radio" name="data[payment_method]" class="payment-type-radio" value="sberbank" id="payment_by_bank" {$paymentTypes.SBERBANK.selected} />
				<label for="payment_by_bank" class="lbl-paymeth">
					<span class="label">Через банк (квитанция)</span>
					<sup class="help"><a href="/faq/87/?TB_iframe=true&amp;height=500&amp;width=600" rel="nofollow" class="thickbox help">?</a></sup>
					<b class="sub-price" id="sberbank-price">{$paymentTypes.SBERBANK.total_without_bonuses} руб.</b>
					<input type="hidden" id="sberbank-total-price" value="{$paymentTypes.SBERBANK.total}" />
				</label>
			</div>
			{/if}
			
			{if $paymentTypes.YAMONEY}
			<div class="radio payment_by_ymoney">
				<input type="radio" name="data[payment_method]" class="payment-type-radio" value="yamoney" id="payment_by_yamoney" {$paymentTypes.YAMONEY.selected} />
				<label for="payment_by_yamoney" class="lbl-paymeth">
					<span class="label">Яндекс.Деньги</span>
					<sup class="help"><a href="/faq/86/?TB_iframe=true&amp;height=500&amp;width=600" rel="nofollow" class="thickbox help">?</a></sup>
					<b class="sub-price" id="yamoney-price">{$paymentTypes.YAMONEY.total_without_bonuses} руб.</b>
					<input type="hidden" id="yamoney-total-price" value="{$paymentTypes.YAMONEY.total}" />
				</label>
			</div>
			{/if}
			
			{if $paymentTypes.WEBMONEY}
			<div class="radio payment_by_webymoney">
				<input type="radio" name="data[payment_method]" class="payment-type-radio" value="webmoney" id="payment_by_webymoney" {$paymentTypes.WEBMONEY.selected} />
				<label for="payment_by_webymoney" class="lbl-paymeth">
					<span class="label">Web Money</span>
					<sup class="help"><a href="/faq/85/?TB_iframe=true&amp;height=500&amp;width=600" rel="nofollow" class="thickbox help">?</a></sup>
					<b class="sub-price" id="webmoney-price">{$paymentTypes.WEBMONEY.total_without_bonuses} руб.</b>
					<input type="hidden" id="webmoney-total-price" value="{$paymentTypes.WEBMONEY.total}" />
				</label>
			</div>
			{/if}
			
			{if $paymentTypes.QIWI}
			<div class="radio payment_by_qiwi">
				<input type="radio" name="data[payment_method]" class="payment-type-radio" value="qiwi" id="payment_by_qiwi" {$paymentTypes.QIWI.selected} />
				<label for="payment_by_qiwi" class="lbl-paymeth">
					<span class="label">Qiwi</span>
					<sup class="help"><a href="/faq/84/?TB_iframe=true&amp;height=500&amp;width=600" rel="nofollow" class="thickbox help">?</a></sup>
					<b class="sub-price" id="qiwi-price">{$paymentTypes.QIWI.total_without_bonuses} руб.</b>
					<input type="hidden" id="qiwi-total-price" value="{$paymentTypes.QIWI.total}" />
				</label>
			</div>
			{/if}
		</div>
		
		<div class="submit-basket">
		<input type="submit" name="save" value="Далее &rarr; Подтвердить заказ" />
		</div>
		
	</form>
	
	<div class="important-info">
		<img src="/images/basket/secur-ssl3.gif" class="sec-img" alt="Securet by thawte" />
		
		<div class="important-text">
			<h3>Важная информация:</h3>
			<ul>
				<li>- Все платежи обрабатываются нашим партнером, системой Assist через защищенный протокол</li>
				<li>- Компания Maryjane не хранит никакой информации о платежах</li>
				<li>- Защита транзакций обеспечивается при помощи протокола SSL 3.0 и сертификатов с длинной ключа 128 бит, предоставляемых сертификационной компанией Thawte.</li>
			</ul>
		</div>
	
	</div>
	
</div>