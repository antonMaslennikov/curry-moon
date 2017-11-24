<div class="good-tpl-boards">
	<ul></ul>
	<!--noindex--><div class="wrap-select">
		<select class="boards-list" >
			<option value="skateboard" hash="" _id="592" data-min-size="17;50" data-max-size="23;82" data-min-price="920">{if $modal}Скейтборд{/if}</option> <!-- data-min-size="w;h" -->
			<option value="snowboard" hash="" _id="593" data-min-size="10;150" data-max-size="40;130" data-min-price="1990">{if $modal}Cноуборд{/if}</option>
			<option value="ski" hash="" _id="611" data-min-size="5;100" data-max-size="20;130" data-min-price="1500">{if $modal}Лыжи{/if}</option>
			<option value="longboard" hash="" _id="595" data-min-size="10;110" data-max-size="40;300" data-min-price="1380">{if $modal}Лонгборд{/if}</option>
			<option value="kite" hash="" _id="594" data-min-size="39;100" data-max-size="44;145" data-min-price="2760">{if $modal}Кайт-борд, вейк-борд{/if}</option>
			<option value="serf" hash="" _id="612" data-min-size="20;60" data-max-size="52;210" data-min-price="5150">{if $modal}Доска для сёрфа{/if}</option>
		</select>
	</div>	
	<div class="boards-size clearfix">
		<div id="boards-sizes-error">{if $modal}Вы не указали точные размеры{else}<br replaces="Вы не указали точные размеры"/>{/if}</div>				
		<div class="label">{if $modal}Уточните размер, мм.*{else}<br replaces="Уточните размер, мм.*"/>{/if}</div>
		<div class="two-inputs clearfix">						
			<input name="width" id="exact_width" maxlength="4" min="100" style="color: rgb(128, 128, 128);text-align:center" placeholder="{$L.GOOD_width}" onfocus="$(this).attr('placeholder',null);" onblur="$(this).attr('placeholder','{$L.GOOD_width}');" />
			<i>X</i>
			<input name="height" id="exact_height" maxlength="4" min="300" style="color: rgb(128, 128, 128);text-align:center" placeholder="{$L.GOOD_height}" onfocus="$(this).attr('placeholder',null);" onblur="$(this).attr('placeholder','{$L.GOOD_height}');" />
		</div>
	</div><!--/noindex-->
</div>