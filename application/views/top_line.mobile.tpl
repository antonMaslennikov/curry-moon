<div class="header-mobile">
	<div class="head">
		<div class="green-top">
			<a class="line" href="#" rel="nofollow"></a>
			
			<a class="lg" href="/" rel="nofollow"><img width="190" height="25" title="{$L.HEADER_title_2}" src="/images/MPV/logo.gif"/></a>
			
			<a class="cart" href="/{if $basket_sum == 0}basket{else}order.v3{/if}/" rel="nofollow"><div class="count" {if $basket_sum == 0}style="display:none"{/if}>{$basket->goodsCount}</div></a>					
			<div style="clear:both"></div>
		</div>	
			
		{if $PAGE->reqUrl.0 == '' || $PAGE->reqUrl.0 == 'main.2015.07' || ($module == 'voting' && $PAGE->reqUrl.1 == 'view')}			
			{if $PAGE->reqUrl.0 == '' || $PAGE->reqUrl.0 == 'main.2015.07'}
				<style type="text/css">.MPV .header-mobile { height:85px; }				
				iframe[name="google_conversion_frame"]{ position:absolute;left: -10000px; }</style>
				{include file="top_menu_search.tpl"}
			{/if}
		{elseif ($module == 'customize' || $module == 'customize.v2') && $style_id}			
			{include file="customize/customize.menu_mobile.tpl"}
		{else}
			{include file="top_menu.url-path.tpl"}
		{/if}
	</div>
</div>

<div id="MobAddCart" style="display:none;">
	<div class="MobAddCart">
		<div class="t">Товарн, успешно<br/>добавлен в корзину</div>
		
		<a class="order" href="/order.v3/" rel="nofollow">Оформить заказ</a>

		<div class="a clearfix">
			<a class="close" href="#" rel="nofollow">Продолжить {*<br/>*}покупки</a>
			{*<a class="basket" href="/basket/" rel="nofollow">Перейти<br/>в корзину</a>*}
		</div>
	</div>
</div>

{*@media only screen and (min-device-width: 768px){
	.MPV .header-mobile .head{width: 680px;}	
	.MPV #wrapper{width: 680px;}	
	.MPV .b-header.top_menu_main .p-head {width: 680px;}
	.MPV #content .big-imgbox .big-preview, .MPV #content .big-imgbox {height: 696px!important;}
}*}
