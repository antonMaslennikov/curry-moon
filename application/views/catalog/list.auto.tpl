<link rel="stylesheet" href="/css/catalog/auto.css" type="text/css" media="all"/>
{if $filters.category != 'enduro' && $filters.category != 'jetski' && $filters.category != 'atv' && $filters.category != 'snowmobile' && $filters.category != 'helmet' && $filters.category != 'helm'}	
	<script type="text/javascript">
		var countElements = {if $count}{$count}{else}0{/if};
		var pageLoaded = {if $page}{$page}{else}1{/if};
		var REQUEST_URI = '{$PAGE_URI}';
		{if $page > 1}var autoscrol_count = 0;{/if}
	</script>		
	<script type="text/javascript">
		$(document).ready(function() { 
			//инициализируем автоподгрузку страниц
			$(window).bind('scroll', pScroll);
		});
	</script>
{/if}

<div class="auto-wrap moveSidebarToLeft {if $filters.category == 'enduro' || $filters.category == 'jetski' || $filters.category == 'atv' || $filters.category == 'snowmobile'|| $filters.category == 'helmet' || $filters.category == 'helm'}enduroList{/if}"> 
	<!-- баннер -->
	{include file="catalog/banner.tpl"}

	<div class="pageTitle table {if $MobilePageVersion}second{/if}">
	<!-- для остального теперь в баннере -->
	{if $filters.category == 'enduro' || $filters.category == 'jetski'|| $filters.category == 'atv' || $filters.category == 'snowmobile'|| $filters.category == 'helmet' || $filters.category == 'helm'}
		<h1>{if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}</h1>
	{/if}
	<!-- фильтр -->
	
	
	{*if $filters.category != 'enduro' && $filters.category != 'jetski' && $filters.category != 'atv' && $filters.category != 'snowmobile' && $filters.category != 'helmet' && $filters.category != 'helm'}
		<a href="/8march/{$filters.category}/{if $filters.category != 'auto'}{$style_slug}/{/if}new/" title="8 марта" rel="nofollow" id="top2014"></a>
		<a href="/23february/{$filters.category}/{if $filters.category != 'auto'}{$style_slug}/{/if}new/" title="23 февраля" rel="nofollow" id="zvezda" ></a>
	{/if*}
	
	
	{*if $task == "auto" || $task == 'stickers'}
		<div class="b-filter_tsh {$PAGE->lang}" style="">
			<a href="/{$module}/{$task}/new/" title="{$L.GOOD_new}" rel="nofollow" class="new-filter {if $orderBy == 'new'}active{/if}">Новое</a>
			<a href="/{$module}/{$task}/" title="{$L.GOOD_popular}" rel="nofollow" class="pop-filter {if $orderBy == 'popular' || $orderBy == ''}active{/if}">Популярное</a>
			<a href="/{$module}/{$task}/grade/" rel="nofollow" class="score-filter {if $orderBy == 'grade'}active{/if}" title="{$L.GOOD_rating}">По оценке</a>
		</div>
		
		<!-- Скрипт для работы этого фильтра  -->
		{literal}
		<script type="text/javascript">
		$(document).ready(function(){
			$(".b-filter_tsh a").click(function(){
				if (!$(this).hasClass('active')) {
					$(".catalog_goods_list .list_wrap").append('<div class="loadding-cover" id="loadding-cover"></div>');
					setTimeout(function(){$("#loadding-cover").remove();}, 3000);
					
					$(".b-filter_tsh a").removeClass('active');
					//$(this).addClass('active');
					$(".b-filter_tsh a."+$(this).attr('class')+'"').addClass('active');//так как еще есть меню верхнее(аналогичное)
					
					var gall_link = $(this).attr('href');
					$.get(gall_link, function(content){
						$(".list_wrap ul").html(content);
						$("#loadding-cover").remove();
						initShowMore();
					});
				}				
				return false;
			});
		});
		</script>		
		{/literal}
	{/if*}	
	</div>
	
	{if $task == "auto"}{*=4 в ряд==*}
		{literal}
			<style>
			.list-auto-wrap {width: 980px; }
			.list_wrap ul li.m12 {padding: 12px 5px 12px 6px;}
			#show-more-link {margin: 32px 0 32px 229px;}
			</style>
		{/literal}	
		
		{if $mode != 'preview'}{*===2 -в ряд===*}
			{literal}
				<style>				
				.list-auto-wrap ,div.div-show-more{border:none!important;}
				.list_wrap ul li.m12 {width: 477px;height: 615px;border-color: #fff;}				
				.list_wrap ul li.m12 a.item img{width: 500px!important;height: 512px!important;}				
				.list_wrap .item .list-img-wrap{width: 478px;height: 512px;}
				.list_wrap ul li.m12 .zoom {left: 240px!important;top: 237px!important;}
				.m12 .item .price {bottom: 120px;}
				.list-auto-wrap li.m12 .list-img-wrap {display: block!important;}
				.list-auto-wrap li.m12 .list-img-wrap-hidden {display: none!important;}
				.list-auto-wrap .m12 .vote {top: 12px;right: 2.5px;}
				.list_wrap ul li.m12 .infobar{width: 417px;}				
				.infobar,.m12 div.item {margin-left: 11px;}
				.m12 .autor-city,.m12 .author{float: left;margin-right: 10px;}
				.m12 .autor-city{position: relative;top: -1px;}
				</style>
			{/literal}	
		{/if}
		
	{elseif !$MobilePageVersion}
		<!-- Фильтр в левом сайдбаре -->	
		{include file="catalog/list.sidebar.tpl"}
	{/if}
	
	
	{if $filters.category == 'enduro' || $filters.category == 'jetski' || $filters.category == 'atv'  || $filters.category == 'snowmobile' || $filters.category == 'helmet' || $filters.category == 'helm'}
	
		{if $MobilePageVersion}
			<p class="link-mobile-galery"> 
			{if $PAGE->reqUrl.2 == 'ready' || $PAGE->reqUrl.2 == 'gallery'}
				<a href="/catalog/enduro/" title="Вернуться в каталог" rel="nofollow">Вернуться в каталог</a>
			{/if}
			{if $PAGE->reqUrl.2 != 'ready'}
				<a href="/catalog/enduro/ready/" title="{$L.SIDEBAR_menu_gallery_of_works}" rel="nofollow">{$L.SIDEBAR_menu_gallery_of_works}</a>
			{/if}
			{if $PAGE->reqUrl.2 != 'gallery'}
				<a href="/catalog/enduro/gallery/" title="Фотогалерея" rel="nofollow">Фотогалерея</a>
			{/if}
			</p>
		{/if}
		
		{if $PAGE->reqUrl.2 != 'ready' && $PAGE->reqUrl.2 != 'gallery'}
			{include file="catalog/list.enduro_top_filter.tpl"}<!-- Фильтр эндуро -->	
		{/if}
		
	{/if}
		
	<!-- список работ -->
	
	<div  class="list-auto-wrap list_wrap">
		
		{if $filters.category != 'enduro' && $filters.category != 'jetski' && $filters.category != 'atv' && $filters.category != 'snowmobile' && $filters.category != 'helmet' && $filters.category != 'helm'}
			{if $SEARCH && $goods|count == 0}
				<style>.list-auto-wrap{ border-left:0 }</style>
				
				<p style="padding:30px">По указанному запросу ничего не найдено</p>
				{include file="catalog/list.trackUser.out_of_media.tpl"}
			{/if}
			
			{if $user && $goods|count == 0}
				<p style="padding:30px">{$L.SHOPWINDOW_nothing_is_found}. <a href="/catalog/{$user.user_login}/">Посмотреть все работы автора?</a></p>
				
				{if ($user.user_id == $USER->id || $USER->id == 27278) && $filters.category}
					<p style="padding-left:30px">Если вы загружали работы, но их здесь нет, то изображения для Вашей работы ещё не созданы</p>
				{/if}
				
				{include file="catalog/list.trackUser.out_of_media.tpl"}
			{/if}
			
			{if $stock.good_stock_quantity <= 0}
				<p style="padding:30px">{$L.SHOPWINDOW_stock_is_empty}</p>
				{include file="catalog/list.trackUser.out_of_media.tpl"}
			{/if}
		{/if}
		
		<ul>
			{if $list_tpl}
				{include file="catalog/$list_tpl"}
			{else}
				{include file="catalog/list.goods.auto.tpl"}
			{/if}
		</ul>
		
	</div>
	{*постраничка*}	
	{include file="catalog/list.pagePagination.tpl"}

	{if !$user_id}
	<!-- Кнопка ВВерх -->
	<div id="button_toTop"><div>Наверх</div></div>
	{/if}
</div>
	
<div style="clear:both"></div>

<div class="list_seotexts">{include file="catalog/list.seotexts.tpl"}</div>