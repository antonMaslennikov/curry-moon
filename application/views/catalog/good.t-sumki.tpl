<div class="good-tpl-sumki">
	<div class="tshirt-name">
		<h3 style="width:auto"><span id="style_name" class="style_name">{*Футболка мужская Regular*}</span>{*.*}
		<!--noindex--><a href="#" class="anchor" name="tab-sizes" rel="nofollow">{if $modal}Таблица размеров{else}<br replaces="Таблица размеров"/>{/if}</a><!--/noindex--></h3>
		
			{if !$modal}
			<ul class="b-good-calc-deliv ovd">
				<!--noindex-->
				<li style="height:auto;padding:0 5px 0 0;"><a href="#!/show-return-exchange" id="show-return-exchange" title="moneyback{*if $sale}-sale{/if*}" rel="nofollow" style="font-size:11px; color:#666;"><br replaces="{$L.GOOD_return_change}"/></a></li>
				<li style="height:auto;padding:0 5px 0 5px;"><a href="#!/show-delivery" id="show-delivery" title="delivery" rel="nofollow" style="font-size:11px;color:#666;"><br replaces="{$L.GOOD_delivery}"/></a></li>
				<li style="height:auto;clear:left;padding:0"></li>
				<!--/noindex-->
			</ul>
			{/if}

	</div>

	<div class="comment"></div>

	<!-- Выбор размера -->
	<!--noindex-->
	<div class="select-size-box" style="visibility:hidden">
		<div class="error-order" id="size-error">{if $modal}Выберите размер!{else}<br replaces="Выберите размер!"/>{/if}</div>
		<input id="good_sizes" type="hidden" name="size" value="">
	</div>
	<!--/noindex-->

	<!-- Выбор цвета -->
	<!--noindex-->
	<div class="b-color-select">
		<div class="error-order" id="color-error">{if $modal}Выберите цвет!{else}<br replaces="Выберите цвет!"/>{/if}</div>
		<input id="good_color" type="hidden" name="color" value="">
	</div>
	<!--/noindex-->
</div>