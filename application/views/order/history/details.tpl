{if $O->logs.delivery_order}
<div class="order-item order-details">
	<b>Заказ на доставку:</b> "{$O->logs.delivery_order.0.result}"
</div>
{/if}

{if $O->logs.restoredfrom}

<p class="error" style="font-weight: bolder;text-align: center">
	Заказ восстановлен из заказа <a href="/{$PAGE->module}/{$O->logs.restoredfrom.0.result}/" class="error">{$O->logs.restoredfrom.0.result}</a> 
</p>

{/if}

{foreach from=$goods item="g"}
<div class="order-item order-details">
	<div class="img">
		<a href="/basket/zoom/{$g.good_id}/{$g.style_id}/{$g.def_side}/?height={if $MobilePageVersion}400&width=260{else}530&width=510{/if}" class="thickbox"><img title="{$g.good_name}" alt="{$g.good_name}" src="{$g.imagePath}"></a>		
		{if $g.good_status == "customize" && $g.imageBackPath}
		<a href="/basket/zoom/{$g.good_id}/{$g.style_id}/back/?height={if $MobilePageVersion}400&width=260{else}530&width=510{/if}" class="thickbox"><img src="{$g.imageBackPath}" alt="{$g.good_name}" title="{$g.good_name}" alt="{$g.good_name}"/></a>
		{/if}		
	</div>
	<div class="name">		
		{if $g.good_status != 'customize'}			
			<a href="{$g.link}" class="work-name" rel="nofollow" title="{$g.good_name}">{$g.good_name}</a>
			<span class="author"> &mdash; автор <a href="/catalog/{$g.user_login}/" rel="nofollow" title="{$g.user_login}">{$g.user_login}</a></span>
		{else}
			<a href="/basket/zoom/{$g.good_id}/{$g.style_id}/{$g.def_side}/?height={if $MobilePageVersion}400&width=260{else}530&width=510{/if}" rel="nofollow" class="thickbox">{$g.good_name}</a>
		{/if}
		
		<span>
			{$g.style_name}{if $g.size_rus != ''}&nbsp;{$g.size_name}&nbsp;({$g.size_rus}){/if}{if $g.comment != ''}, {$g.comment}{/if}
			{if $g.quantity > 1}, {$g.quantity} шт.{/if}
			
			{if $g.author_payment}
				<br/><span style="font-size:9px">автор получит за эту позицию {$g.author_payment} руб.</span>
			{/if}
		</span>
	</div>

	{if ($g.category == 'phones' || $g.category == 'cases' || $g.category == 'bumper' || $g.category == 'ipodmp3') && $g.style_print_block.wall.w > 0 &&  $g.style_print_block.wall.h > 0}
	<a href="/ajax/getWallpapper/{$g.good_id}/{$g.style_id}/" title="Скачать обои" rel="nofollow" class="oboi">Скачать обои</a>
	{/if}

	{if $g.avalible_sizes && $o.user_basket_status == 'ordered'}
	<select class="change-size" onchange="return changeSize('{$g.ubgid}', $(this).val())" style="display:none;">
		{foreach from=$g.avalible_sizes item="s"}
		<option value="{$s.good_stock_id}" {if $s.size_id == $g.size_id}selected="selected"{/if}>{$s.size_name}</option> 
		{/foreach}
	</select>
	<a href="#" title="" rel="nofollow" class="reader1" style="display:none">{$L.ORDERHISTORY_change}</a>
	{/if}
	<div class="price">{$g.tprice} {$L.CURRENT_CURRENCY}</div>
	{if $o.STATUSCODE=='delivered' && !$MobilePageVersion}
		{if $g.exchanged}	
		<span class="vozvratBib"></span>
	{/if}{/if}
	<div style="clear:both"></div>
</div>
{/foreach}

{foreach from=$gifts item="g"}
<div class="order-item order-details">
	<div class="img">
		<img title="{$g.gift_name}" alt="{$g.good_name}" src="{$g.picture_path}">
	</div>
	<div class="name">
		<a href="#" rel="nofollow">{$g.gift_name}</a>
	</div>
	<div class="price">{$g.tprice} {$L.CURRENT_CURRENCY}</div>
	<div style="clear:both"></div>
</div>
{/foreach}

<div style="clear:both"></div>

{if $PAGE->module != 'order.v3'}
<div class="summa-deliver">
	<div class="deliver">
		{if $DETAILS.BUYER_ADDRESS}
		<div class="wrap">
			<div class="title">{$L.ORDERHISTORY_delivery_address}:</div>
			<div class="info">
				{if $o.STATUSCODE == "ordered"}
				<a href="/order.v3/{$o.ID}/" title="{$L.ORDERHISTORY_delivery_address_change}" rel="nofollow" style="color: #A1A1A1;">
					<span class="s" style="white-space: normal">{$DETAILS.BUYER_ADDRESS}</span>
					<span class="reader">{$L.ORDERHISTORY_change}</span>					
				</a>
				{else}
					<span class="s" style="white-space: normal">{$DETAILS.BUYER_ADDRESS}</span>
				{/if}
			</div>
		</div>
		{/if}
		<div style="clear:both"></div>
		<div class="wrap">
			<div class="title">{$L.ORDERHISTORY_phone}:</div>
			<div class="info">
				<span class="s">{$custPHONE}</span>
				{if $o.STATUSCODE == "ordered"}
				<a href="#" rel="nofollow" class="reader" id="show-telefon-2">{$L.ORDERHISTORY_add}</a>
				{/if}				
				<div class="box-info-p telefon-2">
					<i style="left:17px"></i><span class="close"></span>
					<form action="" method="post">
						<input type="text" class="num" placeholder="{$L.ORDERHISTORY_phone}" value="" name="phone" style="font-style: italic;">
						<input type="submit" onclick="return addphone(this)" class="subm" value="{$L.ORDERHISTORY_save}">
					</form>
				</div>
			</div>
		</div>
		<div style="clear:both"></div>
		<div class="wrap block-delivery">
			<div class="title">{$L.ORDERHISTORY_delivery}:</div>
			<div class="info">
				<span class="s">{$DETAILS.DELIVERY_TYPE}.
				{if $order.user_basket_status == 'delivered' && $LOGS.change_status.0.result == 'delivered' && $LOGS.change_status.0.info != ''}
					{$L.ORDERHISTORY_tracking_code} <b>{$LOGS.change_status.0.info}</b>
				{/if}</span>
				<span class="sum-deliver">{if $order.user_basket_delivery_cost > 0}{$order.user_basket_delivery_cost} {$L.CURRENT_CURRENCY}{/if}</span>
				{if $o.STATUSCODE == "ordered"}
				<a href="/order.v3/{$o.ID}/" class="change reader" onclick="" title="" rel="nofollow" name="izmenit_dostavky">{$L.ORDERHISTORY_change}</a>
				{/if}
			</div>	
			<div style="clear:both"></div>
		</div>
		<div style="clear:both"></div>
		{if $o.STATUSCODE != "canceled" && $o.STATUSCODE != "delivered"}
		<div class="wrap">
			<div class="title">{$L.ORDERHISTORY_delivery_period}:</div>
			<div class="info">
				{if $delivery_service_time}отправка в логистическую компанию {/if}<span style="display:block">{$deliver_srok}</span>
				{if $delivery_service_time} (доставка {if $delivery_service_time.time1 && $delivery_service_time.time1 != $delivery_service_time.time2}{$delivery_service_time.time1}-{/if}{$delivery_service_time.time2} дня(ей)){/if}
			</div>
		</div>
		{/if}
		<div style="clear:both"></div>
		<div class="wrap">
			<div class="title">{$L.ORDERHISTORY_payment}:</div>
			<div class="info">
				<span class="s"><b>{$DETAILS.PAYMENT}</b></span>
				{if $o.STATUSCODE == "ordered"}
				<a href="/order.v3/{$o.ID}" rel="nofollow" class="reader" name="izmenit_oplaty">{$L.ORDERHISTORY_change}</a>
				{/if}
			</div>
		</div>
		<div style="clear:both"></div>
	</div>
	<div class="summ-bonus">
		<h3 class="sum-title">{$L.ORDERHISTORY_total}:</h3>
		<h3 class="sum" sum="">{$ttotalPrice + $order.user_basket_delivery_cost - $order.user_basket_payment_partical} {$L.CURRENT_CURRENCY}</h3>
		{if $order.user_basket_payment_partical > 0}
		<div class="bonus_play">{$L.ORDERHISTORY_paid_with_ls} {$order.user_basket_payment_partical} {$L.CURRENT_CURRENCY}</div>
		{/if}
		
		{if $partners_comission > 0}
		<div>Партнёрская комиссия: {$partners_comission} руб.</div>
		{/if}

		{if !$basketBack}
		{else}
			<div class="you_return">
				{if !$LOGS.wholesale}	{* если это не оптовый заказ *}
					{$L.ORDERHISTORY_bonus_back}: {$basketBack} руб.
				{/if}
			</div>
		{/if}
	</div>
</div>
{/if}


{if $O->user_basket_status == "delivered"}
{if $sendreview == 'form'}
<form class="review-purchase" method="post" action="/{$PAGE->module}/sendreview/">
	<div class="topik">Нам важно Ваше мнение:</div>
	<textarea name="text" placeholder="Пожалуйста напишите здесь свой отзыв к покупке!
Нам очень важно Ваше мнение!"></textarea>
	<div class="ttl">Качество продукции:</div>
	<label><input type="radio" name="a" value="Отличное" checked="checked">Отличное</label>
	<label><input type="radio" name="a" value="Среднее">Среднее</label>
	<label><input type="radio" name="a" value="Плохое">Плохое</label>
	<div class="ttl">Качество печати:</div>
	<label><input type="radio" name="b" value="Доволен" checked="checked">Доволен!</label>
	<label><input type="radio" name="b" value="Обычное">Обычное.</label>
	<label><input type="radio" name="b" value="Ужас">Ужас!</label>
	<div class="ttl">Оцените доставку:</div>
	<label><input type="radio" name="c" value="Все в срок" checked="checked">Все в срок!</label>
	<label><input type="radio" name="c" value="Средне">Средне.</label>
	<label><input type="radio" name="c" value="Задержали">Задержали!</label>
	<div class="ttl">Размер:</div>
	<label><input type="radio" name="d" value="Идеально" checked="checked">Идеально!</label>
	<label><input type="radio" name="d" value="Чуть великовато">Чуть великовато.</label>
	<label><input type="radio" name="d" value="Вещь мала">Вещь мала!</label>
	<div class="ttl">Стоит покупать:</div>
	<label><input type="radio" name="e" value="Да" checked="checked">Да</label>
	<label><input type="radio" name="e" value="Нет">Нет</label>
	
	<input type="hidden" name="user_basket_id" value="{$order.user_basket_id}">
	<input type="submit" name="" value="Отправить">
</form>
{else}
	<p class="review-purchase-spasibo">Спасибо за Ваш отзыв!</p>
{/if}
{/if}