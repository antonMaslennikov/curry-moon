
{literal}
<style>
.top-banner {ldelim}display:none{rdelim}
.author_username { float: none; }
.selected51no {
	background:url(/images/to-vote-min-gray.png) no-repeat 0 -22px !important;
}
.selected51no:hover {
	background:url(/images/to-vote-min-gray.png) no-repeat 0 -22px !important;
}
.selected51 { 
	background:url(/images/to-vote-min.png) no-repeat 0 -22px !important;
}
.selected51:hover { 
	background:url(/images/to-vote-min.png) no-repeat 0 -22px !important;
}

.b-catalog_v2 { margin-top:0px; }
.pageTitle .b-filter_tsh { margin-right:0px; }
.catalog_goods_list { margin: 6px 0 0 3px; }

</style>
{/literal}

<script type="text/javascript" src="/js/2012/autoscroll.js"></script>
<script type="text/javascript" src="/js/2012/button_to_top.js"></script>*}



<div class="b-catalog_v2">
	
	
	{if $notees}
	
		{include file="catalog/add_notees.tpl"}
		
	{/if}


	
	<div class="pageTitle" style="width: 769px;">
		
		{if $PAGE->title}
			<h1 style="float:left">{if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}</h1>
		{else}
		
			{if !$TAG && $orderBy == 'new'} 
				<h2 style="color: #504F4F; float: left;  font-family: 'MyriadPro-CondIt'; font-size: 28px;  max-width: 520px;">Новинки</h2>
			{/if}
			{if !$TAG && $orderBy == 'popular'} 
				<h2 style="color: #504F4F; float: left;  font-family: 'MyriadPro-CondIt'; font-size: 28px;  max-width: 520px;">Популярные</h2>
			{/if}
			
			{if $task == 'winners'} 
				<h2 style="color: #504F4F; float: left;  font-family: 'MyriadPro-CondIt'; font-size: 28px;  max-width: 520px;">Купить футболки победителей</h2>
			{/if}
			
			{if $TAG}
			<h1>
				{if $TAG.title}
					{$TAG.title}
				{else}
					Футболки {$TAG.name}
				{/if} 
			</h1>
			{/if}
			
		{/if}
		
		<!--изменить-->
		{if $user && ($USER->user_id == $user_id || $USER->user_id == 27278 || $USER->user_id == 6199 || $USER->user_id == 86455 || $USER->user_id == 105091)}
			{include file="catalog/form_change_name_shop.tpl"}
		{/if}
		
				
		{if $TAG && !$filters.category}
		<div class="b-filter_tsh">
			<a href="/tag/{$TAG.slug}/new/ajax/" rel="nofollow" class="new-filter {* if $orderBy == 'new'}active{/if *}">Новое</a>
			<a href="/tag/{$TAG.slug}/popular/ajax/" rel="nofollow" class="pop-filter {if $orderBy == 'popular'}active{/if}">Популярное</a>
			<a href="/tag/{$TAG.slug}/grade/ajax/" rel="nofollow" class="score-filter {if !$orderBy || $orderBy == 'grade'}active{/if}">По оценке</a>
		</div>
		{/if}
		
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

        {if $filters.category && $category_sexes > 1}
			<!-- выбор пола -->	
			<div id="input_man_woman" class="b-radio-manwomen radio-input selected-woman" style="width: auto; float:right;visibility: visible; padding: 20px 0px 0px 0px;margin: 0px;">
				<a rel="nofollow" href="/{$module}{if $tag}/{$tag}{/if}/category,{$filters.category}/ " id="male" class="type-select {if $filters.sex == 'male'}active{/if}" style="display: block; "></a>
				<a rel="nofollow" href="/{$module}{if $tag}/{$tag}{/if}/category,{$filters.category}/female/" id="female" class="type-select {if $filters.sex == 'female'}active{/if}" style="display: block; "></a>
				<a rel="nofollow" href="#!/select-kids" id="kids" class="type-select" style="{if $category_sexes < 3}display: none;{/if}"></a>
				<input type="hidden" value="" name="gender" id="good_gender">
			</div>
		{/if}
		<div style="clear:both"></div>
	</div>	
	
	{if $photos && !$filters.category}
	<!-- Слайдер -->
	<div class="kids_slider" style="padding-top:20px;margin-bottom:10px;">
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
	
	<div class="catalog_goods_list">		
		<script type="text/javascript">
			var countElements = {if $count}{$count}{else}0{/if};
			var pageLoaded = {$page};
			var REQUEST_URI = '{$PAGE_URI}';
		</script>		
		<script type="text/javascript">		
			$(document).ready(function() { 			
				//инициализируем автоподгрузку страниц
				$(window).bind('scroll', pScroll);
			});
		</script>
				
		<div class="list_wrap {$category}">		
		<ul>
			{if $filters.category}
                {include file="catalog/list.goods.photos.tpl"}
            {else}
                {include file="catalog/list.goods.tpl"}
            {/if}
		</ul>
		</div>
		
	</div>
	
	<!-- Фильтр в правом сайдбаре -->
	{include file="catalog/list.sidebar.tpl"}
	
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

{include file="catalog/list.seotexts.tpl"}

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
		//console.log(_index+'_'+(w*-1)+'px');
		items.stop().animate({ 'left' : (w*-1)+'px' }, 1000, 'swing', function(){
			if (diraction == 'left' && _index < 3) { 
				setTimeout(function(){ loadMore.check(true); }, 10);
			}		
		});
	});	
	
});

</script>
{/literal}