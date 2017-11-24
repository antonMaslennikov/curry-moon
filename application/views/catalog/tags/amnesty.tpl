{literal}
<style>
.top-banner {ldelim}display:none{rdelim}
.author_username { float: none; }
.selected51no {background:url(/images/to-vote-min-gray.png) no-repeat 0 -22px !important;}
.selected51no:hover {	background:url(/images/to-vote-min-gray.png) no-repeat 0 -22px !important;}
.selected51 {	background:url(/images/to-vote-min.png) no-repeat 0 -22px !important;}
.selected51:hover {	background:url(/images/to-vote-min.png) no-repeat 0 -22px !important;}
/*----ховер меню----*/
.sidebar_filter ul li .f-item:hover{color: #67CA95 !important;}
.b-catalog_v2.moveSidebarToLeft .sidebar_filter {margin-top: -8px;}
</style>
<!--[if IE]>
	<style>
	.list_wrap .item .list-img-wrap {margin-top:-3px !important;}
   </style>
<![endif]-->	
{/literal}

{if $filters.category}
	{literal}
		<style>
		.catalog_goods_list {margin-top: 5px;}
		.b-catalog_v2.moveSidebarToLeft .sidebar_filter {margin-top: 10px;}
		.b-catalog_v2 .sidebar_filter {margin-top: 10px;}
		</style>
	{/literal}	
{/if}

<script type="text/javascript" src="/js/vote_catalog.js"></script>
<script type="text/javascript" src="/js/2012/autoscroll.js"></script>
<script type="text/javascript" src="/js/2012/button_to_top.js"></script>
<script type="text/javascript">
	var countElements = {if $count}{$count}{else}0{/if};
	var pageLoaded = {$page};
	var REQUEST_URI = '{$PAGE_URI}';
	var scrollPosafterGo = 850;
</script>		
<script type="text/javascript">
	$(document).ready(function() {
		//инициализируем автоподгрузку страниц
		$(window).bind('scroll', pScroll);
	});
</script>




<a href="/voting/competition/amnesty/" rel="nofollow">
	<img src="/images/banners/amnesty.gif" alt="Дизайн - конкурс Моя Свобода" title="Дизайн - конкурс Моя Свобода" width="980" height="85" border="0">
</a>

<div class="b-catalog_v2 moveSidebarToLeft">

<div class="pageTitle table">

{if $PAGE->title}

    <h1>{if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}</h1>

{else}

    {if !$TAG && $orderBy == 'new'}
        <h2 style="color: #504F4F; float: left;  font-family: 'MyriadPro-CondIt'; font-size: 28px;  max-width: 520px;">Новинки</h2>
    {/if}
    {if !$TAG && $orderBy == 'popular'}
        <h2 style="color: #504F4F; float: left;  font-family: 'MyriadPro-CondIt'; font-size: 28px;  max-width: 520px;">Популярные</h2>
    {/if}

    {if $task == 'winners'}
        <h2 style="color: #504F4F; float: left;  font-family: 'MyriadPro-CondIt'; font-size: 28px;  max-width: 520px;">Купить футболку победители</h2>
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

	{if $filters.category}
		<select class="tags">
			<option value="">Коллекции</option>
			{foreach from=$TAGS item="t"}
			<option value="{$t.slug}" {if $TAG.slug == $t.slug}selected="selected"{/if} _category="{$filters.category}">{$t.name}</option>
			{/foreach}
		</select>	
		{literal}
			<style>
				.pageTitle h1 {width:344px!important;}
			</style>			
			<script>
			$(document).ready(function() { 	
				$('.pageTitle select.tags').change(function(){
					if ($(this).val().length > 0)
						location.href ='/tag/'+ $(this).val()+'/category,'+ $(this).children('option:selected').attr('_category')+'/'; 
				});
			});
			</script>
		{/literal}	
	{/if}


<div class="b-filter_tsh {$PAGE->lang}">
	<a href="{$link}/new/ajax/" rel="nofollow" class="new-filter {if $orderBy == 'new' || $orderBy == ''}active{/if}" title="{$L.GOOD_new}">{*Новое*}</a>
	<a href="{$link}/popular/ajax/" rel="nofollow" class="pop-filter {if $orderBy == 'popular'}active{/if}" title="{$L.GOOD_popular}">{*Популярное*}</a>
	<a href="{$link}/grade/ajax/" rel="nofollow" class="score-filter {if $orderBy == 'grade'}active{/if}" title="{$L.GOOD_rating}">{*По оценке*}</a>
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
                    $(this).addClass('active');
                    var gall_link = $(this).attr('href');
                    $.get(gall_link, function(content){
						$(".list_wrap ul").html(content);
                        $("#loadding-cover").remove();
                        initShowMore();
						
						$(btn_nextpage).parent().css('display','none');
						window.autoscrol_count = 1;
                    });
                }
                return false;
            });
        });
    </script>
{/literal}
</div>

{if $photos}
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


<!-- Фильтр в левом сайдбаре -->
{include file="catalog/list.sidebar.tpl"}


<div class="catalog_goods_list {if !$filters.category}neformat{/if}">
		

	<div class="list_wrap {$filters.category}">
		<ul {if !$filters.category}style="height: {$ULheight+100}px;"{/if}>
			{if $filters.category}
				{include file="catalog/list.goods.photos.tpl"}
			{else}
				{include file="catalog/list.goods.tpl"}
			{/if}
		</ul>
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

{if !$user_id && !$filters && $TAG.details_value}
<br clear="all" />
<p style="font-size:12px;">{$TAG.details_value}</p>
<br clear="all" />
<br />
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

{* if $module == 'catalog' *}
	{include file="catalog/list.seotexts.tpl"}
{* /if *}

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