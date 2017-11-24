<!--noindex-->
<div class="wrap-basket" id="b-qbasket-conteiner" style="display:none;">

	<div style="width: 0px;height: 0px !important;position: absolute;top: -22px;left: 50%;margin-left: -10px !important;border-color: transparent transparent #EFEFEF transparent;border-style: solid;border-width: 11px 11px;"></div>

	<div class="basket">
		<a href="#" title="закрыть" rel="nofollow" class="close"></a>
		<div class="head-top-bask clearfix" style1="{if $goods|count == 0 && $gifts|count == 0}display:none;{/if}">
			{if $module!='order.v3'}
				<a href="#" _href="/basket/" title="{$L.HEADER_q_basket_checkout}" rel="nofollow" class="go-to-order3">{$L.HEADER_q_basket_checkout}</a>
			{/if}
		</div>
		
		<div class="goods-wrap" id="goods-wrap">
			
			{if $basket->basketGoods|count > 0 || $basket->basketGifts|count > 0}
			
				{foreach from=$basket->basketGoods item="g"}
					<div class="item" _ubgid="{$g.user_basket_good_id}" _gid="{$g.good_id}-{$g.good_stock_id}">
						{if $g.imagePath}
						<div class="img">
							<img title="{$g.good_name}" alt="{$g.good_name}" src="{$g.imagePath}">
						</div>
						{/if}
						
						{if $g.good_status == "customize" && $g.imageBackPath}
						<div class="img" style="margin-left:-2px">
							<img src="{$g.imageBackPath}" alt="{$g.good_name}" title="{$g.good_name}" alt="{$g.good_name}"/>
						</div>
						{/if}
						
						<div class="info">
							<div class="name" style="{if $g.size_rus !="" || ($g.good_status == "customize" && $g.imageBackPath)}width:121px;{/if}">
								{if $g.good_id > 0}
									{if $g.good_status != 'customize'}<a class="a" href="{$g.link}" rel="nofollow" title="{$g.good_name}">{$g.good_name}</a>{else}<div class="a">{$g.good_name}</div>{/if}
								{/if}
								<span>{$g.style_name}{if $g.gsGoodId == 20570}{if $g.comment != ''}, {$g.comment}{/if}{/if}</span>
							</div>
							
							<div class="price"><nobr>{$g.tprice}</nobr> {$L.CURRENT_CURRENCY}</div>
							{if $g.size_rus !=""}
							<div class="size">
								<span class="b">{$g.size_name} {if $g.cat_parent != 21}({$g.size_rus}){/if}</span>				
								<sup class="help">{if $g.style_viewsize > 0}<a href="/faq/{$g.style_viewsize}/?height=500&amp;width=600" rel="nofollow" class="help thickbox" >?</a>{else}&nbsp;{/if}</sup>
							</div>
							{/if}		
							
							<div class="qb--quantity">{if $g.quantity > 1}{$g.quantity} шт.{/if}</div>
							
							<input type="hidden" name="quantity" value="{$g.quantity}" />
						</div>
					</div>
			
				{/foreach}
			
				{foreach from=$basket->basketGifts item="g"}
					<div class="item" _ubgid="{$g.user_basket_good_id}">
						<div class="img">
							<img title="{$g.good_name}" alt="{$g.good_name}" src="{$g.picture_path}">
						</div>
						<div class="info">
							<div class="name gift-{$g.gift_id}">
								<div class="a">{$g.gift_name}</div>
								<span></span>
							</div>
							<div class="price"><nobr>{$g.tprice}</nobr> {$L.CURRENT_CURRENCY}</div>
						</div>
					</div>
				{/foreach}
			{else}
				<div class="empty-basket">
					<!--noindex--><p>{$L.HEADER_q_basket_is_empty} :-(</p><!--/noindex-->
				</div>
			{/if}
			
		</div>
		    
		<div  style="clear:both"></div>
		
		{if $freedelivery_rest && $Page.lang =='ru'}
			<div id="freedelivery_deliveryboy_mini">
				{if $freedelivery_rest <= 0}
					Доставка экспресс-курьером бесплатно!
				{else}
					До бесплатной доставки не хватает {$freedelivery_rest} руб!
				{/if}
			</div>
		{/if}		
	
		<div class="wrap-sum-and-inpup" style="display:{if $basket->basketGoods|count > 0 || $basket->basketGifts|count >0}block{else}none{/if};">
			
			<div class="summ-bonus" >
				<span class="sum-title">{$L.HEADER_q_basket_total}:</span>
				<span class="no-sum"><strike>{if $USER->user_bonus > 0}{$basket->basketSum}{else}{/if}</strike></span>
				<span class="sum" _sum="{$basket->basketSum - $bonuses}"><font>{if $basket->basketSum > $bonuses}{$basket->basketSum - $bonuses}{else}0{/if}
				</font>{if $PAGE->lang !='ru'}{$L.CURRENT_CURRENCY}{/if}</span>
				{if $USER->user_bonus > 0}
					<div class="price_bonus">{$L.HEADER_q_basket_price_with_bonuses}</div>
				{/if}
				{* <div class="you_return">{$L.HEADER_q_basket_order_bonus}: {$bonusBack} {$L.CURRENT_CURRENCY}</div> *}
			</div>
			<div class="img-basket">
				<input id="submitQBask" type="submit" value="" style=""/>
			</div>
			
			<input type="hidden" name="particalPayPercent" value="{$particalPayPercent}" /> 
			
		</div>

		<div  style="clear:both"></div>
		
		{if $PAGE->module != 'stock'}
		<div id="basket-merchandise" class="cards-b">
			<div class="tit">
				<div class="one">{$L.HEADER_q_basket_excellent_add_gifts}!</div>
				<div class="tvo clearfix">
					{*<span style="width:274px;float:left;overflow:hidden;">Упаковка...................................................................................................</span>
					<span style="width:36px;float:left;">180 руб.</span>*}
					<span style="width:274px;float:left;overflow:hidden;">{$L.HEADER_q_basket_stickerset}...................................................................................................</span>
					<span style="width:36px;float:left;">{if $PAGE->lang =='ru'}230{else}2.1{/if} {$L.CURRENT_CURRENCY}</span>
				</div>
			</div>
			<div class="items-p clearfix">

				{if $QBpacking|count > 0}
					{foreach from=$QBpacking item="s"}
					<div class="item">
						<img title="" height="" width="92" alt="{$s.gift_name}" src="{$s.picture_path}">
						<a rel="nofollow" class="hid" title="{$s.gift_name}" good_stocks="743160" price="{$s.gift_price}" good_id="{$s.good_id}" gift_id="{$s.gift_id}" href="#"></a>
					</div>
					{/foreach}
				{/if}
				
				{if $QBstickerset|count > 0}
					{foreach from=$QBstickerset item="s"}
					<div class="item">
						<img title="" height="" width="92" alt="{$s.good_name}" src="{$s.picture_path}">
						<a rel="nofollow" class="hid" title="{$s.gift_name}" good_stocks="743160" good_id="{$s.good_id}" href="/stickerset/"></a>
					</div>
					{/foreach}
				{/if}
				
			</div>
		 </div>
		 {/if}
	</div>
</div>		
<!--/noindex--> 