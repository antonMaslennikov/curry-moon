<div class="tags_inc">

	{if !$TAG && !$HOT && ($orderBy == 'new' || $orderBy == '')}<span class="tag activ">новинки</span>{else}<a rel="nofollow" class="tag" href="/catalog/{if $filters.category}{if $filters.category != 'poster' && $filters.category != 'auto'}category,{/if}{$filters.category}/{/if}{if $filters.sex == 'female'}female/{/if}new/">новинки</a>{/if}

	{if !$TAG && $orderBy == 'popular' && $task != 'winners' && !$top}<span class="tag activ">популярные</span>{else}<a rel="nofollow" class="tag" href="/catalog/{if $filters.category}{if $filters.category != 'poster' && $filters.category != 'auto'}category,{/if}{$filters.category}/{/if}{if $filters.sex == 'female'}female/{/if}">популярные</a>{/if}
	
	{if !$TAG && !$HOT && $orderBy == 'win_date'}<span class="tag activ">победители</span>{else}<a rel="nofollow" class="tag" href="/catalog/{if $filters.category}{if $filters.category != 'poster' && $filters.category != 'auto'}category,{/if}{$filters.category}/{/if}{if $filters.sex == 'female'}female/{/if}winners/">победители</a>{/if}
	
	<a rel="nofollow" class="tag {if $top}activ{/if}" href="/catalog/top/{if $filters.category}{if $filters.category != 'poster'}category,{/if}{$filters.category}{if $filters.size && !$fsize_not_selected};size,{$filters.size}{/if}{if $filters.color && !$fcolor_not_selected};color,{$filters.color}{/if}/{/if}{if $filters.sex == 'female'}female/{/if}">лучшее за 10 лет</a>
	
	{*<a rel="nofollow" class="tag {if $TAG.slug == 'odnocvet'}activ{/if}" href="/tag/odnocvet/{if $filters.category}{if $filters.category != 'poster'}category,{/if}{$filters.category}{if $filters.size && !$fsize_not_selected};size,{$filters.size}{/if}{if $filters.color && !$fcolor_not_selected};color,{$filters.color}{/if}/{/if}{if $filters.sex == 'female'}female/{/if}">одноцвет</a>*}
	
	{*<a rel="nofollow" class="tag {if $TAG.slug == 'odnocvet'}activ{/if}" href="/odnocvet/">mjforall одноцвет</a>*}

	{*<a rel="nofollow" class="tag"  href="/catalog/authors/">все авторы</a>
	<a rel="nofollow" class="tag" href="/tag/detskie/">детские</a>*}
	
	{if $TAG.slug == 'givotnue'}<span class="tag activ">животные</span>{else}<a  rel="nofollow"class="tag" href="/tag/givotnue/{if $filters.category}{if $filters.category != 'poster'}category,{/if}{$filters.category}{if $filters.size && !$fsize_not_selected};size,{$filters.size}{/if}{if $filters.color && !$fcolor_not_selected};color,{$filters.color}{/if}/{/if}{if $filters.sex == 'female'}female/{/if}">животные</a>{/if}
	{if $TAG.slug == 'koshka'}<span class="tag activ">кошка</span>{else}<a rel="nofollow" class="tag" href="/tag/koshka/{if $filters.category}{if $filters.category != 'poster'}category,{/if}{$filters.category}{if $filters.size && !$fsize_not_selected};size,{$filters.size}{/if}{if $filters.color && !$fcolor_not_selected};color,{$filters.color}{/if}/{/if}{if $filters.sex == 'female'}female/{/if}">кошка</a>{/if}	
	{if $TAG.slug == 'nadpis_'}<span class="tag activ">надпись</span>{else}<a rel="nofollow" class="tag" href="/tag/nadpis_/{if $filters.category}{if $filters.category != 'poster'}category,{/if}{$filters.category}{if $filters.size && !$fsize_not_selected};size,{$filters.size}{/if}{if $filters.color && !$fcolor_not_selected};color,{$filters.color}{/if}/{/if}{if $filters.sex == 'female'}female/{/if}">надпись</a>{/if}
	{if $TAG.slug == 'monsters'}<span class="tag activ">монстры</span>{else}<a rel="nofollow" class="tag" href="/tag/monsters/{if $filters.category}{if $filters.category != 'poster'}category,{/if}{$filters.category}{if $filters.size && !$fsize_not_selected};size,{$filters.size}{/if}{if $filters.color && !$fcolor_not_selected};color,{$filters.color}{/if}/{/if}{if $filters.sex == 'female'}female/{/if}">монстры</a>{/if}
	{if $TAG.slug == 'dlya_vlublennih'}<span class="tag activ">для влюблённых</span>{else}<a rel="nofollow" class="tag" href="/tag/dlya_vlublennih/{if $filters.category}{if $filters.category != 'poster'}category,{/if}{$filters.category}{if $filters.size && !$fsize_not_selected};size,{$filters.size}{/if}{if $filters.color && !$fcolor_not_selected};color,{$filters.color}{/if}/{/if}{if $filters.sex == 'female'}female/{/if}">для влюблённых</a>{/if}
	{if $TAG.slug == 'grafika'}<span class="tag activ">графика</span>{else}<a rel="nofollow" class="tag" href="/tag/grafika/{if $filters.category}{if $filters.category != 'poster'}category,{/if}{$filters.category}{if $filters.size && !$fsize_not_selected};size,{$filters.size}{/if}{if $filters.color && !$fcolor_not_selected};color,{$filters.color}{/if}/{/if}{if $filters.sex == 'female'}female/{/if}">графика</a>{/if}
	{if $TAG.slug == 'music'}<span class="tag activ">музыка</span>{else}<a rel="nofollow" class="tag" href="/tag/music/{if $filters.category}{if $filters.category != 'poster'}category,{/if}{$filters.category}{if $filters.size && !$fsize_not_selected};size,{$filters.size}{/if}{if $filters.color && !$fcolor_not_selected};color,{$filters.color}{/if}/{/if}{if $filters.sex == 'female'}female/{/if}">музыка</a>{/if}
	{if $TAG.slug == 'personagi'}<span class="tag activ">персонажи</span>{else}<a rel="nofollow" class="tag" href="/tag/personagi/{if $filters.category}{if $filters.category != 'poster'}category,{/if}{$filters.category}{if $filters.size && !$fsize_not_selected};size,{$filters.size}{/if}{if $filters.color && !$fcolor_not_selected};color,{$filters.color}{/if}/{/if}{if $filters.sex == 'female'}female/{/if}">персонажи</a>{/if}
	{if $TAG.slug == 'kosmos'}<span class="tag activ">космос</span>{else}<a rel="nofollow" class="tag" href="/tag/kosmos/{if $filters.category}{if $filters.category != 'poster'}category,{/if}{$filters.category}{if $filters.size && !$fsize_not_selected};size,{$filters.size}{/if}{if $filters.color && !$fcolor_not_selected};color,{$filters.color}{/if}/{/if}{if $filters.sex == 'female'}female/{/if}">космос</a>{/if}
	
	<a rel="nofollow" class="tag" href="/catalog/tags/">все теги</a>

</div>
		