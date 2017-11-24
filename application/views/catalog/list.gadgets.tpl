{literal}
<style>
.catalog_goods_list { width: 769px; }
.top-banner {ldelim}display:none{rdelim}
.author_username { float: none; }
.selected51no {	background:url(/images/to-vote-min-gray.png) no-repeat 0 -22px !important;}
.selected51no:hover {	background:url(/images/to-vote-min-gray.png) no-repeat 0 -22px !important;}
.selected51 {background:url(/images/to-vote-min.png) no-repeat 0 -22px !important;}
.selected51:hover {background:url(/images/to-vote-min.png) no-repeat 0 -22px !important;}
.m12 .item .price {	bottom: 123px !important;}
.m12 .item .b-ordensml img { width:auto !important; }

</style>
{/literal}


<div class="b-catalog_v2 moveSidebarToLeft {if $filters.category != 'moto' && $filters.category != 'boards'}b-catalog_v3{/if}">	
	
	{if $filters.category == 'pillows'}
		<div class="pageTitle table">
			<h1>{if $H1}{$H1}{else}{if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}{/if}</h1>
		</div>
	{/if}
	
	{if !$MobilePageVersion && !$user_id && $modelslist}
		{include file="catalog/list.models_menu.tpl"}
    {/if}
	
	{*if !$MobilePageVersion && ($filters.category == 'cases' || $style_slug == 'case-ipad-mini' || $style_slug == 'case-ipad-3')}*}
	{if !$MobilePageVersion && $style_slug == 'case-iphone-5' && !$TAG}
		{include file="catalog/case-menu.tpl"}	
	{/if}	
	
	{if !$user && !$MobilePageVersion}
		<div class="pageTitle">
			<a rel="nofollow" name="case-catalog"></a>{*скрол с меню чехлов*}
			
			{if ($user && $PAGE->title) || ($PAGE->module == 'search' && $PAGE->title && $style_slug != 'iphone-5' && $style_slug!= "ipad-air" && $style_slug!= "macbook-pro" && $filters.category != 'cases' && $style_slug != 'case-ipad-mini' && $style_slug != 'case-ipad-3') || $filters.category =='pillows'}
				{literal}
					<style>
					.pageTitle h1 {max-width:100%;}
					.b-catalog_v2.moveSidebarToLeft .sidebar_filter {padding-top: 14px;}
					</style>
				{/literal}			
				
				{*
				<h1>{if $SEARCH}
					{include file="catalog/list.search-filter.tpl"}
				{else}			
					{if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}
				{/if}</h1>	
				*}
				
			{/if}			
		
			<!-- Скрипт для работы этого фильтра  -->
			{*
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
			*}
			<div style="clear:both"></div>		
		</div>
	{/if}

	<!-- баннер -->
    {if $filters.category != 'pillows'}		
		{include file="catalog/banner.tpl"}
    {/if}

	{if !$MobilePageVersion}
		<!-- Фильтр в левом сайдбаре -->
		{include file="catalog/list.sidebar.tpl"}
	{/if}
	
	{if !$MobilePageVersion && $tags_collection && $filters.category && $filters.category != 'poster'}	
		<ul class="differentTagTop clearfix">
			{foreach from=$tags_collection item="tag"}
			<li {if $TAG.slug==$tag.slug}class="on"{/if}><a rel="nofollow" href="/tag/{$tag.slug}/{if $Style->id > 0}{$Style->category}/{if $filters.category!='cup'}{$Style->style_slug}/{/if}{elseif $filters.category}{if $filters.category!='phones' && $filters.category!='cases' && $filters.category!='touchpads' && $filters.category!='auto' && $filters.category!='cup' && $filters.category!='bag'}category,{/if}{$filters.category}{if $filters.category=='cases' || $filters.category=='touchpads' || ( $style_slug == 'iphone-6-bumper' || $style_slug == 'iphone-5-bumper' )}/{$style_slug}{/if}{if $filters.size && !$fsize_not_selected};size,{$filters.size}{/if}{if $filters.color && !$fcolor_not_selected};color,{$filters.color}{/if}/{/if}{if $filters.sex == 'female'}female/{/if}{if $filters.category!='cup' && $filters.category!='bag'}new/{/if}">{$tag.title}</a></li>
			{/foreach}			
			{if $TAG.slug == "fotografiya"}
				<li><a href="/foto-na-futbolku/">Напечатать фото на футболку</a></li>
			{/if}			
		</ul>
	{/if}	
	
	<div class="catalog_goods_list {if $filters.category !='pillows'}list_gadgets{/if}">

		<script type="text/javascript">
			var countElements = {if $count}{$count}{else}0{/if};
			var pageLoaded = {$page};
			var REQUEST_URI = '{$PAGE_URI}';
			{if $page > 1}var autoscrol_count = 0;{/if}
		</script>		
		<script type="text/javascript">
			$(document).ready(function() { 
				//инициализируем автоподгрузку страниц
				$(window).bind('scroll', pScroll);
			});
		</script>
		
		{if $photos}
		<!-- Слайдер -->
		<div class="kids_slider">
			<!-- Кнопка скрола влево -->
			<a href="#!/" class="btn-move-left" rel="nofollow"></a>
			<div class="slider-container">
				<div class="left-opacity"></div>
				<div class="slider-items-container">
					<div class="slider-items">			
						{foreach from=$photos.top item=p}
						<a href="/catalog/{$p.user_login}/{$p.good_id}/" class="slider-item"><img src="{$p.picture_path}" /></a>
						{/foreach}
					</div>
				</div>
				<div class="right-opacity"></div>
			</div>
			<!-- Кнопка скрола вправо -->
			<a href="#!/" class="btn-move-right" rel="nofollow"></a>
		</div>
		{/if}	
		<div class="list_wrap {$filters.category}{if $category_style != $category_def_style}_{$category_style}{/if}_catalog {$category}_catalog  matched_{$category_style}_{$category_def_style}" id="{$filters.category}_catalog">
			
			{if $goods|count == 0}
				<p style="padding:30px">Не найдено ни одной работы для данной категории носителей</p>
				{include file="catalog/list.trackUser.out_of_media.tpl"}
			{else}			
				{if $stock.good_stock_quantity <= 0}
					<p style="padding:30px">{$L.SHOPWINDOW_stock_is_empty}</p>
					{include file="catalog/list.trackUser.out_of_media.tpl"}
				{/if}
			
				<ul>
					{include file="catalog/$list_tpl"}
				</ul>
			{/if}
			
			{*постраничка*}	
			{include file="catalog/list.pagePagination.tpl"}

		</div>
		
	</div>
	
	
	{if !$user_id}
		<!-- Кнопка ВВерх -->
		<div id="button_toTop"><div>Наверх</div></div>
	{/if}
	
	
</div>

{if $photos}
	<!-- Слайдер -->
	<div style="clear:both"></div>
	<div style="width:768px;clear:both;margin-top:55px;padding:4px;">
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
	<div  style="float:left;margin-top:20px;width:250px; overflow:hidden; white-space: nowrap;">&larr;&nbsp;<a href="/tag/{$prev_tag.slug}/{$filters.category}/{if $Style}{$Style->style_slug}/{/if}" rel="nofollow" >{$prev_tag.title}</a></div>
{/if}
<br />
{if !$user_id && $next_tag}
	<div  style="float:right;margin-top:10px;text-align:right;width:250px; overflow:hidden; white-space: nowrap;"><a href="/tag/{$next_tag.slug}/{$filters.category}/{if $Style}{$Style->style_slug}/{/if}">{$next_tag.title}</a>&nbsp;&rarr;</div>
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


<br clear="all" /><br />
{if !$MobilePageVersion}
<div class="list_seotexts">
{include file="catalog/list.seotexts.gadgets.tpl"}
</div>
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
		console.log(_index+'_'+(w*-1)+'px');
		items.stop().animate({ 'left' : (w*-1)+'px' }, 1000, 'swing', function(){
			if (diraction == 'left' && _index < 3) { 
				setTimeout(function(){ loadMore.check(true); }, 10);
			}		
		});
	});	
	
});

</script>
{/literal}