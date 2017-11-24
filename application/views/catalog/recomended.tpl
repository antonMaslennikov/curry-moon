<ul class="b-catalog-main">
	{*
	<li class="m12">
		<a class="item" title="Виниловые наклейки на телефоны" href="{$allskins.phone.link}" target="_blank" rel="nofollow" style="margin-bottom:22px;">
			<img alt="Виниловые наклейки на телефоны" src="{$allskins.phone.pic}" height="184">
			<span>Наклейка на {$allskins.phone.name}</span>
		</a>					
	</li>
	
	<li class="m12 m12-right">
		<a class="item" title="Виниловые наклейки на iPad" href="{$allskins.touchpad.link}" target="_blank" rel="nofollow" style="margin-bottom:22px;">
			<img alt="Виниловые наклейки на iPad" src="{$allskins.touchpad.pic}" height="184">			
			<span>Наклейка на {$allskins.touchpad.name}</span>
		</a>					
	</li>
	*}
	
	{foreach from=$related item="g" name="forrelated"} 
		<li class="m12  {if $smarty.foreach.forrelated.last} m12-right {/if}">
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
	
	{foreach from=$best item="g" name="forbest"} 
		<li class="m12  {if $smarty.foreach.forbest.last} m12-right {/if}">
			<a class="item" title="{$g.good_name} - футболки на заказ" href="/catalog/{$g.user_login}/{$g.good_id}/" rel="nofollow">
				<img alt="{$g.good_name} - футболки на заказ" style="background-color: #{$g.bg};" src="{$g.picture}">
				<!-- span class="title">{$g.good_name}</span -->
				{if $g.good_discount > 0}
					<!--золотистый блок-->
					<span style="background-color:#FF8400;display:block; visibility: visible;" class="price gold">{if $g.price}{if $g.price_old > $g.price}<strike>{$g.price_old}</strike>{/if} {$g.price}&nbsp;руб.{else}<strike>1090</strike> 790&nbsp;руб.{/if}</span><!--/noindex-->
				{else}
					<!--зелёный блок-->
					<span style="background-color:#00a851" class="price">{if $g.price}{if $g.price_old > $g.price}<strike>{$g.price_old}</strike>{/if} {$g.price}&nbsp;руб.{else}<strike>1090</strike> 790&nbsp;руб.{/if}</span>
				{/if}
			</a>
			{if $g.place > 0}
			<span class="b-orden-sml" onclick="document.location = 'http://www.maryjane.ru/blog/view/1511/'; return false;"></span>
			{/if}
		</li>
	{/foreach}
	
	{foreach from=$new item="g" key="knew" name="fornew"}
		<li class="m12  {if $knew mod 2 != 0} m12-right {/if}">
			<a class="item" title="{$g.good_name} - футболки на заказ" href="/catalog/{$g.user_login}/{$g.good_id}/" rel="nofollow">
				<img alt="{$g.good_name} - футболки на заказ" style="background-color: #{$g.bg};" src="{$g.picture}">
				<!--span class="title">{$g.good_name}</span -->
				{if $g.good_discount > 0}
					<!--золотистый блок-->
					<span style="background-color:#FF8400;display:block;visibility:visible;" class="price gold">{if $g.price}{if $g.price_old > $g.price}<strike>{$g.price_old}</strike>{/if} {$g.price}&nbsp;руб.{else}<strike>1090</strike> 790&nbsp;руб.{/if}</span><!--/noindex-->
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
	
	<!--для аналитики сколько кликов блок "Рекомендуем"-->
	<script>
		$(document).ready(function(){
			$('#recomendet-content li a').click(function(e){
				debugger;
				trackUser('Страница товара','Правый блок <<Рекомендуем>>', 'клик по реком. продуктам');//трек гугл аналитик
			});
		 });
	</script>
</ul>

	
