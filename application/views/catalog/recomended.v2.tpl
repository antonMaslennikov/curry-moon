<ul class="b-catalog-main">
	
	{foreach from=$recommendations item="razdel"}
	<li style="clear: both;margin-left: 0;">
		{*<h4>{$razdel.title}</h4>*}
		<ul style="position:relative;height:{$razdel.height}px;">
		{foreach from=$razdel.goods item="g" name="forrelated"} 
			<li class="m12" style="left:{$g.x}px;top:{$g.y}px;height1:{$g.h}px;">
				<a class="item" title="{$g.good_name} - футболки на заказ" href="/catalog/{$g.user_login}/{$g.good_id}/" rel="nofollow">
					<img alt="{$g.good_name} - футболки на заказ" style="background-color: #{$g.bg};" src="{$g.picture}">
					<!-- span class="title">{$g.good_name}</span -->
	            {if $g.good_discount > 0}
					<!--золотистый блок-->
					<span style="background-color:#FF8400;display:block; visibility:visible;" class="price gold">{if $g.price}{if $g.price_old > $g.price}<strike>{$g.price_old}</strike>{/if} {$g.price}&nbsp;руб.{else}<strike>1090</strike> 790&nbsp;руб.{/if}</span><!--/noindex-->
				{else}
					<!--зелёный блок-->
					<span style="background-color:#00a851;" class="price">{if $g.price}{if $g.price_old > $g.price}<strike>{$g.price_old}</strike>{/if} {$g.price}&nbsp;руб.{else}<strike>1090</strike> 790&nbsp;руб.{/if}</span>
				{/if}
				
				</a>
				{if $g.place > 0}
				<span class="b-orden-sml" onclick="document.location = 'http://www.maryjane.ru/blog/view/1511/'; return false;"></span>
				{/if}
			</li>
			
		{/foreach}
			<li style="clear: both;margin: 0;"></li>
		</ul>
	</li>
	{/foreach}
	
	<!--для аналитики сколько кликов блок "Рекомендуем"-->
	<script>
		$(document).ready(function(){
			$('#recomendet-content li a').click(function(e){
				trackUser('Страница товара','Правый блок <<Рекомендуем>>', 'клик по реком. продуктам');//трек гугл аналитик
			});
		 });
	</script>
</ul>

	
