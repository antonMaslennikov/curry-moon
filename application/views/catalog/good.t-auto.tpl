{*<script type="text/javascript" src="/js/2012/auto.js"></script>*}

<div class="good-tpl-auto type-auto type-auto-simple" style="display:none">
	<ul class="auto-info">
		<li>{$L.GOOD_auto_text1}</li>
		<li>{$L.GOOD_auto_text2}</li>
		<li>{$L.GOOD_auto_text3}</li>
		<li>{$L.GOOD_auto_text4}</li>
		<li>{$L.GOOD_auto_text5}</li>
		<li>{$L.GOOD_auto_text6}</li>
		{*<li>{$L.GOOD_auto_text7}</li>*}
		{*<li>Наклейки напечатаны на профессиональной плёнке 3м</li>
		<li>Наклеиваются без пузырьков и легко удаляется</li>
		<li>Все наклейки вырезаны по контуру</li>
		<li>Высококачественная печать</li>
		<li>Ламинация зашишает от выцветания и царапин</li>
		<li>Гарантия на ламинацию 3 года</li>
		<li>Не портит лако-красочное покрытие</li>*}
	</ul>
	<div class="auto-params clearfix">
		<!--noindex--><div class="wrap-sizes clearfix">
			<span class="auto-title">{if $modal}{$L.GOOD_auto_sizes2}:{else}<br replaces="{$L.GOOD_auto_sizes2}:"/>{/if}</span>
			<ul class="auto-sizes clearfix">
				{foreach from=$styles.auto.618.sizes item="size" key="size_id"}
				<li gender="618" value="{$size_id}">
					<span>{if $modal}{$size.en}{else}<br replaces="{$size.en}"/>{/if}</span>
				</li>
				{/foreach}
			</ul>
			<ul class="auto-laminar clearfix">	
				{foreach from=$styles.auto.288.sizes item="size" key="size_id"}
				<li gender="288" value="{$size_id}">
					<span>{if $modal}{$size.en}{else}<br replaces="{$size.en}"/>{/if}</span>
				</li>
				{/foreach}
			</ul>
		</div><!--/noindex-->
	</div>
	
	{*
	<div class="icon-auto clearfix">	
		<label class="pl _activ clearfix" gender="618">
			<div class="ic"></div>	
			<div class="textI">
				<span>{if $modal}самоклеющаяся<br>пленка{else}<br replaces="самоклеющаяся<br>пленка"/>{/if}</span>
			</div>
		</label>
		<label class="za clearfix" gender="288">							
			<div class="ic"></div>	
			<div class="textI">
				<span>{if $modal}с защитным<br>слоем{else}<br replaces="с защитным<br>слоем"/>{/if}</span>
			</div>
		</label>
	</div>
	*}
	
</div>