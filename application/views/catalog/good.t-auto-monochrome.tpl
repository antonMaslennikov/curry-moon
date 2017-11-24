<div class="good-tpl-auto type-auto-monochrome" style="display:none">
	<ul class="auto-info">
		<li>{$L.GOOD_auto_text1}</li>
		<li>{$L.GOOD_auto_text2}</li>
		<li>{$L.GOOD_auto_text3}</li>
		<li>{$L.GOOD_auto_text4}</li>
		<li>{$L.GOOD_auto_text5}</li>
		<li>{$L.GOOD_auto_text6}</li>
		<li>{$L.GOOD_auto_text7}</li>
	</ul>
	<div class="auto-params clearfix">
		<p style="text-align: center">Данный товар закончился на складе</p>
		{*
		<!--noindex-->
		<div class="wrap-sizes auto-size-cm clearfix">
			<span class="auto-title">{if $modal}{$L.GOOD_auto_sizes2}{else}<br replaces="{$L.GOOD_auto_sizes2}:"/>{/if}</span>
			<ul class="auto-sizes">
				{foreach from=$styles.auto.629.sizes item="size" key="size_id"}
					{if $size_id != 'custom'}
					<li class="auto-active" value="{$size_id}">
						<span>{if $modal}{$size.en}{else}<br replaces="{$size.en}"/>{/if}</span>
					</li>
					{/if}
				{/foreach}
			</ul>
			
			<a href="#" class="more_something" rel="nofollow">{if $modal}Указать размер{else}<br replaces="Указать размер"/>{/if}</a>
			
		</div>
		<div class="wrap-sizes auto-size-my clearfix" style="display:none">
			<span class="auto-title">{if $modal}{$L.GOOD_auto_sizes2}{else}<br replaces="{$L.GOOD_auto_sizes2}:"/>{/if}</span>
			<div class="auto-sizes-custom">
				<input type="text" placeholder="{$L.GOOD_widthCM}" koef="{$monochrome_koef}">
				x 
				<input type="text"  placeholder="{$L.GOOD_heightCM}" koef="{1 / $monochrome_koef}">
			</div>
			<a href="#" class="more_something close" rel="nofollow">{if $modal}Закрыть{else}<br replaces="Закрыть"/>{/if}<span></span></a>

		</div>
		
		<div class="auto-colors clearfix">
			<span class="titl">{if $modal}Цвет<br/>плёнки{else}<br replaces="Цвет<br/>плёнки:"/>{/if}</span>
			
			<div class="b-color-select">
				<input id="good_color" type="hidden" name="color" value="1">
			</div>	
		</div>
		
		<div class="auto-front-back clearfix">
			<span class="titl">{if $modal}Клеится на стекло изнутри?{else}<br replaces="Клеится на стекло изнутри?"/>{/if}</span>			
			<div class="link">
				<a href="#" class="on" rel="nofollow">{if $modal}НЕТ{else}<br replaces="НЕТ"/>{/if}</a>
				<a href="#" class="" rel="nofollow">{if $modal}ДА{else}<br replaces="ДА"/>{/if}</a>				
			</div>

		</div>
		*}
		
		{*
		<div class="icon-auto" style="border-bottom: 1px dashed orange;">	
			<label class="pl activ">
				<div class="ic"></div>	
				<div class="textI">
					<input type="hidden" value=""/>
					<font>130 руб</font><br>
					<span>самоклеющаяся<br>пленка</span>
				</div>
				<div style="clear:both;"></div>
			</label>
			<label class="za">							
				<div class="ic"></div>	
				<div class="textI">	
					<input type="hidden" value=""/>
					<font>490 руб</font><br>
					<span>с защитным<br>слоем</span>
				</div>
				<div style="clear:both;"></div>
			</label>
			<div style="clear:both"></div>
		</div>*}
		
		
        {*<div>
			<span class="auto-title">Защита:</span>
			<ul class="auto-laminar">
				<li class="auto-active" val="true"><span>с ламинацией</span></li>
				<li val="false"><span>без ламинации</span></li>
			</ul>
		</div>*}
		
		<!--/noindex-->	
	</div>
</div>

{if $Style->id == 629}
<style>
	.good-price, #one_click_order_block, #submit {
		display:none;
	}
	.b-good-price-submit {
		background:none;
	}
</style>
{/if}
{*<script type="text/javascript" >
	//переменные для расчета наклеек
	stickers_set = {
		production_time_printing : parseFloat({$production_time_printing}),
		production_time_cutting : parseFloat({$production_time_cutting}),
		production_cost : parseFloat({$production_cost}),
		ink_cost : parseFloat({$ink_cost}),
		price_margins : {$price_margins}
	}
</script>*}