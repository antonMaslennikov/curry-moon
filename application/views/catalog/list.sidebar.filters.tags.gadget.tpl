<div class="tags_inc" >

	{if !$TAG && !$HOT && $orderBy == 'new'}<span class="tag activ">новинки</span>{else}<a rel="nofollow" class="tag" href="/catalog/{$filters.category}/{if $style_slug != ''}{$style_slug}/{/if}new/">новинки</a>{/if}
	
	{if !$TAG && !$top && ($orderBy == 'popular' || $orderBy == '')}<span class="tag activ">популярные</span>{else}<a rel="nofollow" class="tag" href="/catalog/{$filters.category}/{if $style_slug != ''}{$style_slug}/{/if}">популярные</a>{/if}	
	
	{if !$TAG && !$HOT && $orderBy == 'win_date'}<span class="tag activ">победители</span>{else}<a rel="nofollow" class="tag" href="/catalog/{$filters.category}/{if $style_slug != ''}{$style_slug}/{/if}winners/">победители</a>{/if}
	
	<a rel="nofollow" class="tag {if $top}activ{/if}" href="/catalog/top/{$filters.category}/{if $style_slug != ''}{$style_slug}/{/if}{if $filters.size && !$fsize_not_selected}size,{$filters.size}/{/if}">лучшее за 10 лет</a>
	
	{*<a rel="nofollow" class="tag {if $TAG.slug == 'odnocvet'}activ{/if}" href="/tag/odnocvet/{$filters.category}/{if $style_slug != ''}{$style_slug}/{/if}{if $filters.size && !$fsize_not_selected}size,{$filters.size}/{/if}">одноцвет</a>*}

	{if $TAG.slug == 'givotnue'}<span class="tag activ">животные</span>{else}<a rel="nofollow" class="tag" href="/tag/givotnue/{$filters.category}/{if $style_slug != ''}{$style_slug}/{/if}{if $filters.size && !$fsize_not_selected}size,{$filters.size}/{/if}">животные</a>{/if}
	{if $TAG.slug == 'koshka'}<span class="tag activ">кошка</span>{else}<a rel="nofollow" class="tag" href="/tag/koshka/{$filters.category}/{if $style_slug != ''}{$style_slug}/{/if}{if $filters.size && !$fsize_not_selected}size,{$filters.size}/{/if}">кошка</a>{/if}
	{if $TAG.slug == 'nadpis_'}<span class="tag activ">надпись</span>{else}<a rel="nofollow" class="tag" href="/tag/nadpis_/{$filters.category}/{if $style_slug != ''}{$style_slug}/{/if}{if $filters.size && !$fsize_not_selected}size,{$filters.size}/{/if}">надпись</a>{/if}
	{if $TAG.slug == 'monsters'}<span class="tag activ">монстры</span>{else}<a rel="nofollow" class="tag" href="/tag/monsters/{$filters.category}/{if $style_slug != ''}{$style_slug}/{/if}{if $filters.size && !$fsize_not_selected}size,{$filters.size}/{/if}">монстры</a>{/if}
	{if $TAG.slug == 'dlya_vlublennih'}<span class="tag activ">для влюблённых</span>{else}<a rel="nofollow" class="tag" href="/tag/dlya_vlublennih/{$filters.category}/{if $style_slug != ''}{$style_slug}/{/if}{if $filters.size && !$fsize_not_selected}size,{$filters.size}/{/if}">для влюблённых</a>{/if}
	{if $TAG.slug == 'grafika'}<span class="tag activ">графика</span>{else}<a rel="nofollow" class="tag" href="/tag/grafika/{$filters.category}/{if $style_slug != ''}{$style_slug}/{/if}{if $filters.size && !$fsize_not_selected}size,{$filters.size}/{/if}">графика</a>{/if}
	{if $TAG.slug == 'music'}<span class="tag activ">музыка</span>{else}<a rel="nofollow" class="tag" href="/tag/music/{$filters.category}/{if $style_slug != ''}{$style_slug}/{/if}{if $filters.size && !$fsize_not_selected}size,{$filters.size}/{/if}">музыка</a>{/if}
	{if $TAG.slug == 'personagi'}<span class="tag activ">персонажи</span>{else}<a rel="nofollow" class="tag" href="/tag/personagi/{$filters.category}/{if $style_slug != ''}{$style_slug}/{/if}{if $filters.size && !$fsize_not_selected}size,{$filters.size}/{/if}">персонажи</a>{/if}
	{if $TAG.slug == 'kosmos'}<span class="tag activ">космос</span>{else}<a rel="nofollow" class="tag" href="/tag/kosmos/{$filters.category}/{if $style_slug != ''}{$style_slug}/{/if}{if $filters.size && !$fsize_not_selected}size,{$filters.size}/{/if}">космос</a>{/if}
	
	<a rel="nofollow" class="tag" href="/catalog/tags/">все теги</a>
</div>
		