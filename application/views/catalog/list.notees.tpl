{if !$MobilePageVersion}
	{include file="notees/add_notees.tpl"}
{/if}

{literal}
<style>
	.infobar .vote_count {margin-left: 7px;}
	.navigation-page{left: -83px!important;}
	
	.neformat .list_wrap ul li.m12 .vote {top: 12px;right: 11px;}
	.neformat {width: 766px;}
	.neformat .list_wrap{width:766px;}
	.neformat .item .price {background-color:rgb(0, 168, 81);}
	.b-catalog_v2.moveSidebarToLeft .sidebar_filter {width: 207px;margin:6px 0 0 0;}
	.moveSidebarToLeft div.catalog_goods_list{width:770px;}
</style>
{/literal}

{if $note==''}	
	{include file="catalog/list.fix.menu.tpl"}
{elseif $USER->meta->mjteam == 'super-admin' || $USER->meta->mjteam == 'developer'}	
	{include file="catalog/list.fix.menu.tpl"}
	{literal}<style>.fix-menu{ border-bottom:2px dashed orange }</style>{/literal}
{/if}

{if $MobilePageVersion}
	{include file="catalog/list.mobile.menu.tpl"}
{/if}

<!--list.notees.tpl-->
<script type="text/javascript">
	var countElements = {if $count}{$count}{else}0{/if};
	var pageLoaded = {$page};
	var REQUEST_URI = '{$PAGE_URI}';
	var list_elements = '.itemsList';
	{if $page > 1}var autoscrol_count = 0;{/if}
	
	$(document).ready(function() { 			
		//инициализируем автоподгрузку страниц
		$(window).bind('scroll', pScroll);
		
		$(".b-filter_tsh a").click(function() {
			var self = this;
			if (!$(this).hasClass('active')) {
				$(".itemsList").append('<div class="loadding-cover" id="loadding-cover"></div>');
				setTimeout(function(){ $("#loadding-cover").remove(); }, 3000);
				
				$(".b-filter_tsh a").removeClass('active');
				//$(this).addClass('active');
				$(".b-filter_tsh a."+$(self).attr('class')).addClass('active');//так как еще есть меню верхнее(аналогичное)
				
				var gall_link = $(this).attr('href');
				$.get(gall_link, function(content){
					//$(".itemsList ul").html(content); //noteesblock
					//$(".itemsList ul li:not(:first), .itemsList ul .navigation-page").remove();
					//$('.itemsList ul').append($(content));
					$('.itemsList ul').html($(content));
					$("#loadding-cover").remove();
					initShowMore();
				});
			}
			return false;
		});
	});
</script>


<div class="tabz clearfix BigNotees tabz3">
	<!--noindex-->
	<a href="/tag/nadpis_/" {if $TAG.slug == 'nadpis_'}class="active"{/if} rel="nofollow">На русском</a>	
	<a href="/tag/english/" {if $TAG.slug == 'english'}class="active"{/if} rel="nofollow">На английском</a>
	<a href="/tag/nadpis_/category,futbolki/kids/new/" rel="nofollow">Детские</a>
	<!--/noindex-->
</div>


{if $note==''}
	{*	
	<ul class="topTagsNadpis clearfix">		
		{if $TAG.slug == 'nadpis_'}
			{foreach from=$notees_top_tags item="tag"}
			<li><a href="/tag/{$tag.slug}/" title="{$tag.name}" rel="nofollow">{$tag.name}</a> ({$tag.count})</li>
			{/foreach}
		{else if $TAG.slug == 'english'}
			
		{/if}
	</ul>
	*}
	
	{literal}
	<script type="text/javascript">
	$(document).ready(function(){
		$(".topTagsNadpis a").click(function(){
			if (!$(this).hasClass('active')) {
				$(".catalog_goods_list .list_wrap").append('<div class="loadding-cover" id="loadding-cover"></div>');
				setTimeout(function(){$("#loadding-cover").remove();}, 3000);
				
				$(".topTagsNadpis a").removeClass('active');
				$(this).addClass('active');
				var gall_link = $(this).attr('href');
				$.get(gall_link+'ajax/', function(content){
					//фид неформат
					$('.catalog_goods_list').addClass('neformat');
					$('.catalog_goods_list').removeClass('list_trjapki');
					$('.b-catalog_v2').removeClass('b-catalog_v3');				
					/*кнопки фильтра сменим*/
					$('.b-filter_view a').removeClass('active');
					$(".b-filter_view a.view-other").addClass('active');					
					
					
					$('.b-filter_tsh a').each(function(){						
						var sort = $(this).attr('data-sort');
						var url = '/tag/'+'{/literal}{$TAG.slug}{literal}'+'/'+sort+'/ajax/';
						$(this).attr('href', url);
					});
					//контент					
					$(".list_wrap ul").html(content);	
					$("#loadding-cover").remove();			
					$('.pagesGreen').hide();
					
					initShowMore();
				});
			}				
			return false;
		});
	});
	</script>		
	{/literal}	
{/if}

<div class="b-catalog_v2 moveSidebarToLeft">					

	<div class="pageTitle table" >
		{if $PAGE->title}<h1 {if !$MobilePageVersion}style="width:630px"{/if}>{if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}</h1>{/if}	
	

{if $note==''}			
		<!-- Кнопки -->		
		<div class="b-filter_tsh" style="right:65px">				
			<noindex>
			<a data-sort="new" href="/tag/{$TAG.slug}/new/ajax/" class="new-filter {if $orderBy == 'new'}active{/if}" rel="nofollow" title="{$L.GOOD_new}">{*Новые*}</a>
			<a data-sort="popular" href="/tag/{$TAG.slug}/popular/ajax/" class="pop-filter {if $orderBy == 'popular' || $orderBy == '' || !$orderBy}active{/if}" rel="nofollow" title="{$L.GOOD_popular}">{*Популярное*}</a>
			<a data-sort="grade" href="/tag/{$TAG.slug}/grade/ajax/" class="score-filter {if $orderBy == 'grade'}active{/if}" rel="nofollow" title="{$L.GOOD_rating}">{*По оценке*}</a>
			</noindex>
		</div>

		<div class="b-filter_view">
			<noindex>
			<a href="{$mlink}category,futbolki/ajax/" rel="nofollow" class="view-pipl" title=""></a>
			<a href="{$mlink}ajax/" rel="nofollow" class="view-other active" title=""></a></noindex>
		</div>
		
		<noindex>
		<script>
			var urlViews = ["{if $user}/{$module}/{$user.user_login}/{elseif $TAG}{if $TAG.slug != $module}/tag{/if}/{$TAG.slug}/{else}/{$module}/{/if}category,futbolki/|%sort%|/ajax/", "{if $user}/{$module}/{$user.user_login}/{elseif $TAG}{if $TAG.slug != $module}/tag{/if}/{$TAG.slug}/{else}/{$module}/{/if}|%sort%|/ajax/"];	
		
		{literal}
			$(document).ready(function(){	
				$('.b-filter_view a').click(function(){				
					var self = this;
					$(".catalog_goods_list .list_wrap").append('<div class="loadding-cover" id="loadding-cover"></div>');
					setTimeout(function(){$("#loadding-cover").remove();}, 15000);
					$.get($(this).attr('href'), function(html){
						$('.list_wrap').html('<ul>'+html+'</ul>');
						$("#loadding-cover").remove();
						$('.b-filter_view a').removeClass('active');
						$(".b-filter_view a."+$(self).attr('class')).addClass('active');//так как еще есть меню верхнее(аналогичное)
						
						 if ($(self).index() == 1) {
							$('.catalog_goods_list').addClass('neformat');
							$('.h4_select_color, .b-color-select').hide();
							$('.catalog_goods_list').removeClass('list_trjapki');
							$('.b-catalog_v2').removeClass('b-catalog_v3');
						 }else{ 
							$('.catalog_goods_list').removeClass('neformat');
							$('.h4_select_color, .b-color-select').show();
							$('.catalog_goods_list').addClass('list_trjapki');
							$('.b-catalog_v2').addClass('b-catalog_v3');
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
			});	
		{/literal}
		</script>
		</noindex>
	</div>
{/if}	
	
	{if !$MobilePageVersion}
	<div class="sidebar_filter">
		{literal}
		<script type="text/javascript">
			$(document).bind("ready", mainloadNoteNew);
			function mainloadNoteNew(){ 
				loadNoteNew();
			}
		</script>
		{/literal}
		<div id="loaderN" style="position:absolute;top:95px;left:10px;width: 189px;height: 20px;overflow:hidden;height:20px"><img width="208" height="13" style="margin-left:-5px" title="загрузка" src="/img/reborn/thickbox/loading.gif"></div>
		<div id="noteesblock" class="noteSmall clearfix">
			{include file="notees.widget.tpl"}				
		</div>
		
		<ul class="notees_winners">
			{foreach from=$notees_winners item="w"}
				<li class="b-dashed {if $PAGE->reqUrl.1 == $w.id}winner_active{/if}">
					{if $note>0}
					<a href="/senddrawing.design/new/note/{$w.id}/" class="pls" rel="nofollow" title="Прислать дизайн с данной цитатой">+</a>
					{/if}
					<div class="txt">
						{if $w.goods > 0}
							<a href="/notees/{$w.id}/" rel="nofollow">{$w.text}</a>
						{else}
							<a href="/customize/note/{$w.id}/" rel="nofollow">{$w.text}</a>
						{/if} 
					</div>			
					<i class="noteBub">								
						<a href="/profile/{$w.user_id}/" rel="nofollow">{$w.user_login}</a>
					</i>
				</li>
			{/foreach}
		</ul>	
	</div>
	{/if}
	
	<div class="catalog_goods_list neformat">
		<div class="list_wrap itemsList">
			<ul style="height:{$ULheight+100}px">
				{include file="catalog/list.goods.tpl"}				
			</ul>
			
			{*постраничка*}	
			{include file="catalog/list.pagePagination.tpl"}
		</div>	
	
		{*{if $PAGES}<div class="pages"><div class="listing"><div>Страницы:</div>{$PAGES}<a href="/{$filters}/{$orderby}/all/" class="{if $page == 'all'}on{/if}" rel="nofollow">Все</a></div></div>{/if}*}
	</div>		
</div>

<br clear="all" />

<div class="list_seotexts">
	<h2 style="font-size:12px;margin:0">Купить футболку с надписью</h2>
	{include file="catalog/list.seotexts.tpl"}
</div>

{if !$user_id}
	<!-- Кнопка ВВерх -->
	<div id="button_toTop"><div>Наверх</div></div>
{/if}