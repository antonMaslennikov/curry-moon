{if $filters.category != 'enduro' && $filters.category != 'jetski' && $filters.category != 'atv'  && $filters.category != 'snowmobile' && $filters.category != 'helmet' && $filters.category != 'helm' && $goods|count > 0}
	{include file="catalog/list.fix.menu.gadgets.tpl"}
	
	{if $MobilePageVersion}
		{include file="catalog/list.mobile.menu.gadgets.tpl"}
	{/if}
{/if}

{if !$SEARCH && !$user && $PAGE->module != 'search' && !$MobilePageVersion && $style_slug != 'iphone-5' && $style_slug!= "ipad-air" && $style_slug!= "macbook-pro" && $category != 'cases' && $category != 'laptops' && $style_slug != 'case-ipad-mini' && $style_slug != 'case-ipad-3'}
	<div class="top-banners">
		<!-- Заголовок -->
		

		<div class="pageTitle">
			{if $filters.category == 'enduro' || $filters.category == 'jetski' || $filters.category == 'atv' || $filters.category == 'snowmobile' || $filters.category == 'helmet' || $filters.category == 'helm'}
				<div class="phone" href="tel://+74996538669">+7 (499) 653-8669</div>	
			{else}
			<div class="f-wall"></div>		
			<div class="h1">
				<h1>{if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}</h1>			
				{if $category == 'moto'}
					<h3>Качественные наклейки с специальным слоем ламинации</h3>
				{/if}
			</div>
			{/if}
		</div>

		
		{if $style_slug == 'iphone-5s' || $style_slug == 'iphone-5s-resin'}
			<div class="sticker_case">
				<a rel="nofollow" href="/catalog/phones/iphone-5s/new/" class="sticker {if $style_slug == 'iphone-5s'}active{/if}"></a>
				{*<a rel="nofollow" href="/catalog/phones/iphone-5s-resin/new/" class="smola {if $style_slug == 'iphone-5s-resin'}active{/if}"></a>*}
				<a rel="nofollow" href="/catalog/cases/case-iphone-5/new/" class="case {if $category == 'cases'}active{/if}"></a>
				<a rel="nofollow" href="/catalog/phones/iphone-5-bumper/new/" class="bumper {if $style_slug == 'iphone-5-bumper'}active{/if}"></a>	
				<div style="clear:left;"></div>
			</div>
		{elseif $style_slug == 'case-iphone-5' || $style_slug == 'iphone-5' || $style_slug == 'iphone-5-bumper' || $style_slug == 'iphone-5-resin'}
			<div class="sticker_case">
				<a rel="nofollow" href="/catalog/phones/iphone-5/new/" class="sticker {if $style_slug == 'iphone-5'}active{/if}"></a>
				{*<a rel="nofollow" href="/catalog/phones/iphone-5-resin/new/" class="smola {if $style_slug == 'iphone-5-resin'}active{/if}"></a>*}
				<a rel="nofollow" href="/catalog/cases/case-iphone-5/new/" class="case {if $category == 'cases'}active{/if}"></a>
				<a rel="nofollow" href="/catalog/phones/iphone-5-bumper/new/" class="bumper {if $style_slug == 'iphone-5-bumper'}active{/if}"></a>
				<div style="clear:left;"></div>
			</div>
		{elseif $style_slug == 'case-iphone-4' || $style_slug == 'iphone-4' || $style_slug == 'iphone-4-bumper' || $style_slug == 'iphone-4-resin'}
			<div class="sticker_case">
				<a rel="nofollow" href="/catalog/phones/iphone-4/new/" class="sticker {if $style_slug == 'iphone-4'}active{/if}"></a>
				{*<a rel="nofollow" href="/catalog/phones/iphone-4-resin/new/" class="smola {if $style_slug == 'iphone-4-resin'}active{/if}"></a>*}
				<a rel="nofollow" href="/catalog/cases/case-iphone-4/new/" class="case {if $category == 'cases'}active{/if}"></a>
				{*<a rel="nofollow" href="/catalog/phones/iphone-4-bumper/" class="bumper {if $style_slug == 'iphone-4-bumper'}active{/if}"></a>*}
				<div style="clear:left;"></div>
			</div>
		{elseif $style_slug == 'case-iphone-6' || $style_slug == 'iphone-6' || $style_slug == 'iphone-6-bumper' || $style_slug == 'iphone-6-resin'}
			<div class="sticker_case">
				<a rel="nofollow" href="/catalog/phones/iphone-6/new/" class="sticker {if $style_slug == 'iphone-6'}active{/if}"></a>				
				{*<a rel="nofollow" href="/catalog/phones/iphone-6-resin/new/" class="smola {if $style_slug == 'iphone-6-resin'}active{/if}"></a>		*}		
				<a rel="nofollow" href="/catalog/cases/case-iphone-6/new/" class="case {if $category == 'cases'}active{/if}"></a>
				<a rel="nofollow" href="/catalog/phones/iphone-6-bumper/" class="bumper {if $style_slug == 'iphone-6-bumper'}active{/if}"></a>
				<div style="clear:left;"></div>
			</div>	
		{elseif $style_slug == 'case-iphone-6-plus' || $style_slug == 'iphone-6-plus'  || $style_slug == 'iphone-6-plus-resin'}
			<div class="sticker_case">
				<a rel="nofollow" href="/catalog/phones/iphone-6-plus/new/" class="sticker {if $style_slug == 'iphone-6-plus'}active{/if}"></a>
				{*<a rel="nofollow" href="/catalog/phones/iphone-6-plus-resin/new/" class="smola {if $style_slug == 'iphone-6-plus-resin'}active{/if}"></a>	*}
				<a rel="nofollow" href="/catalog/cases/case-iphone-6-plus/new/" class="case {if $category == 'cases'}active{/if}"></a>
				<div style="clear:left"></div>
			</div>
		{elseif $style_slug == 'ipad-mini' || $style_slug == 'case-ipad-mini'}
			<div class="sticker_case">
				<a rel="nofollow" href="/catalog/touchpads/ipad-mini/new/" class="sticker {if $style_slug == 'ipad-mini'}active{/if}"></a>			
				{*<a rel="nofollow" href="/catalog/touchpads/case-ipad-mini/new/" class="case {if $style_slug == 'case-ipad-mini'}active{/if}"></a>*}
				<div style="clear:left"></div>
			</div>
		{elseif $style_slug == 'ipad-4-retina' || $style_slug == 'case-ipad-4-retina'}
			<div class="sticker_case">
				<a rel="nofollow" href="/catalog/touchpads/ipad-4-retina/new/" class="sticker {if $style_slug == 'ipad-4-retina'}active{/if}"></a>			
				{*<a rel="nofollow" href="/catalog/touchpads/case-ipad-4-retina/new/" class="case {if $style_slug == 'case-ipad-4-retina'}active{/if}"></a>*}
				<div style="clear:left;"></div>
			</div>
		{*elseif $style_slug == 'ipad-air-2'}
			<div class="sticker_case">
				<a rel="nofollow" href="/catalog/touchpads/ipad-air-2/new/" class="sticker {if $style_slug == 'ipad-air-2'}active{/if}"></a>	
				<div style="clear:left;"></div>
			</div>*}
		{/if}
		{*if $filters.category=='boards'}
		<div class="submenu-bg"></div>		
			<ul class="submenu boards">
				<li class="{if $style_slug == 'skateboard'}activ{/if}"><a href="/catalog/boards/skateboard/" rel="nofollow" title="">Скейтборд</a></li>	
				<li class="{if $style_slug == 'longboard'}activ{/if}"><a href="/catalog/boards/longboard/" rel="nofollow" title="">Лонгборд</a></li>	
				<li class="{if $style_slug == 'snowboard'}activ{/if}"><a href="/catalog/boards/snowboard/" rel="nofollow" title="">Сноуборд</a></li>	
				<li class="{if $style_slug == 'ski'}activ{/if}"><a href="/catalog/boards/ski/" rel="nofollow" title="">Лыжи</a></li>
				<li class="{if $style_slug == 'kite'}activ{/if}"><a href="/catalog/boards/kite/" rel="nofollow" title="">Кайтсерфинг/Вэйк</a></li>
				<li class="{if $style_slug == 'serf'}activ{/if}"><a href="/catalog/boards/serf/" rel="nofollow" title="">Серфинг</a></li>
			</ul>	
		{/if*}
		{*elseif $PAGE->url != "/catalog/cases/new/" && $category != 'moto' && $category != 'auto'  && $filters.category != 'enduro' && $filters.category != 'jetski' && $filters.category != 'atv' && $filters.category != 'snowmobile' && $filters.category != 'helmet' && $filters.category != 'helm'}
			<div class="submenu-bg"></div>	
			<div class="select">
				<!--noindex-->
				<select class="global list-laptops {if $category == "laptops"} border-select{/if}">
					<option value="">{$L.SIDEBAR_menu_st_laptop}</option>
					{include file="select.menu.laptops.tpl"}
				</select>
				<select class="global list-touchpads {if $category == "touchpads" && $style_slug != 'case-ipad-mini'} border-select{/if}">
					<option value="">{$L.SIDEBAR_menu_st_touchpads}</option>
					{include file="select.menu.touchpads.tpl"}
				</select>
				<select class="global list-phones {if $category == "phones"} border-select{/if}">
					<option value="">{$L.SIDEBAR_menu_st_pohes}</option>
					{include file="select.menu.phones.tpl"}
				</select>
				<select class="global list-ipodmp3 {if $category == 'ipodmp3'} border-select{/if}">
					{include file="select.menu.ipodmp3.tpl"}	
				</select>
				<!--/noindex-->
			</div>		
			<ul class="submenu">
				{   *<li class="{if $style_slug == 'iphone-4-resin'}active{/if}"><a href="{if $style_slug == 'iphone-4-resin'}#{else}/catalog/phones/iphone-4-resin/new/{/if}" class="labels-phones" rel="nofollow" title="{$L.SIDEBAR_menu_st_iphone4_pitch}">iPhone 4 cо смолой</a></li>	*   }
				<li class="{if $style_slug == 'iphone-6-bumper'}active{/if}"><a href="{if $style_slug == 'iphone-6-bumper'}#{else}/catalog/phones/iphone-6-bumper/{/if}" class="labels-phones" rel="nofollow" title="{$L.HEADER_menu_bumper_Iphone6}">{$L.HEADER_menu_bumper_Iphone6}</a></li>
				<li class="{if $style_slug == 'iphone-5-bumper'}active{/if}"><a href="{if $style_slug == 'iphone-5-bumper'}#{else}/catalog/phones/iphone-5-bumper/{/if}" class="labels-phones" rel="nofollow" title="{$L.SIDEBAR_menu_bumper_Iphone5}">{$L.SIDEBAR_menu_bumper_Iphone5}</a></li>
				{   *<li class="{if $style_slug == 'iphone-4-bumper'}active{/if}"><a href="{if $style_slug == 'iphone-4-bumper'}#{else}/catalog/phones/iphone-4-bumper/{/if}" class="labels-phones" rel="nofollow" title="{$L.SIDEBAR_menu_bumper_Iphone4}">{$L.SIDEBAR_menu_bumper_Iphone4}</a></li>*   }
				<li class="{if $style_slug == 'case-iphone-4'}active{/if}"><a href="{if $style_slug == 'case-iphone-4'}#{else}/catalog/cases/case-iphone-4/new/{/if}" class="labels-phones" rel="nofollow" title="{$L.SIDEBAR_menu_cases_Iphone4}">{$L.SIDEBAR_menu_cases_Iphone4}</a></li>
				<li class="{if $style_slug == 'case-iphone-5'}active{/if}"><a href="{if $style_slug == 'case-iphone-5'}#{else}/catalog/cases/case-iphone-5/new/{/if}" class="labels-phones" rel="nofollow" title="{$L.SIDEBAR_menu_cases_Iphone5}">{$L.SIDEBAR_menu_cases_Iphone5}</a></li>	
				<li class="{if $style_slug == 'case-iphone-6'}active{/if}"><a href="{if $style_slug == 'case-iphone-6'}#{else}/catalog/cases/case-iphone-6/new/{/if}" class="labels-phones" rel="nofollow" title="{$L.SIDEBAR_menu_cases_Iphone6}">{$L.SIDEBAR_menu_cases_Iphone6}</a></li>		
				<li class="{if $style_slug == 'case-iphone-6-plus'}active{/if}"><a href="{if $style_slug == 'case-iphone-6-plus'}#{else}/catalog/cases/case-iphone-6-plus/new/{/if}" class="labels-phones" rel="nofollow" title="{$L.SIDEBAR_menu_cases_Iphone6} plus">{$L.SIDEBAR_menu_cases_Iphone6} plus</a></li>	
				<li class="{if $style_slug == 'case-ipad-mini'}active{/if}"><a href="{if $category == "touchpads" && $category_style == 349}#{else}/catalog/touchpads/case-ipad-mini/new/{/if}" class="labels-ipad" rel="nofollow" title="{$L.SIDEBAR_menu_cases_Ipad_mini}">{$L.SIDEBAR_menu_cases_Ipad_mini}</a></li>
			</ul>		
			<script>
				$('.top-banners .select select').change(function(){
					if ($(this).val() != "" && $(this).val() != "-2")
						location.href = '/catalog/' + $(this).children('option:selected').attr('_category') + '/' + $(this).val() + '/new/'; 
				});
			</script>	
		{/if*}
		
		{if $category == 'cases' || $style_slug == 'case-ipad-mini' || $style_slug == 'case-ipad-3'}
			<ul class="cases-aple">
				<li class="{if $style_slug == 'case-iphone-4'}active{/if}">
					{if $style_slug == 'case-iphone-4'}<span class="tabs-a">{$L.SIDEBAR_menu_cases_Iphone4}</span>{else}<a href="/catalog/cases/new/" class="tabs-a" rel="nofollow" title="{$L.SIDEBAR_menu_cases_Iphone4}">{$L.SIDEBAR_menu_cases_Iphone4}</a>{/if}
				</li>
				<li class="{if $style_slug == 'case-iphone-5'}active{/if}">
					{if $style_slug == 'case-iphone-5'}<span class="tabs-a">{$L.SIDEBAR_menu_cases_Iphone5}</span>{else}<a href="/catalog/cases/case-iphone-5/new/" class="tabs-a" rel="nofollow" title="{$L.SIDEBAR_menu_cases_Iphone5}">{$L.SIDEBAR_menu_cases_Iphone5}</a>{/if}
				</li>		
				<li class="{if $style_slug == 'case-iphone-6'}active{/if}">
					{if $style_slug == 'case-iphone-6'}<span class="tabs-a">{$L.SIDEBAR_menu_cases_Iphone6}</span>{else}<a href="/catalog/cases/case-iphone-6/new/" class="tabs-a" rel="nofollow" title="{$L.SIDEBAR_menu_cases_Iphone6}">{$L.SIDEBAR_menu_cases_Iphone6}</a>{/if}
				</li>
				<li class="{if $style_slug == 'case-ipad-mini'}active{/if}">
					{if $style_slug == 'case-ipad-mini'}<span class="tabs-a">{$L.SIDEBAR_menu_cases_Ipad_mini}</span>{else}<a href="/catalog/touchpads/case-ipad-mini/new/" class="tabs-a" rel="nofollow" title="{$L.SIDEBAR_menu_cases_Ipad_mini}">{$L.SIDEBAR_menu_cases_Ipad_mini}</a>{/if}
				</li>		
				{*<li class="{if $style_slug == 'case-ipad-3'}active{/if}">
					{if $style_slug == 'case-ipad-3'}<span class="tabs-a">{$L.SIDEBAR_menu_cases_Ipad3}</span>{else}<a href="/catalog/touchpads/case-ipad-3/new/" class="tabs-a" rel="nofollow" title="{$L.SIDEBAR_menu_cases_Ipad3}">{$L.SIDEBAR_menu_cases_Ipad3}</a>{/if}
				</li>*}
				{*
				<li class="{if $style_slug == 'case-ipad-4-retina'}active{/if}">
					{if $style_slug == 'case-ipad-4-retina'}<span class="tabs-a">Чехол iPad Retina</span>{else}<a href="/catalog/touchpads/case-ipad-4-retina/new/" class="tabs-a" rel="nofollow" title="Чехол iPad Retina">Чехол iPad Retina</a>{/if}
				</li>
				*}
			</ul>
		{/if}
			
		{if $task == 'auto' || $task == '1color'}
			{*<img src="/images/b/082012/b-2=3.jpg" width="980" height="300" alt="{if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}"/>*}

			<img src="/images/b/082012/b-2.jpg" width="980" height="300" alt="{if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}"/>
			<img style="position:absolute;top:16px;right:12px;" src="/images/b/082012/1+12.gif" width="465" height="267" alt="при покупке двух наклеек третья наклейка бесплатно" title="при покупке двух наклеек третья наклейка бесплатно"/>

		{elseif $task == 'stickers'}
			<img src="/images/b/082012/b_school2.jpg" width="980" height="300" alt="{if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}"/>
		{elseif $task == 'gadget'}
			{if $style_slug == 'iphone-4-bumper' || $style_slug == 'iphone-5-bumper'}
				<img src="/images/b/082012/bumpers.jpg" width="980" height="300" alt="{if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}"/>
			{elseif ($category == "phones" || $category == "ipodmp3") && $style_slug != 'iphone-5'}
				<img src="/images/b/082012/b-1.jpg" width="980" height="300" alt="{if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}"/>
			{elseif $category == "cases"}
				<img src="/images/b/082012/b_hard_case.jpg" width="980" height="300" alt="{if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}"/>
			{elseif $category == "touchpads"}
				<img src="/images/b/082012/b_ipad2.jpg" width="980" height="300" alt="{if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}"/>
			{elseif $category == "laptops"}
				<img src="/images/b/082012/b_nout2.jpg" width="980" height="300" alt="{if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}"/>
			{elseif $category == 'moto'}
				<img src="/images/b/082012/moto.jpg" width="980" height="300" alt="{if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}"/>		
			{elseif $filters.category=='boards'}
			<img src="/images/b/boards/1.jpg" width="980" height="300" alt="{if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}"/>	
			{/if}
		{elseif $filters.category == 'enduro' || $filters.category == 'jetski' || $filters.category == 'atv' || $filters.category == 'snowmobile' || $filters.category == 'helmet' || $filters.category == 'helm'}
		
			{*if $USER->meta->mjteam*}				
				<img src="/images/b/enduro3.jpg" width="765" height="300" alt="Галерея готовых наклеек на эндуро мотоциклы">
					
				{assign var=soc_width value=205}
				{include file="catalog/tabs.soc.vk_fb.tpl"}
			{*else}			
				<img src="/images/b/enduro.jpg" width="980" height="300" alt="{if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}"/>			
			{/if*}
		{/if}
		
		

		{if $category == 'moto'}
			<div class="moto-menu clearfix">
				<a href="/catalog/moto/" rel="nofollow" class="item-10 {if $style_slug == 'moto-stickers-na-bak'}activ{/if}" title=""><span></span></a>
				<a href="/catalog/moto/naklejki-na-bak-motociklov/" rel="nofollow" class="item-1 {if $style_slug == 'naklejki-na-bak-motociklov'}activ{/if}" title=""><span></span></a>
				 <a href="/catalog/moto/naklejki-na-motocikl-kupit/" rel="nofollow" class="item-2 {if $style_slug == 'naklejki-na-motocikl-kupit'}activ{/if}" title=""><span></span></a>
				 <a href="/catalog/moto/naklejki-na-bak-motocikla/" rel="nofollow" class="item-3 {if $style_slug == 'naklejki-na-bak-motocikla'}activ{/if}" title=""><span></span></a>
				 <a href="/catalog/moto/kupit-naklejki-na-motocikl/" rel="nofollow" class="item-4 {if $style_slug == 'kupit-naklejki-na-motocikl'}activ{/if}" title=""><span></span></a>
				 <a href="/catalog/moto/naklejki-na-bak-honda/" rel="nofollow" class="item-5 {if $style_slug == 'naklejki-na-bak-honda'}activ{/if}" title=""><span></span></a>
				 <a href="/catalog/moto/naklejki-na-moto/" rel="nofollow" class="item-6 {if $style_slug == 'naklejki-na-moto'}activ{/if}" title=""><span></span></a>
				 {*<a href="/catalog/moto/fuel-tank-sticker-6/" rel="nofollow" class="item-7 {if $style_slug == 'fuel-tank-sticker-6'}activ{/if}" title=""><span></span></a>*}
				 
				 <a href="/catalog/moto/motonaklejki-na-bak/" rel="nofollow" class="item-8 {if $style_slug == 'motonaklejki-na-bak'}activ{/if}" title="" ><span></span></a>
				 <a href="/catalog/moto/moto-naklejki-na-bak/" rel="nofollow" class="item-9 {if $style_slug == 'moto-naklejki-na-bak'}activ{/if}" title="" ><span></span></a>
			</div>
		{/if}

		{if $filters.category=='boards'}			
			<div class="boards-menu-square clearfix">
				<a href="/catalog/boards/snowboard/" rel="nofollow" class="item-1 {if $style_slug == 'snowboard'}activ{/if}" title=""></a>
				<a href="/catalog/boards/ski/" rel="nofollow" class="item-2 {if $style_slug == 'ski'}activ{/if}" title=""></a>
				<a href="/catalog/boards/skateboard/" rel="nofollow" class="item-3 {if $style_slug == 'skateboard'}activ{/if}" title=""></a>
				<a href="/catalog/boards/longboard/" rel="nofollow" class="item-4 {if $style_slug == 'longboard'}activ{/if}" title=""></a>
				<a href="/catalog/boards/serf/" rel="nofollow" class="item-5 {if $style_slug == 'serf'}activ{/if}" title=""></a>
				<a href="/catalog/boards/kite/" rel="nofollow" class="item-6 {if $style_slug == 'kite'}activ{/if}" title=""></a>				 	
			</div>
			{*else}
			<div class="boards-menu">
				 <a href="/catalog/boards/skateboard/" rel="nofollow" class="item-1 {if $style_slug == 'skateboard'}activ{/if}" title=""><span></span></a>
				 <a href="/catalog/boards/longboard/" rel="nofollow" class="item-2 {if $style_slug == 'longboard'}activ{/if}" title=""><span></span></a>
				 <a href="/catalog/boards/snowboard/" rel="nofollow" class="item-3 {if $style_slug == 'snowboard'}activ{/if}" title=""><span></span></a>
				 <a href="/catalog/boards/ski/" rel="nofollow" class="item-4 {if $style_slug == 'ski'}activ{/if}" title=""><span></span></a>
				 <a href="/catalog/boards/kite/" rel="nofollow" class="item-5 {if $style_slug == 'kite'}activ{/if}" title=""><span></span></a>	
				 <a href="/catalog/boards/serf/" rel="nofollow" class="item-6 {if $style_slug == 'serf'}activ{/if}" title=""><span></span></a>	
				 <div style="clear: both;"></div>
			</div>
			{/if*}
		{/if}
		
		{*if $filters.category == 'enduro' && ($USER->id == 105091 || $USER->id == 6199)}
			<div class="enduro-menu" style="border-bottom: 1px dashed orange;">
				 <a href="/catalog/enduro/ktm/" rel="nofollow" class="item-1 {if $task == 'ktm'}activ{/if}" title="ktm"></a>			 
				 <a href="#" rel="nofollow" class="item-2 " title=""></a>
				 <a href="/catalog/enduro/honda/" rel="nofollow" class="item-3 {if $task == 'honda'}activ{/if}" title="Нonda"></a>
				 <a href="#" rel="nofollow" class="item-4 " title=""></a>
				 <div style="clear: both;"></div>
			</div>
		{/if*}
	</div>
{/if}

{*if  $filters.category != 'auto' && $filters.category != 'moto' && $filters.category != 'boards' && $filters.category != 'enduro'  && $filters.category != 'jetski' && $filters.category != 'atv' && $filters.category != 'snowmobile' && ($USER->id == 27278 || $USER->id == 105091 || $USER->id == 6199)}
	<style>.bottom-sliderG { border-bottom: 1px dashed orange; } </style>
	{include file="catalog/slider.tpl"}
{/if*}

{if $user ||($filters.category != 'enduro' && $filters.category != 'jetski' && $filters.category != 'atv' && $filters.category != 'snowmobile' && $filters.category != 'helmet' && $filters.category != 'helm')}
<div class="pageTitle table" style="font-size:11px;position:relative">

	{if !$user}
		{if $task == "auto" || $task == "1color"}
		
		<div class="b-filter-monoColor clearfix">
			<a href="/catalog/auto/new/" title="Цветные" rel="nofollow" class="color {if $task != '1color' && $Style->id != 629}active{/if}">Цветные</a>
			{* <a href="/catalog/auto/auto-monochrome/new/" title="1 цвет" rel="nofollow" class="mono {if $task == '1color' || $Style->id == 629}active{/if}">1 цвет</a>*}
			<a href="/stickermize/style,603/" title="Свой рисунок" rel="nofollow" class="custom">Свой рисунок</a>
		</div>
		
		{/if}
		
		{if !$MobilePageVersion && ($task == "auto" || $task == "1color")}
			<style>.sidebar_filter{ margin-top:0 } .doble_menu{ padding-top:0 }</style>
			
			{* if !$SEARCH}<a class="konkurs_flag" href="/senddrawing.sticker1color/" rel="nofollow" title="{$L.MAIN_competition_win} 15000 р.*" >{$L.MAIN_competition_win} 15000 р.*</a>{/if *}
		{/if}
	{/if}
	
	{if $user || $MobilePageVersion || $style_slug == 'iphone-5' || $style_slug== "ipad-air" || $style_slug== "macbook-pro" || $category == 'cases' || $category == 'laptops' || $style_slug == 'case-ipad-mini' || $style_slug == 'case-ipad-3' ||($task == "auto" && $SEARCH)}
		<h1>
			{if $SEARCH}
				{if $task == "auto"}<style>.moveSidebarToLeft .pageTitle h1{ float:left;padding:0;margin:16px 0 0 30px }</style>{/if}
				{include file="catalog/list.search-filter.tpl"}
			{else}			
				{if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}
			{/if}
		</h1>	
		<style>.MPV .pageTitle .b-filter_tsh{ right:44px!important; }
		.MPV .moveSidebarToLeft .pageTitle { display: none; }
		.MPV .moveSidebarToLeft .pageTitle.table { display: block; }</style>
	{/if}	
		

	
	{*<a href="/tag/novuygod/{$filters.category}/{if $filters.category != 'auto'}{$style_slug}/{/if}" title="Новый год" rel="nofollow" id="elka"></a>*}
	
	{if !$MobilePageVersion}	
		{if $SEARCH && $task == "auto"}{*уберем на поиске авто а тто налазит*}
		{else if $category != 'moto' && $filters.category != 'enduro' && $filters.category != 'jetski' && $filters.category != 'atv' && $filters.category != 'boards' && $filters.category != 'moto' && $filters.category != 'snowmobile' && $filters.category != 'helmet' && $filters.category != 'helm'}	
			{*<a href="/catalog/{$filters.category}/{if $filters.category != 'auto'}{$style_slug}/{/if}top2015/" title="Популярные в 2015 году" rel="nofollow" id="top2015"></a>*}

			{*<a href="/23february/{$filters.category}/{if $filters.category != 'auto'}{$style_slug}/{/if}new/" title="23 февраля" rel="nofollow" id="feb23"></a>*}
			
			{*<a href="/8march/{$filters.category}/{if $filters.category != 'auto'}{$style_slug}/{/if}new/" title="8 марта" rel="nofollow" id="marta8"></a>*}

		{/if}
	{/if}
		
	{*<a href="/8march/{$filters.category}/{if $filters.category != 'auto'}{$style_slug}/{/if}new/" title="8 марта" rel="nofollow" id="top2014"></a>
	<a href="/23february/{$filters.category}/{if $filters.category != 'auto'}{$style_slug}/{/if}new/" title="23 февраля" rel="nofollow" id="zvezda" ></a>*}

	
	{if $category == 'moto'}
		<h2>Стикеры со специальным слоем ламината.</h2>
	{elseif $MobilePageVersion || ($task != "1color" && $task != "auto" && $filters.category != 'boards' && $filters.category != 'moto' && $category != 'moto')}
		<div class="NLtags{if $MobilePageVersion}-mobile{/if}">
			<select class="tags" style="font-size: 13px;">
				<option value="">{$L.LIST_menu_collections}</option>
				
				{*<option value="8march" _category="{$filters.category}" {if $PAGE->module == '8march'}selected="selected"{/if} _collection="true">8 марта</option>
				<option value="14february" _category="{$filters.category}" {if $PAGE->module == '14february'}selected="selected"{/if} _collection="true">14 февраля</option>
				<option value="23february" _category="{$filters.category}" {if $PAGE->module == '23february'}selected="selected"{/if} _collection="true">23 февраля</option>*}
				
				{foreach from=$TAGS item="t"}
				<option value="{$t.slug}" img="{$t.picture_path}" {if $TAG.slug == $t.slug}selected="selected"{/if} _category="{$filters.category}" _style="{$style_slug}">{$t.name}</option>
				{/foreach}
			</select>
		</div>
		<style>.NLtags .selectbox .dropdown { width: 772px!important; left: -323px!important;} </style>
		{literal}
			<script>
				$('select.tags').change(function(){
					if ($(this).val().length > 0) {
						var sid = $(this).children('option:selected').attr('_style');
					
						if (!sid)
							sid = '';
					
						link = (($(this).children('option:selected').attr('_collection')) ? '/' : '/tag/') + $(this).val() + '/' + $(this).children('option:selected').attr('_category') + '/' + ((sid.length > 0) ? sid + '/' : '');
					
						location.href = link;
					} 
				});
			</script>
		{/literal}
	{/if}	
	{if !$MobilePageVersion}
		{if $goods|count > 0}
		<div class="b-filter_tsh" style="float:right;margin:15px 0 0 0">
			<a href="{$base_link}/new/" title="{$L.GOOD_new}" rel="nofollow" class="new-filter {if $orderBy == 'new'}active{/if}">{*Новое*}</a>
			<a href="{$base_link}/popular/" title="{$L.GOOD_popular}" rel="nofollow" class="pop-filter {if $orderBy == 'popular' || $orderBy == ''}active{/if}">{*Популярное*}</a>
			<a href="{$base_link}/grade/" rel="nofollow" class="score-filter {if $orderBy == 'grade'}active{/if}" title="{$L.GOOD_rating}">{*По оценке*}</a>
		</div>
		{/if}
		
		{if $task == "auto"}
			{literal}
			<style>
				.pageTitle .b-filter_tsh {right: 65px!important;}
				.MPV .pageTitle .b-filter_tsh {right: 77px!important;}
			</style>
			{/literal}				
			<div class="b-filter_view">
				<a href="/{$module}/{$task}/new/" rel="nofollow" class="view-pipl {if $mode != 'preview'}active{/if}" title=""></a>
				<a href="/{$module}/{$task}/preview/" rel="nofollow" class="view-other {if $mode == 'preview'}active{/if}" title=""></a>
			</div>
		{/if}	
	{/if}
</div>
{else}
	{if $filters.category != 'enduro' && $filters.category != 'jetski' && $filters.category != 'atv' && $filters.category != 'snowmobile' && $filters.category != 'helmet' && $filters.category != 'helm'}
		<br clear="all"/>
	{/if}
		<br clear="all"/>		
{/if}
<div style="clear:both"></div>