	<!--/templates/order/v3/order.tpl-->
<script src="http://api-maps.yandex.ru/2.0-stable/?load=package.standard&lang=ru-RU" type="text/javascript"></script>

<script type="text/javascript">
	var user_deliver_posible = {$user_deliver_posible};
	var deliveryboy_deliver_posible = {$deliveryboy_deliver_posible};
	var citys = {$jcitys};
	var order_delivery_date = '{$order->user_basket_delivery_date_formated}';
	var order_delivery_type = '{$order->user_basket_delivery_type}';
</script>

<input type="hidden" name="module" value="{$module}" />

<input type="hidden" name="order-address[delivery_point]" value="{$order->address.delivery_point}" />



<div class="wo2013 {$PAGE->lang}">

	<form method="post" class="quick_basket clearfix">
		
		{if $MobilePageVersion}
		<div class="mobile-ser-code-switcher" onclick="$('.promocod_actived').show();$('input[name=serCode]').focus()">
			<a href="#promo-code" class="dashed">Ввести промо-код</a>
		</div>
		{/if}
		
		<input type="hidden" name="source" value="" />
		
		{if !$MobilePageVersion}
			{include file="order/freedelivery.deliveryboy.tpl"}
		{/if}	
	
		<div class="left-wrap">	
		
		<div class="wrapper promocod_actived" {if $MobilePageVersion && $PAGE->reqUrl.1 != 'error'}style="display:none"{/if}>
			
			<a name="promo-code"></a>
			
			{if $PAGE->reqUrl.1 == 'success' && $PAGE->reqUrl.2 == '2'}
				<span class="box-input-result complite"></span>
				<span class="text complite">{$L.ORDERV3_promocode_success} {$basket->user_basket_discount}%)</span>
				{literal}<script>$(document).ready(function(){trackUser('Промокод','ok',module);});</script>{/literal}
			{else}	
				<span class="prc">{$L.ORDERV3_promocode}:</span><input type="text" class="" name="serCode" />
			{/if}
			
			{if $PAGE->reqUrl.1 == 'error' && $PAGE->reqUrl.2 == '4' || ($ERROR && $certification_error)}
				<div>
					<span class="box-input-result error" onclick="$(this).parent().hide()"></span>
					<span class="text error">{$L.ORDERV3_certificate_already_activated}</span>
					{literal}<script>$(document).ready(function(){trackUser('Промокод','error-'+module,'{/literal}{$L.ORDERV3_certificate_already_activated}{literal}');});</script>{/literal}
				</div>
			{/if}
			
			{if $PAGE->reqUrl.1 == 'error' && $PAGE->reqUrl.2 == '5'}
				<div>
					<span class="box-input-result error" onclick="$(this).parent().hide()"></span>
					<span class="text error">Время жизни данного промо-кода истекло</span>
					{literal}<script>$(document).ready(function(){trackUser('Промокод','error-'+module,'Время жизни данного промо-кода истекло');});</script>{/literal}
				</div>
			{/if}
				
		</div>
		
		
		
		
		{if $address}
		<div class="part-makingOrder clearfix" style="border: 6px solid #EFEFEF; width: 515px;margin:0 0 20px 0;">
				<div class="orderData-oldAdress" style="margin: 0 0 10px 0;">
					<table class="orderData" style="width: 456px;border: 0;margin-left: 46px;">
						<tr>
							<th colspan="4" style="background: none;text-align: left;padding:0;">
								<div style="margin:20px 0px 13px;font-size: 25px;line-height: 22px;font-weight: normal;font-family: 'MyriadPro-CondIt', arial; text-transform:none;display: inline-block;color:#504F4F;">
									{$L.ORDERV3_addresses}
								</div>
								{if $address|@count > 0}
									<a href="#pokazat-select-city" id="new_addr" style="margin: 28px 7px 0;"class="adr-show-hide-new">{$L.ORDERV3_new_address}</a>
								{/if}
							</th>
						</tr>
					<tbody id="oldadresses">
				
						{foreach from=$address item="row" key="k"}
						<tr style="display:none;">
							<td class="radioinput">
								<!--a class="edit-delvaddr" onclick="javascript: order.editAddress(this); return false;" href="/{$module}/get_address/{$row.id}/">Изменить</a-->
								<input type="radio" name="prev_address" value="{$row.id}" {$row.checked} />
							</td>
							<td class="adress" style="margin-left:0px; padding-left:0px;width: 395px;position: relative;">
								{$row.full_address}
								<div class="line"></div>
							</td>
							{*<td class="date" align="right" style="padding-right:10px;width:30%; font-size:11px; text-align:right"><span>{$row.order_date}</span></td>*}
							<td class="delete" align="right"><a href="/{$module}/delete_address/{$row.id}" onclick="javascript: return confirm('{$L.ORDERV3_address_delete_confirm}?')"><img src="/images/reborn/0.gif" alt="" title="{$L.ORDERV3_delete}" /></a></td>
						</tr>
						{/foreach}			
					</tbody>
					</table>
				</div>
				
				{if $address|@count > 2}
					<a href="#" id="show_addr" class="adr-show-hide-new">{$L.ORDERV3_more_addresses}</a>
					<a href="#" id="hide_addr" style="display: none;" class="adr-show-hide-new">{$L.ORDERV3_hide_addresses}</a>	
				{/if}
				
				<div style="margin-bottom:20px"></div>
		</div>
		{/if}	
	
		<div class="wrapper" style="width: 515px;">
			<div class="block-left clearfix">
				<div class="title clearfix">
					<div class="h1">{$L.ORDERV3_your_records}</div>
					<!--a href="#" title="" rel="nofollow" class="link" style="margin:16px 40px 0 0;">Вход для зарегистрированных</a-->					
				</div>
				<div class="wrap-lab">
					<label for="basket_phone">{$L.ORDERV3_phone}*</label>
					<input name="phone" id="basket_phone" value="{$user_phone}" placeholder="{$L.ORDERV3_phone}" >
					<span class="box-input-result"></span>
					<span class="error_sml"><!-- Это обязательное поле --></span>
				</div>
				{if !$USER->authorized}
				<div class="wrap-lab">
					<label for="basket_email" style="">E-mail*</label>
					<input name="email" id="basket_email" value="{$user_email}" placeholder="e-mail" >
					<span class="box-input-result"></span>
					<span class="error_sml"><!-- Это обязательное поле --></span>
				</div>
				{/if}
				<div class="wrap-lab">
					<label for="basket_fio" style="">{$L.ORDERV3_fio}*</label>
					<input name="fio" id="basket_fio" value="{$user_name}" placeholder="{$L.ORDERV3_fio}" >
					<span class="box-input-result"></span>
					<span class="error_sml"><!-- Это обязательное поле --></span>
				</div>
				
				{*
				<div class="wrap-lab-2" style="display: block;clear: both; font-family: arial; font-size: 17px; margin-bottom: 15px; position: relative;">
					<label><input type="checkbox" name="personal_data" style="margin-right: 5px;vertical-align: middle;" /> Я согласен с обработкой моих данных*</label><br />
					<small style="font-size: 12px;margin-left: 26px; margin-top: 4px;display:inline-block">В соответствии с федеральным законом Российской Федерации от 27 июля 2006г. №152-ФЗ "О персональных данных"</small> 
				</div>
				*}
			</div>
			<div class="block-left clearfix" style="padding-top:16px">
				<div class="title" style="margin-bottom:17px;line-height: 32px;">
					<a href="#" name="pokazat-select-city" class="add-new-adr" rel="nofollow"></a>
					<div class="h1" id="type-delivery-title" style="padding-top:4px;margin-bottom:0px;">{$L.ORDERV3_delivery_variant}</div>
					<input type="hidden" name="country" id="basket_country" class="input select_country" value="{$order_country}" />
					<span class="wrap-your_city left">
						&nbsp;{$L.ORDERV3_your_city}:&nbsp;<a id="select-city" href="/#TB_inline?width={if $MobilePageVersion}260{else}550{/if}&height=700&inlineId=p-region" class="thickbox" rel="nofollow">{$default_city_name}</a>						
					</span>
					<div style="clear:both"></div>
					<div class="line"></div>
				</div>	
				
				{if $freedelivery_rest}
					{if $freedelivery_rest <= 0}
						<p><b>Бесплатно доставка для Вас. Спасибо что заказали на {$basket->basketSum} руб!</b></p>
					{/if}
				{/if}
						
				<div class="b-deliver">		
					<div class="radio check_samovivoz select_samovivoz clearfix">
						<input type="radio" name="data[delivery]" id="deliver_samovivoz" value="user" checked="checked" {if $dt_default == 'user'}checked="checked"{/if}>
						<label for="deliver_samovivoz">
							<span class="label">{$L.ORDERV3_free} &mdash; <b>{$L.ORDERV3_selfdelivery}</b><br />(г.Москва м. Бауманская)</span>
							 <span class="help" style="float:none"><a href="/faq/37/?height=500&width={if $MobilePageVersion}260{else}600{/if}&positionFixed=true" rel="nofollow" class="thickbox help">?</a></span>									
							<br/>
							<span class="duble-data-samovivoz left paddingTop-2"></span>
							
							<b style="display:none" class="sub-price-bonuses" style="color:black">{$L.ORDERV3_free_2}</b>
						</label>
						<div class="more_options_box paddingTop-6 clearfix">
								
							{if $delivery_time_intervals.user|count > 0}							
							<select style="width:90px;float:left;margin:5px 0;padding-left:4px" name="data[user_time]">
								{foreach from=$delivery_time_intervals.user item="i"}
								<option value="{$i.caption}" {if $i.disabled}_disabled="true"{/if}>{$i.caption}</option>
								{/foreach}
							</select>
							{/if}
							
							<div class="calendar userkalendarik" _input="data[user_date]">
								<div class="day"><span class="d">{$L.ORDERV3_d_mon}</span><span class="d">{$L.ORDERV3_d_true}</span><span class="d activ">{$L.ORDERV3_d_wed}</span><span class="d">{$L.ORDERV3_d_thu}</span><span class="d">{$L.ORDERV3_d_fri}</span><span class="d">{$L.ORDERV3_d_sat}</span><span class="d">{$L.ORDERV3_d_sum}</span></div>
								<div style="clear:left;"></div>
								<div class="chislo">
									<span class="icon left"></span>
									<ul>
										<li class="l">12</li><li class="l">13</li>
										<li class="l activ">14</li><li>15</li>
										<li>16</li><li>17</li><li>18</li><li>19</li>
									</ul>
									<span class="icon right"></span>
								</div>
							</div>						
						</div>
					</div>
					
					{*
					<div class="radio select_metro clearfix">
						<input type="radio" name="data[delivery]" value="metro" id="deliver_metro" {if $dt_default == 'metro'}checked="checked"{/if}>
						<label for="deliver_metro">
							<span class="label">{if $deliveryTypes.metro.cost == 0} {$L.ORDERV3_free} {else} {if $PAGE->lang == 'en'}{$deliveryTypes.metro.cost_usd}{else}{$deliveryTypes.metro.cost}{/if} {$L.CURRENT_CURRENCY}{/if} &mdash; <b>В метро</b>&nbsp;&nbsp;(г. Москва, кольцевая линия)</span>
							<span class="help"><a href="/faq/131/?height=500&width={if $MobilePageVersion}260{else}600{/if}&positionFixed=true" rel="nofollow" class="thickbox help">?</a></span>
							</br>
							<span class="duble-data-metro left paddingTop-2"></span>
							
							<b style="display:none" class="sub-price-bonuses">{if $deliveryTypes.metro.cost == 0} {$L.ORDERV3_free} {else} {if $PAGE->lang == 'en'}{$deliveryTypes.metro.cost_usd}{else}{$deliveryTypes.metro.cost}{/if} {$L.CURRENT_CURRENCY}{/if}</b>
						</label>						
						<div class="more_options_box" style="display:none">
							<div>
								<select name="data[select_metro]" id="select_metro" style="width:200px;">
									<option value="" class="first">{$L.ORDERV3_metro}</option>									 
									<option value="35" {if 35 == $order_metro}selected="selected"{/if}>Белорусская</option>
									<option value="68" {if 68 == $order_metro}selected="selected"{/if}>Новослободская</option>
									<option value="69" {if 69 == $order_metro}selected="selected"{/if}>Проспект Мира</option>
									<option value="6"  {if 6 == $order_metro}selected="selected"{/if}>Комсомольская</option>
									<option value="46" {if 46 == $order_metro}selected="selected"{/if}>Курская</option>
									<option value="72" {if 72 == $order_metro}selected="selected"{/if}>Таганская</option>
									<option value="30" {if 30 == $order_metro}selected="selected"{/if}>Павелецкая</option>
									<option value="74" {if 74 == $order_metro}selected="selected"{/if}>Добрынинская</option>
									<option value="75" {if 75 == $order_metro}selected="selected"{/if}>Октябрьская</option>
									<option value="13" {if 13 == $order_metro}selected="selected"{/if}>Парк Культуры</option>
									<option value="42" {if 42 == $order_metro}selected="selected"{/if}>Киевская</option>
									<option value="78" {if 78 == $order_metro}selected="selected"{/if}>Краснопресненская</option>	
								</select>
								<span class="box-input-result" style="margin:12px 0 0 17px;"></span><br/>
								<span class="error_sml"><!-- Это обязательное поле --></span>
							</div>						
							<div class="input good_time">
								<label for="metro_time" style="display:none">{$L.ORDERV3_convenient_time}</label>
								
								{if 1}
									<select style="width:90px;float: left;margin:5px 0;padding-left: 4px;" name="data[metro_time]">
										<option value="с 11 до 18">с 11 до 18</option>
										<option></option>
										<option value="с 11 до 14">с 11 до 14</option>
										<option value="с 14 до 16">с 14 до 16</option>
										<option value="с 16 до 18">с 16 до 18</option>
										<option value="с 18 до 21">с 18 до 21</option>
									</select>
								{else}
									<input type="text" name="data[metro_time]" style="float:none;width: 186px;height: 12px;" id="metro_time" value="{$metrotime}" placeholder="Удобное время" />
								{/if}
									
								<div class="calendar metrokalendarik" _input="data[metro_date]">
									<div class="day"><span class="d">{$L.ORDERV3_d_mon}</span><span class="d">{$L.ORDERV3_d_true}</span><span class="d activ">{$L.ORDERV3_d_wed}</span><span class="d">{$L.ORDERV3_d_thu}</span><span class="d">{$L.ORDERV3_d_fri}</span><span class="d">{$L.ORDERV3_d_sat}</span><span class="d">{$L.ORDERV3_d_sum}</span></div>
									<div style="clear:left;"></div>
									<div class="chislo">
										<span class="icon left"></span>
										<ul>
											<li class="l">12</li><li class="l">13</li>
											<li class="l activ">14</li><li>15</li>
											<li>16</li><li>17</li><li>18</li><li>19</li>
										</ul>
										<span class="icon right"></span>
									</div>
								</div>
								<div style="clear:left;"></div>
								
								<span class="box-input-result" style="margin: 50px 0px 0px 20px"></span><br/>
								<span class="error_sml"><!-- Это обязательное поле --></span>
							</div>
						</div>
					</div>
					*}
					
					<div class="radio select_kurer clearfix">
						<input type="radio" name="data[delivery]" value="deliveryboy" id="delivery_kurer" {if $dt_default == 'deliveryboy'}checked="checked"{/if}>
						<label for="delivery_kurer">
							<span class="label">{if $deliveryTypes.deliveryboy.cost == 0} {$L.ORDERV3_free} {else} {if $PAGE->lang == 'en'}{$deliveryTypes.deliveryboy.cost_usd}{else}{$deliveryTypes.deliveryboy.cost}{/if} {$L.CURRENT_CURRENCY}{/if} &mdash; <b>{$L.ORDERV3_courier}</b>&nbsp;&nbsp;</span>
							<span class="help"><a href="/faq/38/?height=500&width={if $MobilePageVersion}260{else}600{/if}&positionFixed=true" rel="nofollow" class="thickbox help">?</a></span>
							</br><span class="duble-data-deliveryboy left paddingTop-3"></span>
							<b style="display:none" class="sub-price-bonuses">{if $deliveryTypes.deliveryboy.cost == 0} {$L.ORDERV3_free_2} {else} {if $PAGE->lang == 'en'}{$deliveryTypes.deliveryboy.cost_usd}{else}{$deliveryTypes.deliveryboy.cost}{/if} {$L.CURRENT_CURRENCY}{/if}</b><span style="font-weight:bold;position: relative;top:3px;">. В пределах МКАД</span>
						</label>
						
						<div class="more_options_box paddingTop-6 clearfix" style="display:none">
							<!--span id="delivery-date" style="display: block;font-weight: bold;">Доставим 14 февраля</span-->
							<select style=" width:90px;float: left;margin:5px 0;padding-left: 4px" name="data[deliveryboy_time]">
								<option value="с 11 до 18">с 11 до 18</option>
								<option></option>
								<option value="с 11 до 14">с 11 до 14</option>
								<option value="с 14 до 16">с 14 до 16</option>
								<option value="с 16 до 18">с 16 до 18</option>
							</select>
							<div class="calendar kalendarik" _input="data[deliveryboy_date]">
								<div class="day"><span class="d">{$L.ORDERV3_d_mon}</span><span class="d">{$L.ORDERV3_d_true}</span><span class="d activ">{$L.ORDERV3_d_wed}</span><span class="d">{$L.ORDERV3_d_thu}</span><span class="d">{$L.ORDERV3_d_fri}</span><span class="d">{$L.ORDERV3_d_sat}</span><span class="d">{$L.ORDERV3_d_sum}</span></div>
								<div style="clear:left;"></div>
								<div class="chislo">
									<span class="icon left"></span>
									<ul>
										<li class="l">12</li><li class="l">13</li>
										<li class="l activ">14</li><li>15</li>
										<li>16</li><li>17</li><li>18</li><li>19</li>
									</ul>
									<span class="icon right"></span>
								</div>
							</div>
							
							<div>
								<select name="data[select_metro_deliveryboy]" id="select_metro" style="width:307px;margin-top:8px;">
									<option value="" class="first">{$L.ORDERV3_metro}</option>
									{foreach from=$metro_stations item="m"}									 
									<option value="{$m.metro_id}" {if $m.metro_id == $order_metro}selected="selected"{/if}>{$m.metro_name}</option>
									{/foreach}
								</select>
								<span class="box-input-result" style="margin:54px 0 0 17px;"></span><br/>
								<span class="error_sml" style="padding:1px"><!-- Это обязательное поле --></span>
							</div>
						</div>
					</div>
					
					{* курьер день-в-день *}
					{if $deliveryTypes.deliveryboy_vip}
					<div class="radio select_kurer_vip clearfix">
						<input type="radio" name="data[delivery]" value="deliveryboy_vip" id="delivery_kurer_vip" {if $dt_default == 'deliveryboy_vip'}checked="checked"{/if}>
						<label for="delivery_kurer_vip">
							<span class="label">{if $deliveryTypes.deliveryboy_vip.cost == 0} {$L.ORDERV3_free} {else} {if $PAGE->lang == 'en'}{$deliveryTypes.deliveryboy_vip.cost_usd}{else}{$deliveryTypes.deliveryboy_vip.cost}{/if} {$L.CURRENT_CURRENCY}{/if} &mdash; <b>{$L.ORDERV3_courier_vip}</b>&nbsp;&nbsp;</span>
							<span class="help"><a href="/faq/207/?height=500&width={if $MobilePageVersion}260{else}600{/if}&positionFixed=true" rel="nofollow" class="thickbox help">?</a></span>
							</br><span class="duble-data-deliveryboy_vip left paddingTop-3"></span>
							<b style="display:none" class="sub-price-bonuses">{if $deliveryTypes.deliveryboy_vip.cost == 0} {$L.ORDERV3_free_2} {else} {if $PAGE->lang == 'en'}{$deliveryTypes.deliveryboy_vip.cost_usd}{else}{$deliveryTypes.deliveryboy_vip.cost}{/if} {$L.CURRENT_CURRENCY}{/if}</b><span style="font-weight:bold;position: relative;top:3px;">. В пределах МКАД</span>
						</label>
						
						<div class="more_options_box paddingTop-6 clearfix" style="display:none">
							
							{if $deliveryTypes.deliveryboy_vip.next_day}
							<p>Доставка возможна только завтра &ndash; <b>{$deliveryTypes.deliveryboy_vip.next_day}</b></p>
							{/if}
							
							<select style=" width:90px;float: left;margin:5px 0;padding-left: 4px" name="data[deliveryboy_vip_time]">
								<option value="с 11 до 18">с 11 до 18</option>
								<option></option>
								<option value="с 11 до 14">с 11 до 14</option>
								<option value="с 14 до 16">с 14 до 16</option>
								<option value="с 16 до 18">с 16 до 18</option>
							</select>
							
							<div>
								<br />
								<select name="data[select_metro_deliveryboy_vip]" id="select_metro" style="width:307px;margin-top:8px;">
									<option value="" class="first">{$L.ORDERV3_metro}</option>
									{foreach from=$metro_stations item="m"}									 
									<option value="{$m.metro_id}" {if $m.metro_id == $order_metro}selected="selected"{/if}>{$m.metro_name}</option>
									{/foreach}
								</select>
								<span class="box-input-result" style="margin:54px 0 0 17px;"></span><br/>
								<span class="error_sml" style="padding:1px"><!-- Это обязательное поле --></span>
							</div>
						</div>
					</div>
					{/if}
					
					<div class="radio kurierIMlog_self clearfix" style="display:none" initShowKurierToApartment="{if $default_city_IMlog_self > 0 && default_city_IMlog_self != ''}1{else}0{/if}">
						
						<input type="radio" name="data[delivery]" value="IMlog_self" id="kurierIMlog_self" {if $dt_default == 'IMlog_self'}checked="checked"{/if}>
						
						<label for="kurierIMlog_self">
							<span class="sub-price-bonuses">{if $default_city_IMlog_self > 0 && $default_city_IMlog_self != ''}{if $PAGE->lang == 'en'}{$default_city_IMlog_self.cost_usd}{else}{$default_city_IMlog_self.cost}{/if}{/if}</span> 
							<span class="label"> &mdash; <b>{$L.ORDERV3_selfdelivery_point}</b></span>
							<span class="help"><a href="/faq/169/?height=500&width={if $MobilePageVersion}260{else}600{/if}&positionFixed=true" rel="nofollow" class="thickbox help">?</a></span><br />{if $default_city_IMlog_self}[{$default_city_IMlog_self.cost}]{/if}
							<span class="delivery-days left paddingTop-2">{$default_city_IMlog_self.time}</span>
							<span class="left nowrap paddingTop-2">&nbsp; не считая дня заказа</span>
							<div class="link_map paddingTop-2" style="clear:both">{*Выбрать адрес пункта выдачи на карте*}</div>
						</label>
						
						<div class="more_options_box paddingTop-10" style="display:none;border:0">
						</div>
						{*
						<div class="rating_deliver" title="">
							<span></span>
							<span></span>
							<span></span>
						</div>
						*}
					</div>					
					<div class="radio kurierIML clearfix" style="display:none" initShowKurierIML="{if $default_city_im > 0 && $default_city_im != ''}1{else}0{/if}">
						<input type="radio" name="data[delivery]" value="IMlog" id="kurierIML" {if $dt_default == 'IMlog'}checked="checked"{/if}>
						<label for="kurierIML">
							<b class="sub-price-bonuses">{if $default_city_im > 0 && $default_city_im != ''}{if $PAGE->lang == 'en'}{$default_city_im.cost_usd}{else}{$default_city_im.cost}{/if}{/if}</b>
							<span class="label"><b>{$L.ORDERV3_IMcourier} <span class="delivery-days">{$default_city_im.time}</span></b> </span>
							<span class="nowrap">не считая дня заказа</span>
						</label>												
						<div class="more_options_box paddingTop-0 clearfix" style="display:none">
							<span>Возможно увеличение срока доставки на 1 день</span><br>
							<select style=" width:90px;float:left;margin:5px 0;padding-left:4px" name="data[IMlog_time]">
								<option value="с 9 до 14">с 9 до 14</option>
								<option value="с 9 до 18">с 9 до 18</option>
								<option value="с 13 до 18">с 13 до 18</option>
							</select>
						</div>
						<div class="rating_deliver" title="">
							<span></span>
							<span></span>
							<span></span>
						</div>
					</div>				
					
					{*
					<div class="radio select_kurer_mkad" style="display:none;">
						<input type="radio" name="data[delivery]" value="deliveryboy_mkad" id="delivery_kurer_mkad">
						<label for="delivery_kurer_mkad">
							<span class="label">{if $deliveryTypes.deliveryboy_mkad.cost == 0} {$L.ORDERV3_free} {else} {if $PAGE->lang}{$deliveryTypes.deliveryboy_mkad.cost_usd}{else}{$deliveryTypes.deliveryboy_mkad.cost}{/if} {$L.CURRENT_CURRENCY}{/if} &mdash; <b>Курьер до квартиры за МКАД</b>&nbsp;&nbsp;</span>
							<span class="help"><a href="/faq/38/?height=500&amp;width={if $MobilePageVersion}260{else}600{/if}&amp;positionFixed=true" rel="nofollow" class="thickbox help">?</a></span>
							</br><span class="duble-data-deliveryboy_mkad left paddingTop-2"></span>
							<span class="left paddingTop-2">&nbsp; не считая дня заказа</span>
							
							<b style="display:none;" class="sub-price-bonuses">{if $deliveryTypes.deliveryboy_mkad.cost == 0} {$L.ORDERV3_free_2} {else} {if $PAGE->lang}{$deliveryTypes.deliveryboy_mkad.cost_usd}{else}{$deliveryTypes.deliveryboy_mkad.cost}{/if} {$L.CURRENT_CURRENCY}{/if}</b>
						</label>
						
						<div class="more_options_box" style="display:none;padding-top: 6px;">
							<!--span id="delivery-date" style="display: block;font-weight: bold;">Доставим 14 февраля</span-->
							<select style=" width:90px;float: left;margin:5px 0;padding-left: 4px;" name="data[deliveryboy_mkad_time]">
								<option value="с 11 до 18">с 11 до 18</option>
								<option></option>
								<option value="с 11 до 14">с 11 до 14</option>
								<option value="с 14 до 16">с 14 до 16</option>
								<option value="с 16 до 18">с 16 до 18</option>
								<option value="с 18 до 21">с 18 до 21</option>
							</select>
							<div class="calendar kalendarik" _input="data[deliveryboy_mkad_date]">
								<div class="day"><span class="d">{$L.ORDERV3_d_mon}</span><span class="d">{$L.ORDERV3_d_true}</span><span class="d activ">{$L.ORDERV3_d_wed}</span><span class="d">{$L.ORDERV3_d_thu}</span><span class="d">{$L.ORDERV3_d_fri}</span><span class="d">{$L.ORDERV3_d_sat}</span><span class="d">{$L.ORDERV3_d_sum}</span></div>
								<div style="clear:left;"></div>
								<div class="chislo">
									<span class="icon left"></span>
									<ul>
										<li class="l">12</li><li class="l">13</li>
										<li class="l activ">14</li><li>15</li>
										<li>16</li><li>17</li><li>18</li><li>19</li>
									</ul>
									<span class="icon right"></span>
								</div>
							</div>
							<div style="clear:left"></div>
						</div>
						<div style="clear:both"></div>
					</div>
					*}
					
					<div class="radio select_delivery clearfix" style="display:none">
						<input type="radio" name="data[delivery]" value="post" id="deliver_post" {if $dt_default == 'post'}checked="checked"{/if}>
						<label for="deliver_post">
							<b class="sub-price-bonuses"></b>
							<span class="label"><b>{$L.ORDERV3_russian_post}</b></span>
							<span class="help"><a href="/faq/39/?height=500&amp;width={if $MobilePageVersion}260{else}600{/if}&amp;positionFixed=true" rel="nofollow" class="thickbox help">?</a></span>
							
							{if $deliveryTypes.post.post_zakaznoe}<br/><span class="post_zakaznoe" {if $order_country!='838'}style="display:none"{/if}>Доставка заказным письмом.</span>{else}{/if}
						</label>
						<div class="more_options_box" style="display:none;visibility:hidden"></div>
						<div class="rating_deliver" title="">
							<span></span>
							<span></span>
						</div>
					</div>
					{*
					<div class="radio kurierdpd_self clearfix" style="display:none" initShowKurierToApartment="{if $default_city_dpd_self > 0 && default_city_dpd_self != ''}1{else}0{/if}">
						<input type="radio" name="data[delivery]" value="dpd_self" id="kurierdpd_self" {if $dt_default == 'dpd_self'}checked="checked"{/if}>
						<label for="kurierdpd_self">
							<b class="sub-price-bonuses">{if $default_city_dpd_self > 0 && $default_city_dpd_self != ''}{if $PAGE->lang == "en"}{$default_city_dpd_self.cost_usd}{else}{$default_city_dpd_self.cost}{/if}{/if}</b>
							<span class="label"><b>{$L.ORDERV3_selfdelivery_point_dpd} <span class="delivery-days">{$default_city_dpd_self.time}</span></b></span>
							<span class="left nowrap paddingTop-2">не считая дня заказа</span>
							<div class="link_map paddingTop-2" style="clear:both"></div>
						</label>
						<div class="more_options_box paddingTop-10" style="display:none;border:0"></div>
						<div class="rating_deliver" title="">
							<span></span>
							<span></span>
							<span></span>
							<span></span>
							<span></span>
						</div>
					</div>
					<div class="radio select_DPD clearfix" style="display:none;" initShowDPD="{if $default_city_dpd > 0 && $default_city_dpd != ''}1{else}0{/if}">
						<input type="radio" name="data[delivery]" value="dpd" id="deliver_dpd" {if $dt_default == 'dpd'}checked="checked"{/if}>
						<label for="deliver_dpd">
							<b class="sub-price-bonuses">{if $default_city_dpd > 0 && $default_city_dpd != ''}{if $PAGE->lang}{$default_city_dpd.cost_usd}{else}{$default_city_dpd.cost}{/if}{/if}</b>
							<span class="label"><b>{$L.ORDERV3_DPD_courier} (<span class="delivery-days">{$dpd_delivery_time} {if $dpd_delivery_time == 1}{$L.ORDERV3_days3}{elseif ($dpd_delivery_time >= 2 && $dpd_delivery_time <= 4) || $dpd_delivery_time == '1-2' || $dpd_delivery_time == '2-3' || $dpd_delivery_time == '3-4'}{$L.ORDERV3_days2}{else}{$L.ORDERV3_days1}{/if}</span>)</b></span>
							<span class="help"><a href="/faq/145/?height=500&width={if $MobilePageVersion}260{else}600{/if}&positionFixed=true" rel="nofollow" class="thickbox help">?</a></span>	
							<span class="nowrap">не считая дня заказа</span>	
													
						</label>
						<div class="more_options_box paddingTop-6 clearfix" style="display:none">
							<select style=" width:90px;float:left;margin:5px 0;padding-left:4px" name="data[dpd_time]">
								<option value="с 9 до 14">с 9 до 14</option>
								<option value="с 18 до 21">с 18 до 21 платно</option>
								<option value="с 9 до 18">с 9 до 18</option>
								<option value="с 13 до 18">с 13 до 18</option>
							</select>
						</div>
						<div class="rating_deliver" title="">
							<span></span>
							<span></span>
							<span></span>
							<span></span>
							<span></span>
						</div>
					</div>
					*}
					{*
					<div class="radio kurierToApartment clearfix" style="display:none;" initShowKurierToApartment="{if $default_city_baltick > 0 && $default_city_baltick != ''}1{else}0{/if}">
						<input type="radio" name="data[delivery]" value="baltick" id="kurierToApartment" {if $dt_default == 'baltick'}checked="checked"{/if}>
						<label for="kurierToApartment">
							<b class="sub-price-bonuses">{if $default_city_baltick > 0 && $default_city_baltick != ''}{$default_city_baltick.cost}{/if}</b>
							<span class="label"><b>{$L.ORDERV3_courier} <span class="delivery-days">{$default_city_baltick.time}</span></b></span>
						</label>
						<div class="more_options_box" style="display:none;"></div>
						<div class="rating_deliver" title="">
							<span></span>
							<span></span>
							<span></span>
							<span></span>
							<span></span>
						</div>
					</div>					
					*}
				</div>
				<div style="clear: both;"></div>
				<div class="address clearfix" style="display:none">
					<div class="h1">{$L.ORDERV3_address}</div>
					<div class="line"></div>
					<div class="change-city">
						<div class="wrap-lab city" style="visibility:hidden;display:none;width:0px;height:0px;display:none;">
							<label for="basket_city" style="display:none">{$L.ORDERV3_city}</label>
							<input name="city_name" id="basket_city_search" def="{if $default_city_id}{$default_city_name}{else}Москва{/if}" value="{if $default_city_id}{$default_city_name}{else}Москва{/if}" class="input input_city" placeholder="{$L.ORDERV3_city}" />
							<span class="box-input-result"></span>
							<span class="error_sml"></span>
							<input type="hidden" name="city" id="basket_city" value="{if $default_city_id}{$default_city_id}{else}{/if}" />
						</div>
						<div class="wrap-lab postal_code" style="display:none;margin: 14px 0px 0px 4px;">
							<label for="postal_code" style="display:none"></label>
							<input name="postal_code" id="postal_code" value="{$default_postal_code}" placeholder="{$L.ORDERV3_zip_code}" class="input input_postcode" />
							<span class="box-input-result" style="right: 14px;"></span>
							<div style="clear:left;"></div>
							<span class="error_sml"></span>
						</div>
					</div>
					<div style="clear:left;"></div>
					<div class="wrap-lab address_comment" style="">
						<textarea name="address_comment" placeholder="{$L.ORDERV3_delivery_address}" id="basket_comm" >{$default_address}</textarea>
						<span class="box-input-result"></span>
						<span class="error_sml"></span>
					</div>
				</div>
				<div style="clear:left;"></div>
				<div class="more_options_box comment_wrap clearfix" style="margin:0 0 0 4px;">
					<!--label for="comment_text" class="comm-label" style="width: 384px;float: left;">Комментарий к заказу:</label-->
					<textarea name="data[comment_text]" placeholder="{$L.ORDERV3_order_comment}"  id="comment_text">{$delivery_comment}</textarea>
				</div>
			</div>
			
			<div class="block-left">
				
				<a name="payment"></a>
				
				<div class="title" style="margin-bottom:17px;">
					<div class="h1" style="margin-bottom:2px;">{$L.ORDERV3_choose_payment}</div>
					<div class="line"></div>
				</div>				
				<div class="b-payment">
					{if $bonuses > 0}
					<div class="checkbox my-bonuses {if $maxParticalPayPercent < 100}dvestroki{/if} clearfix">
						<input type="checkbox" name="data[my_bonuses]" id="my_bonuses" {if !$id || $order->user_basket_payment_partical > 0} checked="checked" {/if} / >
						<input type="hidden" name="my_bonuses" id="my_bonuses_value" value="{$bonuses}" />
						<label for="my_bonuses">
							{if $maxParticalPayPercent < 100}
								Использовать мои бонусы (max {$maxParticalPayPercent}% от заказа),<br/><font style="font-size:11px;">на бонусном счету останется {$rest_bonuses} бонусов.</font>
							{else}
								<b class="sub-price-bonuses" style="">{$bonuses} {$L.CURRENT_CURRENCY} &mdash; {$L.ORDERV3_mybonuses}</b>
							{/if}
							
							{* <b style=""> &mdash; {$L.ORDERV3_mybonuses}</b>	(Max {$maxParticalPayPercent}%)	*}
							
							<sup class="help"><a href="/faq/188/?height=500&width={if $MobilePageVersion}260{else}600{/if}&positionFixed=true" rel="nofollow" class="thickbox help">?</a></sup>
						</label>
					</div>
					{/if}
					
					{if $ls_can_pay>0}
					<div class="checkbox lichnyy-schet clearfix">
						<input type="checkbox" name="data[lichnyy_schet]" id="lichnyy_schet" {if $ls_can_pay>0 && !$checkbox_payed_without_ls}checked="checked"{/if}/>
						<input type="hidden" name="ls_total_pay" id="lichnyy_schet_value" value="{$ls_total_pay}"/>
						<input type="hidden" name="" id="lichnyy_schet_value_can_pay" value="{$ls_can_pay}"/>
						<div class="icon"></div>
						<font class="plus20">+{$bppercent}%</font>
						<label for="lichnyy_schet">
							<b class="sub-price-bonuses">{$ls_can_pay} {$L.CURRENT_CURRENCY} &mdash; Личный счет</b>							
							<sup class="help"><a href="/faq/137/?height=500&width={if $MobilePageVersion}260{else}600{/if}&positionFixed=true" rel="nofollow" class="thickbox help">?</a></sup>							
						</label>				
						{if $bppercent > 0}		
						<div class="buttonPlus30bonuses">
							<div>Выгоднее на {$bppercent}%</div>
						</div>
						{/if}
					</div>
					{/if}					
					
					{if $paymentTypes.CASH}
					<div class="radio payment_by_cash {if $default_city_id != 1}disable{/if}" >
						<input type="radio" name="data[payment_method]" class="payment-type-radio" value="cash" id="payment_by_cash" {if $default_city_id != 1}disabled="disabled" {else} checked="checked" {/if} {$paymentTypes.CASH.checked}>
						<label for="payment_by_cash" class="lbl-paymeth selected_b">
							<span class="label" t=" &mdash; {$L.ORDERV3_cash}">{$L.ORDERV3_cash}</span>
							<b class="sub-price" id="cash-price" sum="{$paymentTypes.CASH.total_without_bonuses}">{$paymentTypes.CASH.total_without_bonuses} {$L.CURRENT_CURRENCY}</b>
							<input type="hidden" id="cash-total-price" value="{$paymentTypes.CASH.total}">
						</label>
					</div>
					{/if}

					{if $paymentTypes.CASHON && ($USER->user_id == 105091 || $USER->user_id == 27278 || $USER->user_id == 6199 || $USER->user_id == 63250)}
					<div class="radio payment_by_cashon" style="border-bottom: 1px dashed orange;">
						<input type="radio" name="data[payment_method]" class="payment-type-radio" value="cashon" id="payment_by_cashon" />
						<label for="payment_by_cash" class="lbl-paymeth">
							<span class="label" t=" &mdash; {$L.ORDERV3_cashon}"> {$paymentTypes.CASHON.total_without_bonuses} {$L.CURRENT_CURRENCY} &mdash; {$L.ORDERV3_cashon}</span>
							<sup class="help"><a href="/faq/112/?height=500&width={if $MobilePageVersion}260{else}600{/if}&positionFixed=true" rel="nofollow" class="thickbox help">?</a></sup>
							<b class="sub-price" id="cashon-price" sum="{$paymentTypes.CASHON.total_without_bonuses}">{$paymentTypes.CASHON.total_without_bonuses} {$L.CURRENT_CURRENCY}</b>
							<input type="hidden" id="cashon-total-price" value="{$paymentTypes.CASHON.total}" />
						</label>
					</div>
					{/if}

					{if $paymentTypes.CREDITCARD}
					<div class="radio payment_by_cards {if $paymentTypes.CREDITCARD.disabled} disable{/if}" data-disable="{if $paymentTypes.CREDITCARD.disabled}true{else}false{/if}">
						<input type="radio" name="data[payment_method]" class="payment-type-radio" value="creditcard" id="payment_by_cards" {if $default_city_id != 1} checked="checked" {/if} {$paymentTypes.CREDITCARD.checked} {if $paymentTypes.CREDITCARD.disabled}disabled=""{/if}>
						<label for="payment_by_cards" class="lbl-paymeth">
							<span class="label" t=" &mdash; {$L.ORDERV3_creditcard}">{* {$paymentTypes.CREDITCARD.total_without_bonuses} {$L.CURRENT_CURRENCY} &mdash; *}{$L.ORDERV3_creditcard}</span>
							<span class="help"><a href="/faq/88/?height=500&amp;width={if $MobilePageVersion}260{else}600{/if}&amp;positionFixed=true" rel="nofollow" class="thickbox help">?</a></span>
							<b class="sub-price" id="creditcard-price" sum="{$paymentTypes.CREDITCARD.total_without_bonuses}">{$paymentTypes.CREDITCARD.total_without_bonuses} {$L.CURRENT_CURRENCY}</b>
							<input type="hidden" id="creditcard-total-price" value="{$paymentTypes.CREDITCARD.total}">
						</label>
					</div>
					{/if}
					
					{* if $paymentTypes.CREDITCARD_onplace}
					<div class="radio payment_by_cards_onplace {if $paymentTypes.CREDITCARD_onplace.disabled} disable{/if}" data-disable="{if $paymentTypes.CREDITCARD_onplace.disabled}true{else}false{/if}">
						<input type="radio" name="data[payment_method]" class="payment-type-radio" value="creditcard_onplace" id="payment_by_cards_onplace" {if $default_city_id != 1} checked="checked" {/if} {$paymentTypes.CREDITCARD_onplace.checked} {if $paymentTypes.CREDITCARD_onplace.disabled}disabled=""{/if}>
						<label for="payment_by_cards_onplace" class="lbl-paymeth">
							<span class="label" t=" &mdash; {$L.ORDERV3_creditcard_onplace}"><img src="/images/3/fresh.gif" width="22" height="13" alt="{$L.HEADER_menu_new}" title="{$L.HEADER_menu_new}" style="position:relative;top:-7px;left: -22px;margin-right:-22px;">{$L.ORDERV3_creditcard_onplace}</span>
							<span class="help"><a href="/faq/172/?height=500&amp;width={if $MobilePageVersion}260{else}600{/if}&amp;positionFixed=true" rel="nofollow" class="thickbox help">?</a></span>
							<b class="sub-price" id="creditcard_onplace-price" sum="{$paymentTypes.CREDITCARD_onplace.total_without_bonuses}">{$paymentTypes.CREDITCARD_onplace.total_without_bonuses} {$L.CURRENT_CURRENCY}</b>
							<input type="hidden" id="creditcard_onplace-total-price" value="{$paymentTypes.CREDITCARD_onplace.total}">
							
							<div style="clear:both;font-size:11px;margin-left:9px;margin-top: 7px;line-height:11px;">Только Visa и mastercard</div>
						</label>
					</div>
					{/if *}
					
					{if $paymentTypes.SBERBANK}
					<div class="radio payment_by_bank">
						<input type="radio" name="data[payment_method]" class="payment-type-radio" value="sberbank" id="payment_by_bank" {$paymentTypes.SBERBANK.checked}>
						<label for="payment_by_bank" class="lbl-paymeth" style="width: 335px;">
							<span class="label" t=" &mdash; {$L.ORDERV3_sberbank2}">{* {$paymentTypes.SBERBANK.total_without_bonuses} {$L.CURRENT_CURRENCY} &mdash; *}{$L.ORDERV3_sberbank}</span>
							<span class="help"><a href="/faq/87/?height=500&width={if $MobilePageVersion}260{else}600{/if}&positionFixed=true" rel="nofollow" class="thickbox help">?</a></span>
							<b class="sub-price" id="sberbank-price" sum="{$paymentTypes.SBERBANK.total_without_bonuses}">{$paymentTypes.SBERBANK.total_without_bonuses} {$L.CURRENT_CURRENCY}</b>
							<input type="hidden" id="sberbank-total-price" value="{$paymentTypes.SBERBANK.total}">
							<div style="clear: both;margin-left: 10px;">(только для юр. лиц. реквизиты оставьте в комментариях)</div>
						</label>
					</div>
					{/if}
					
					{if $paymentTypes.YAMONEY}
					<div class="radio payment_by_ymoney">
						<input type="radio" name="data[payment_method]" class="payment-type-radio" value="yamoney" id="payment_by_yamoney" {$paymentTypes.YAMONEY.checked}>
						<label for="payment_by_yamoney" class="lbl-paymeth">
							<span class="label" t=" &mdash; {$L.ORDERV3_yamoney2}">{* {$paymentTypes.SBERBANK.total_without_bonuses} {$L.CURRENT_CURRENCY} &mdash; *}{$L.ORDERV3_yamoney}</span>
							<span class="help"><a href="/faq/86/?height=500&width={if $MobilePageVersion}260{else}600{/if}&positionFixed=true" rel="nofollow" class="thickbox help">?</a></span>
							<b class="sub-price" id="yamoney-price" sum="{$paymentTypes.SBERBANK.total_without_bonuses}">{$paymentTypes.YAMONEY.total_without_bonuses} {$L.CURRENT_CURRENCY}</b>
							<input type="hidden" id="yamoney-total-price" value="{$paymentTypes.SBERBANK.total}">
						</label>
					</div>
					{/if}
					
					{if $paymentTypes.WEBMONEY}					
					<div class="radio payment_by_webymoney">
						<input type="radio" name="data[payment_method]" class="payment-type-radio" value="webmoney" id="payment_by_webymoney" {$paymentTypes.WEBMONEY.checked}>
						<label for="payment_by_webymoney" class="lbl-paymeth">
							<span class="label" t=" &mdash; {$L.ORDERV3_webmoney2}">{* {$paymentTypes.WEBMONEY.total_without_bonuses} {$L.CURRENT_CURRENCY} &mdash; *}{$L.ORDERV3_webmoney2}</span>
							<span class="help"><a href="/faq/85/?height=500&amp;width={if $MobilePageVersion}260{else}600{/if}&amp;positionFixed=true" rel="nofollow" class="thickbox help">?</a></span>
							<b class="sub-price" id="webmoney-price" sum="{$paymentTypes.WEBMONEY.total_without_bonuses}">{$paymentTypes.WEBMONEY.total_without_bonuses} {$L.CURRENT_CURRENCY}</b>
							<input type="hidden" id="webmoney-total-price" value="{$paymentTypes.WEBMONEY.total}">
						</label>
					</div>
					{/if}					
					
					{if $paymentTypes.QIWI}
					<div class="radio payment_by_qiwi" style="margin-bottom:18px;">
						<input type="radio" name="data[payment_method]" class="payment-type-radio" value="qiwi" id="payment_by_qiwi" {if $order_country != 838}disabled="disabled"{/if} {$paymentTypes.QIWI.checked}>
						<label for="payment_by_qiwi" class="lbl-paymeth" {if $order_country != 838}style="cursor:default;"{/if}>
							<span class="label" t=" &mdash; Qiwi">{* {$paymentTypes.QIWI.total_without_bonuses} {$L.CURRENT_CURRENCY} &mdash; *}Qiwi</span>
							<span class="help"><a href="/faq/84/?height=500&width={if $MobilePageVersion}260{else}600{/if}&positionFixed=true" rel="nofollow" class="thickbox help">?</a></span>
							<b class="sub-price" id="qiwi-price" sum="{$paymentTypes.QIWI.total_without_bonuses}">{$paymentTypes.QIWI.total_without_bonuses} {$L.CURRENT_CURRENCY}</b>
							<input type="hidden" id="qiwi-total-price" value="{$paymentTypes.QIWI.total}">
						</label>
					</div>
					{/if}
				</div>			
				
				<div style="clear:both"></div>
			</div>
			{if !$MobilePageVersion}
			<div class="submit-basket">
				<input type="submit" name="save" value="{if !$id}{$L.ORDERV3_confirm_order}{else}{$L.ORDERV3_save}{/if}">
				{if $id}
				<input type="hidden" name="ID" value="{$id}" />
				{/if}
				
				<div id="private_prolicy">
					Нажимая на кнопку "Подтвердить заказ", Вы принимаете условия <a href="/private_policy/" target="_blank">публичной оферты</a>
				</div>
			</div>
			{/if}
			
		</div>
	</div>
	
		<div class="right-wrap clearfix">
			<div class="wrapper" id="cart-block">
				<div class="block-right">
					<div class="title">
						{if !$MobilePageVersion}<div class="h1" style="padding-top:4px">{$L.ORDERV3_basket}</div>{/if}
						{if !$id}
						<a href="/basket/" title="" rel="nofollow" class="link" style="margin:16px 1px 0 0">{$L.ORDERV3_edit_order}</a>
						{/if}
					</div>
					
					<div class="wrapik-q-goods">
						{foreach from=$goods item="g"}
						<div class="q-goods">
							<div class="img">								
								{if $g.imagePath}
								<a href="/basket/zoom/{$g.good_id}/{$g.style_id}/{$g.def_side}/?height=530&width=510&category={$g.category}" rel="nofollow" data-big="" class="glisse {*thickbox*}">
									<img title="{$g.good_name}" alt="{$g.good_name}" src="{$g.imagePath}">
								</a>
								{/if}
								{if $g.good_status == "customize" && $g.imageBackPath}
								<a href="/basket/zoom/{$g.good_id}/{$g.style_id}/back/?height=530&width=510"  data-big="" class="glisse {*thickbox*}">
									<img src="{$g.imageBackPath}" width="85" alt="{$g.good_name}" title="{$g.good_name}" />
								</a>
								{/if}
							</div>
							<div class="wrap">
								<div class="q-name" {if $g.good_status == "customize" && $g.imageBackPath}style="width: 120px;"{/if}>
									{if $g.cat_parent == 76}
										{$g.good_name}
									{else}
										<a title="{$g.good_name}" href="{$g.link}" rel="nofollow" {if $g.good_status == 'customize'}style="cursor: text;text-decoration: none;color: #000000;"{/if}>{$g.good_name}</a>
									{/if}
									<span>
										{$g.style_name}
										{*{if $g.size_rus != ''}&nbsp;<font>{$g.size_name}&nbsp;({$g.size_rus})</font><sup class="help">{if $g.style_viewsize > 0}<a href="/faq/{$g.style_viewsize}/?height=500&amp;width=600;positionFixed=true" rel="nofollow" class="help thickbox">?</a>{else}&nbsp;{/if}</sup>{/if}*}
										{if $g.comment != ''}, {$g.comment}{/if}
									</span>
									{if $g.author_payment}
										<span class="author_payment" style="font-size:9px;line-height:14px;width:auto">автор получит {$g.author_payment} руб.</span>
									{/if}
								</div>							
								{if $g.size_rus != '' || $g.cat_parent == 21}
								<div class="size">
									<span class="b">{$g.size_name} {if $g.cat_parent != 21}({$g.size_rus}){/if}</span>				
									<sup class="help">{if $g.style_viewsize > 0}<a href="/faq/{$g.style_viewsize}/?height=500&amp;width={if $MobilePageVersion}260{else}600{/if};positionFixed=true" rel="nofollow" class="help thickbox" >?</a>{else}&nbsp;{/if}</sup>
								</div>
								{/if}
								
								<div class="q-price">{$g.tprice} {$L.CURRENT_CURRENCY}</div>
								{if $g.quantity>=2}
									<div class="q-quantity">{$g.quantity} шт.</div>
								{/if}							
							</div>
						</div>
						{*if $USER->user_id == 105091 || $USER->user_id == 27278 || $USER->user_id == 6199}
							{if $g.good_status == "customize"}
								<div class="q-goods itIsMyPicture clearfix" style="border-bottom:1px dashed orange;">
									<div class="ico left"></div>									
									<div class="inp left"><input type="checkbox"></div>
									<div class="text left">										
										Я подтверждаю, что это мое изображение, я хочу<br/>его выставить на продажу и<br/><a href="/promo/" _href="/senddrawing.design/startSell/{$src_id}/{$style.category}/{$style.style_id}/" rel="nofollow">получать до 20% с продаж</a>
									</div>
								</div>
							{/if}
						{/if*}	
						{/foreach}
						
						{foreach from=$gifts item="g"}
						<div class="q-goods">
							<div class="img">
								<img title="{$g.good_name}" alt="{$g.good_name}" src="{$g.picture_path}">
							</div>
							<div class="wrap">
								<div class="q-name">
									{$g.gift_name}
								</div>							
								<div class="q-price">{$g.tprice} {$L.CURRENT_CURRENCY}</div>
							</div>
						</div>
						{/foreach}
					</div>
					
					<div style="clear:both;"></div>	
					<div class="summ-deliver clearfix">
						<input type="hidden" name="deliveryboy-deliver-srok" value="{$deliveryboy_deliver_srok}" />
						<input type="hidden" name="deliveryboy_vip-deliver-srok" value="{$deliveryboy_deliver_srok}" />
						<input type="hidden" name="metro-deliver-srok" value="{$deliveryboy_deliver_srok}" />
						<input type="hidden" name="user-deliver-srok" value="{$user_deliver_srok}" />						
						<div class="h3 deliver-title" style="padding-left:76px"></div>
						<span class="help" style="float:right;display:none"><a href="/faq/37/?height=500&width={if $MobilePageVersion}260{else}600{/if}&positionFixed=true" rel="nofollow" class="thickbox help">?</a></span>
						<div class="h3 deliver-srok"></div>
						<div class="h3 sum-title"></div>
						
						<div class="h3 sum"><font>{$L.ORDERV3_without_delivery}</font>{if $PAGE->lang !='ru'}{$L.CURRENT_CURRENCY}{/if}</div>
					</div>
 					
					<div class="summ-bonus clearfix">
						<div class="sum-title">{$L.ORDERV3_total}:</div>							
						<div class="sum" sum="{$basket_sum}"><font>{$basket_sum_minus_bonuses}</font>{if $PAGE->lang !='ru'}{$L.CURRENT_CURRENCY}{/if}</div>
						{if $USER->user_bonus > 0}
							<span class="no-sum"><strike>{$basket_sum}</strike></span>
						{/if}
						
						<div class="final-bonus-payment" id="final-bonus-payment"></div>
						
						{if $ls_can_pay>0}
						<div class="final-lichnyy-schet-payment" id="final-lichnyy-schet-payment">Оплачено с личного счёта: {$ls_can_pay} {$L.CURRENT_CURRENCY}</div>
						{/if}
						
						{*
						<div class="you_return">{$L.ORDERV3_order_bonus}: {$bonusBack} {$L.CURRENT_CURRENCY}</div>
						*}
						
						{if $basket->basketSum - $USER->user_bonus >= 5000}
						<div style="text-align:right;color:red;">потребуется  предоплата<br />менеджер уточнит</div>
						{/if}
					</div>
				</div>

				{*if $USER->city == 'Москва' || $USER->user_id == 105091 || $USER->user_id == 27278 || $USER->user_id == 86455}
					<div style="margin:0 22px 20px">
					{if !$smarty.cookies.ZBFza200}
					<a href="/ajax/makegift/" rel="nofollow" class="zaberi_moy_fotbolky">
						<img src="/images/basket/otdam_futbolky.jpg" alt="Отдам футболку" title="Отдам футболку" width="146" height="31" border="0" >
					</a>
					{else}
						<img src="/images/basket/otdam_futbolky_ok.jpg" alt="Вам начислено 200 руб." title="Вам начислено 200 руб." width="156" height="41" border="0" style="margin-left:-5px">
					{/if}
					</div>
				{/if*}			
								
			</div>
					
		</div>
		
		{if $MobilePageVersion}
			{*literal}<script type="text/javascript">
			$('.quick_basket .left-wrap').before($( '.quick_basket .right-wrap'));
			</script>{/literal*}
			
			<div class="submit-basket">
				<input type="submit" name="save" value="{if !$id}{$L.ORDERV3_confirm_order}{else}{$L.ORDERV3_save}{/if}">
				
				{if $id}
				<input type="hidden" name="ID" value="{$id}" />
				{/if}
			</div>
		{/if}
	</form>
	
	<div style="clear:both"></div>
	<!--окно городов и стран-->
	<div class="p-region" id="p-region" style="display:none">
		<div class="p-region-country">
			<div class="item {if $order_country == '838'}active{/if}">
				<a href="#c838" rel="nofollow">{$L.ORDERV3_russia}</a>
			</div>
			<div class="item {if $order_country == '880'}active{/if}">
				<a href="#c880" rel="nofollow">{$L.ORDERV3_ukraine}</a>
			</div>
			<div class="item  {if $order_country == '693'}active{/if}">
				<a href="#c693" rel="nofollow">{$L.ORDERV3_belorussia}</a>
			</div>
			<div class="item {if $order_country == '759'}active{/if}">
				<a href="#c759" rel="nofollow">{$L.ORDERV3_kazakstan}</a>
			</div>
			<div class="region" style="float:right;margin-right: 40px;">
				<span class="show-region">{$L.ORDERV3_other_region}</span>
				<select size="10" name="country" id="basket_country_2" class="input select_country" style="display: none;margin: 5px;">
					{foreach from=$country item="c"}			
					<option value="{$c.country_id}" {if $c.country_id == $order_country}selected="selected"{/if}>{$c.country_name}</option>
					{/foreach}
				</select>
			</div>			
			<div style="clear:both;"></div>
			{if $PAGE->lang=="en"}
				<div class="eng clearfix">
					<div class="item {if $order_country == '863'}active{/if}">
						<a href="#c863" rel="nofollow">United States</a>
					</div>
					<div class="item {if $order_country == '726'}active{/if}">
						<a href="#c726" rel="nofollow">Germany</a>
					</div>
					<div class="item {if $order_country == '782'}active{/if}">
						<a href="#c782" rel="nofollow">Latvia</a>
					</div>
					<div class="item {if $order_country == '763'}active{/if}">
						<a href="#c763" rel="nofollow">Сanada</a>
					</div>
					<div class="item {if $order_country == '895'}active{/if}">
						<a href="#c895" rel="nofollow">Czech Republic</a>
					</div>
					<div class="item {if $order_country == '755'}active{/if}">
						<a href="#c755" rel="nofollow">Spain</a>
					</div>
					<div class="item {if $order_country == '888'}active{/if}">
						<a href="#c888" rel="nofollow">France</a>
					</div>
					<div class="item {if $order_country == '709'}active{/if}">
						<a href="#c709" rel="nofollow">United Kingdom</a>
					</div>
					<div class="item {if $order_country == '747'}active{/if}">
						<a href="#c747" rel="nofollow">Israel</a>
					</div>
				</div>
			{/if}
			<!--div class="close"><img width="15" height="15" title="Удалить" src="/images/icons/delete_gray.gif"></div-->
		</div>
		<div class="p-region-city">
			<div class="p-region-city-current">
				<span class="p-region-city-current-region">{$default_city_name}</span>
				<a href="#" rel="nofollow" class="p-region-city-current-edit">{$L.ORDERV3_change}</a>
			</div>
			<div class="p-region-city-input" style="display:none">
				<input type="text" value="" placeholder="укажите ваш регион" />
				<button disabled="disabled" id="p-region-city-save" >{$L.ORDERV3_save}</button>
			</div>
			<div style="clear:both"></div>
			<div  style="min-height:80px">
				{foreach from=$citys key="countryKey" item="country"}
				<!--города по стране-->
				<div class="c{$countryKey} b-popup-city-offices-list-tabs-item clearfix" style="display:{if $order_country == $countryKey}block{else}none{/if}">
					{foreach from=$country.columns item="column"}
						<!--колонка-->
						<div class="p-region-city-offices-column">		
						{foreach from=$column key="letter" item="c"}
							<div class="p-region-city-offices-cell">
								<div class="p-region-city-offices-cell-letter">{$letter}</div>
								{foreach from=$c item="city"}
								<div class="p-region-city-offices-item">
									<a href="#{$city.id}" postcost="{$city.postcost}" cost="{$city.cost}" time="{$city.time}" post_zakaznoe="{$city.post_zakaznoe}" rel="nofollow" class="a-pseudo-item" {if $default_city_name==$city.name}style="font-weight: bold"{/if}>{$city.name}</a>
								</div>
								{/foreach}
							</div>
						{/foreach}
						</div>
					{/foreach}
				</div><!--конец страны-->
				{/foreach}
			</div><!--конец стран-->
			
			<div class="short-info">
				<p>{$L.ORDERV3_delivery_notice}</p>
			</div>
		</div>
	</div>
</div>