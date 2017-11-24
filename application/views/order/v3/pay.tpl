<div class="wrap payment">	
{if $order.user_basket_payment_confirm == 'false' && $order.user_basket_payment_type != 'ls' && $order.user_basket_status != 'canceled' && $order.user_basket_status != 'delivered'}
	{* <a href="#" title="{$L.CONFIRM_change_payment}" rel="nofollow" class="read-payment" onclick="$('#type-payment').hide().next('form').show();return false;" style="">{$L.CONFIRM_change_payment}</a> *}
	<a href="/order.v3/{$order.user_basket_id}/#payment" title="{$L.CONFIRM_change_payment}" rel="nofollow" class="read-payment">{$L.CONFIRM_change_payment}</a>
{/if}
<div class="order-sub">
	<div class="h3 title">{$L.CONFIRM_cost}:</div>
	{if $order.user_basket_payment_partical > 0}<strike>{$order.user_basket_payment_partical + $basket_sum}</strike>{/if}
	<div class="h3 sum-type" style="color:#535758;font-size:17px;line-height:16px;">{$basket_sum} {$L.CURRENT_CURRENCY}</div>
	<div class="h3 title">{$L.CONFIRM_payment}:</div>
	<div class="h3 sum-type type" id="type-payment">{$basketPayment}</div>
	<!--div class="link"-->
	<form action="/orderhistory/" id="form-type-payment" method="post" style="display:none;float: left;margin:6px 0 0 0;">
		<select name="paymentype" style="width: 176px;" onchange="if ($(this).val().length != 0) $(this).parent().submit();">
			<option value=""></option>
			{foreach from=$paymentTypes key=ptk item="pt"}
			<option value="{$ptk}">{$pt}</option>
			{/foreach}
		</select>
		<input type="hidden" name="changept" value="1" />
		<input type="hidden" name="id" value="{$basketNum}" />
		<input type="hidden" name="next" value="/{$module}/confirm/{$basketNum}/" />
	</form>		
	<!--/div-->
	<div class="h3 title clear">{$L.CONFIRM_delivery}:</div>
	<div class="h3 sum-type type read-deliv-t">
		{$basketDelivery}<a href="/order.v3/{$order.user_basket_id}/" title="{$L.CONFIRM_change_deivery_type}" rel="nofollow" class="" style="">{$L.CONFIRM_change2}</a>
		
		{if $order.user_basket_delivery_type == 'user'}	
		<p>
			(о готовности Вам поступит СМС)
		</p>
		{/if}
	</div>
	
	
	{if $order.delivery_point_schema}
	<br clear="all" />
	<p class=" clear">
		<b>Адрес пункта самовывоза:</b><br />
		<small>{$order.delivery_point_address}<br /><b>Как проехать:</b><br />{$order.delivery_point_schema}</small>
	</p>	
	{/if}

	<div style="clear:both;"></div>
	{if $PAYMENT_FORM}	
		<div class="sub-basket">
			{include file=$PAYMENT_FORM}			
		</div>
	{/if}
</div>

<div style="clear:both;"></div>
</div>