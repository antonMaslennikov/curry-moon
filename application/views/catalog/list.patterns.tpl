<script type="text/javascript"> 
	var countElements = {if $count}{$count}{else}0{/if};
	var pageLoaded = {$page};
	var REQUEST_URI = '{$PAGE_URI}';
	var list_elements = '.list_wrap ul li.m13';
	var autoscrol_count = 0;
	
	$(document).ready(function() { 			
		//инициализируем автоподгрузку страниц
		$(window).bind('scroll', pScroll);
	});
</script>

{if $goods|count > 0}
	{include file="catalog/list.fix.menu.tpl"}
{/if}	

<div class="b-catalog_v2 moveSidebarToLeft patterns">

	<div class="pageTitle table">
		
		<h1 {if !$MobilePageVersion && ($user || $SEARCH)}style="width:747px"{/if}>{if $SEARCH}{include file="catalog/list.search-filter.tpl"}{else}{if $H1}{$H1}{else}{if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}{/if}{/if}</h1>

		{if !$user && !$SEARCH && !$MobilePageVersion}
			{* <img title="Запечатка со всех сторон. Печать и пошив 7 раб. дней" alt="Запечатка со всех сторон" class="bann" src="/images/patterns/small-banner-paterns3.gif" width="463" height="39"/> *}

			{* <a href="/tag/petux/{if $filters.category == 'futbolki' || $filters.category == 'tolstovki' || $filters.category == 'sweatshirts' || $filters.category == 'longsleeve_tshirt'}category,{/if}{$filters.category}{if $filters.size && !$fsize_not_selected};size,{$filters.size}{/if}{if $filters.color && !$fcolor_not_selected};color,{$filters.color}{/if}/{if $style_slug}{$style_slug}/{/if}{if $filters.sex == 'female' || $filters.sex == 'kids'}{$filters.sex}/{/if}" title="Новый год" rel="nofollow" id="rooster"></a> *}
			{* <a href="/tag/novuygod/{if $filters.category == 'futbolki' || $filters.category == 'tolstovki' || $filters.category == 'sweatshirts' || $filters.category == 'longsleeve_tshirt'}category,{/if}{$filters.category}{if $filters.size && !$fsize_not_selected};size,{$filters.size}{/if}{if $filters.color && !$fcolor_not_selected};color,{$filters.color}{/if}/{if $style_slug}{$style_slug}/{/if}{if $filters.sex == 'female' || $filters.sex == 'kids'}{$filters.sex}/{/if}" title="Новый год" rel="nofollow" id="elka2016"></a> *}
			{* <a href="/catalog/{if $filters.category == 'futbolki' || $filters.category == 'tolstovki' || $filters.category == 'sweatshirts' || $filters.category == 'longsleeve_tshirt'}category,{/if}{$filters.category}{if $filters.size && !$fsize_not_selected};size,{$filters.size}{/if}{if $filters.color && !$fcolor_not_selected};color,{$filters.color}{/if}/{if $style_slug}{$style_slug}/{/if}{if $filters.sex == 'female' || $filters.sex == 'kids'}{$filters.sex}/{/if}top2016/" title="Популярные в 2016 году" rel="nofollow" id="top2016"></a> *}
			
			{*<a href="/8march/{if $filters.category != 'poster'}category,{/if}{$filters.category}{if $filters.size && !$fsize_not_selected};size,{$filters.size}{/if}{if $filters.color && !$fcolor_not_selected};color,{$filters.color}{/if}/{if $filters.sex == 'female'}female/{/if}{if $style_slug}{$style_slug}/{/if}" title="8 марта" rel="nofollow" id="marta8"></a>*}
			{*<a href="/23february/{if $filters.category != 'poster'}category,{/if}{$filters.category}{if $filters.size && !$fsize_not_selected};size,{$filters.size}{/if}{if $filters.color && !$fcolor_not_selected};color,{$filters.color}{/if}/{if $filters.sex == 'female'}female/{/if}{if $style_slug}{$style_slug}/{/if}" title="23 февраля" rel="nofollow" id="feb23"></a>*}
			{*<a href="/14february/{if $filters.category != 'poster'}category,{/if}{$filters.category}{if $filters.size && !$fsize_not_selected};size,{$filters.size}{/if}{if $filters.color && !$fcolor_not_selected};color,{$filters.color}{/if}/{if $filters.sex == 'female'}female/{/if}{if $style_slug}{$style_slug}/{/if}" title="14 февраля" rel="nofollow" id="feb14"></a>*}
			
		{/if}
		
		{include file="catalog/list.collections.tpl"}
		
		<div class="b-filter_tsh">
			<a href="/{if $TAG && $module == 'tag'}{$module}/{$TAG.slug}{else}catalog{/if}/{$Style->category}/{$Style->style_slug}/new/" title="{$L.GOOD_new}" rel="nofollow" class="new-filter {if $orderBy == 'new'}active{/if}">{*Новое*}</a>
			<a href="/{if $TAG && $module == 'tag'}{$module}/{$TAG.slug}{else}catalog{/if}/{$Style->category}/{$Style->style_slug}/" rel="nofollow" class="pop-filter {if !$orderBy || $orderBy == 'popular'}active{/if}" title="{$L.GOOD_popular}">{*Популярное*}</a>
			<a href="/{if $TAG && $module == 'tag'}{$module}/{$TAG.slug}{else}catalog{/if}/{$Style->category}/{$Style->style_slug}/grade/" rel="nofollow" class="score-filter {if $orderBy == 'grade'}active{/if}" title="{$L.GOOD_rating}"></a>
		</div>

	</div>
	
	{if !$MobilePageVersion}
		<!-- Фильтр в левом сайдбаре -->
		{include file="catalog/list.sidebar.tpl"}
	{/if}

	<div class="catalog_goods_list patterns">
		<div class="list_wrap">		
			<ul>
				{if $PAGE->reqUrl.2 == ''}
				<li class="m13">
					<a rel="nofollow" class="item" href="#">
						<span class="list-img-wrap">				
							<img src="/images/patterns/item25.jpg" "765" height="353">
						</span>
					</a>					
					<div class="item">													
						{if $USER->authorized && ($USER->id == $user_id || $USER->id == 27278 || $USER->id == 6199 || $USER->id == 52426 || $USER->id == 105091)}
							<a class="btn_editing" href="/senddrawing.pattern/{$g.good_id}/"></a>
						{/if}
						<!--noindex-->
						<span  class="price">1600&nbsp;руб.</span>							
						<!--/noindex-->	
						<a href="#" class="title">Название категории</a>							
						<!--noindex-->
						<a href="#" rel="nofollow" class="mini-avatar" title=""><img src="/J/avatars/avatar_f6be71_tbn_25.gif" width="25" height="25" class="avatar"></a>
						<span class="author">							
							<a rel="nofollow" href="#">автор</a><img src="http://www.maryjane.ru/images/dlp/designer_level-2.gif" border="0" title="6" alt="6" class="dl"></span>
						<div class="autor-city">Город</div>
						<!--/noindex-->
						<div style="clear:both"></div>						
					</div>
					{if $USER->id == 27278 || $USER->id == 6199 || $USER->id == 105091}
						<div class="r-m12-menu">
							<a href="#" rel="nofollow" class="edittags" _id="{$g.good_id}">теги</a>
							<span>|</span>
							<a href="#" class="hideDesign" _id="{$g.good_id}" _style="{$Style->id}" rel="nofollow">{if $g.hidden}показать{else}скрыть{/if}</a>
							<span>|</span>
							<a href="#" class="disableDesign" _id="{$g.good_id}"_style="{$Style->id}" _category="{$filters.category}" rel="nofollow">{if $g.hidden}вкл.{else}выкл.{/if}</a>
							{if $g.good_status == 'archived'}
							<span>|</span>
							<a href="#" class="promote2" _id="{$g.good_id}" _to="pretendent" rel="nofollow">победитель</a>
							{/if}
							{if $g.good_status == 'pretendent'}
							<span>|</span>
							<a href="#" class="promote2" _id="{$g.good_id}" _to="archived" rel="nofollow">архив</a>
							{/if}
							
							<span>|</span>
							<a href="/ajax/setsex/" title="" class="setsex" data-sex="male" _id="{$g.good_id}" rel="nofollow">муж.</a>
							<span>|</span>
							<a href="/ajax/setsex/" title="" class="setsex" data-sex="female" _id="{$g.good_id}" rel="nofollow">жен.</a>
							<span>|</span>
							<a href="/ajax/setsex/" title="" class="setsex" data-sex="kids" _id="{$g.good_id}" rel="nofollow">дет.</a>
							
							
							{* 
							<span>|</span>
							<a href="/ajax/setsex/" class="setsex" data-sex="{$g.good_sex}" _id="{$g.good_id}" rel="nofollow">
								{if $g.good_sex == 'male'}
									муж.
								{elseif $g.good_sex == 'female'}
									жен.
								{elseif $g.good_sex == 'kids'}
									дет.
								{else}
									пол1
								{/if}
							</a>
							<span>|</span>
							<a href="/ajax/setsexalt/" class="setsex" data-sex="{$g.good_sex_alt}" _id="{$g.good_id}" rel="nofollow">
								{if $g.good_sex_alt == 'male'}
									муж.
								{elseif $g.good_sex_alt == 'female'}
									жен.
								{elseif $g.good_sex_alt == 'kids'}
									дет.
								{else}
									пол2
								{/if}
							</a>
							*}
						</div>
					{/if}
				</li>
				{/if}
				
				{if $SEARCH && $goods|count == 0}
					<p style="font-size:16px;padding:30px">{$L.SHOPWINDOW_nothing_is_found}. <a href="/search/?q={$SEARCH}" rel="nofollow">Поискать везде?</a></p>
					{include file="catalog/list.trackUser.out_of_media.tpl"}
				{elseif $TAG && $goods|count == 0}
					<p style="padding:30px">{$L.SHOPWINDOW_nothing_is_found}. <a href="/tag/{$TAG.slug}/">Поискать везде?</a></p>
					{include file="catalog/list.trackUser.out_of_media.tpl"}
				{elseif $user && $goods|count == 0}	
					<p style="font-size:16px;padding:30px">У дизайнера <a href="/catalog/{$user.user_login}/" rel="nofollow">{$user.user_login}</a> нет работ с полной запечаткой на данном носителе. <a href="/catalog/patterns/{$Style->style_slug}/">Посмотреть работы других авторов</a></p>
				{/if}
				
				{if $USER->meta->mjteam == "super-admin" && $TAG}
				<div style="font-size: 11px;border:1px dashed orange;margin-bottom:20px;padding:8px;color:orange;width: calc(100% - 27px)">
					Специально для тебя, Сергей!<br />
					1. Если тег "{$TAG.name}" входит в коллецию<br />
					2. То по нашему плану, то нужно выводить все работы с тегами из этой колекции:
					{foreach from=$tags_collection name="tcf" item="t"}
					{$t.title}{if !$smarty.foreach.tfc.last}, {/if}
					{/foreach}
				</div>
				{/if}
				
				{include file="catalog/list.goods.patterns.tpl"}
				
			</ul>
			
			{*постраничка*}	
			{include file="catalog/list.pagePagination.tpl"}
		</div>
	</div>
	
	{if !$user_id}
		<!-- Кнопка ВВерх -->
		<div id="button_toTop"><div>{$L.SHOPWINDOW_top}</div></div>
	{/if}
</div>

{if !$user_id}
	<br clear="all" />
	<div class="list_seotexts">
	{include file="catalog/list.seotexts.tpl"}
	</div>
{/if}

<div class="wrap-quick-buy-bg" style="display: none;"></div>
<div class="wrap-quick-buy" style="display: none;">
	<a href="#" rel="nofollow" class="close-popup" title="Завершить"></a>
	<div class="quick-buy">
		<div class="goods-wrap clearfix">

			<div class="select-size-box" style="padding: 25px 0">
				
				<input type="hidden" name="quick_buy_good_id" />
				
				<div style="margin-bottom: 15px;">Выберите размер:</div>
				{foreach from=$style_sizes item="s"}
				<a class="one-size" quantity="{$s.quantity}" id="size-item2" data-price="{$s.price}" data-stock_id="{$s.good_stock_id}" data-size="{$s.size_name}" name="{$s.size_id}" href="#" rel="nofollow">{$s.size_rus}<br><span>{$s.size_name}</span></a>
				{/foreach}
			</div>	

		</div>
	</div>
</div>