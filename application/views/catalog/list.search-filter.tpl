<div class="pageTitle-search-filter">
	Результаты поиска по запросу: <font>"{$SEARCH}"</font>
	{if $filters.category}
		{if $Style->style_slug} {* винил *}
			<a href="/catalog/{$filters.category}/{$Style->style_slug}/new/" rel="nofollow" title="искать везде" class="everywhere"></a>
		{else} {* одежда *}
			<a href="/catalog/category,{$filters.category}{if $filters.size};size,{$filters.size}{/if}/{if $filters.sex!='male'}{$filters.sex}/{/if}new/" rel="nofollow" title="искать везде" class="everywhere"></a>
		{/if}
	{else if $backLink && $MobilePageVersion}	
		<a href="{$backLink}" rel="nofollow" title="" class="everywhere"></a>
	{/if}
	<br/>
	
	{if $filters.category}В категории:{else}{/if}

	{if $Style->category =='patterns' || $Style->category =='patterns-sweatshirts'}
		<a href="/catalog/{$Style->category}/{$Style->style_slug}/" rel="nofollow" class="category">
		{if $Style->style_slug == 'full-printed-t-shirt-male'}
			Футболка мужская 
		{else if $Style->style_slug == 'full-printed-t-shirt-female' || $Style->style_slug == 'full-printed-t-shirt-female-short'}
			Футболка женская 
		{else if $Style->style_slug == 'full-printed-sweatshirt-male'}
			Свитшот мужской	
		{else if $Style->style_slug == 'full-printed-sweatshirt-female'}
			Свитшот женский		
		{/if}полная запечатка</a>
	{elseif $filters.category == 'futbolki' || $filters.category == 'sweatshirts' || $filters.category == 'tolstovki' || $filters.category=='mayki-alkogolichki' || $filters.category=='longsleeve_tshirt'}
		<a href="/catalog/category,{$filters.category}/{if $filters.sex!='male'}{$filters.sex}/{/if}new/" rel="nofollow" class="category">
			{if $filters.category=='futbolki'}Футболка{else if $filters.category=='sweatshirts'}
			Свитшот{else if $filters.category=='longsleeve_tshirt'}Футболка с длинным рукавом{else if $filters.category=='tolstovki'}Толстовка{else if $filters.category=='mayki-alkogolichki'}Майка{/if}			
			{if $filters.sex=='male'}мужская{else if $filters.sex=='female'}женская{else if $filters.sex=='kids'}детская{/if}
		</a>
	{else}	
		<a href="/catalog/{if $style_slug == 'case-iphone-4' || $style_slug == 'case-iphone-5' || $style_slug == 'case-iphone-6' || $style_slug == 'case-iphone-6-plus' || $style_slug == 'case-iphone-4-glossy' || $style_slug == 'case-iphone-5-glossy' || $style_slug == 'case-iphone-6-glossy' || $style_slug == 'case-iphone-6-plus-glossy' || $style_slug == 'case-ipad-mini'}cases{else}{$filters.category}{/if}/{$style_slug}/new/" rel="nofollow" class="category">
			{if $style_slug == 'case-iphone-4'} 
			Чехол на iPhone 4 матовый
			{else if $style_slug == 'case-iphone-4-glossy'}
			Чехол на iPhone 4 глянцевый
			{else if $style_slug == 'case-iphone-5'}
			Чехол на iPhone 5 матовый
			{else if $style_slug == 'case-iphone-5-glossy'}
			Чехол на iPhone 5 глянцевый
			{else if $style_slug == 'case-iphone-6'}
			Чехол на iPhone 6 матовый
			{else if $style_slug == 'case-iphone-6-glossy'}
			Чехол на iPhone 6 глянцевый
			{else if $style_slug == 'case-iphone-6-plus'}
			Чехол на iPhone 6 plus матовый
			{else if  $style_slug == 'case-iphone-6-plus-glossy'}
			Чехол на iPhone 6 plus глянцевый
			{else if $style_slug == 'case-ipad-mini'}
			Чехол на iPad mini
			{else if $style_slug == 'case-galaxy-s5'}
			Чехол на Samsung Galaxy S5 матовый
			{else if $style_slug == 'iphone-5-bumper'}
			Бампер на iPhone 5, 5s
			{else if $style_slug == 'iphone-6-bumper'}
			Бампер на iPhone 6
			{else if $category == 'phones'}
			Наклейка на телефоны
			{else if $category == 'touchpads'}
			Наклейка на планшет
			{else if $category == 'laptops'}
			Наклейка на ноутбук
			{else if $category == 'ipodmp3'}
			Наклейка на плеер
			{/if}
			
			{if $category == 'moto'}
			Наклейка на мотоциклы
			{else if $category == 'cup'}
			Наклейка на кружки
			{else if $category == 'boards'}
			Наклейка на доски
			{else if $category == 'poster'}
			Постеры
			{else if $task == 'auto' || $task == '1color'}
			Наклейка на автомобиль
			{/if}
		</a>
	{/if}
	
	
	{* if $filters.category}<a href="/search/?q={$SEARCH}" rel="nofollow" title="искать везде" class="everywhere"></a>{/if *}
</div>