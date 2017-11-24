<ul class="info"></ul>
<input type="hidden" name="printPrice" value='{$textilePrintPrice}'>
<br/>
<!--noindex-->Ткань:&nbsp;&nbsp;<!--/noindex-->
<select name="type" data-margin="{$textileMargin}">
	{foreach from=$styles.textile key="k" item="t"}
		<option value="{$k}" data-price="{$t.selfPrice}" {if $k == $Style->id}selected="selected"{/if}>{$t.style_name}</option>
	{/foreach}
</select>
<br/><br/>

<!--noindex-->
<input type="hidden" name="textile-width" value="{$textileStandartSize.width}" />
<input type="hidden" name="textile-height" value="{$textileStandartSize.height}" />

Размер полотна: {$textileStandartSize.width} x {$textileStandartSize.height} м

{*
<select name="list">
	<option value="1.5">1,5 м</option>
	<option value="3">3 м</option>
	<option value="4.5">4,5 м</option>
	<option value="6">6 м</option>
	<option value="7.5">7,5 м</option>
	<option value="9">9 м</option>
	<option value="10.5">10,5 м</option>
	<option value="12">12 м</option>
	<option value="13.5">13,5 м</option>
	<option value="15">15 м</option>
</select>
*}
<!--/noindex-->