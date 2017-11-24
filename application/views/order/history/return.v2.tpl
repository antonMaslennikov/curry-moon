{if $positions}
	{foreach from=$positions key="k" item="pos"}
	<div class="item clear">
		<div class="icon-v">
			<input type="checkbox" class="hid" name="pos[{$pos.user_basket_good_id}]" id="checkbox-{$k}" {if $positions|count == 1}checked="checked"{/if} />
			{*<a rel="nofollow" href="#" class="v  {if $positions|count == 1}activ{/if}"></a>*}
			<label for="checkbox-{$k}"></label>
		</div>					
		<div class="img">
			<img title="{$pos.style_name} {$pos.good_name}" alt="" src="{$pos.pic}" height="38">
		</div>
		<div class="info clear">
			<div class="name">
				{if $pos.good_status != 'customize'}<a class="a" title="{$pos.good_name}" href="/catalog/{$pos.user_login}/{$pos.good_id}/" rel="nofollow">{$pos.good_name}</a>{else}<div class="a">{$pos.good_name}</div>{/if}
				<span>{if $pos.gsGoodId == 20570}{if $pos.comment != ''}{$pos.comment}{/if}{/if}{if $pos.size_rus !=""}{$pos.size_rus}{/if}</span>
			</div>
			<div class="price">{$pos.tprice} {$L.CURRENT_CURRENCY}</div>
		</div>
	</div>
	{/foreach}
{else}
	<p>Нет позиций для возврата</p>
{/if}
