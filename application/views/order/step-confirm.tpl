

<!-- order/confirm.tpl -->

<div class="order-last-step">
	<div class="print-wrap"><a href="/{$module}/print/{$order.user_basket_id}/" class="btn-print" target="_blank">Распечатать заказ</a> или <a href="/orderhistory/{$order.user_basket_id}/#{$order.user_basket_id}" target="_blank">Открыть историю заказов</a></div>
	<div class="payment_form">
		{if $PAYMENT_FORM}
			{include file=$PAYMENT_FORM}
		{/if}
	</div>
	<div class="b-order-ticket">
		<div class="order-number-line">
			<div class="order-number">
				<strong>{$order.phone_2}</strong>
				
				<div class="your-tel">
					<span>ВАШ ТЕЛ. {$order.phone_1}</span>&nbsp;<b>{$order.phone_2}</b>
				</div>
			</div>

            {if $order.user_basket_status != 'canceled' && $USER->user_id == 27278 || $USER->user_id == 6199 || $USER->user_id == 63250}
            <div style="clear:both;margin-left:140px;padding-top:30px"><input type="checkbox" name="sms_info" checked="ckecked" style="vertical-align: middle;" /> информировать меня по СМС</div>
            {/if}

			<div class="or-number" style="visibility:hidden;">
				<span>или {$order.user_basket_id}</span>
				<a class="help-popup-btn help-popup-gray" href="http://www.maryjane.ru/faq/141/?TB_iframe=true&amp;height=500&amp;width=600" rel="nofollow">?</a>
			</div>
		</div>
	</div>
<div class="payment_form">
	{if $PAYMENT_FORM}	
		{include file=$PAYMENT_FORM}
	{/if}
	</div>	

<div class="payment_method">
	<a href="#"><img src="/images/order/assist.jpg" alt="Assist" title="Assist" width="103" height="41" /></a>
	<a href="#"><img src="/images/order/secured_by_thawte.jpg" alt="Secured by Thawte" title="Secured by Thawte" width="52" height="40" /></a>
	<a href="#"><img src="/images/order/visa.jpg" alt="Visa" title="Visa" width="78" height="39" /></a>
	<a href="#"><img src="/images/order/mastercard.jpg" alt="MasterCard" title="MasterCard" width="76" height="39" /></a>

    <script type="text/javascript">
        $('.payment_method a').click(function(){
            $('.payAssist').submit();
            return false;
        });

        $('input[name=sms_info]').change(function(){
            $.get('/ajax/order_sms_info/', {ldelim}'basket_id' : '{$order.user_basket_id}', 'sms_info' : $(this).attr('checked'){rdelim}, function() {

            });
        });
    </script>
</div>
</div>

<p>

</p>



{if $GA_T_I}
<script type="text/javascript">

	trackUser('Оформление заказа', 'заказ подтверждён', '{$basketNum}');
	
	/*
	var _gaq = _gaq || [];

	_gaq.push(['_setAccount', 'UA-2491544-1']);
	_gaq.push(['_trackPageview']);
	_gaq.push(['_addTrans',
	    '{$order.user_basket_id}',    	// order ID - required
	    'maryjane.ru',       	// affiliation or store name
    	'{$totalPrice}',     // total - required
	    '0',           		// tax
		'{$deliveryCost}',   // shipping
	    '{$basketCity}',     // city
	    '{$basketKray}',     // state or province
	    '{$basketCountry}'   // country
	]);

	{foreach from=$GA_T_I item="i"}  
	_gaq.push(['_addItem',
	    '{$order.user_basket_id}',           							// order ID - required
	    '{$i.good_stock_id}',       		    // SKU/code
	    '{$i.good_name}',      				// product name
	    '{$i.style_name}',  					// category or variation
	    '{$i.user_basket_good_total_price}',  // unit price - required
	    '{$i.user_basket_good_quantity}'      // quantity - required
	]);
	{/foreach}
	
	_gaq.push(['_trackTrans']); //submits transaction to the Analytics servers
	*/
</script>
{/if}