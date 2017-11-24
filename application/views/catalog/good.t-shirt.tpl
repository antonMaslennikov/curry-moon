<div class="tshirt-name">
	<h3 style="width:auto"><span id="style_name" class="style_name"></span></h3>
	
	{*if !$modal}		
		<ul class="b-good-calc-deliv ovd clearfix">
			<!--noindex-->
				<li style="height:auto;float: none;"><a href="#!/show-composition-maintenance" id="show-composition-maintenance" title="composition" rel="nofollow">{$L.GOOD_sostav}</a></li>

				{ *<li style="height:auto;padding:0 5px 0 0"><a href="#!/moneyback" id="show-return-exchange" title="moneyback{ * if $sale}-sale{/if* }" rel="nofollow" style="font-size:11px; color:#666;"><br replaces="{$L.GOOD_return_change}"/></a></li>* }
				<li style="height:auto;padding:0 5px 0 5px"><a href="#!/show-delivery" id="show-delivery" title="delivery" rel="nofollow" style="font-size:11px;color:#666"><br replaces="{$L.GOOD_delivery}"/></a></li>
			<!--/noindex-->			
		</ul>
	{/if*}
	
	<div id="good-style-composition" style="clear:both;font-size: 11px;">{$Style->style_composition}</div>
	
	<!--noindex--><a href="#" class="anchor dashed" name="tab-sizes" rel="nofollow">{if $modal}{$L.GOOD_size_table}{else}<br replaces="{$L.GOOD_size_table}"/>{/if}</a><!--/noindex-->
</div>

{* Выбор размера *}	
<!--noindex-->		
<div class="select-size-box">
	<div class="error-order" id="size-error">{if $modal}{$L.GOOD_choose_size}{else}<br replaces="{$L.GOOD_choose_size}"/>{/if}</div>
	<input id="good_sizes" type="hidden" name="size" value="">	
</div>
<!--/noindex-->

{* Выбор цвета *}
<!--noindex-->
<div class="b-color-select">
	<div class="error-order" id="color-error">{if $modal}{$L.GOOD_choose_color}{else}<br replaces="{$L.GOOD_choose_color}"/>{/if}</div>
	<input id="good_color" type="hidden" name="color" value="">
</div>
<!--/noindex-->