{if $PAGE->lang =='ru'}
	{include file="order/freedelivery.deliveryboy.tpl"}
{/if}

{if $basket->user_basket_discount == 10}
<div class="red-galka">
	Мы рады Вас видеть снова!
	{if $MobilePageVersion}<br/>{/if}
	<div class="ga"></div>
	Ваша скидка {$basket->user_basket_discount} %
	&nbsp;&nbsp;действует 24 часа.
</div>
{/if}

<div class="basket-page {$PAGE->lang} clearfix">
	
	{if $error}

		{if $error == 1}
			<div class="error_order">{$L.BASKET_errors_duplicate_user}</div>
		{elseif $error == 2}
			{literal}<script>$(document).ready(function(){trackUser('Промокод','error-'+module,'Вы не можете использовать сертификат, пока вы не авторизованы');});</script>{/literal}
			<div class="error_order">Вы не можете использовать сертификат, пока вы не авторизованы.<br />
			<a href="/login/">{$L.BASKET_authorization}</a>  | <a href="/registration/">{$L.BASKET_registration}</a></div>
		{elseif $error == 3}
			{literal}<script>$(document).ready(function(){trackUser('Промокод','error-'+module,'{/literal}{$L.BASKET_certificate_number_error}{literal}');});</script>{/literal}
			{*в низу ошибка теперь<div class="error_order">{$L.BASKET_certificate_number_error}</div>*}
		{elseif $error == 4}
			{literal}<script>$(document).ready(function(){trackUser('Промокод','error-'+module,'{/literal}{$L.BASKET_certificate_already_activated}{literal}');});</script>{/literal}
			{*в низу ошибка теперь<div class="error_order">{$L.BASKET_certificate_already_activated}</div>*}
		{elseif $error == 5}
			{literal}<script>$(document).ready(function(){trackUser('Промокод','error-'+module,'{/literal}{$L.BASKET_certificate_limit}{literal}');});</script>{/literal}
			{*в низу ошибка теперь<div class="error_order">{$L.BASKET_certificate_limit}</div>*}
		{elseif $error == 6}
			<div class="error_order">{$L.BASKET_already_added}</div>
		{elseif $error == 8}
			<div class="error_order">{$L.BASKET_product_not_found}</div>
		{elseif $error == 9}
			{literal}<script>$(document).ready(function(){trackUser('Промокод','error-'+module,'{/literal}{$L.BASKET_certificate_activate_error}{literal}');});</script>{/literal}
			{*в низу ошибка теперь<div class="error_order">{$L.BASKET_certificate_activate_error}</div>*}
		{else}
			<div class="error_order">{$error.text}</div>
		{/if}
	{/if}

	{if $success}
	<div class="error_order" style="background:#FFCC00; color: #000;">
		{literal}<script>$(document).ready(function(){trackUser('Промокод','ok',module);});</script>{/literal}
		
		{if $success == "1"}
			{$L.BASKET_certificate_success_1} {$bonusesGiven} {$L.BASKET_certificate_success_1_1}
		{/if}
	
		{if $success == "2"}
			{$L.BASKET_certificate_success_2} {if $discountPercent}{$discountPercent}%{else}{$L.BASKET_certificate_success_3}{/if}.
		{/if}
	
		{if $success == "3"}
			Поздравляем, к вашему заказу будет приложен подарок - фрисби.
		{/if}
	
		<a href="/{$module}/">{$L.BASKET_close}</a>
		
	</div>
	{/if}
	
	{if $USER->meta->givegifts}
	<div class="error_order" style="background:#FFCC00;color:#000;">Добавьте в корзину товар на сумму превышающую 3000 рублей и получите 300 руб в подарок</div>
	{/if}
	
	
	
	
	
	<!-- Таблица с товарами -->
	<div id="basket_content" style="margin-top:20px">
		<input type="hidden" id="bbpercent" value="{$bbpercent}" />
		
		<form method="post" action="/{$PAGE->module}/sercodesubmit/">
			
			<table border="0" cellpadding="0" cellspacing="0" class="basket_goods" id="list_goods">	

				{if $goods|count == 0 && $gifts|count == 0}
				
					{literal}<style type="text/css">table.basket_goods {width:570px;margin:0}</style>{/literal}
					
					<tr class="one-line basket_is_empty">
						<td colspan="6" nowrap ><a href="{if $ref &&  $ref!='http://www.maryjane.ru/basket/'}{$ref}{else}/catalog/{/if}" rel="nofollow" title="{$L.BASKET_go_to_catalog}">{$L.BASKET_basket_is_empty}</a>		
						{if $HTTP_REFERER =="http://www.maryjane.ru/basket/"}
							{literal}<script>$(document).ready(function(){trackUser('вКорзинуПустая','Удалил все товары','');});</script>{/literal}
						{else}
							{literal}<script>$(document).ready(function(){trackUser('вКорзинуПустая','Зашел в пустую корзину','c {/literal}{$HTTP_REFERER}{literal}');});</script>{/literal}
						{/if}
						</td>
					</tr>				
				
				{else}
									
					{if $MobilePageVersion}
						<a class="without-reg-btn" href="/order.v3/">{$L.BASKET_buy}</a>	
					{/if}		
								
					{foreach from=$gifts item="g"}
					<tr class="one-line {if $g.avalible < 0} one-pink-line {/if}">
						<td class="td" colspan="6">
							<table border="0" cellpadding="0" cellspacing="0" class="item-table">
								{if $MobilePageVersion}
								<tr>
									<td class="td previev" rowspan="3" width="">
										<div class="g-name-wrap">
											<div class="good-img">
												<img class="" src="{$g.picture_path}" alt="{$g.gift_name}" />
											</div>
										</div>
									</td>
									<td class="td" width="" valign="top" style="text-align:right">
										{if $g.gift_id == 61}
										<div class="sum-p-sert">{$g.tprice}</div>
										<div class="g-count sert present" style="width: 71px;margin:0;position: absolute;top: 17px;right:-1px;text-align: center;height: 32px;overflow: hidden;display: none;">
											<input type="text" class="g-total" value="{$g.tprice}" ajax="/{$module}/edit_gift/{$g.gift_id}/?price=[price]" name="sert_value" style="margin-top:2px;width:50px;text-align: center">
											<span class="g-co" style="margin-top: 5px; display: inline;">
												<a class="change_input one-more" href="/basket/goodPlus1/?good_id=48422&amp;good_stock_id=72355&amp;dir=1">+</a>
												<a class="change_input one-less" href="/basket/goodPlus1/?good_id=48422&amp;good_stock_id=72355&amp;dir=-1">-</a>
											</span>
										</div>										
										{/if}
										<div class="g-total {if $g.gift_id == 61}edit{/if}" quantity="{$g.quantity}" price="{$g.tprice/$g.quantity}" ><font>{$g.tprice}</font>{if $PAGE->lang !='ru'}{$L.CURRENT_CURRENCY}{/if}</div>
									</td>
								</tr>
								<tr>
									<td class="td" height="">
										<div class="g-name-wrap opisanie-t">	
											<span class="good-descr">{$g.gift_name}</span>
										</div>
										<!--категория-->
										{if $g.gift_description}
										<div class="g-name-wrap opisanie-t">{$g.gift_description}</div>
										{/if}										
									</td>
								</tr>
								<tr>
									<td class="td" style="padding-bottom: 20px;vertical-align:bottom">
										<!--количество-->
										<div class="g-name-wrap" style="height:22px;float:left">
											<div class="g-count">
												<span style="display: inline-block;padding-top: 3px;float: left;">{$L.BASKET_quantity_short}:</span>
												<input type="text" class="cntedit" value="{$g.quantity}" ajax="/{$module}/giftPlus1/?gift_id={$g.gift_id}&dir=1&quantity=[count]" name="quantity[1]" />
												<span class="cnt text">{$g.quantity}{$L.BASKET_pcs}.</span>
												<span class="g-co">
													<a class="change_input one-more" href="/{$module}/giftPlus1/?gift_id={$g.gift_id}&dir=1">+</a>
													<a class="change_input one-less" href="/{$module}/giftPlus1/?gift_id={$g.gift_id}&dir=-1">-</a>
												</span>
											</div>
										</div>
										<a class="delete_good" title="{$L.BASKET_delete}" href="/{$module}/delete_gift/{$g.gift_id}/">
											<span>{$L.BASKET_delete}</span><img style="float: left;" width="15" height="15"  src="/images/icons/delete_gray.gif">
										</a>
										<div style="clear:both"></div>
									</td>
								</tr>
								{else}
								<tr>
									<td class="td previev" rowspan="8" width="90px">
										<div class="g-name-wrap">
											<div class="good-img">
												<img class="" src="{$g.picture_path}" alt="{$g.gift_name}" />
											</div>
										</div>
									</td>
									<td class="td" colspan="3" height="5px">
									</td>									
								</tr>
								<tr valign="bottom">
									<td class="td" height="18px" colspan="3" style="text-align:right"></td>								
								</tr>
								<tr>
									<td class="td" height="18px">
										<div class="g-name-wrap .name-t">
											<span class="good-link">
												{$g.gift_name}
											</span>
										</div>
									</td>
									<td class="td" width="40" valign="bottom" style="text-align:right">
									</td>
									<td class="td" width="70" style="text-align:right;{if $g.gift_id == 61}height:22px{/if}">
										{if $g.gift_id == 61}
										<div class="sum-p-sert">{$g.tprice}</div>
										<div class="g-count sert present" style="width: 71px;margin:0;position: absolute;top: 17px;right:-1px;text-align: center;height: 32px;overflow: hidden;display:none">
											<input type="text" class="g-total" value="{$g.tprice}" ajax="/{$module}/edit_gift/{$g.gift_id}/?price=[price]" name="sert_value" style="margin-top: 2px; width: 50px;text-align: center;">
											<span class="g-co" style="margin-top:5px;display: inline">
												<a class="change_input one-more" href="/basket/goodPlus1/?good_id=48422&amp;good_stock_id=72355&amp;dir=1">+</a>
												<a class="change_input one-less" href="/basket/goodPlus1/?good_id=48422&amp;good_stock_id=72355&amp;dir=-1">-</a>
											</span>
										</div>										
										{/if}
										<div class="g-total {if $g.gift_id == 61}edit{/if}" quantity="{$g.quantity}" price="{$g.tprice/$g.quantity}" ><font>{$g.tprice}</font>{if $PAGE->lang !='ru'}{$L.CURRENT_CURRENCY}{/if}</div>
									</td>
								</tr>
								<tr>
									<td class="td" valign="top" colspan="2" height="18px">
										<!--категория-->
										<div class="g-name-wrap"></div>
									</td>							
									<td class="td">
										<div class="g-disc" align="center" style="text-align:right"></div>
									</td>
								</tr>
								<tr height="30px">
									<td class="td" colspan="2" valign="top">
										<!--описание-->
										<div class="g-name-wrap opisanie-t">
											{if $g.gift_description}
												{$g.gift_description}
											{/if}
										</div>
									</td>							
									<td class="td">
										{*<div class="g-price" style="text-align: right;">{$g.gift_price} руб.</div>*}
									</td>
								</tr>
								<tr>
									<td class="td" colspan="2"  height="18px" style="color:#A9ABAB;"></td>							
									<td class="td"></td>
								</tr>
								<tr>
									<td class="td">
										<!--количество-->
										<div class="g-name-wrap" style="height: 22px;">
											<div class="g-count">
												<span style="display: inline-block;padding-top: 3px;float: left;">{$L.BASKET_quantity}:</span>
												<input type="text" class="cntedit" value="{$g.quantity}" ajax="/{$module}/giftPlus1/?gift_id={$g.gift_id}&dir=1&quantity=[count]" name="quantity[1]" />
												<span class="cnt text">{$g.quantity}{$L.BASKET_pcs}.</span>
												<span class="g-co">
													<a class="change_input one-more" href="/{$module}/giftPlus1/?gift_id={$g.gift_id}&dir=1">+</a>
													<a class="change_input one-less" href="/{$module}/giftPlus1/?gift_id={$g.gift_id}&dir=-1">-</a>
												</span>
											</div>
										</div>
									</td>								
									<td class="td"  colspan="2">
										<a class="delete_good" title="{$L.BASKET_delete}" href="/{$module}/delete_gift/{$g.gift_id}/">
											<span>{$L.BASKET_delete}</span><img style="float: left;" width="15" height="15"  src="/images/icons/delete_gray.gif">
										</a>
										<div style="clear:both"></div>
									</td>
								</tr>
								<tr height="18px">
									<td class="td" colspan="3"></td>
								</tr>
								{/if}
							</table>
						</td>						
					</tr>
					{/foreach}
					
					{foreach from=$goods item="g"}
					<tr class="one-line {if $g.good_status == 'customize'}customize-line{/if}">
						<td class="td" colspan="6">
							<div style="position: relative">
							<table border="0" cellpadding="0" cellspacing="0" class="item-table {$g.category} {if $g.avalible < 0}item_reserve{/if}">
							{if $MobilePageVersion}								
								{if $g.avalible < 0}
								<tr>					
									<td class="td" colspan="2">	
										<div class="b-pink-arr-left">
											{$L.BASKET_product_is_reserved_1}, <a href="/catalog/{$g.user_login}/{$g.good_id}/" style="color:#fff">{$L.BASKET_product_is_reserved_2}</a>
										</div>
									</td>
								</tr>
								{/if}
								<tr>
									<td class="td previev" rowspan="3">
										<div class="g-name-wrap">
											{if $g.imagePath}
											<div class="good-img">
												<a href="/{$module}/zoom/{$g.good_id}/{$g.style_id}/{$g.def_side}/?height=400&width=260&category={$g.category}" data-big="" class="glisse {*thickbox*}">
													<img class="{$g.category}" src="{$g.imagePath}" width="85" alt="{$g.good_name}" />
													<span class="icon-zoom"></span>
												</a>
											</div>
											{/if}
											{if $g.good_status == "customize" && $g.imageBackPath}
												<div class="good-img">
													<a href="/{$module}/zoom/{$g.good_id}/{$g.style_id}/back/?height=400&width=260&category={$g.category}" data-big="" class="glisse {*thickbox*}">
														<img class="{$g.category}" src="{$g.imageBackPath}" width="85" alt="{$g.good_name}" />
														<span class="icon-zoom"></span>
													</a>
												</div>
											{/if}
										</div>
									</td>
									<td class="td" style="text-align:right">
										{if $g.avalible >= 0}
											<div class="wpar-m-total">
												{if $g.gift_id == 61}
												<input type="text" class="g-total" value="{$g.tprice}" ajax="/{$module}/edit_gift/{$g.gift_id}/?price=[price]" style="display:none" />
												{/if}
												<div class="g-total {if $g.gift_id == 61}edit{/if}" quantity="{$g.quantity}" price="{$g.tprice/$g.quantity}"><font>{$g.tprice}</font>{if $PAGE->lang !='ru'}{$L.CURRENT_CURRENCY}{/if}</div>
											</div>											
											{if $g.discount > 0}
												<span class="no-sum"><strike>{$g.price * $g.quantity}</strike></span>
											{/if}
										{/if}
									</td>
								</tr>
								<tr>
									<td class="td">
										{if 1==2} {*уберем пока*}
										<div class="g-name-wrap name-t">	
											<span class="good-link">
												{if $g.cat_parent == 76}
													{$g.good_name}
												{else}
													<a href="{if $g.good_status != 'customize'}{$g.link}{else}/{$module}/zoom/{$g.good_id}/{$g.style_id}/?height=400&width=260{/if}" class="work-name {if $g.good_status == 'customize'}thickbox{/if}" title="{$g.good_name}" rel="nofollow">
														{if $g.good_status == "customize" && $g.imageBackPath}{* $g.good_name|truncate:6:"..." *}{$g.good_name}{else}{$g.good_name}{/if}
													</a>
													{if $g.good_status != 'customize'}<span class="author"> &mdash; автор <a href="/catalog/{$g.user_login}/" title="{$g.user_login}" rel="nofollow">{$g.user_login}</a></span>{/if}
												{/if}
											</span>
										</div>
										{/if}	
										<!--категория-->
										<div class="g-name-wrap opisanie-t">
											<span class="good-descr">{$g.style_name}</span>
											<span class="good-descr-all" style="color: #A9ABAB">{if $g.avalible >= 0 && $g.size_rus != ''}, &nbsp;{$g.size_name}&nbsp;{if $g.size_rus != ''}({$g.size_rus}){/if}&nbsp;{/if}
											{*
											{if $g.faq_id != "0" && $g.faq_id != ''}<sup class="help"><a class="help thickbox" rel="nofollow" href="/faq/{$g.faq_id}/?height=400&amp;width=260">?</a></sup>{/if}
											{if $g.cat_parent > 1}{if $g.comment != ''}, {$g.comment}{else}, защитная плёнка{/if}{else}{if $g.cat_parent == 1}<br />{$g.style_composition}{/if}{/if}
											*}
											</span>
										</div>	
										{*if $g.author_payment}
											<span class="author-poly4it">автор получит за эту позицию {$g.author_payment} руб.</span>
										{/if*}										
									</td>
								</tr>
								<tr>
									<td class="td" style="padding-bottom:20px;vertical-align:bottom">
										{if $g.avalible >= 0}
										<!--количество-->
										<div class="g-name-wrap" style="height:22px;float:left">
											<div class="g-count">
											<span style="display:inline-block;padding-top: 3px;float:left">{$L.BASKET_quantity_short}:</span>
												<input type="text" class="cntedit" value="{$g.quantity}" ajax="/{$module}/goodPlus1/?good_id={$g.good_id}&good_stock_id={$g.good_stock_id}&quantity=[count]" name="quantity[1]" />
												<span class="cnt text">{$g.quantity}{$L.BASKET_pcs}.</span>
												<span class="g-co">
													<a class="change_input one-more" href="/{$module}/goodPlus1/?good_id={$g.good_id}&good_stock_id={$g.good_stock_id}&dir=1">+</a>
													<a class="change_input one-less" href="/{$module}/goodPlus1/?good_id={$g.good_id}&good_stock_id={$g.good_stock_id}&dir=-1">-</a>
												</span>
											</div>
										</div>
										{/if}
										<a class="delete_good" title="{$L.BASKET_delete}" href="/{$module}/delete_good/{$g.good_id}/{$g.good_stock_id}/">
											<span>{$L.BASKET_delete}</span><img style="float: left;" width="15" height="15"  src="/images/icons/delete_gray.gif">
										</a>
										<div style="clear:both;"></div>
									</td>
								</tr>
								{else}							
								<tr>
									<td class="td previev" rowspan="8" width="90px">
										<div class="g-name-wrap">
											<div class="good-img">
												{if $g.imagePath}
												<a href="/{$module}/zoom/{$g.good_id}/{$g.style_id}/{$g.def_side}/?height=530&width={if $g.category == 'cup'}1510{else}510{/if}&category={$g.category}" data-big="" class="glisse {*thickbox*}">
													<img class="{$g.category}" src="{$g.imagePath}" width="85" alt="{$g.good_name}" />
													<span class="icon-zoom"></span>
												</a>
												{/if}
												{if $g.good_status == "customize" && $g.imageBackPath}
												<a href="/{$module}/zoom/{$g.good_id}/{$g.style_id}/back/?height=530&width=510&category={$g.category}" data-big="" class="glisse {*thickbox*}">
													<img class="{$g.category}" src="{$g.imageBackPath}" width="85" alt="{$g.good_name}" />
													<span class="icon-zoom"></span>
												</a>
												{/if}
											</div>
										</div>
									</td>
									<td class="td" colspan="3" height="5px"></td>
								</tr>
								<tr valign="bottom">
									<td class="td height-min2" colspan="3" style="text-align:right"></td>
								</tr>
								<tr>
									<td class="td" height="18px">
										<div class="g-name-wrap name-t">
											{if $g.good_id > 0}	
											<span class="good-link">
												{if $g.cat_parent == 76}
													{$g.good_name}
												{else}	
													<a href="{if $g.good_status != 'customize'}{$g.link}{else}/{$module}/zoom/{$g.good_id}/{$g.style_id}/{$g.def_side}/?height=530&width=510{/if}" class="work-name {if $g.good_status == 'customize'}thickbox{/if}" title="{$g.good_name}" rel="nofollow">
														{if $g.good_status == "customize" && $g.imageBackPath}{* $g.good_name|truncate:6:"..." *}{$g.good_name}{else}{$g.good_name}{/if}
													</a>
													{if $g.good_status != 'customize'}<span class="author"> &mdash; автор <a href="/catalog/{$g.user_login}/" title="{$g.user_login}" rel="nofollow">{$g.user_login}</a></span>{/if}
												{/if}
											</span>
											{/if}
										</div>										
									</td>
									{if ($g.style_id == 429 || $g.style_id == 407 || $g.style_id == 390) && $g.tprice / $g.quantity == 660}
										
										<td class="td stock" colspan="2" rowspan="5" width="40" valign="top" style="text-align:right">
											{if $g.avalible >= 0}
												{if $g.discount > 0}
												<span class="no-sum"><strike>{$g.price * $g.quantity}</strike></span>
												{/if}
												
												<span class="g-total g-total-stock {if $USER->user_partner_status > 0}edit{/if}" quantity="{$g.quantity}" price="{$g.tprice / $g.quantity}"><font>{$g.tprice}</font>{if $PAGE->lang !='ru'}{$L.CURRENT_CURRENCY}{/if}</span>
												
												<p class="sum-description">* Товар произведенный на заказ по акции за 600р обмену и возврату не подлежит</p>
											{/if}
										</td>
										
									{else}
									
										<td class="td isStrike" width="40" valign="bottom" style="text-align:right">
											{if $g.avalible >= 0}
												{if $g.discount > 0}
												<span class="no-sum"><strike>{$g.price * $g.quantity}</strike></span>
												{/if}
											{/if}
										</td>
										<td class="td" width="70" style="text-align:right">
											{if $g.avalible >= 0}
												
												{if $USER->user_partner_status > 0}
												<input type="text" class="g-total" ajax="/{$module}/edit_price/{$g.user_basket_good_id}/?price=[price]" quantity="{$g.quantity}" price="{$g.tprice / $g.quantity}" value="{$g.tprice}" style="width:50px;display:none" />
												{/if}
												
												<div class="g-total {if $USER->user_partner_status > 0}edit{/if}" quantity="{$g.quantity}" price="{$g.tprice / $g.quantity}"><font>{$g.tprice}</font>{if $PAGE->lang !='ru'}{$L.CURRENT_CURRENCY}{/if}</div>
											{/if}
										</td>
										
									{/if}
								</tr>
								<tr>
									<td class="td" valign="top" colspan="2" height="18px">
										<!--категория-->
										<div class="g-name-wrap opisanie-t">
											<span class="good-descr">{$g.style_name}</span>
											<span class="good-descr-all" style="color: #A9ABAB;">{if $g.avalible >= 0 && ($g.size_rus != '' || $g.cat_parent == 21)}, &nbsp;{$g.size_name}&nbsp;{if $g.size_rus != ''}({$g.size_rus}){/if}&nbsp;{/if}{if $g.faq_id != "0" && $g.faq_id != ''}<sup class="help"><a class="help thickbox" rel="nofollow" href="/faq/{$g.faq_id}/?height=500&width=600">?</a></sup>{/if}{if $g.cat_parent == 1 || $g.style_id == 758}<p>{$g.style_composition}</p>{/if}{if $g.cat_parent == 23}, {$g.size_name}{/if}</span>
										</div>	
										{if $g.author_payment}
											<span class="author-poly4it">автор получит за эту позицию {$g.author_payment} руб.</span>
										{/if}
									</td>							
									<td class="td">
										<div class="g-disc" align="center" style="text-align: right">
											{*if $g.discount > 0}
												{$g.discount}%
											{/if*} 
										</div>
									</td>
								</tr>
								<tr class="height-min">
									<td class="td" colspan="2" valign="top"></td>
									<td class="td">										
										{if $g.avalible < 0}
											<div class="b-pink-arr-left">
												{$L.BASKET_product_is_reserved_1}, <a href="{$g.link}" style="color:#fff">{$L.BASKET_product_is_reserved_2}</a>
											</div>	
										{else}
											{*перенесли ниже*}
											{*if $g.cat_slug == 'futbolki' && ( $USER->city == 'Москва' || $USER->user_id == 105091 || $USER->user_id == 27278)}
												<div class="obertka-bg {if $g.box < 0}off{/if}">
													<a href="/{$module}/box/{$g.good_id}/{$g.good_stock_id}/" class="obertka" title="{if $g.box > 0}{$L.BASKET_gift_box}{else}{$L.BASKET_free_package}{/if}">
													</a>
													<sup {if $g.box > 0}style="display: none"{/if} class="help help-obertka"><a class="help thickbox" rel="nofollow" href="/faq/90/?height=500&width=600">?</a></sup>	
												</div>
											{/if*}
										{/if}
									</td>
								</tr>
								<tr>
									<td class="td" colspan="2"  height="18px" style="color: #A9ABAB">
										{if $g.avalible >=0}
											{$L.BASKET_wilbe_available} {$g.deliver_srok}
										{/if}
									</td>					
									<td class="td">
									</td>
								</tr>
								<tr>
									<td class="td">
										<!--количество-->
										{* if $g.avalible >=0 *}
										<div class="g-name-wrap" style="height:22px">
											<div class="g-count">
												<span style="display: inline-block;padding-top:3px;float:left">{$L.BASKET_quantity}:</span>
													<input type="text" class="cntedit" value="{$g.quantity}" ajax="/{$module}/goodPlus1/?good_id={$g.good_id}&good_stock_id={$g.good_stock_id}&quantity=[count]" name="quantity[1]" />
												<span class="cnt text">{$g.quantity}{$L.BASKET_pcs}.</span>
												<span class="g-co">
													<a class="change_input one-more" href="/{$module}/goodPlus1/?good_id={$g.good_id}&good_stock_id={$g.good_stock_id}&dir=1">+</a>
													<a class="change_input one-less" href="/{$module}/goodPlus1/?good_id={$g.good_id}&good_stock_id={$g.good_stock_id}&dir=-1">-</a>
												</span>
												{if $g.avalible < 0}<em class="rests">доступно: {$g.quantity + $g.avalible}</em>{/if}
											</div>
										</div>
										{* /if *}
									</td>								
									<td class="td"  colspan="2">
										<a class="delete_good" title="{$L.BASKET_delete}" href="/{$module}/delete_good/{$g.good_id}/{$g.good_stock_id}/">
											<span>{$L.BASKET_delete}</span><img style="float: left;" width="15" height="15"  src="/images/icons/delete_gray.gif">
										</a>
										<div style="clear:both"></div>
									</td>
								</tr>
								<tr height="18px">
									<td class="td" colspan="3">
									</td>
								</tr>
								{/if}
							</table>
							
							{if !$MobilePageVersion && $USER->authorized && $g.good_status == "customize" && ($g.ps_src > 0 || $g.ps_src_back > 0)  && $g.style_id != 712}
								{if $g.tooSmall}
								<div class="itIsMyPicture clearfix {if $g.tooSmall}tooSmall{/if}">
									<div class="text left">
										Размеры изображения слишком малы для качественной печати
									</div>
								</div>
								{/if}
								
								{*
								<div class="itIsMyPicture clearfix {if $g.tooSmall}tooSmall{/if}">
								{if !$g.tooSmall}
									<div class="ico left"></div>
									<div class="inp left"><a href="/senddrawing.design/startSellMywork/{$g.good_id}/{$g.style_id}/" target="_blank" rel="nofollow"><img src="/images/basket/checkbox_no.png" width="16" height="16"/></a></div>
									<div class="text left">
										Я подтверждаю, что это мое изображение, я хочу<br/>его выставить на продажу и<br/><a href="/promo/" target="_blank" rel="nofollow">получать до 20% с продаж</a>
									</div>
								{else}
									<div class="text left">
										Размеры изображения слишком малы для качественной печати
									</div>
								{/if}
								</div>
								*}				
							{/if}
							
							{if $g.gifts}
							<input type="hidden" name="avalible_gifts" value="{$g.gifts}" />
							{/if}
							
							{* if $g.avalible >=0 && ($g.cat_slug == 'futbolki' || $g.cat_slug == 'longsleeve_tshirt') && $USER->city == 'Москва'}
								<div class="obertka-bg {if $g.box < 0}off{/if}">
									<a href="/{$module}/box/{$g.good_id}/{$g.good_stock_id}/" class="obertka" title="{if $g.box > 0}{$L.BASKET_gift_box}{else}{$L.BASKET_free_package}{/if}">
									</a>
									<sup {if $g.box > 0}style="display:none"{/if} class="help help-obertka"><a class="help thickbox" rel="nofollow" href="/faq/90/?height=500&width=600">?</a></sup>	
								</div>
							{/if *}
							
							</div>
						</td>						
					</tr>
					{/foreach}
						
						{if $stickers > 2}
							<tr class="one-line">
								<td class="td" colspan="6">
									<div class="sticerOneList clearfix">
										<input type="checkbox" name="sticerOneList" _discount="{$sticerOneListDiscount}" />
										<div class="text">Внимание! Вы хотите чтобы наклейки были размещены на 1 листе? (скидка 10%)</div>
										{*
										<input type="hidden" name="sticerOneList" value="" />
										<input type="checkbox" name="sticerOneList-switcher" checked="checked" />
										<div class="text">Внимание! Наклейки могут быть размещены на 1 листе</div>
										*}
									</div>
								</td>						
							</tr>
						{/if}
						
						
						{if $g.avalible >=0 && $MobilePageVersion}						
						<tr class="wilbe_available">
							<td>
								{$L.BASKET_wilbe_available} {$deliver_srok}
							</td>						
						</tr>
						{/if}
						
					{/if}
					
					<!-- Кнопка пересчитать и другой текст -->
					<tr class="frst-footer" style="border-top:6px solid #EFEFEF">
						<td colspan="3" class="gate-code">
						</td>
						<td colspan="2" valign="top" style="padding:22px 0 0 0">
							{*<input class="btn-recalc" type="button" value="{$L.BASKET_recalculate}" style="display:none"/>*}
						</td>
						<td></td>
					</tr>
				</table>
			
				{if $goods|count > 0 || $gifts|count > 0}
				
					<div align="right" class="total-cost-block" style="margin-bottom: 20px;">
						{if $USER->user_bonus > 0}
							<div class="price-back">
								{if $USER->exchenged_bonuses > 0}
								{literal}
								<style>
									#exchange_line {font-size:16px;color:#E07638;padding-bottom:20px}
									#exchange_line .sum {float:right;width:143px}
									#exchange_line .text {float:right;text-align:right;width:80px}
									#exchange_line .ico {
										background-position: -5px -88px;
									    width: 20px;
									    height: 20px;
									    background-image: url(/images/order/buttons_ypravlenie_zakazom_2.png);
									    background-repeat: no-repeat;
									    float:left;
									    margin-right:8px;
									   }
								</style>
							    {/literal}
								<div id="exchange_line">
									<div class="sum">
											{$USER->exchenged_bonuses} {$L.CURRENT_CURRENCY}
									</div>
									<div class="text">
										<div class="ico">&nbsp;</div>
										Обмен
									</div>
								</div>
								<br clear="all">
								{/if}
								<div class="gran">
									<span class="price">
										<font>{if $bonuses > $basket_sum}{$basket_sum}{else}{$bonuses}{/if}</font>
										{if $PAGE->lang !='ru'}{$L.CURRENT_CURRENCY}{/if}
									</span>
								</div>
								<span class="descr">{$L.BASKET_paid_with_bonuses} (max {$maxParticalPayPercent}%)</span>
							</div>
							{if $maxParticalPayPercent < 100}
							<div class="price-back">
								<div class="gran"><span class="perc">{$maxParticalPayPercent}%</span></div>
								<span class="descr">Максимальное количество бонусов на заказ</span>
							</div>
							{/if}
						{/if}
						
						{if $ls_can_pay > 0}
						<div class="price-back">
							 <span class="descr">
								<input type="checkbox" name="input_basket_sum_without_ls" value="1" {*name="checkbox_basket_sum_without_ls"*} checked="checked"/>
								Я хочу оплатить заказ с личного счёта{if $bppercent > 0}: (скидка {$bppercent}%){/if}
							</span>
							<div class="gran"><span class="price"><font>{$ls_can_pay}</font>{if $PAGE->lang !='ru'}{$L.CURRENT_CURRENCY}{/if}</span></div>
						</div>	
						{/if}
						
						<div class="first-price">	
							<span class="descr" style="font-size:20px">
							{if $USER->authorized}
								{$L.BASKET_price_without_1}
							{else}									
								{$L.BASKET_price_without_2}
							{/if}
							</span>
							<div class="gran">
								<span class="price" id="total-price">
									<font>{if $USER->user_bonus > 0}{if $bonuses > $basket_sum}0{else}{$basket_sum - $bonuses - $ls_can_pay}{/if}{else}{$basket_sum-$ls_can_pay}{/if}</font>
									
									<input type="hidden" name="basket_sum_without_ls_payment" value="{if $USER->user_bonus > 0}{if $bonuses > $basket_sum}0{else}{$basket_sum - $bonuses}{/if}{else}{$basket_sum}{/if}" />
									
									{if $PAGE->lang !='ru'}{$L.CURRENT_CURRENCY}{/if}
								</span>
								
								{if $USER->user_bonus > 0 || $ls_can_pay > 0}
									<strike>{$basket_sum}</strike>
									<input type="hidden" name="basket_sum_with_ls" value="{if $USER->user_bonus > 0}{if $bonuses > $basket_sum}0{else}{$basket_sum - $bonuses - $ls_can_pay}{/if}{else}{$basket_sum-$ls_can_pay}{/if}" />
								{/if}										
							</div>								
						</div>
						
						{*
						<div class="price-back">
							<span class="descr">{$L.BASKET_bonus_back}</span>
							<div class="gran"><span class="price" id="total-refound"><font>{$bonusBack}</font>{if $PAGE->lang !='ru'}{$L.CURRENT_CURRENCY}{/if}</span></div>								
						</div>
						*}							
					</div>

					<div class="b-reg-btn clearfix" style="position:relative;float:none">
						<div class="gate-code-notes" style="float:left;{if $MobilePageVersion}width: 100% !important; text-align: center; margin-bottom: 15px;{else}width: 318px;{/if}margin-top:8px;color:#5C6261;font-size:14px">
							{$L.BASKET_promocode}:								
							
							{if $activated_certificate}
								<span><b>{$activated_certificate->certification_password}</b> активирован</span> 
								<a href="#" title="ввести другой код" style="position: relative; top: 4px; left: 5px;" onclick="$(this).prev().hide(); $(this).next().show(); $(this).hide(); return false;"><img src="/images/icons/delete.png" alt="другой" /></a>
							{/if}
							<input type="text" id="serCode" name="serCode" style="margin:2px 0 4px 7px;width: 69px;height: 22px;border:1px solid #999999;{if $activated_certificate}display:none{/if}"/>
							
							{if $error && ($error == 3 || $error == 4 || $error == 5 || $error == 9)}
								<div class="error_order" style="clear: both;">
								{if $error == 3}
									{$L.BASKET_certificate_number_error}
								{elseif $error == 4}
									{$L.BASKET_certificate_already_activated}
								{elseif $error == 5}
									{$L.BASKET_certificate_limit}
								{elseif $error == 9}
									{$L.BASKET_certificate_activate_error}
								{/if}
								</div>
							{/if}
						</div>
						
						<input type="submit" title="{$L.BASKET_buy}"  value="{if $MobilePageVersion}{$L.BASKET_buy}{/if}" class="without-reg-btn" id="submitSeRCode" name="submitSeRCode">
					</div>
				{/if}
			</form>			
	</div>
	
	{if !$MobilePageVersion}	
		{if $GIFTS|count > 0 || $stickerset}
		<div class="b-additional-goods-box">
			<!--h3>Не забудьте справа:</h3-->		
			<div class="b-additional-goods">
				<div style="background: url(/images/order/triangle90.gif)top center no-repeat;position:absolute;height:19px;width:12px;top:68px;left: -16px"></div>
				
				{foreach from=$GIFTS item="g"}				
					{if $g.gift_id == 61}					
						<form action="/{$module}/add_gift/{$g.gift_id}/" class="sert" method="post">
							<div class="add-good" id="gift-61">
								<div class="img-wrap" style="width:142px;margin:7px 15px 0 15px">
									<img src="{$g.picture_path}" alt="{$g.gift_name}" style="margin:0">
									<div class="g-count" style="width: 71px;margin:0;position:absolute;top: 28px;left:40px;text-align:center;height: 25px;overflow:hidden;">
										<input type="text" class="cntedit"  value="1500" name="sert_value" style="margin-top:2px" />
										<span class="g-co" style="margin-top:0">
											<a class="change_input one-more" href="/basket/goodPlus1/?good_id=48422&amp;good_stock_id=72355&amp;dir=1">+</a>
											<a class="change_input one-less" href="/basket/goodPlus1/?good_id=48422&amp;good_stock_id=72355&amp;dir=-1">-</a>
										</span>
										<span class="cnt text" style="margin:4px 0px 0px -2px;color:#000">твоя цена</span>
									</div>
								</div>
								<div class="gift-name">{$L.BASKET_gift_certificate}
									{if $g.gift_link}<a href="{$g.gift_link}" class="help-hint" target="_blank">?</a>{/if}
									<a href="#" class="add-to-basket price" title="{$L.BASKET_add_to_basket}">
										<span class="sum">твоя цена</span>
										<input type="submit" class="red_butt" style="margin-right: -1px;" value="" />
									</a>							
								</div>
								<div style="clear:left;"></div>
								<div class="wall" {if $goods|count == 0}style="height: 138px;"{/if}></div>
								<div class="line-b"{if $goods|count == 0}style="display:none"{/if}></div>	
							</div>
						</form>					
					{else}				
						<div class="add-good {$g.gift_type}" id="gift-{$g.gift_id}">
							<div class="img-wrap">
								{if $g.gift_type == 'forcup'}<a href="/{$module}/zoom/{$g.good_id}/725/?height=530&width=1510" class="thickbox add-to-basket">{else}<a href="{if $g.gift_type != 'forphones' && $g.gift_type != 'forcases' && $g.gift_type != 'forlaptops' && $g.gift_type != 'fortouchpads' && $g.gift_type != 'forposter' && $g.gift_type != 'forauto' && $g.gift_type != 'forcup'}/{$module}/add_gift/{$g.gift_id}/#add-to-basket{else}/ajax/addgoodtobasket/?good_id={$g.good_id}&good_stock_id={$g.good_stock_id}{if $g.gift_type == 'forauto'}&price={$g.price}&comment={$g.comment}{/if}{/if}" class="add-to-basket">{/if}<img src="{$g.picture_path}" alt="{$g.gift_name}"></a>
							</div>
							<div class="gift-name">{$g.gift_name}
								{if $g.gift_link} 
								<a href="{$g.gift_link}" class="help-hint" target="_blank">?</a>
								{/if}
								<a href="{if $g.gift_type != 'forphones' && $g.gift_type != 'forcases' && $g.gift_type != 'forlaptops' && $g.gift_type != 'fortouchpads' && $g.gift_type != 'forposter' && $g.gift_type != 'forauto' && $g.gift_type != 'forcup'}/{$module}/add_gift/{$g.gift_id}/#add-to-basket{else}/ajax/addgoodtobasket/?good_id={$g.good_id}&good_stock_id={$g.good_stock_id}{if $g.gift_type == 'forauto'}&price={$g.price}&comment={$g.comment}{/if}{/if}" class="add-to-basket price" title="{$L.BASKET_add_to_basket}">
									<span class="sum">{if $g.gift_discount > 0}<s>{$g.gift_price}</s>{/if} {$g.price} {$L.BASKET_valuta}</span>
									<span class="red_butt"></span>
									<!--img  src="/images/order/red_cart_basket.png" width="46" alt="В корзину"/-->
								</a>
							</div>
							<div style="clear:left"></div>
							<div class="wall"></div>
							<div class="line-b"></div>	
						</div>
					{/if}
					
				{/foreach}
				
				{if $USER->meta->mjteam == 'super-admin' || $USER->meta->mjteam == 'grand_manager' || $USER->meta->mjteam == 'designer'}					
					<div class="add-good {$g.gift_type}" id="gift-{$g.gift_id}" style="border:1px dashed orange">
						<div class="img-wrap">
							<a href="/ajax/addgoodtobasket/?good_id=109249&good_stock_id=746122" class="add-to-basket"><img src="/images/0.gif"></a>
						</div>
						<div class="gift-name">Доработка макета
							<a href="/ajax/addgoodtobasket/?good_id=109249&good_stock_id=746122" class="add-to-basket price" title="{$L.BASKET_add_to_basket}">
								<span class="sum">300 руб.</span>
								<span class="red_butt"></span>
								<!--img  src="/images/order/red_cart_basket.png" width="46" alt="В корзину"/-->
							</a>
						</div>
						<div style="clear:left;"></div>
						<div class="wall"></div>
						<div class="line-b"></div>	
					</div>						
				{/if}
				
				{if $packing}
				<form action="/{$module}/add_gift/{$packing.0.gift_id}/" class="listalka bymaga" method="post">
					<div class="add-good">
						<div class="img-wrap good-img">
							<a href="#" class="prev arrow" style="left:-7px"></a>
							{foreach from=$packing item="g" name="pfoo"}
								<a href="{$g.zoom}" {if $smarty.foreach.pfoo.first}style="display:block;"{/if} class="add-to-basket thickbox">
									<img src="{$g.picture_path}" alt="{$g.gift_name}" _id="{$g.gift_id}">
								<span class="icon-zoom"></span>
								</a>
							{/foreach}
							<a href="#" class="next arrow" style="right:-7px"></a>
						</div>
						<div class="gift-name"><span class='n'>{$packing.0.gift_name}</span>
							{if $packing.0.gift_link}<a href="{$packing.0.gift_link}" class="help-hint" target="_blank">?</a>{/if}
							<a href="#" class="add-to-basket price" title="{$L.BASKET_add_to_basket}">
								<span class="sum">{if $packing.0.gift_discount > 0}<s>{$packing.0.gift_price}</s>{/if} {if $packing.0.price} {$packing.0.price}{else}180{/if} руб.</span>
								<input type="submit" class="red_butt" value=""/>
							</a>							
						</div>
						<div style="clear:left"></div>
						<div class="wall"></div>
						<div class="line-b"></div>	
					</div>
				</form>
				{/if}
				
				{if $stickerset}
				<form action="/ajax/addgoodtobasket/?good_id={$stickerset.0.good_id}&good_stock_id=743160"  class="listalka stickerset" method="post">
					<div class="add-good">
						<div class="img-wrap good-img">
							<a href="#" class="prev arrow"></a>							
							{foreach from=$stickerset item="g" name=foo}
							<a _href="" data-big="{$g.picture_path}" {if $smarty.foreach.foo.first}style="display:block"{/if} class="add-to-basket glisse {*thickbox*}">
								<img src="{$g.picture_path}" alt="{$g.good_name}" _id="{$g.good_id}">
								<span class="icon-zoom"></span>
							</a>
							{/foreach}							
							<a href="#" class="next arrow"></a>
						</div>
						<div class="gift-name"><span class='n'>{$stickerset.0.good_name}</span>
							<a href="#" class="add-to-basket price" title="{$L.BASKET_add_to_basket}">
								<span class="sum">{$stickerset_price} {$L.BASKET_valuta}</span>
								<input type="submit" class="red_butt" value="" />
							</a>							
						</div>
						<div style="clear:left"></div>
						<div class="wall"></div>
						<div class="line-b"></div>	
					</div>
				</form>
				{/if}			
			</div>
			<div style="clear:left"></div>
		</div>
		{/if}
	{/if}
</div>

<div style="clear:left"></div>
<div class="empty_for_footer"></div>

{literal}
<script type="text/javascript">
	$(document).ready(function(){
		/*трек добавления в корзину стикерсетов*/
		$(".b-additional-goods .stickerset a.add-to-basket").click(function(){
			trackUser('Stickerset','страница - корзина',$(this).attr('title'));//трек аналитика
		});	

		/*трек добавления в корзину стикерсетов*/
		$(".b-additional-goods .bymaga a.add-to-basket").click(function(){
			trackUser('Обертка','страница - корзина',$(this).attr('title'));//трек аналитика
		});			
		
		//$('#listalka .img-wrap img').first().show();
		$('.listalka').each(function () {
			if($(this).find('.img-wrap .add-to-basket').length<2)
				$(this).find('.img-wrap .arrow').hide();
		});
		
        $('.listalka .next, .listalka .prev').click(function(){
			var self =$(this).parents('form.listalka');
			var count= self.find('.img-wrap .add-to-basket').length;
			if(count>1){
				var ind= self.find('.img-wrap .add-to-basket:visible').index();
				var pr='next';
				if($(this).hasClass("prev")) pr='prev';
				if (count>ind && pr=='next'){
					self.find('.img-wrap .add-to-basket:visible').hide().next('.add-to-basket').css('display','block');
				}else if (ind>1 && pr=='prev'){
					self.find('.img-wrap .add-to-basket:visible').hide().prev('.add-to-basket').css('display','block');
				}else{
					//console.log(count,ind);				
					self.find('.img-wrap .add-to-basket').css('display','none');
					if (pr=='next') 
						self.find('.img-wrap .add-to-basket:first').css('display','block');
					else
						self.find('.img-wrap .add-to-basket:last').css('display','block');
				}
				
				var action = self.attr('action');
				if(self.hasClass('bymaga'))
					action='/basket/add_gift/'+	self.find('.img-wrap .add-to-basket:visible img').attr('_id')+'/';
				else
					action = action.replace(/good_id=([\d]+)/,'good_id='+	self.find('.img-wrap .add-to-basket:visible img').attr('_id'));
				
				//self.attr('action','/basket/add_gift/'+self.find('.img-wrap img:visible').attr('_id')+'/');
				
				self.attr('action', action);
				self.find('.gift-name span.n').text(self.find('.img-wrap .add-to-basket:visible img').attr('alt'));
			}
			return false;
		});
		
		// Минус один к товару	
		$("#serHiddenForm form").submit(function(){
			var result = true;
		
			if ($("#serCode").val() == '') {
				$("#serCode").val('введите код');
				$("#serCode").css({color:'#F00'}).animate({color:'#FFF'}, 500, function(){
					$("#serCode").val('').css({color:'#333'});  $("#serCode").focus(); });		
				
					result = false && result;
			
			}
	
			if ($("#serCodeEmail").length > 0) {						
				if ($("#serCodeEmail").val() == $("#serCodeEmail").prev('label').text()) {
					$("#serCodeEmail").addClass("error");
					result = false && result;
				}
			} 
			
			return result
		});
   });
   
   // форма для вводка кода скидки 
	function showSerHiddenForm(obj) {
		$(obj).hide();
		$(obj).next().show();
		$("#serCode").focus();
		return false;
	}
</script>
{/literal}