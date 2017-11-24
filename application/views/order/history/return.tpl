{if $positions|count > 0}
{*<link rel="stylesheet" href="/css/change.quick.css" type="text/css" />
<div class="wrap-change">
	<div class="change">
		<div class="goods-wrap">
			<div class="t-change">{$L.ORDERHISTORY_return_choose_items}</div>
			<form action="/{$module}/return/" method="post">*}
				
				{foreach from=$positions item="pos"}
				<div class="item">
					<div class="icon-v">
						<input type="checkbox" class="hid" name="pos[{$pos.user_basket_good_id}]" {if $positions|count == 1}checked="checked"{/if} />
						<a rel="nofollow" href="#" class="v  {if $positions|count == 1}activ{/if}"></a>
					</div>					
					<div class="img">
						<img title="{$pos.style_name} {$pos.good_name}" alt="" src="{$pos.pic}">
					</div>
					<div class="info">
						<div class="name" {if $pos.size_rus ==""}style="width: 155px;"{/if}>
							{if $pos.good_status != 'customize'}<a class="a" title="{$pos.good_name}" href="/catalog/{$pos.user_login}/{$pos.good_id}/" rel="nofollow">{$pos.good_name}</a>{else}<div class="a">{$pos.good_name}</div>{/if}
							<span>{$pos.style_name}{if $pos.gsGoodId == 20570}{if $pos.comment != ''}, {$pos.comment}{/if}{/if}{if $pos.size_rus !=""}, {$pos.size_rus}{/if}</span>
						</div>
						{* if $pos.size_rus !=""}
						<div class="size">
							<span class="b">{$pos.size_rus}</span>				
							<sup class="help">{if $pos.style_viewsize > 0}<a href="/faq/{$pos.style_viewsize}/?height=500&amp;width=600" rel="nofollow" class="help thickbox" >?</a>{else}&nbsp;{/if}</sup>
						</div>
						{/if *}
						<div class="price">{$pos.tprice} {$L.CURRENT_CURRENCY}</div>
						<div style="clear:both;"></div>
					</div>
				</div>
				{/foreach}
				{*<div style="clear:both;"></div>
				<div class="summ-bonus">
					<span class="sum-title">{$L.ORDERHISTORY_return_summ}:</span>
					<!--span class="no-sum"><strike>{if $basket_sum_minus_bonuses < $basket_sum}{$basket_sum}{else}{/if}</strike></span-->
					<span class="sum">3250</span>
				</div>
				<div class="img-change">
					<input id="submitQBask" type="submit" value="" style=""/>
				</div>
				<div style="clear:both;"></div>
			</form>
		</div>
	</div>
</div>*}


{*if $positions|count > 0}
	<form action="/{$module}/return/" method="post">

		<ul>
		{foreach from=$positions item="pos"}
			<li>
				<input type="checkbox" name="pos[{$pos.user_basket_good_id}]" /> 
				{$pos.good_name}<br />
				{$pos.style_name} 
				
				{if $pos.quantity > 1}
					<input type="text" name="q[{$pos.user_basket_good_id}]" value="{$pos.quantity}" />
				{else}
					1 шт.
				{/if}
				{$pos.tprice} руб.
			</li>
		{/foreach}
		</ul>
		<input type="submit" name="submit" value="Оформить возврат" />
	</form>
*}

{else}
	{$L.ORDERHISTORY_return_empty}
{/if}
