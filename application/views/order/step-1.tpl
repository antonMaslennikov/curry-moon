<!-- step-1.tpl -->

{if $address}

<div class="part-makingOrder" align="center">
	<form action="/{$module}/" method="post">
		<div class="orderData-oldAdress" style="width:750px;">
			<table class="orderData">
			<tr>
				<th colspan="4">
					<div style="margin-top:20px; margin-bottom:20px; font-size:16px; text-transform:none; text-align:center">
						Ваши адреса доставки
					</div>
				</th>
			</tr>
			<tbody id="oldadresses">
		
				{foreach from=$address item="row"}
				<tr>
					<td class="radioinput">
						<a class="edit-delvaddr" onclick="javascript: order.editAddress(this); return false;" href="/{$module}/get_address/{$row.id}/">Изменить</a>
						<input type="radio" name="prev_address" value="{$row.id}" {$row.checked} />
					</td>
					<td class="adress" style="margin-left:0px; padding-left:0px; width:45%">
						{$row.full_address}
					</td>
					<td class="date" style="40%; font-size:11px; text-align:right"><span>{$row.order_date}</span></td>
					<td class="delete" style="5%"><a href="/{$module}/delete_address/{$row.id}" onclick="javascript: return confirm('Вы действительно хотите удалить этот адрес?')"><img src="/img/reborn/0.gif" alt="" title="Удалить" /></a></td>
				</tr>
				{/foreach}
		
			</tbody>
			</table>
		</div>
		<div class="submit-basket" style="margin:0px" >
			<a href="#" name="#pokazat-nugniy_blok" class="new-deliv-address-link" onclick="order.openFormNewAddr();">ввести новый адрес доставки</a>
			<input type="submit" id="submit-delivery-btn" name="save" value="Далее &rarr; Оплата и доставка" style="margin-left:40px;" />
		</div>
	</form>
</div>

{/if}

<form method="post" class="step1-form" id="delivery_address" style="display: {$new_address_showed};">
	<div class="">
		<label for="basket_fio" style="display:none">ФИО</label>
		<input name="fio" id="basket_fio" value="{$user_name}" class="input input_fio" />        <div id="fio_res" class="box-input-result"></div>
		
		<label for="basket_phone" style="display:none">номер телефона</label>
		<input name="phone" id="basket_phone" value="{$user_phone}" class="input input_phone" /> <div id="phone_res" class="box-input-result"></div> <div class="input_error" id="basket_phone_err">Только цифры</div>
		
		{*
		<label for="basket_skype" style="display:none">Skype (не обязательно)</label>
		<input name="skype" id="basket_skype" value="{$user_skype}" class="input input_skype" /> <div id="skype_res" class="box-input-result"></div>
		*}
		
		<label for="basket_email" style="display:none">E-mail</label>
		<input name="email" id="basket_email" value="{$user_email}" class="input input_email" style="width:250px" /> <div id="email_res" class="box-input-result"></div><div class="input_error" id="basket_email_err">Это не похоже на email</div>
		
		<!-- Второй уровень ввода данных -->
		<div class="checkbox_samovivoz">
			<input type="checkbox" name="samovivoz" id="basket_samovivoz" class="checkbox" />
			<label for="basket_samovivoz">Самовывоз (<em>Москва, м.Бауманская <a href="/about/" target="_blank">полный адрес</a></em>)</label>
		</div>
		<div class="no_samovivoz">
			<select name="country" id="basket_country" class="input select_country">
				<option value="838">Россия</option>
				
				{foreach from=$country item="c"}			
				<option value="{$c.country_id}" {$c.selected}>{$c.country_name}</option>
				{/foreach}
				
			</select>
			<label for="basket_city" style="display:none">Город</label>
			<input name="city_name" id="basket_city_search" value="{if $default_city_id}{$default_city_name}{else}Москва{/if}" class="input input_city" /> <div id="city_res" class="box-input-result">
			<input type="hidden" name="city" id="basket_city" value="{if $default_city_id}{$default_city_id}{else}1{/if}" _value="{if $default_city_id}{$default_city_name}{else}Москва{/if}" />
			</div>
			
			<label for="basket_city" style="display:none">Почтовый индекс</label>
			<input name="postal_code" id="postal_code" value="{$default_postal_code}" class="input input_postcode" /> <div id="postal_code_res" class="box-input-result"></div>
			
			<label for="basket_comm" style="display:none">Адрес доставки</label>
			<textarea name="address_comment" id="basket_comm" class="input input_comment">{$default_address}</textarea><div id="address_res" class="box-input-result"></div>
		</div>	
		<!-- Третьий уровень формы --> 
		<div class="checkbox_subs" style="visibility:{$subscrube_visible}">
			<input type="checkbox" name="subscribe" id="basket_subscribe" class="checkbox" checked="checked" />
			<label for="basket_subscribe">Подписаться на новинки и акции</label>
		</div>
		<div class="submit-basket">
			<input type="submit" name="save" value="Далее &rarr; Оплата и доставка" />
		</div>
		<div class="you_return">За этот заказ Вам вернется <strong>{$bonusBack} руб.</strong></div>
		
	</div>		
</form>

{literal}
<link rel="stylesheet" href="/css/jquery.autocomplete.css" type="text/css" />
<script type="text/javascript" src="/js/jquery.autocomplete.pack.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$('#basket_country').change(function(){
			$('#basket_city_search').val('').attr('placeholder','Город').focus();
		});
		
		$("#basket_city_search").
			autocomplete('/ajax/?action=city_autocomplit').
			result(function(event, item) {
				$('#basket_city').val(item[1]).change();
				if (item[1] == '1')
					$('#postal_code, #postal_code_res').hide().removeClass('error').val('');
				else
					$('#postal_code').show().css('visibility','visible');
			});
	});
</script>
{/literal}

<div class="additional-markers" style="display: {$new_address_showed};"><img src="/images/basket/bottom-marks.gif" width="586" height="99" alt="Maryjane.ru - Самый безопасный магазин футболок"/></div>