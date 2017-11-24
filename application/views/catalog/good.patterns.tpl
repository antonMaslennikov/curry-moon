{*вроде не юзается Но в шалоне подключен*}
<div class="tshirt-name">
	<h3 style="width: auto;"><span id="style_name" class="style_name">{*Футболка мужская Regular*}</span>.
	<!--noindex--><a href="#" class="anchor" name="tab-sizes" rel="nofollow"><br replaces="{$L.GOOD_size_table}"/></a><!--/noindex--></h3>

	{if !$modal}
		<ul class="b-good-calc-deliv ovd">
			<!--noindex-->
			{*if $module=="catalog.v2"}
				<li style="height:auto;float: none;"><a href="#!/show-composition-maintenance" id="show-composition-maintenance" title="composition" rel="nofollow">{$L.GOOD_sostav}</a></li>
			{else*}
				<li style="height:auto;padding:0 5px 0 0;"><a href="#!/moneyback" id="show-return-exchange" title="moneyback{*if $sale}-sale{/if*}" rel="nofollow" style="font-size:11px; color:#666;"><br replaces="{$L.GOOD_return_change}"/></a></li>
				<li style="height:auto;padding:0 5px 0 5px;"><a href="#!/show-delivery" id="show-delivery" title="delivery" rel="nofollow" style="font-size:11px; color:#666;"><br replaces="{$L.GOOD_delivery}"/></a></li>
				<li style="height:auto;clear:left;padding:0"></li>
			{*/if*}
			<!--/noindex-->
		</ul>
	{/if}

</div>

<!-- Выбор размера -->
<!--noindex-->
<div class="select-size-box">
	<div class="error-order" id="size-error"><br replaces="{$L.GOOD_choose_size}"/></div>
	<input id="good_sizes" type="hidden" name="size" value="">
</div>
<!--/noindex-->
