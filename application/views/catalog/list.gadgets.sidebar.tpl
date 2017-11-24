<style>
	.sidebar-links { float:left; margin-top:21px; padding-left:34px; width:157px; }
	.sidebar-links ul { list-style-type:none; }
	.sidebar-links ul li { /*height:42px;*/ margin: 0 0 11px;font-weight: bolder; color:#00A851 !important }
	.sidebar-links a { font-weight: normal; }
</style>
<div class="sidebar-links">
	<ul>
		{*<li>{if $TAG.slug != '8marta'}<a class="f-item f-item" style="margin-bottom:5px;color:red" href="/{$module}/{$category}/tag/8marta/" rel="nofollow">8 марта</a>{else}8 марта{/if}</li>*}	
   		
		{*<li>{if $TAG.slug != 'novuygod'}<a href="/{$module}/{$category}/tag/novuygod/" rel="nofollow" style="color:red">Новый год</a>{else}Новый год{/if}</li>*}

		<li>{if !$TAG && ($orderBy == "popular" || $orderBy == "")}Популярные{else}<a href="/{$module}/{$category}/{if $category_style != $category_def_style}{$style_slug}/{/if}">Популярные</a>{/if}</li>
		<li>{if !$TAG && $orderBy == "new"}Новинки{else}<a href="/{$module}/{$category}/{if $category_style != $category_def_style}{$style_slug}/{/if}new/">Новинки</a>{/if}</li>
		<li>{if !$TAG && $orderBy == "grade"}По оценке{else}<a href="/{$module}/{$category}/{if $category_style != $category_def_style}{$style_slug}/{/if}grade/">По оценке</a>{/if}</li>

		<li>{if $task == 'top'}Популярные за 10 лет{else}<a class="f-itemf-item" href="/catalog/{$category}/{$style_slug}/top/" style="font-weight:normal">Популярные за 10 лет</a>{/if}</li>

		{*<li>{if $TAG.slug != 'voennue'}<a class="f-item f-item" href="/{$module}/{$category}/tag/voennue/" rel="nofollow">23 февраля</a>{else}23 февраля{/if}</li>*}		
		{*<li>{if $TAG.slug != 'dlya_vlublennih'}<a class="f-item f-item" href="/{$module}/{$category}/tag/dlya_vlublennih/{if $category_style != $category_def_style}{$style_slug}/{/if}">Для влюблённых</a>{else}Для влюблённых{/if}</li>*}
	
		<li><a class="f-item filter-red1" style="font-weight:normal; font-size:12px" href="/stickermize/style,315/#back" target="_blank" rel="nofollow">Заказать чехол для iPhone 4s,4 с моей картинкой</a></li>
		
		<li>{if $TAG.slug != 'devushka'}<a href="/{$module}/{$category}/{if $category_style != $category_def_style}{$style_slug}/{/if}tag/devushka/">Девушки</a>{else}Девушки{/if}</li>
		<li>{if $TAG.slug != 'tekstura'}<a href="/{$module}/{$category}/{if $category_style != $category_def_style}{$style_slug}/{/if}tag/tekstura/">Текстура</a>{else}Текстура{/if}</li>
		<li>{if $TAG.slug != 'personagi'}<a href="/{$module}/{$category}/{if $category_style != $category_def_style}{$style_slug}/{/if}tag/personagi/">Персонажи</a>{else}Персонажи{/if}</li>
		<li>{if $TAG.slug != 'zayac'}<a href="/{$module}/{$category}/{if $category_style != $category_def_style}{$style_slug}/{/if}tag/zayac/">Зайцы</a>{else}Зайцы{/if}</li>
		<li>{if $TAG.slug != 'detskie'}<a href="/{$module}/{$category}/{if $category_style != $category_def_style}{$style_slug}/{/if}tag/detskie/">Детские</a>{else}Детские{/if}</li>
		<li>{if $TAG.slug != 'grafika'}<a href="/{$module}/{$category}/{if $category_style != $category_def_style}{$style_slug}/{/if}tag/grafika/">Графика</a>{else}Графика{/if}</li>
		<li>{if $TAG.slug != 'kosmos'}<a href="/{$module}/{$category}/{if $category_style != $category_def_style}{$style_slug}/{/if}tag/kosmos/">Космос</a>{else}Космос{/if}</li>
		<li>{if $TAG.slug != 'fotografiya'}<a href="/{$module}/{$category}/{if $category_style != $category_def_style}{$style_slug}/{/if}tag/fotografiya/">Фотография</a>{else}Фотография{/if}</li>
        
        <li><a class="f-item" id="filter-" href="/catalog/authors/" rel="nofollow" style=" font-style:italic; color:#339966; font-size:14px">Все авторы</a></li>
		<li><a class="f-item" id="filter-" href="/catalog/tags/" rel="nofollow" style=" font-style:italic;color:#339966; font-size:14px;float: none;">Все теги</a> ...</li>
        
        <li><a class="f-item filter-red" style="font-weight:normal" href="/stickermize/style,224/#back" target="_blank" rel="nofollow">Конструктор наклеек</a></li>
        		
	</ul>

	<div class="top10tags">
		<div class="wrap">
		{foreach from=$tags item="t"}
			<a class="tag" rel="nofollow" href="/{$module}/{$category}/tag/{$t.slug}/">{$t.name}</a>
		{/foreach}
		</div>
	</div>
	<br clear="all" /><br />
	
	{if $TAG.details_value_more}
    <div style="background:#ccc; padding:10px">
		<div style="color:white; margin-top:-5px; margin-bottom:-5px; float:left;width:100px;cursor:pointer " onclick="swichBlockVisible('catalog-block', this)">подробно</div>
		<!--div style="width:30px;line-height:15px;color:#fff;font-size:150%;cursor:pointer" class="right center">+</div-->
		<div class="clr"></div>
	</div>
	{/if}
</div>