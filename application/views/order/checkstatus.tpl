	{if !$smarty.post.search}
	<div style="text-align: center;padding-top: 100px">
		<h4>Введите последние 4 цифры телефона, на который был сделан заказ</h4>
		<form method="post" action="/{$PAGE->module}/checkstatus/" style="width: 100%;padding-bottom:30px;padding-top:15px">
			<input type="text" name="search" placeholder="номер заказа" value="{$smarty.post.search}" style="font-size: 40px;width:120px" />
			<input type="submit" value="найти" style="font-size:40px" />
		</form>
	</div>
	{/if}
	
	{if $smarty.post.search}	
	<div class="checkstatus clearfix">	
		{if $order}	
			{if $order->user_basket_status != 'delivered' && $order->user_basket_status != 'canceled'}
				{if $order->user_basket_delivery_type != 'user'}
					{if $order->deliverydate}
						<div class="status ordered">Заказ <span class="nomer">№{$order->shortNumber}</span> будет доставлен {if $delivery_service_time}в логистическую компанию{/if} {$order->deliverydate}{if $order->user_basket_delivery_type == "deliveryboy" || $order->user_basket_delivery_type == "metro"}, за час позвоним{/if}{if $delivery_service_time} (доставка {if $delivery_service_time.time1 && $delivery_service_time.time1 != $delivery_service_time.time2}{$delivery_service_time.time1}-{/if}{$delivery_service_time.time2} дня(ей)){/if}</div>
					{/if}
				{else}
					<div class="status ordered">Заказ <span class="nomer">№{$order->shortNumber}</span> {if $order->deliverydate}будет готов {$order->deliverydate}{else}{/if}. Перед приездом дождитесь СМС-оповещения о готовности</div>
				{/if}
			{/if}
			<div class="orderInfo clearfix">		
				<div class="topic clearfix"><label>Заказ</label> <span class="ib"><b>№{$order->shortNumber}</b> {$order->user_basket_date_rus} &mdash; "{$order->status}"</span></div>	
				<div class="infoRead left">
					{if $order->user_id == $USER->id}
					<div>
						<label>Телефон</label> {$order->address.phone} {if $order->user_basket_status == "ordered"}<a href="/order.v3/{$order->id}/" class="reader" rel="nofollow" >Изменить</a>{/if}
					</div>
					{/if}
					<div>
						<label>Доставка</label> {$order->user_basket_delivery_type_rus} {if $order->user_basket_status == "ordered" && $order->user_id == $USER->id}<a href="/order.v3/{$order->id}/" class="reader" rel="nofollow" >Изменить</a>{/if}
					</div>	
					{if $order->user_basket_status != "canceled"}
					<div>
						<label>Дата отправки</label> {$order->deliverydate} &nbsp; {if $order->user_basket_status == "ordered" && $order->user_id == $USER->id}<a href="/order.v3/{$order->id}/" class="reader" rel="nofollow" >Изменить</a>{/if}
					</div>	
					{/if}
					<div>
						<label>Оплата</label> <b>{$order->user_basket_payment_type_rus}</b> {if $order->user_basket_payment_confirm == 'true'}(оплачено){/if} &nbsp; {if $order->user_basket_status == "ordered" && $order->user_id == $USER->id}<a href="/order.v3/{$order->id}/" class="reader" rel="nofollow" >Изменить</a>{/if}
					</div>	
				
					{if $order->user_basket_status != 'delivered' && $order->user_basket_status != 'canceled'}	
					{if $USER->authorized  && $order->user_id == $USER->id}
					<div class="clearfix">
						<label class="last left">CМС статус</label>
						<input type="hidden" name="sms_info" />
						<div class="sms_info left clearfix">
							<span class="sms-b {if $sms_info_checked == 'TRUE'}on{/if}" title="{$L.CONFIRM_on}."></span>
							<span class="sms-b {if !$sms_info_checked}on{/if}" title="{$L.CONFIRM_off}."></span>
						</div>	
					</div>
					{/if}
					{/if}
				</div>	
				
				<div class="attention left">
					{if !$USER->authorized}
					<div class="z"></div>
					Вносить изменения в <br>заказ могут <a rel="nofollow" {if !$USER->authorized}}href="/login/" title="{$L.HEADER_authorization}" onclick="{if $USER->client->ismobiledevice == '1'}document.location = $(this).attr('_href');return false;{else}return qLogin.showForm();{/if}"{/if}>авторизированные</a><br/> пользователи
					<br/><br/>				
					{/if}	
					<b><a href="mailto:team@maryjane.ru" class="showFeedback" title="Напишите нам" rel="nofollow">Напишите нам</a></b>
					<br/> 
					(Мы ответим в течении 20мин.)
				</div>	
				
			</div>
		{else}
			Заказа с таким номером не найдено
		{/if}	
	</div>
	{/if}
	
{*else}
<div style="text-align: center;padding-top: 100px">
	<h4>Введите последние 4 цифры телефона, на который был сделан заказ</h4>
	
	<form method="post" action="/{$PAGE->module}/checkstatus/" style="width: 100%;padding-bottom:30px;padding-top:15px">
		<input type="text" name="search" placeholder="номер заказа" value="{$smarty.post.search}" style="font-size: 40px;width:120px" />
		<input type="submit" value="найти" style="font-size:40px" />
	</form>
	
	{if $smarty.post.search}
		<p style="font-size:18px">
		{if $order}
			Заказ находится в статусе "{$order->status}"
			{if $order->deliverydate && $order->user_basket_delivery_type != 'user'}
			<p>
				Заказ будет доставлен {$order->deliverydate}
			</p>
			{/if}
			<p>
				<a href="/orderhistory/{$order->id}/">внести изменения в заказ</a> |
				<a href="#" class="showFeedback">задать вопрос по заказу</a> (Мы ответим в течение 30 минут)
			</p>
		{else}
			Заказа с таким номером не найдено
		{/if}
		</p>
	{/if}
</div>
{/if*}