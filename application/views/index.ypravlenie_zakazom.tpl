{*для главной - упрощенный в индесе*}

{if !$MobilePageVersion}
	<div style="position:relative;width:980px;height:0px;margin:0 auto">{include file="order/history/form.change.order.tpl"}</div>

	<div id="refundExchangeHint" style="display:none">
		<div class="refundExchangeHint" style="display:none">		
			<span>На Ваш счёт начислено <font class="prc">100</font> руб. Оформите новый заказ.</span>
			<br/>
			<a href="/orderhistory/return/cancel/" rel="nofollow">Я передумал!</a>		
		</div>
		<div class="loadRefundExchangeHint" style="display:none"></div>
	</div>
{/if}
	
{if (!$active_order.hideExchangePanel || $exchengedBonuses > 0) && !$smarty.cookies.closeYpravlenieZakazom && !$smarty.cookies.closeActiveOrder}
	<script type="text/javascript" src="/js/calendar.js"></script>
	<script type="text/javascript">
		{if $active_deliveryboy_deliver_posible}
			var deliveryboy_deliver_posible = {$active_deliveryboy_deliver_posible};
		{else if $deliveryboy_deliver_posible}
			var deliveryboy_deliver_posible = {$deliveryboy_deliver_posible};
		{else}
			var deliveryboy_deliver_posible = [];
		{/if}
		
		//var module = '{$module}';// есть в хереде		
	</script>

	<div class="ypravlenie_zakazom {$PAGE->lang}" {if $smarty.cookies.closeYpravlenieZakazom == $active_order.user_basket_id}style="display:none"{/if}>
		<div class="y">
			<a rel="nofollow" href="/orderhistory/" class="zakazi-link">
				<span class="ico-o"></span>
				<span class="titl">{$L.HEADER_management_my_orders}</span>
			</a>
			
			{if $exchengedBonuses == 0}
			<a rel="nofollow"  href="/orderhistory/{$active_order.user_basket_id}/" class="n-zakaz" title="{$active_order.status}{if $active_order.user_basket_status != 'delivered' && $active_order.printed >= $active_order.goods}, {$L.HEADER_management_made}{/if}">{$L.HEADER_management_order} № {$active_order.order}</a>
			{/if}
			
			{*|| $USER->user_id == 105091
			1-  статут доставлен бонусы есть=9px
			2-  статут доставлен бонусов нету и кода отслеживан нету=145px
			3-  статут доставлен бонусов нету и код отслеживан есть=80px
			4 - оплачен но не доставлен =10px не (кэшом/наложкой/бонусами) если есть время когда забрать
			5 - оплачен но не доставлен =100px не (кэшом/наложкой/бонусами)
			6 - оплачен но не доставлен =155px 
			7- нету ануляции заказа =85px 
			8- не оплачен и недоставлен: сбербанк - и в резерве 2px без резерва 20px, остальная оплата 6px
			9- остальное =1px
			*}
			
			<div class="otstupLeft" style="height:35px;float:left;width: 
			{if $active_order.user_basket_status == 'delivered' && $exchengedBonuses > 0}9px
			{elseif $active_order.user_basket_status == 'delivered' && $exchengedBonuses <= 0 && !$active_order.trackingCode}145px
			{elseif $active_order.user_basket_status == 'delivered' && $exchengedBonuses <= 0}80px
			{elseif $active_order.user_basket_status != 'delivered' && $active_order.user_basket_payment_confirm == 'true' && $active_order.user_basket_payment_type != 'ls' && $active_order.user_basket_payment_type != 'cash' && $active_order.user_basket_payment_type != 'cashon'}
				{if $active_order.reserv > 0}10px{else}100px{/if}			
			{elseif $active_order.user_basket_status != 'delivered' && $active_order.user_basket_payment_confirm == 'true'}155px
			{elseif ($active_order.user_basket_status != 'ordered' && $active_order.user_basket_status != 'active') || $active_order.printed > 0}85px	
			{elseif $active_order.user_basket_payment_confirm == 'false' && $active_order.user_basket_status != 'delivered'} 
				{if $active_order.user_basket_payment_type == 'sberbank'}
					{if $active_order.reserv > 0}2px{else}20px{/if}
				{else}6px{/if}
			{else}1px{/if};"></div>
			
			{if $active_order.user_basket_status == 'delivered'}
				{if $exchengedBonuses < $active_order.totalGoodsPrice}
				<a href="#" class="border change-link" rel="nofollow">
					<span class="ico-o"></span>
					<span class="titl">
						{$L.HEADER_management_exchange_days} {$active_order.exchangeIsPosible} д.)
					</span>
				</a>
				{/if}				
				
				<span class="border ya-peredumal" style="{if $exchengedBonuses < $active_order.totalGoodsPrice}margin-left:47px;{/if}display:{if $exchengedBonuses > 0}block;{else}none;{/if}">
					<span class="titl" style="border:0">
						{$L.HEADER_management_your_account_charged} <font class="prc">{$exchengedBonuses}</font> {$L.HEADER_management_valuta} {$L.HEADER_management_regiser_new_order}.&nbsp;&nbsp;<a href="/orderhistory/return/cancel/" class="titl" style="float: none;display: inline;" rel="nofollow">{$L.HEADER_management_my_i_changed}!</a>
					</span>
				</span>				
				
				 <a rel="nofollow" href="#" class="border close-link" title="{$L.HEADER_management_close}" data-order-id="{$active_order.user_basket_id}" data-days="{$active_order.exchangeIsPosible}">
					<span class="titl">{$L.HEADER_management_close}</span>
					<span class="ico-o"></span>			
				</a>
				
				{if $active_order.trackingCode}
					<div class="trackingCode" style="float:left;font-size:12px;margin:12px 15px 0 25px;color:#ffffff;"><span>{$L.HEADER_management_trackingCode}:</span> <a style="color:#fff;" rel="nofollow" target="_blank" href="{if $active_order.user_basket_delivery_type == 'post'}{*http://www.russianpost.ru/tracking20/?{$active_order.trackingCode *}https://www.pochta.ru/tracking#{$active_order.trackingCode}{elseif $active_order.user_basket_delivery_type == 'dpd'}http://www.dpd.com/tracking/{else}#{/if}">{$active_order.trackingCode}</a>
					</div>
				{/if}
			{/if}
				
			{if $active_order.user_basket_payment_confirm == 'false' && $active_order.user_basket_status != 'delivered'}
				<span class="rez-zakaz-d">
					{if $active_order.reserv < 50}
				{if $active_order.reserv > 0}
					{$L.HEADER_management_in_store}: {$active_order.reserv} {if $active_order.reserv == 1}{$L.HEADER_management_days1}{elseif $active_order.reserv >= 2 && $active_order.reserv <= 4}{$L.HEADER_management_days2}{else}{$L.HEADER_management_days3}{/if}
				{else} 
					{$L.HEADER_management_reserve_expired} 
				{/if}
				{/if}
				</span>
			{/if}
			
			{if $active_order.user_basket_payment_type != 'ls' && $active_order.user_basket_payment_type != 'cash' && $active_order.user_basket_payment_type != 'cashon'}
				{if $active_order.user_basket_payment_confirm == 'false'}
					{if $active_order.user_basket_payment_type == 'sberbank'}
						<a rel="nofollow" target="_blank" href="/payments/sberbank/{$active_order.user_basket_id}/" class="border print-kvatansia" title="распечатать">
							<span class="ico-o"></span>
							<span class="titl">{$L.HEADER_management_receipt}</span>
						</a>
					{else}
						{if $active_order.reserv > 0}{literal}<style>.ypravlenie_zakazom input{margin:2px 4px 2px 0px;}</style>{/literal}{/if}
						
						{include file=$active_order.PAYMENT_FORM}
						
						{if $active_order.user_basket_payment_type == 'creditcard'  || $active_order.user_basket_payment_type == 'yamoney' || $active_order.user_basket_payment_type == 'webmoney' || $active_order.user_basket_payment_type == 'qiwi'}
														
							{if $active_order.reserv > 0 && $active_order.user_basket_delivery_type == 'user'}{literal}<style>.ypravlenie_zakazom .rez-zakaz-d{margin-left:4px;}</style>{/literal}{/if}
							{literal}	
								<script type="text/javascript">
									$(document).ready(function() {
										$(".ypravlenie_zakazom form input:submit").click(function(){
											debugger;
											trackUser('Оплата Эл.валютой','{/literal}{$active_order.user_basket_payment_type}{literal}');//трек гугл аналитик
										});
									});
								</script>
							{/literal}
						{/if}
					{/if}
				{else}
				
					{if $active_order.user_basket_status != 'delivered'}
						<div class="oplacheno_no-deliver">{$L.HEADER_management_paid_delivery} "{$active_order.delivery}" {if $active_order.reserv > 0 && $active_order.user_basket_delivery_type == 'user' && !$active_order.isPrinted}{else}{$active_order.deliver_srok}{/if}</div>
					{/if}

				{/if}
			{/if}
			
			{if $active_order.user_basket_status != 'delivered'}
				{if $active_order.user_basket_delivery_type != 'user' && $active_order.user_basket_delivery_type != 'IMlog_self' && $active_order.user_basket_delivery_type != 'dpd_self'}
						<div class="dostav-link border">
						<span class="ico-o"></span>
						<a href="/order.v3/{$active_order.user_basket_id}/" class="titl">{$L.HEADER_management_address_delivery}</a>
						{*
						<div class="box-info-p" style="height: 100px;">
							<i></i><span class="close"></span>
							<style>
							.kalendarik .chislo ul li.activ{
								color: #ffffff; 
								background: red;
							}
							</style>
							<form method="post" class="" action="">
								<div class="kalendarik"><input type="hidden" name="data[kurer_date]" value="27/02/2013"><div class="day"><span class="d">Пн</span><span class="d">Вт</span><span class="d activ">Ср</span><span class="d">Чт</span><span class="d">Пт</span><span class="d">Сб</span><span class="d">Вс</span></div><div style="clear:left;"></div><div class="chislo"><span class="icon left disabled" disabled="disabled"></span><ul><li class="l">25</li><li class="l">26</li><li class="activ">27</li><li>28</li><li>1</li><li>2</li><li class="l">3</li></ul><span class="icon right"></span></div></div>
								<div style="clear:left;"></div>
								<select style=" width:90px;float: left;margin: 12px 0 10px 55px;padding-left: 4px;" name="data[kurer_time]">
									<option value="с 11 до 14">с 11 до 14</option>
									<option value="с 14 до 16">с 14 до 16</option>
									<option value="с 16 до 18">с 16 до 18</option>
									<option value="с 18 до 21">с 18 до 21</option>
								</select>
								<a rel="nofollow" href="#" style="display:none;" class="galo4ka"></a>
								<a href="/order.v3/{$active_order.user_basket_id}/" class="read-adress-dostavki" rel="nofollow" title="{$L.HEADER_management_change_delivery}">{$L.HEADER_management_change_delivery}</a>
								<input type="submit" style="display:none;" />
							</form>
						</div>
						*}
					</div>
				{else}
					<a rel="nofollow" href="{if $active_order.user_basket_payment_confirm == 'true'}/orderhistory/{else}/order.v3/print/{/if}{$active_order.user_basket_id}/" class="border sxema-proezda" title="{$L.HEADER_management_scheme}">
						<span class="ico-o"></span>
						<!--span class="titl">Схема проезда</span-->
					</a>			
					<a rel="nofollow" href="{if $active_order.user_basket_payment_confirm == 'true'}/orderhistory/{else}/order.v3/{/if}{$active_order.user_basket_id}/" class="border smen-samovivoz" style="margin: 5px 6px 0 0px;">
						<span class="titl {if $active_order.user_basket_payment_confirm == 'true'}not_dashed{/if}">{$L.HEADER_management_pickup}</span>
					</a>
				
					{if $active_order.reserv > 0}
						{if $active_order.user_basket_delivery_type == 'user'}
							<span class="text_order_gotov" title="{if $active_order.isPrinted}Заказ готов.{else}Заказ будет готов {$active_order.deliver_srok}.{/if}">
							{if $active_order.isPrinted}
								Заказ готов.
							{else}
								Заказ будет готов {$active_order.deliver_srok}.
							{/if}
							</span>
						{/if}	
					{/if}
				{/if}
			{/if}
			
			{if $active_order.user_basket_status == 'ordered' && $active_order.printed == 0}
			<a rel="nofollow" href="/orderhistory/cancel/?height=210&width={if $MobilePageVersion}260{else}400{/if}" class="border anull-link thickbox" title="{$L.HEADER_management_cancel}">	
				<span class="ico-o"></span>	
				<span class="titl">{$L.HEADER_management_cancel}</span>			
			</a>
			{/if}

			<div style="clear:both"></div>
		</div>
	</div>
	<div style="clear:both"></div>
{/if}