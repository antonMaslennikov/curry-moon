{* 3ий уровень меню - "хлебные крошки" (for top_menu.v5.tpl and top_line.mobile.tpl) *}
{if $PAGE->breadcrump && $PAGE->breadcrump|count > 0}
<div class="b-title-url-path">
	<div class="url-path">
		{* <a href="/" rel="nofollow">{$L.HEADER_kroshki_main}</a>&#8594; *} 
		{foreach from=$PAGE->breadcrump item="i" name="bc_foreach"}
		
			{if !$smarty.foreach.bc_foreach.last}
				{if $i.link}
					<a href="{$i.link}">{$i.caption}</a>
				{else}
					{$i.caption}
				{/if}
			{else}
				{if $module == 'catalog' && $task == "good"}
					<h1>{$i.caption}</h1>
				{else}
					{$i.caption}
				{/if}
			{/if}
			
			{if !$smarty.foreach.bc_foreach.last}&rarr;{/if}
			
		{/foreach}
	</div>
	
	{if !$MobilePageVersion && ($PAGE->module == 'customize' || $PAGE->module == 'stickermize') && $style_id && $style.sizes}
		<div class="top-delivery-auto clearfix">
			<div>
				{$USER->city}. Доставим {if $customize_delivery_srok && $USER->city == 'Москва'}{$customize_delivery_srok}{else} в течении двух недель{/if}
				{if $USER->city == 'Москва' && $style.category == "stickers"}
				<br /><b>Минимальный заказ 5000р</b>
				{/if}
			</div>
		</div>
		
		{if $exchangeAlert}
		<div class="top-stock-message clearfix"><p class="sum-description">* Товар произведенный на заказ по акции за 660Р обмену и возврату не подлежит</p></div>
		
		<style>
			.top-stock-message {
				width: 273px;
				text-align: right;
				background-color: #f9d01c;
				padding: 5px 10px;
				position: absolute; top:0; 
				right: 0;
				z-index: 100;
			}
			.top-stock-message p {
				margin: 0
			}
		</style>
		{/if}
	{/if}	
</div>
{/if}