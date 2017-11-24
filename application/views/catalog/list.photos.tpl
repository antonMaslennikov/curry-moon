<script type="text/javascript"> 
	var countElements = {if $count}{$count}{else}0{/if};
	var pageLoaded = {$page};
	var REQUEST_URI = '{$PAGE_URI}';
	{if $page > 1}var autoscrol_count = 0;{/if}
		
	$(document).ready(function() { 			
		//инициализируем автоподгрузку страниц
		$(window).bind('scroll', pScroll);
	});
</script>

{literal}
<style>
	.author_username {float:none;}
	.selected51no {background:url(/images/to-vote-min-gray.png) no-repeat 0 -22px !important;}
	.selected51no:hover {background:url(/images/to-vote-min-gray.png) no-repeat 0 -22px !important;}
	.selected51 {background:url(/images/to-vote-min.png) no-repeat 0 -22px !important;}
	.selected51:hover {background:url(/images/to-vote-min.png) no-repeat 0 -22px !important;}
	.allpager {display:none;}
</style>
{/literal}

{if ($module=="catalog" || $TAG || $SEARCH)  && $goods|count > 0 && $TAG.slug != "parnie_futbolki"}
	{if $category != 'sumki'  && $category != 'bag' && $filters.category != 'patterns-bag'}
		{include file="catalog/list.fix.menu.tpl"}
	{/if}
	
	{if $MobilePageVersion}
		{include file="catalog/list.mobile.menu.tpl"}
	{/if}
{/if}


{if ($USER->id == 105091 || $USER->id == 6199 || $USER->id == 27278) && ($category == 'sumki' ||  $category == 'bag') && $goods|count > 0}
	{include file="catalog/list.fix.menu.tpl"}
	<style>.fix-menu{ border-bottom:2px dashed orange } {if $user}.fix-menu .body-tags { visibility:hidden }{/if}</style>	
{/if}	
	
<div class="b-catalog_v2 moveSidebarToLeft {if $user && !$filters.category} {else}b-catalog_v3{/if}">
	
	{if !$MobilePageVersion && $SEARCH}
		<div class="tabz clearfix">
			<a href="#" rel="nofollow" class="active">{$L.SHOPWINDOW_catalog}</a>
			<a href="/voting/competition/main/?q={$SEARCH}#goods" rel="nofollow">голосование</a>		
			<a href="/search/archived/?q={$SEARCH}" rel="nofollow" {if $filters.good_status == "archived"}class="active"{/if}>{$L.SHOPWINDOW_archive}</a>
			<a href="/blog/search/?q={$SEARCH}" rel="nofollow">{$L.SHOPWINDOW_blogs}</a>
			<a href="/faq/search/?q={$SEARCH}" rel="nofollow">f.a.q</a>
		</div>
	{/if}
	
	<div class="pageTitle table clearfix">
		
		{if $SEARCH}
			<h1>{include file="catalog/list.search-filter.tpl"}</h1>
		{else if $PAGE->utitle}
			<h1>{if $H1}{$H1}{else}{if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}{/if}</h1>				
		{else}		
			<h1>
				{if $TAG && !$filters.category}
					{if $TAG.title}
						{$TAG.title}
					{else}
						Футболки {$TAG.name}
					{/if}
				{else}
					{$PAGE->title}
				{/if} 
			</h1>
		{/if}
	

		{if !$user && !$MobilePageVersion}
		
			{*<a href="/tag/petux/{if $filters.category == 'futbolki' || $filters.category == 'tolstovki' || $filters.category == 'sweatshirts' || $filters.category == 'longsleeve_tshirt'}category,{/if}{$filters.category}{if $filters.size && !$fsize_not_selected};size,{$filters.size}{/if}{if $filters.color && !$fcolor_not_selected};color,{$filters.color}{/if}/{if $style_slug}{$style_slug}/{/if}{if $filters.sex == 'female' || $filters.sex == 'kids'}{$filters.sex}/{/if}" title="Новый год" rel="nofollow" id="rooster"></a>*}
		
			{*<a href="/tag/novuygod/{if $filters.category == 'futbolki' || $filters.category == 'tolstovki' || $filters.category == 'sweatshirts' || $filters.category == 'longsleeve_tshirt'}category,{/if}{$filters.category}{if $filters.size && !$fsize_not_selected};size,{$filters.size}{/if}{if $filters.color && !$fcolor_not_selected};color,{$filters.color}{/if}/{if $style_slug}{$style_slug}/{/if}{if $filters.sex == 'female' || $filters.sex == 'kids'}{$filters.sex}/{/if}" title="Новый год" rel="nofollow" id="elka2016"></a>*}
			
			{*<a href="/catalog/{if $filters.category == 'futbolki' || $filters.category == 'tolstovki' || $filters.category == 'sweatshirts' || $filters.category == 'longsleeve_tshirt'}category,{/if}{$filters.category}{if $filters.size && !$fsize_not_selected};size,{$filters.size}{/if}{if $filters.color && !$fcolor_not_selected};color,{$filters.color}{/if}/{if $style_slug}{$style_slug}/{/if}{if $filters.sex == 'female' || $filters.sex == 'kids'}{$filters.sex}/{/if}top2016/" title="Популярные в 2016 году" rel="nofollow" id="top2016"></a>*}
				
			{*<a href="/catalog/{if $filters.category != 'poster'}category,{/if}{$filters.category}{if $filters.size && !$fsize_not_selected};size,{$filters.size}{/if}{if $filters.color && !$fcolor_not_selected};color,{$filters.color}{/if}/{if $filters.sex == 'female' || $filters.sex == 'kids'}{$filters.sex}/{/if}{if $style_slug}{$style_slug}/{/if}top2015/" title="Популярные в 2015 году" rel="nofollow" id="top2015"></a>*}
	
			{*<a href="/tag/pop-art/new/" title="pop-art" rel="nofollow" id="popArt"></a>*}
			
			{*<a href="/8march/{if $filters.category != 'poster'}category,{/if}{$filters.category}{if $filters.size && !$fsize_not_selected};size,{$filters.size}{/if}{if $filters.color && !$fcolor_not_selected};color,{$filters.color}{/if}/{if $filters.sex == 'female'}female/{/if}{if $style_slug}{$style_slug}/{/if}" title="8 марта" rel="nofollow" id="marta8"></a>*}
			{*<a href="/23february/{if $filters.category != 'poster'}category,{/if}{$filters.category}{if $filters.size && !$fsize_not_selected};size,{$filters.size}{/if}{if $filters.color && !$fcolor_not_selected};color,{$filters.color}{/if}/{if $filters.sex == 'female'}female/{/if}{if $style_slug}{$style_slug}/{/if}" title="23 февраля" rel="nofollow" id="feb23"></a>*}
			{*<a href="/14february/{if $filters.category != 'poster'}category,{/if}{$filters.category}{if $filters.size && !$fsize_not_selected};size,{$filters.size}{/if}{if $filters.color && !$fcolor_not_selected};color,{$filters.color}{/if}/{if $filters.sex == 'female'}female/{/if}{if $style_slug}{$style_slug}/{/if}" title="14 февраля" rel="nofollow" id="feb14"></a>*}
			
			{* <a href="/tag/lev/new/" title="Для львов" rel="nofollow" id="forLion">Для львов</a> *}
			
		{/if}

		
	    {if $user && ($USER->id == $user_id || $USER->id == 27278 || $USER->id == 6199 || $USER->id == 86455 || $USER->id == 105091)}
	       {include file="catalog/form_change_name_shop.tpl"}
	    {/if}
		
		{if !$MobilePageVersion}
			{if $SEARCH && $goods|count == 0}{else if $TAG.slug != "parnie_futbolki"}		
			<div class="b-filter_tsh">
				<a data-sort="new" href="{$base_link}/new/ajax/" rel="nofollow" class="new-filter {if $orderBy == 'new'}active{/if}" title="{$L.GOOD_new}"></a>
				<a data-sort="popular" href="{$base_link}/popular/ajax/" rel="nofollow" class="pop-filter {if $orderBy == 'popular' || $orderBy == ''}active{/if}" title="{$L.GOOD_popular}"></a>
				<a data-sort="grade" href="{$base_link}/grade/ajax/" rel="nofollow" class="score-filter {if $orderBy == 'grade'}active{/if}" title="{$L.GOOD_rating}"></a>
			</div>
			{/if}
		{/if}
		
		{if !$MobilePageVersion && $filters.category=='futbolki' && ($USER->id == 27278 ||$USER->id == 6199)}
		<span class="FAKEselectbox" style="border-bottom:1px dashed orange;position: absolute;right:0;top:-13px">
			<div class="select">
				<div class="text"></div>
				<b class="trigger"><i class="arrow"></i></b>
			</div>
			<div class="dropdown">
				<ul>
					<li class="aInside {if $orderBy == 'new'}selected{/if}"><a href="{$base_link}/new/">Новинки</a></li>
					<li class="aInside {if $orderBy == 'popular_by_category'}selected{/if}"><a href="{$base_link}/popular_by_category/">Лидеры продаж</a></li>
					<li class="aInside {if $orderBy == 'artdir'}selected{/if}"><a href="{$base_link}/artdir/">Выбор арт-директора</a></li>
					 
					<li><b>Лучшие работы по:</b></li>
					<li class="aInside {if $orderBy == 'avg_grade_designers'}selected{/if}"><a href="{$base_link}/avg_grade_designers/">Мнению дизайнеров</a></li>
					<li class="aInside {if $orderBy == 'rating'}selected{/if}"><a href="{$base_link}/rating/">Мнение Покупателей</a></li>
					<li class="aInside {if $orderBy == 'visits'}selected{/if}"><a href="{$base_link}/visits/">Кол-ву просмотров</a></li>
					 
					<li><b>Что еще посмотреть:</b></li>
					<li class="aInside {if $orderBy == 'recently_buy'}selected{/if}"><a href="{$base_link}/recently_buy/">Что покупали недавно</a></li>
					<li class="aInside {if $orderBy == 'recently_view'}selected{/if}"><a href="{$base_link}/recently_view/">Что недавно смотрели</a></li>
					<li class="aInside {if $orderBy == 'likes'}selected{/if}"><a href="{$base_link}/likes/">Количество лайков</a></li>
				</ul>
			</div>
		</span>		
		{/if}
			
		{include file="catalog/list.collections.tpl"}
		
		{if !$MobilePageVersion}
			{if $SEARCH && $goods|count == 0}{else if  $TAG.slug != "parnie_futbolki"}	
				{if $filters.category  && $filters.category!='bag' && $filters.category!='cup' && $filters.category != 'patterns-bag'}
					{literal}
						<style>
							.pageTitle .b-filter_tsh {right:65px!important;}					
						</style>
					{/literal}
					<div class="b-filter_view"><noindex>
						<a href="{$link}/ajax/" rel="nofollow" class="view-pipl  active" title=""></a>
						<a href="{$link}/ajax/preview/" rel="nofollow" class="view-other {if 1 == 2}active{/if}" title=""></a>
					</noindex></div>
				{/if}
			{/if}
		{/if}
		
		<noindex>
			<script>
				var urlViews = ["{$blink}|%sort%|/ajax/", "{$blink}|%sort%|/ajax/preview/"];
			</script>
		</noindex>
	</div>
	
	{if !$MobilePageVersion}
		<!-- Фильтр в левом сайдбаре -->
		{include file="catalog/list.sidebar.tpl"}
	{/if}
	
	<div class="catalog_goods_list {if ($filters.sex || $style_slug == 'sumka-s-polnoj-zapechatkoj' || $filters.category == 'textile') && $filters.category != 'poster'}list_trjapki{/if}">

		{if $TAG.slug == 'nadpis_' || $TAG.slug == 'english'}
		<div class="tabz clearfix BigNotees">
			<!--noindex-->
			<a href="{if $TAG.slug == 'nadpis_'}#{else}/tag/nadpis_/{if $Style}{$Style->category}/{$Style->style_slug}/{/if}{if $filters.size_name}{$filters.size_name}/{/if}{/if}" {if $TAG.slug == 'nadpis_'}class="active"{/if} rel="nofollow">На русском</a>	
			<a href="{if $TAG.slug == 'english'}#{else}/tag/english/{if $Style}{$Style->category}/{$Style->style_slug}/{/if}{if $filters.size_name}{$filters.size_name}/{/if}{/if}" {if $TAG.slug == 'english'}class="active"{/if} rel="nofollow">На английском</a>
			<!--/noindex-->
		</div>
		{/if}
	
		{if !$MobilePageVersion && $tags_collection && $filters.category && $filters.category != 'poster' && !$user}	
			<ul class="differentTag clearfix">
				{foreach from=$tags_collection item="tag"}
				<li {if $TAG.slug == $tag.slug}class="on"{/if}><a rel="nofollow" href="{$tag.link}">{$tag.title}</a></li>
				{/foreach}			
				{if $TAG.slug == "fotografiya"}
					<li><a href="/foto-na-futbolku/">Напечатать фото на футболку</a></li>
				{/if}			
			</ul>
		{/if}
	
		{if !$MobilePageVersion && $filters.category == 'poster' && !$user}
		<ul class="differentTag clearfix">
			<li {if $TAG.slug=='abstrakciya'}class="on"{/if}><a href="/tag/abstrakciya/poster/{if $style_slug}{$style_slug}/{/if}" title="абстракция" rel="nofollow">абстракция</a></li>
			<li {if $TAG.slug=='akvarel_'}class="on"{/if}><a href="/tag/akvarel_/poster/{if $style_slug}{$style_slug}/{/if}" title="акварель" rel="nofollow">акварель</a></li>
			<li {if $TAG.slug=='angel-i-d_yavol'}class="on"{/if}><a href="/tag/angel-i-d_yavol/poster/{if $style_slug}{$style_slug}/{/if}" title="ангел" rel="nofollow">ангел</a></li>			
			<li {if $TAG.slug=='angliya'}class="on"{/if}><a href="/tag/angliya/poster/{if $style_slug}{$style_slug}/{/if}" title="англия" rel="nofollow">англия</a></li>			
			<li {if $TAG.slug=='volk'}class="on"{/if}><a href="/tag/volk/poster/{if $style_slug}{$style_slug}/{/if}" title="волк" rel="nofollow">волк</a></li>
			<li {if $TAG.slug=='drakon'}class="on"{/if}><a href="/tag/drakon/poster/{if $style_slug}{$style_slug}/{/if}" title="дракон" rel="nofollow">дракон</a></li>			
			<li {if $TAG.slug=='eda'}class="on"{/if}><a href="/tag/eda/poster/{if $style_slug}{$style_slug}/{/if}" title="еда" rel="nofollow">еда</a></li>			
			<li {if $TAG.slug=='givopis_'}class="on"{/if}><a href="/tag/givopis_/poster/{if $style_slug}{$style_slug}/{/if}" title="живопись" rel="nofollow">живопись</a></li>			
			<li {if $TAG.slug=='indiya'}class="on"{/if}><a href="/tag/indiya/poster/{if $style_slug}{$style_slug}/{/if}" title="индия" rel="nofollow">индия</a></li>			
			<li {if $TAG.slug=='korabl_'}class="on"{/if}><a href="/tag/korabl_/poster/{if $style_slug}{$style_slug}/{/if}" title="корабль" rel="nofollow">корабль</a></li>			
			<li {if $TAG.slug=='english'}class="on"{/if}><a href="/tag/english/poster/{if $style_slug}{$style_slug}/{/if}" title="лондон" rel="nofollow">лондон</a></li>			
			<li {if $TAG.slug=='loshad_'}class="on"{/if}><a href="/tag/loshad_/poster/{if $style_slug}{$style_slug}/{/if}" title="лошадь" rel="nofollow">лошадь</a></li>			
			<li {if $TAG.slug=='cartoons'}class="on"{/if}><a href="/tag/cartoons/poster/{if $style_slug}{$style_slug}/{/if}" title="мультфильм" rel="nofollow">мультфильм</a></li>			
			<li {if $TAG.slug=='nadpis_'}class="on"{/if}><a href="/tag/nadpis_/poster/{if $style_slug}{$style_slug}/{/if}" title="надпись" rel="nofollow">надпись</a></li>				
			<li {if $TAG.slug=='motivaciya'}class="on"{/if}><a href="/tag/motivaciya/poster/{if $style_slug}{$style_slug}/{/if}" title="мотивация" rel="nofollow">мотивация</a></li>				
			<li {if $TAG.slug=='osen_'}class="on"{/if}><a href="/tag/osen_/poster/{if $style_slug}{$style_slug}/{/if}" title="осень" rel="nofollow">осень</a></li>			
			<li {if $TAG.slug=='wwf'}class="on"{/if}><a href="/tag/wwf/poster/{if $style_slug}{$style_slug}/{/if}" title="панда" rel="nofollow">панда</a></li>
			<li {if $TAG.slug=='peyzag'}class="on"{/if}><a href="/tag/peyzag/poster/{if $style_slug}{$style_slug}/{/if}" title="пейзаж" rel="nofollow">пейзаж</a></li>	
			<li {if $TAG.slug=='piksel_-art'}class="on"{/if}><a href="/tag/piksel_-art/poster/{if $style_slug}{$style_slug}/{/if}" title="пиксель" rel="nofollow">пиксель</a></li>
			<li {if $TAG.slug=='poceluy'}class="on"{/if}><a href="/tag/poceluy/poster/{if $style_slug}{$style_slug}/{/if}" title="поцелуй" rel="nofollow">поцелуй</a></li>			
			<li {if $TAG.slug=='priroda'}class="on"{/if}><a href="/tag/priroda/poster/{if $style_slug}{$style_slug}/{/if}" title="природа" rel="nofollow">природа</a></li>	
			<li {if $TAG.slug=='pticu'}class="on"{/if}><a href="/tag/pticu/poster/{if $style_slug}{$style_slug}/{/if}" title="птицы" rel="nofollow">птицы</a></li>	
			<li {if $TAG.slug=='rastenie'}class="on"{/if}><a  href="/tag/rastenie/poster/{if $style_slug}{$style_slug}/{/if}" title="растения" rel="nofollow">растения</a></li>			
			<li {if $TAG.slug=='robot'}class="on"{/if}><a href="/tag/robot/poster/{if $style_slug}{$style_slug}/{/if}" title="робот" rel="nofollow">робот</a></li>
			<li {if $TAG.slug=='samolet'}class="on"{/if}><a href="/tag/samolet/poster/{if $style_slug}{$style_slug}/{/if}" title="самолет" rel="nofollow">самолет</a></li>
			<li {if $TAG.slug=='slonu'}class="on"{/if}><a href="/tag/slonu/poster/{if $style_slug}{$style_slug}/{/if}" title="слон" rel="nofollow">слон</a></li>
			<li {if $TAG.slug=='cvetu'}class="on"{/if}><a href="/tag/cvetu/poster/{if $style_slug}{$style_slug}/{/if}" title="цветы" rel="nofollow">цветы</a></li>					
			<li {if $TAG.slug=='chay-i-kofe'}class="on"{/if}><a href="/tag/chay-i-kofe/poster/{if $style_slug}{$style_slug}/{/if}" title="чай и кофе" rel="nofollow">чай и кофе</a></li>
		</ul>		
		{/if}
		
		{if !$MobilePageVersion &&  $filters.category=='cup' && !$tags_collection}	
		<ul class="differentTag">
			<li {if $TAG.slug=='uzor'}class="on"{/if}><a href="/tag/uzor/cup/" title="узор" rel="nofollow">узор</a></li>
			<li {if $TAG.slug=='lyubov_'}class="on"{/if}><a href="/tag/lyubov_/cup/" title="любовь" rel="nofollow">любовь</a></li>
			<li {if $TAG.slug=='detskie'}class="on"{/if}><a href="/tag/detskie/cup/" title="детские" rel="nofollow">детские</a></li>
			<li {if $TAG.slug=='uyut'}class="on"{/if}><a href="/tag/uyut/cup/" title="уют" rel="nofollow">уют</a></li>
			<li {if $TAG.slug=='chay-i-kofe'}class="on"{/if}><a href="/tag/chay-i-kofe/cup/" title="чай" rel="nofollow">чай и кофе</a></li>
			<li {if $TAG.slug=='sladosti'}class="on"{/if}><a href="/tag/sladosti/cup/" title="сладости" rel="nofollow">сладости</a></li>
			<li {if $TAG.slug=='priroda'}class="on"{/if}><a href="/tag/priroda/cup/" title="природа" rel="nofollow">природа</a></li>
		</ul>
		{/if}	
		
		{if $PAGE->reqUrl.2 == 'collection'}		
		<style> .pageTitle.table .NLtags{ display:none } </style>		
		<div class="collection_tags">
			<ul>
				<li>{*фейк для цветов*}</li>
				{foreach from=$TAGS item="t"}
					<li {if $TAG.slug == $t.slug}class="selected"{/if}>
						<a title="{$t.name}" href="/tag/{$t.slug}/{if $Style}{$Style->category}/{$Style->style_slug}/{/if}{if $filters.size_name}{$filters.size_name}/{/if}" rel="nofollow">
							<img width="125" height="125" title="" alt="{$t.name}" src="{$t.picture_path}"><br/>{$t.name}
						</a>					
					</li>					
				{/foreach}
				<li class="allTag"><a href="/tag/" rel="nofollow" class="bg">больше тегов</a></li>
				<br clear="all"/><br/>
			</ul>
		</div>
		{/if}	
		
		<div class="list_wrap {$category} {$filters.sex} {if $category!=$filters.category}{$filters.category}{/if}">	
			
			{if $SEARCH && $goods|count == 0}
				<p style="padding:30px">{$L.SHOPWINDOW_nothing_is_found}. Вы искали по {$razdel}, <a href="/search/?q={$SEARCH}">поискать везде?</a></p>
				{include file="catalog/list.trackUser.out_of_media.tpl"}
			{/if}
			
			{if $user && $goods|count == 0}
				<p style="padding:30px">{$L.SHOPWINDOW_nothing_is_found}. <a href="/catalog/{$user.user_login}/">Посмотреть все работы автора?</a></p>
				
				{if ($user.user_id == $USER->id || $USER->id == 27278) && $filters.category}
					<p style="padding-left:30px">Если вы загружали работы, но их здесь нет, то изображения для Вашей работы ещё не созданы</p>
				{/if}
				
				{include file="catalog/list.trackUser.out_of_media.tpl"}
			{/if}
			
			{if $TAG && $goods|count == 0}
				<p style="padding:30px">Не обнаружено работы с тегом "{$TAG.name}" на данном носителе.</p>
				{include file="catalog/list.trackUser.out_of_media.tpl"}
			{/if}
			
			{if $stock.good_stock_quantity <= 0}
				<p style="padding:30px">
					{$L.SHOPWINDOW_stock_is_empty}
					
					{if $filters|count > 0}
						<br><br>
						Поиск осуществлен по фильтрам:
						{if $filters.size}
							Размер <b>{$filters.size_name}</b>
						{/if}
						{if $filters.color}
							Цвет <b>{$filters.color_name}</b>
						{/if}
						
						&nbsp;&nbsp;&nbsp;&nbsp;
						
						<img src="/images/icons/delete.gif" class="ico">
						<a href="{if $TAG.slug != $module && !$SEARCH}/{$module}{/if}{if $SEARCH}/catalog{/if}{if $TAG.slug}/{$TAG.slug}{/if}/{$filters.category}{if $orderBy == 'new'}/{$orderBy}{/if}/">Отменить все фильтры</a>
					{/if}
				</p>
				{include file="catalog/list.trackUser.out_of_media.tpl"}
			{/if}

			<ul>
				{if $list_tpl}
					{include file="catalog/$list_tpl"}
				{else}
					{if $filters.category}
						
						{if $filters.category == "sumki"}
						<li class="m12" style="height:340px;">
							<a rel="nofollow" class="item"  href="/sale/view/17318/category,sumki/">
								<span class="list-img-wrap" style="height:283px;">
									<img src="/J/static-cache/fittingroom/sumki/495/17318.sale.jpeg" width="255" height="261" title="MARYJANE - Сумки тканевые" alt="MARYJANE - Сумки тканевые" style="margin-top:10px;" />			
								</span>
							</a>	
							{*<div class="infobar">
								<div class="vote_count vote_count_id_">
									<span>0</span>
								</div>
								<div class="preview_count">0</div>
							</div>
							<div class="vote " _id="" title="Мне нравится. Нравится 70 людям">
								<span>70</span>
							</div>*}
							<div class="item">
								<a rel="nofollow" href="/sale/view/17318/category,sumki/" class="title">Сумка MARYJANE</a>					
								<!--noindex--><span style="background-color: rgb(0, 168, 81); visibility: hidden; display: none;" class="price"> 390&nbsp;руб.</span><!--/noindex-->
								<!--noindex--><span class="author"><a rel="nofollow" title="Дизайнер MARYJANE" href="/catalog/MARYJANE/">MARYJANE</a></span><div class="autor-city">Москва</div><!--/noindex-->
							</div>
						</li>
						{/if}
						
						{include file="catalog/list.goods.photos.tpl"}
					{else}
						{include file="catalog/list.goods.tpl"}
					{/if}
				{/if}
			</ul>
			
			{*постраничка*}	
			{include file="catalog/list.pagePagination.tpl"}
			
		</div>
		
	</div>
	
{if $user && $MobilePageVersion && ($USER->id == 6199 || $USER->id == 105091)}
	<br clear="all" />
	<div class="wrap-mobile-doble_menu" style="border: 1px dashed orange;">{include file="catalog/list.sidebar.filters.drop-down.tpl"}</div>
{/if}	
	
	
	{if !$user_id}
		<!-- Кнопка ВВерх -->
		<div id="button_toTop"><div>{$L.SHOPWINDOW_top}</div></div>
	{/if}
</div>

{if $photos}
	<!-- Слайдер -->
	<div style="clear:both"></div>
	<div style="width:768px;float: right;margin-top:55px;padding:4px;">
	<div class="kids_slider">
		<!-- Кнопка скрола влево -->
		<a href="#!/" class="btn-move-left" rel="nofollow"></a>
		<div class="slider-container">
			<div class="left-opacity"></div>
			<div class="slider-items-container">
				<div class="slider-items">			
					{foreach from=$photos.bottom item=p}
					<a href="/catalog/{$p.user_login}/{$p.good_id}/" class="slider-item"><img src="{$p.picture_path}" /></a>
					{/foreach}
				</div>
			</div>
			<div class="right-opacity"></div>
		</div>
		<!-- Кнопка скрола вправо -->
		<a href="#!/" class="btn-move-right" rel="nofollow"></a>
	</div>
	</div>
{/if}

<br clear="all" />
{if !$user_id && $prev_tag}
	<div  style="float:left;margin-top:20px;width:250px; overflow:hidden; white-space: nowrap;">&larr;&nbsp;<a href="/tag/{$prev_tag.slug}/" rel="nofollow" >{$prev_tag.title}</a></div>	
{/if}
<br />
{if !$user_id && $next_tag}
	<div  style="float:right;margin-top:10px;text-align:right;width:250px; overflow:hidden; white-space: nowrap;"><a href="/tag/{$next_tag.slug}/">{$next_tag.title}</a>&nbsp;&rarr;</div>	
{/if}

{if $user_id}
	<br clear="all" />
	{if $next_user}
		<div  style="float:left;margin-top:20px">&larr;&nbsp;<a href="/{$module}/{$prev_user.user_login}/" rel="nofollow">{$prev_user.user_login}</a></div>			
	{/if}
	{if $next_user}
		<div  style="float:right;margin-top:20px"><a href="/{$module}/{$next_user.user_login}/">{$next_user.user_login}</a>&nbsp;&rarr;</div>			
	{/if}
   	<br clear="all" />
{/if}

{if !$MobilePageVersion}
{if !$user_id}
	<br clear="all" />
	<div class="list_seotexts">
	{include file="catalog/list.seotexts.tpl"}
	</div>
{/if}
{/if}

{literal}
<script type="text/javascript">
function swichBlockVisible(block, obj) {
	if ($(obj).text() == "+") {
		$(obj).text('-');
	} else {
		$(obj).text('+');
	}
	$('#' + block).slideToggle('slow');
	return false;
}

$(document).ready(function(){
	$('.b-filter_view a').click(function(){
	
		var self = this;
		//$('.list_wrap').css('opacity','0.4');
		$.cookie('dataview['+REQUEST_URI+']', ($(this).hasClass('view-pipl')?'view-1':'view-2'), { expires: 1});
		$(".catalog_goods_list .list_wrap").append('<div class="loadding-cover" id="loadding-cover"></div>');
		setTimeout(function(){$("#loadding-cover").remove();}, 15000);
		$.get($(this).attr('href'), function(html){
			$('.list_wrap').html('<ul>'+html+'</ul>');
			//$('.list_wrap').css('opacity','1');
			$("#loadding-cover").remove();
			$('.b-filter_view a').removeClass('active');
			$(".b-filter_view a."+$(self).attr('class')).addClass('active');//так как еще есть меню верхнее(аналогичное)
			
			 if ($(self).index() == 1) {
				$('.catalog_goods_list').addClass('neformat');				
				$('.h4_select_color, .b-color-select').hide();
				$('.catalog_goods_list').removeClass('list_trjapki');
				$('.b-catalog_v2').removeClass('b-catalog_v3');
				if ({/literal}{if $filters.category == 'poster' || $filters.category == 'cup'}true{else}false{/if}{literal}){
					$('.catalog_goods_list .list_wrap').removeClass('{/literal}{$filters.category}{literal}');
				}
			 }else{ 				
				$('.catalog_goods_list').removeClass('neformat');				
				$('.h4_select_color, .b-color-select').show();
				if ({/literal}{if $filters.sex && $filters.category != 'poster'}true{else}false{/if}{literal}){
					$('.catalog_goods_list').addClass('list_trjapki');
					$('.b-catalog_v2').addClass('b-catalog_v3');
				}
				if ({/literal}{if $filters.category == 'poster' || $filters.category == 'cup'}true{else}false{/if}{literal}){
					$('.catalog_goods_list .list_wrap').addClass('{/literal}{$filters.category}{literal}');
					$('.b-catalog_v2').addClass('b-catalog_v3');
				}
			 }
			 
			 $('.fix-menu .b-color-select.none').hide();

			 $('.b-filter_tsh a').each(function(){
				var sort = $(this).attr('data-sort');
				var url = urlViews[$(self).index()].replace('|%sort%|', sort);
				$(this).attr('href', url);
			});
			
			initShowMore();
		});
		return false;
	});
	
	$(".b-filter_tsh a").click(function(){
		var self = this;
		$.cookie('datasort['+REQUEST_URI+']', $(this).attr('data-sort'), { expires: 1});
		if (!$(this).hasClass('active')) {
			$(".catalog_goods_list .list_wrap").append('<div class="loadding-cover" id="loadding-cover"></div>');
			setTimeout(function(){$("#loadding-cover").remove();}, 15000);
			
			$(".b-filter_tsh a").removeClass('active');
			//$(this).addClass('active');
			$(".b-filter_tsh a."+$(self).attr('class')).addClass('active');//так как еще есть меню верхнее(аналогичное)

			var gall_link = $(this).attr('href');
			$.get(gall_link, function(content){
				$(".list_wrap ul").html(content);
				$("#loadding-cover").remove();
				
				var sort = $(self).attr('data-sort');
				$('.b-filter_view a').each(function(){
					var url = urlViews[$(this).index()].replace('|%sort%|', sort);
					$(this).attr('href', url);
				});
				
				initShowMore();
			});
		}				
		return false;
	});
	
	loadMore = {
		page: 0,
		slider: '',
		check: function(reverse){
			if (reverse) {
				this.page = this.slider.attr('rpage');
				this.page = parseInt(!this.page?0:this.page);
				this.slider.attr('rpage', --this.page);
			} else {
				this.page = this.slider.attr('page');
				this.page = parseInt(!this.page?0:this.page);
				this.slider.attr('page', ++this.page);
			}
			
			var self = this;
			self.slider.attr('blocked', 1);
			$.getJSON('/ajax/get_tag_photo/detskie/?page='+this.page, function(response){
				var items = self.slider.find('.slider-items');
				for(var i=0;i<response.length;i++) {
					var item = $('<a href="/catalog/'+response[i].user_login+'/'+response[i].good_id+'/" class="slider-item"><img src='+response[i].picture_path+'></a>');
					if (reverse) {
						items.prepend(item);
						/*item[0].onclick = function() {
							var k = parseInt(items.css('left')) - $(this).width() - 3;
							var l = parseInt(items.attr('index'));
							items.css('left', k+'px').attr('index',++l);
						}*/
						var k = parseInt(items.css('left')) - (item.width()>0?item.width():170) - 3;
						var l = parseInt(items.attr('index'));
						items.css('left', k+'px').attr('index',++l);
					}
					else
						items.append(item);
				}
				self.slider.attr('blocked', 0);
			});
		}
	}
	
	//инициализируем слайдер с детскими
	$('.kids_slider .btn-move-left, .kids_slider .btn-move-right').click(function(){
		var slider = $(this).parent('.kids_slider');
		var items = slider.find('.slider-items');
		var diraction = ($(this).hasClass('btn-move-left')?'left':'right');
		
		if (slider.attr('blocked') == '1') return;
		loadMore.slider = slider;
		
		//прокручиваемый элемент
		var index = items.attr('index');
		if (typeof index == 'undefined' || index == null) index=3; else index = parseInt(index);
		
		//направление прокрутки
		if (diraction == 'right') index++; else index--;
		if (index < 0) index = 0;
		if (index > slider.find('.slider-items img').length - 4) index = parseInt(slider.find('.slider-items img').length-4);
		items.attr('index', index);

		if (diraction == 'right' && slider.find('.slider-items img').length - index - 4 < 2) {
			setTimeout(function(){ loadMore.check(); }, 10);
		}
		
		var p = Math.abs(parseInt(items.css('left')));
		if (diraction == 'left' && (p == 131 || p == 0)) {
			w = 0;
			items.attr('index', -1);
		} else {
			var w = -131;
			var _index = index;
			slider.find('.slider-items img').each(function(){
				if (index-- > 0) {
					w += $(this).width()+3;
				} else return;
			});
		}
		//console.log(_index+'_'+(w*-1)+'px');
		items.stop().animate({ 'left' : (w*-1)+'px' }, 1000, 'swing', function(){
			if (diraction == 'left' && _index < 3) { 
				setTimeout(function(){ loadMore.check(true); }, 10);
			}		
		});
	});	
	//debbgger;
});

</script>
{/literal}

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