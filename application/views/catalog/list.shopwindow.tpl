{literal}<style>
	.author_username {float: none;}
	.blockAuthor {margin-top: 13px;}
	.b-catalog_v2.moveSidebarToLeft .sidebar_filter{margin-top: -8px;}
	/*.b-catalog_v2.list_main_user .list_wrap ul.userW .m12 .r-m12-menu a {text-transform: uppercase;font-size:10px;}*/
</style>{/literal}

<script type="text/javascript" src="/js/vote_catalog.js"></script>{*не на людях надо*}
<script type="text/javascript" src="/js/2012/autoscroll.js"></script>

<script type="text/javascript">
	var countElements = {if $count}{$count}{else}0{/if};
	var pageLoaded = {if $page}{$page}{else}1{/if};
	var REQUEST_URI = '{$PAGE_URI}';
	var scrollPosafterGo = 850;
</script>		
<script type="text/javascript">
	$(document).ready(function() {
		//инициализируем автоподгрузку страниц
		$(window).bind('scroll', pScroll);
	});
</script>

{*======зум========*}
{*if !$MobilePageVersion}	
<script type="text/javascript" src="/js/cloud-zoom.1.0.2_list.js"></script>
{literal}<script type="text/javascript">	
	$(document).ready(function () {		
		$('.cloud-zoom').CloudZoom();	

		$('.list_main_user .list_wrap').on('click','ul.userW li.m12 .mousetrap',function (){
			document.location = $(this).prev().attr('href');
		});
	});	
</script>{/literal}{/if*}

{include file="catalog/list.fix.menu.tpl"}

	
{if $MobilePageVersion}
	{include file="catalog/list.mobile.menu.tpl"}
{/if}


<div class="b-catalog_v2 moveSidebarToLeft {if $user}b-catalog_v3 list_main_user{/if}">

	{if $SEARCH}
		<div class="tabz clearfix">
			<a href="#" rel="nofollow" class="active">{$L.SHOPWINDOW_catalog}</a>
			<a href="/blog/search/?q={$SEARCH}" rel="nofollow">{$L.SHOPWINDOW_blogs}</a>
			<a href="/faq/search/?q={$SEARCH}" rel="nofollow">f.a.q</a>
		</div>
	{/if}

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

	{*<a href="/8march/{if $filters.category}{if $filters.category != 'poster' && $filters.category}category,{/if}{$filters.category}{if $filters.size && !$fsize_not_selected};size,{$filters.size}{/if}{if $filters.color && !$fcolor_not_selected};color,{$filters.color}{/if}/{if $filters.sex == 'female'}female/{/if}{if $style_slug && $filters.category != 'poster'}{$style_slug}/{/if}{/if}" title="Подарки к 8 марта" rel="nofollow" id="zvezda"></a>*}
	
	
	{if !$MobilePageVersion}
		<div class="author-sharing-btn">
			<script type="text/javascript" src="//yandex.st/share/share.js" charset="utf-8"></script>
			<div class="yashare-auto-init" data-yashareL10n="ru" data-yashareType="link" data-yashareQuickServices=""></div> 					
		</div>		
		{literal}<script type="text/javascript">	
		$(document).ready(function () {			
			if($('.your-link').length && $('.your-link .b-ref-link-1111').length){
				$('body').on('click','.b-share-popup-wrap a',function (){
					$('.your-link a.form_link').hide();
					getPromoCode();
				});			
			}
		});</script>{/literal}
	{/if}	
	
	
    <!--изменить-->
    {if $user && ($USER->user_id == $user_id || $USER->user_id == 27278 || $USER->user_id == 6199 || $USER->user_id == 86455)}
        {include file="catalog/form_change_name_shop.tpl"}
    {/if}


    {if $TAG}
        <div class="b-filter_tsh {$Page.lang}">
            <a href="{if $TAG.slug != $module}/tag{/if}/{$TAG.slug}/new/ajax/{if $filters.category}category,{$filters.category}{if $filters.size && !$fsize_not_selected};size,{$filters.size}{/if}{if $filters.color && !$fcolor_not_selected};color,{$filters.color}{/if}/{/if}{if $filters.sex == 'female'}female/{/if}" rel="nofollow" class="new-filter {* if $orderBy == 'new'}active{/if *}" title="{$L.GOOD_new}" data-sort="new">{*Новое*}</a>
            <a href="{if $TAG.slug != $module}/tag{/if}/{$TAG.slug}/popular/ajax/{if $filters.category}category,{$filters.category}{if $filters.size && !$fsize_not_selected};size,{$filters.size}{/if}{if $filters.color && !$fcolor_not_selected};color,{$filters.color}{/if}/{/if}{if $filters.sex == 'female'}female/{/if}" rel="nofollow" class="pop-filter {if $orderBy == 'popular'}active{/if}" title="{$L.GOOD_popular}" data-sort="popular">{*Популярное*}</a>
            <a href="{if $TAG.slug != $module}/tag{/if}/{$TAG.slug}/grade/ajax/{if $filters.category}category,{$filters.category}{if $filters.size && !$fsize_not_selected};size,{$filters.size}{/if}{if $filters.color && !$fcolor_not_selected};color,{$filters.color}{/if}/{/if}{if $filters.sex == 'female'}female/{/if}" rel="nofollow" class="score-filter {if !$orderBy || $orderBy == 'grade' || $orderBy == 'place'}active{/if}" title="{$L.GOOD_rating}" data-sort="grade">{*По оценке*}</a>
        </div>
    {elseif $filters.category == 'poster'}
        <div class="b-filter_tsh {$Page.lang}">
            <a href="/{$module}/poster/new/" rel="nofollow" class="new-filter {if $orderBy == 'new'}active{/if}" title="{$L.GOOD_new}" data-sort="new">{*Новое*}</a>
            <a href="/{$module}/poster/popular/" rel="nofollow" class="pop-filter {if $orderBy == 'popular' || $orderBy == ''}active{/if}" title="{$L.GOOD_popular}" data-sort="popular">{*Популярное*}</a>
            <a href="/{$module}/poster/grade/" rel="nofollow" class="score-filter {if $orderBy == 'grade'}active{/if}" title="{$L.GOOD_rating}" data-sort="grade">{*По оценке*}</a>
        </div>

    {else}

        {if !$SEARCH}

			{if $user}
				<div class="b-filter_tsh {$Page.lang}">
					<a href="/{$module}/{$user.user_login}/new/" rel="nofollow" class="new-filter {if $orderBy == 'new' || $orderBy == 'shopwindow'}active{/if}" title="{$L.GOOD_new}" data-sort="new">{*Новое*}</a>
					<a href="/{$module}/{$user.user_login}/popular/" rel="nofollow" class="pop-filter {if $orderBy == 'popular'}active{/if}" title="{$L.GOOD_popular}" data-sort="popular">{*Популярное*}</a>
					<a href="/{$module}/{$user.user_login}/grade/" rel="nofollow" class="score-filter {if $orderBy == 'grade'}active{/if}" title="{$L.GOOD_rating}" data-sort="grade">{*По оценке*}</a>
				</div>			
			{else}		
				<div class="b-filter_tsh {$Page.lang}">
					<a href="/{$module}{if $user}/{$user.user_login}{/if}/new/ajax{if $filters.category}/category,{$filters.category}{if $filters.size && !$fsize_not_selected};size,{$filters.size}{/if}{if $filters.color && !$fcolor_not_selected};color,{$filters.color}{/if}/{/if}{if $filters.sex != 'male'}{$filters.sex}/{/if}" rel="nofollow" class="new-filter {if $orderBy == 'new' || $orderBy == 'shopwindow'}active{/if}" title="{$L.GOOD_new}" data-sort="new">{*Новое*}</a>
					<a href="/{$module}{if $user}/{$user.user_login}{/if}/popular/ajax{if $filters.category}/category,{$filters.category}{if $filters.size && !$fsize_not_selected};size,{$filters.size}{/if}{if $filters.color && !$fcolor_not_selected};color,{$filters.color}{/if}/{/if}{if $filters.sex != 'male'}{$filters.sex}/{/if}" rel="nofollow" class="pop-filter {if $orderBy == 'popular'}active{/if}" title="{$L.GOOD_popular}" data-sort="popular">{*Популярное*}</a>
					<a href="/{$module}{if $user}/{$user.user_login}{/if}/grade/ajax{if $filters.category}/category,{$filters.category}{if $filters.size && !$fsize_not_selected};size,{$filters.size}{/if}{if $filters.color && !$fcolor_not_selected};color,{$filters.color}{/if}/{/if}{if $filters.sex != 'male'}{$filters.sex}/{/if}" rel="nofollow" class="score-filter {if $orderBy == 'grade'}active{/if}" title="{$L.GOOD_rating}" data-sort="grade">{*По оценке*}</a>
				</div>
			{/if}
			
        {/if}
        
    {/if}

	<!-- Скрипт для работы этого фильтра  -->
	{literal}
	    <script type="text/javascript">
			$(document).ready(function(){
	            
				$(".b-filter_tsh a").click(function(){
					var self = this;
					if (!$(this).hasClass('active')) {
	                    $(".catalog_goods_list .list_wrap").append('<div class="loadding-cover" id="loadding-cover"></div>');
	                    setTimeout(function(){$("#loadding-cover").remove();}, 3000);
	
	                    $(".b-filter_tsh a").removeClass('active');
	                    //$(this).addClass('active');
						$(".b-filter_tsh a."+$(self).attr('class')+'"').addClass('active');//так как еще есть меню верхнее(аналогичное)
						
	                    var gall_link = $(this).attr('href');
	                    $.get(gall_link, function(content){
							$(".list_wrap ul").html(content);
	                        $("#loadding-cover").remove();
							initShowMore();
							
							var sort = $(self).attr('data-sort');
							$('.b-filter_view a').each(function(){
								var url = urlViews[$(this).index()].replace('|%sort%|', sort);
								$(this).attr('href', url);
							});
							
							
							//if (!MobilePageVersion && !$('.catalog_goods_list').hasClass('neformat'))$('.cloud-zoom').CloudZoom();
							
							
							$(btn_nextpage).parent().css('display','none');
							window.autoscrol_count = 1;
	                    });
	                }
	                return false;
	            });
	        });
	    </script>
	{/literal}

	{if $user}
		{literal}
			<style>
				.list_wrap ul li.m12 {padding: 3px 35px 10px;}
				.m12 .vote {right: 2px;top: 3px;}
				.b-filter_tsh {right:65px!important;}
			</style>
		{/literal}
		
		<div class="b-filter_view"><noindex>
			{if $user}
			<a href="/{$module}/{$user.user_login}/" rel="nofollow" class="view-pipl active" title=""></a>
			{elseif $TAG}
			<a href="/tag/{$TAG.slug}/category,futbolki/new/ajax/" rel="nofollow" class="view-pipl" title=""></a>
			{else}
			<a href="/{$module}/category,futbolki/new/ajax/" rel="nofollow" class="view-pipl" title=""></a>
			{/if}
			<a href="{if $blink}{$blink}{else}/{$module}/{$user.user_login}/{/if}ajax/" rel="nofollow" class="view-other {if !$user}active{/if}" title=""></a>
		</noindex></div>
		
	{/if}

	<noindex>
	<script>
		var urlViews = ["/{$module}/{$user.user_login}/|%sort%|/", "/{$module}/{$user.user_login}/|%sort%|/ajax/"];
	</script>
	</noindex>
</div>


{*if $photos}
	<!-- Слайдер -->
	<div class="kids_slider" style="padding-top:20px;margin-bottom:10px;margin-left: 100px;width: 880px">
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
{/if*}

	<!-- Фильтр в левом сайдбаре -->
	{include file="catalog/list.sidebar.tpl"}

	<div class="catalog_goods_list">
		<div class="list_wrap">		
			
			{if $shopwindow|count == 0}
				{if $SEARCH}
				
					<p style="padding:30px">По указанному запросу ничего не найдено</p>
					{include file="catalog/list.trackUser.out_of_media.tpl"}
				
				{else}
				
					<p style="padding:30px">Автор не загрузил ещё ни одной работы</p>	
				
				{/if}
			
			{/if}
			
			{if $offerAccepted}
			<p style="padding:30px;text-align: center;background-color: #d1fce3">Спасибо за то что приняли наше предложение</p>
			{/if}
			
			{if $user}	
			<ul style="height: {$ULheight+100}px;" class="userW">								
				{include file="catalog/list.goods.user_shopwindow.tpl"}
			</ul>
			{else}
			<ul style="height: {$ULheight+100}px;">		
				{if $list_tpl}
					{include file="catalog/$list_tpl"}
				{else}
					{include file="catalog/list.goods.tpl"}
				{/if}			
			</ul>
			{/if}
		</div>
	</div>
</div>

{*if $photos}
	<!-- Слайдер -->
	<div style="clear:both"></div>
	<div style="margin-left: 100px;width: 870px;clear:both;margin-top:55px;padding:4px;">
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
{/if*}


<br clear="all" />
{if !$user_id && $prev_tag}
	<div  style="float:left;margin-top:20px;width:250px; overflow:hidden; white-space: nowrap;">&larr;&nbsp;<a href="/tag/{$prev_tag.slug}/" rel="nofollow" >{$prev_tag.title}</a></div>
{/if}
<br />
{if !$user_id && $next_tag}
	<div  style="float:right;margin-top:10px;text-align:right;width:250px; overflow:hidden; white-space: nowrap;"><a href="/tag/{$next_tag.slug}/">{$next_tag.title}</a>&nbsp;&rarr;</div>
{/if}



{if $user_id && ($next_user || $prev_user)}
<br clear="all" />

    {if $prev_user}
    <div  style="float:left;margin-top:20px">&larr;&nbsp;<a href="/{$module}/{$prev_user.user_login}/" rel="nofollow">{$prev_user.user_login}</a></div>
    {/if}
    {if $next_user}
    <div  style="float:right;margin-top:20px"><a href="/{$module}/{$next_user.user_login}/">{$next_user.user_login}</a>&nbsp;&rarr;</div>
    {/if}

<br clear="all" />
{/if}

{if  $TAG && $tag=='nadpis_'}
	<br clear="all"/><br/>
	{include file="catalog/slider.tpl"}
{/if}


{if $module == 'catalog' || $module == 'tag'}
	<div class="list_seotexts">
		{include file="catalog/list.seotexts.tpl"}
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
				
				//$(self).addClass('active');
				$(".b-filter_view a."+$(self).attr('class')+'"').addClass('active');//так как еще есть меню верхнее(аналогичное)
		

				if ($(self).index() == 1) {
					$('.catalog_goods_list').addClass('neformat');
					$('.h4_select_color, .b-color-select').hide();
					//$('.list_wrap').removeClass('futbolki');		
					//$('.catalog_goods_list').removeClass('list_trjapki');
					$('.b-catalog_v2').removeClass('b-catalog_v3');
					$('.b-catalog_v2').removeClass('list_main_user');	
				 }else{ 
					//if (!MobilePageVersion)$('.cloud-zoom').CloudZoom();
					$('.catalog_goods_list').removeClass('neformat');
					$('.h4_select_color, .b-color-select').show();					
					//	$('.list_wrap').addClass('futbolki');	
					$('.b-catalog_v2').addClass('b-catalog_v3');
					$('.b-catalog_v2').addClass('list_main_user');						
					$('.list_wrap >ul').addClass('userW');	
					//$('.catalog_goods_list').addClass('list_trjapki');
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