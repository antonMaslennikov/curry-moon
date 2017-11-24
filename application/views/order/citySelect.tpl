<div class="p-region" id="p-region">
		<div class="p-region-country">
			<div class="item {if $order_country == '838'}active{/if}">
				<a href="#c838" rel="nofollow">{$L.ORDERV3_russia}</a>
			</div>
			<div class="item {if $order_country == '880'}active{/if}">
				<a href="#c880" rel="nofollow">{$L.ORDERV3_ukraine}</a>
			</div>
			<div class="item  {if $order_country == '693'}active{/if}">
				<a href="#c693" rel="nofollow">{$L.ORDERV3_belorussia}</a>
			</div>
			<div class="item {if $order_country == '759'}active{/if}">
				<a href="#c759" rel="nofollow">{$L.ORDERV3_kazakstan}</a>
			</div>
			<div class="region" style="float:right;margin-right: 40px;">
				<span class="show-region">{$L.ORDERV3_other_region}</span>
				<select size="10" name="country" id="basket_country_2" class="input select_country" style="display: none;margin: 5px;">
					{foreach from=$country item="c"}			
					<option value="{$c.country_id}" {if $c.country_id == $order_country}selected="selected"{/if}>{$c.country_name}</option>
					{/foreach}
				</select>
			</div>			
			<div style="clear:both;"></div>
			{if $PAGE->lang=="en"}
				<div class="eng">
					<div class="item {if $order_country == '863'}active{/if}">
						<a href="#c863" rel="nofollow">United States</a>
					</div>
					<div class="item {if $order_country == '726'}active{/if}">
						<a href="#c726" rel="nofollow">Germany</a>
					</div>
					<div class="item {if $order_country == '782'}active{/if}">
						<a href="#c782" rel="nofollow">Latvia</a>
					</div>
					<div class="item {if $order_country == '763'}active{/if}">
						<a href="#c763" rel="nofollow">Сanada</a>
					</div>
					<div class="item {if $order_country == '895'}active{/if}">
						<a href="#c895" rel="nofollow">Czech Republic</a>
					</div>
					<div class="item {if $order_country == '755'}active{/if}">
						<a href="#c755" rel="nofollow">Spain</a>
					</div>
					<div class="item {if $order_country == '888'}active{/if}">
						<a href="#c888" rel="nofollow">France</a>
					</div>
					<div class="item {if $order_country == '709'}active{/if}">
						<a href="#c709" rel="nofollow">United Kingdom</a>
					</div>
					<div class="item {if $order_country == '747'}active{/if}">
						<a href="#c747" rel="nofollow">Israel</a>
					</div>
					<div style="clear:both;"></div>
				</div>
			{/if}
			<!--div class="close"><img width="15" height="15" title="Удалить" src="http://www.maryjane.ru/images/icons/delete_gray.gif"></div-->
		</div>
		<div class="p-region-city">
			<div class="p-region-city-current">
				<span class="p-region-city-current-region">{$default_city_name}</span>
				<a href="#" rel="nofollow" class="p-region-city-current-edit">{$L.ORDERV3_change}</a>
			</div>
			<div class="p-region-city-input" style="display:none;">
				<input type="text" value="" placeholder="укажите ваш регион" />
				<button disabled="disabled" id="p-region-city-save" >{$L.ORDERV3_save}</button>
			</div>
			<div style="clear:both;"></div>
			<div  style="min-height: 80px;">
			{foreach from=$citys key="countryKey" item="country"}
			<!--города по стране-->
			<div class="c{$countryKey} b-popup-city-offices-list-tabs-item" style="display:{if $order_country == $countryKey}block{else}none{/if}">
				{foreach from=$country.columns item="column"}
					<!--колонка-->
					<div class="p-region-city-offices-column">		
					{foreach from=$column key="letter" item="c"}
						<div class="p-region-city-offices-cell">
							<div class="p-region-city-offices-cell-letter">{$letter}</div>
							{foreach from=$c item="city"}
							<div class="p-region-city-offices-item">
								<a href="#{$city.id}" postcost="{$city.postcost}" cost="{$city.cost}" time="{$city.time}"  rel="nofollow" class="a-pseudo-item" {if $default_city_name==$city.name}style="font-weight: bold;"{/if}>{$city.name}</a>
							</div>
							{/foreach}
						</div>
					{/foreach}
					</div>
				{/foreach}
				<div style="clear:left;"></div>	
			</div><!--конец страны-->
			{/foreach}
			</div><!--конец стран-->
			
			<div class="short-info">
				<p>{$L.ORDERV3_delivery_notice}</p>
			</div>
		</div>
	</div>