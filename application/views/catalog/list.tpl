{literal}
<style>
	.author_username {float: none;}
	.selected51no {background:url(/images/to-vote-min-gray.png) no-repeat 0 -22px !important;}
	.selected51no:hover {background:url(/images/to-vote-min-gray.png) no-repeat 0 -22px !important;}
	.selected51 {background:url(/images/to-vote-min.png) no-repeat 0 -22px !important;}
	.selected51:hover {	background:url(/images/to-vote-min.png) no-repeat 0 -22px !important;}
	.blockAuthor {margin-top: 13px;}
	/*.b-catalog_v2.moveSidebarToLeft .sidebar_filter{margin-top: -8px;}*/
</style>
<!--[if IE]>
	<style>
	.list_wrap .item .list-img-wrap {margin-top:-3px !important;}
   </style>
<![endif]-->	
{/literal}

{if $filters.category != 'poster' && $goods|count > 0}
	{include file="catalog/list.fix.menu.tpl"}
	
	{if $MobilePageVersion}
		{include file="catalog/list.mobile.menu.tpl"}
	{/if}
{/if}	

{if $ACTIONS}
	<div class="actions clearfix">	
			
		<div class="color1">При регистрации, покупая на 3000 руб. вы получаете скидку 300 руб.</div>
		
		<div class="color2">Бесплатная доставка при заказе от 5000 руб.</div>
		
		<div class="color3">10% скидка в день рождения, действует на 1 заказ, в течении 7 дней.</div>
		
		{* <div class="color4">Скидка 200 руб, на новые принты в каталоге! Каждые 2 недели новые принты.</div> *}
		
		<div class="color5">Наклейки. При заказе 2-х наклеек в каталоге, 3-я бесплатно (по самой низкой цене).</div>
		
	</div>
{/if}

{if ($TAG.slug == 'nadpis_' || $TAG.slug == 'english') && !$filters.category && !$MobilePageVersion}
	{include file="notees/add_notees.tpl"}
	
	<div class="tabz clearfix BigNotees tabz3">
		<!--noindex-->
		<a href="/tag/nadpis_/" {if $TAG.slug == 'nadpis_'}class="active"{/if} rel="nofollow">На русском</a>	
		<a href="/tag/english/" {if $TAG.slug == 'english'}class="active"{/if} rel="nofollow">На английском</a>
		<a href="/tag/nadpis_/category,futbolki/kids/" rel="nofollow">Детские</a>
		<!--/noindex-->
	</div>
{/if}

{if !$MobilePageVersion && $Style->model_preview}
	<div class="blockDescriptionNOTgeneratedGadget clearfix">
		<img class="left" width="" height="" title="" alt="" src="{$Style->model_preview}"/>
		
		<div class="left">
			<h1>{if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}</h1>
			
			{$Style->style_composition}
		</div>
	</div>
{/if}

<div class="b-catalog_v2 moveSidebarToLeft">

	{if !$MobilePageVersion && $SEARCH}
		<div class="tabz clearfix">
			<a href="/search/?q={$SEARCH}" rel="nofollow" {if !$filters.good_status}class="active"{/if}>{$L.SHOPWINDOW_catalog}</a>
			<a href="/voting/competition/main/?q={$SEARCH}#goods" rel="nofollow">голосование</a>		
			<a href="/search/archived/?q={$SEARCH}" rel="nofollow" {if $filters.good_status == "archived"}class="active"{/if}>{$L.SHOPWINDOW_archive}</a>
			<a href="/blog/search/?q={$SEARCH}" rel="nofollow">блог</a>
			<a href="/faq/search/?q={$SEARCH}" rel="nofollow">помощь</a>	
		</div>
	{/if}

	{*if $category == 'phones'}
		<div class="top-banner-line980">
			<img  src="/images/banners/113.gif" alt="При покупке 2х наклеек третья наклейка бесплатно" title="При покупке 2х наклеек третья наклейка бесплатно" width="980" height="100" border="0" >
		</div>
	{/if*}
	
	
<div class="pageTitle table">

	{if !$Style->model_preview || $MobilePageVersion}		
		{if $SEARCH}
			<h1>{include file="catalog/list.search-filter.tpl"}</h1>
		{else if $PAGE->title}	
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
	{/if}

	{if !$user && !$MobilePageVersion}
		
		{*<a href="/tag/novuygod/{if $filters.category}{if $filters.category != 'poster' && $filters.category}category,{/if}{$filters.category}{if $filters.size && !$fsize_not_selected};size,{$filters.size}{/if}{if $filters.color && !$fcolor_not_selected};color,{$filters.color}{/if}/{if $filters.sex == 'female' || $filters.sex == 'kids'}{$filters.sex}/{/if}{if $style_slug && $filters.category != 'poster'}{$style_slug}/{/if}{/if}" title="Новый год" rel="nofollow" id="elka"></a>*}

		{*<a href="/catalog/{if $filters.category}{if $filters.category != 'poster' && $filters.category}category,{/if}{$filters.category}{if $filters.size && !$fsize_not_selected};size,{$filters.size}{/if}{if $filters.color && !$fcolor_not_selected};color,{$filters.color}{/if}/{if $filters.sex == 'female' || $filters.sex == 'kids'}{$filters.sex}/{/if}{if $style_slug && $filters.category != 'poster'}{$style_slug}/{/if}{/if}top2015/" title="Популярные в 2015 году5" rel="nofollow" id="top2015"></a>*}


		{*<a href="/23february/{if $filters.category}{if $filters.category != 'poster' && $filters.category}category,{/if}{$filters.category}{if $filters.size && !$fsize_not_selected};size,{$filters.size}{/if}{if $filters.color && !$fcolor_not_selected};color,{$filters.color}{/if}/{if $filters.sex == 'female'}female/{/if}{if $style_slug && $filters.category != 'poster'}{$style_slug}/{/if}{/if}new/" title="23 февраля" rel="nofollow" id="feb23"></a>*}

	
		{*<a href="/14february/{if $filters.category}{if $filters.category != 'poster' && $filters.category}category,{/if}{$filters.category}{if $filters.size && !$fsize_not_selected};size,{$filters.size}{/if}{if $filters.color && !$fcolor_not_selected};color,{$filters.color}{/if}/{if $filters.sex == 'female'}female/{/if}{if $style_slug && $filters.category != 'poster'}{$style_slug}/{/if}{/if}new/" title="14 февраля" rel="nofollow" id="feb14"></a>*}
	
		{*<a href="/8march/{if $filters.category}{if $filters.category != 'poster' && $filters.category}category,{/if}{$filters.category}{if $filters.size && !$fsize_not_selected};size,{$filters.size}{/if}{if $filters.color && !$fcolor_not_selected};color,{$filters.color}{/if}/{if $filters.sex == 'female'}female/{/if}{if $style_slug && $filters.category != 'poster'}{$style_slug}/{/if}{/if}new/" title="8 марта" rel="nofollow" id="marta8"></a>*}
	{/if}
	
    <!--изменить-->
    {if $user && ($USER->user_id == $user_id || $USER->user_id == 27278 || $USER->user_id == 6199 || $USER->user_id == 86455 || $USER->user_id == 105091)}
        {include file="catalog/form_change_name_shop.tpl"}
    {/if}
	
	{if !$user}
		{if $SEARCH && $MobilePageVersion}
			{*непоказываем*}
		{else}		
			<div class="NLtags{if $MobilePageVersion}-mobile{/if}">
				<select class="tags">
					<option value="">{$L.LIST_menu_collections}</option>
					
					{*<option value="14february" img="http://ic1.maryjane.ru/J/uploaded/2014/08/25/17cd9dc6fb31fc1f36ed98816a65f0b0.jpeg"  {if $TAG.slug == '14february'}selected="selected"{/if} _category="{$filters.category}">14 февраля</option>
					<option value="23february" img="http://ic2.maryjane.ru/J/uploaded/2014/07/28/da96fa4fe6c327ec4032b777d2d65e05.jpeg" _collection="true"  {if $TAG.slug == '23february'}selected="selected"{/if} _category="{$filters.category}">23 февраля</option>*}
					{foreach from=$TAGS item="t"}
						<option value="{$t.slug}" img="{$t.picture_path}"  {if $TAG.slug == $t.slug}selected="selected"{/if} _category="{$filters.category}">{$t.name}</option>
					{/foreach}
					
					{*<option value="14february" {if $PAGE->module == '14february'}selected="selected"{/if} _collection="true" _category="{$filters.category}">14 февраля</option>
					<option value="23february" {if $PAGE->module == '23february'}selected="selected"{/if} _collection="true"_category="{$filters.category}">23 февраля</option>
					<option value="8march" {if $PAGE->module == '8march'}selected="selected"{/if} _collection="true" _category="{$filters.category}">8 марта</option>*}
				</select>
			</div>	
			{literal}
				<script>
				$(document).ready(function() { 	
					$('.pageTitle select.tags').change(function(){
						var tag = $(this).val();
						var cat = $(this).children('option:selected').attr('_category');
						
						if (tag.length > 0) {
							location.href = (($(this).children('option:selected').attr('_collection')) ? '/' : '/tag/') + $(this).val()+'/'+(cat.length>0?'category,'+ $(this).children('option:selected').attr('_category')+'/':'');
						} 
					});
				});
				</script>
			{/literal}	
		{/if}
	{/if}

	{if !$MobilePageVersion}
	
		{if $goods|count > 0}
		
			<div class="b-filter_tsh">
				<a href="{$base_link}/new/ajax/" rel="nofollow" class="new-filter {if $orderBy == 'new' || $orderBy == 'shopwindow'}active{/if}" title="{$L.GOOD_new}" data-sort="new">{*Новое*}</a>
				<a href="{$base_link}/popular/ajax/" rel="nofollow" class="pop-filter {if $orderBy == 'popular'}active{/if}" title="{$L.GOOD_popular}" data-sort="popular">{*Популярное*}</a>
				<a href="{$base_link}/grade/ajax/" rel="nofollow" class="score-filter {if $orderBy == 'grade'}active{/if}" title="{$L.GOOD_rating}" data-sort="grade">{*По оценке*}</a>
			</div>
			        
		{/if}
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
						$(".b-filter_tsh a."+$(this).attr('class')).addClass('active');//так как еще есть меню верхнее(аналогичное)
						
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
	
	{if !$MobilePageVersion}
		{if ($user || $TAG  || $SEARCH || ($module=='catalog' && !$filters.category)) && $goods|count > 0}
			{literal}
				<style>
					.m12 .vote {right: 2px;top: 3px;}
					.b-filter_tsh {right:65px!important;}
				</style>
			{/literal}
			
			<div class="b-filter_view"><noindex>
				{if $user}
				<a href="/{$module}/{$user.user_login}/category,futbolki/new/ajax/" rel="nofollow" class="view-pipl" title=""></a>
				{elseif $TAG}
				<a href="{if $TAG.slug != $module}/tag{/if}/{$TAG.slug}/category,futbolki/new/ajax/" rel="nofollow" class="view-pipl" title=""></a>
				{else}
				<a href="{if $SEARCH}/catalog{/if}/{$module}/{if $SEARCH}{$SEARCH}/{/if}category,futbolki/new/ajax/" rel="nofollow" class="view-pipl" title=""></a>
				{/if}
				<a href="{$blink}ajax/" rel="nofollow" class="view-other active" title=""></a>
			</noindex></div>			
		{/if}
	{/if}
	
	<noindex>
	<script>
		var urlViews = ["{if $SEARCH}/catalog{/if}{if $user}/{$module}/{$user.user_login}/{elseif $TAG}{if $TAG.slug != $module}/tag{/if}/{$TAG.slug}/{else}/{$module}/{if $SEARCH}{$SEARCH}/{/if}{/if}category,futbolki/|%sort%|/ajax/", "{if $SEARCH}/catalog{/if}{if $user}/{$module}/{$user.user_login}/{elseif $TAG}{if $TAG.slug != $module}/tag{/if}/{$TAG.slug}/{else}/{$module}/{if $SEARCH}{$SEARCH}/{/if}{/if}|%sort%|/ajax/"];		
	</script>
	</noindex>
	
</div>


{if $photos}
	<!-- Слайдер -->
	<div class="kids_slider" style="padding-top:20px;margin-bottom:10px;margin-left: 100px;width: 880px;">
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

{if !$MobilePageVersion}
	<!-- Фильтр в левом сайдбаре -->
	{include file="catalog/list.sidebar.tpl"}
{/if}

<div class="catalog_goods_list neformat">		
	
	{if !$MobilePageVersion && !$filters.category && !$user}	
		<ul class="differentTag clearfix">
			{foreach from=$tags_collection item="tag"}
			<li {if $TAG.slug==$tag.slug}class="on"{/if}><a href="/tag/{$tag.slug}/new/">{$tag.title}</a></li>
			{/foreach}			
			{if $TAG.slug == "fotografiya"}
				<li><a href="/foto-na-futbolku/">Напечатать фото на футболку</a></li>
			{/if}
		</ul>
	{/if}
	
	<script type="text/javascript">
		var countElements = {if $count}{$count}{else}0{/if};
		var pageLoaded = {$page};
		var REQUEST_URI = '{$PAGE_URI}';
		var scrollPosafterGo = 850;
		{if $page > 1}var autoscrol_count = 0;{/if}
	</script>		
	{if $user && !$filters.category} {else}
	<script type="text/javascript">
		$(document).ready(function() {
			//инициализируем автоподгрузку страниц
			$(window).bind('scroll', pScroll);
		});
	</script>
	{/if}
		
    <div class="list_wrap">		
		
		{if $SEARCH && $goods|count == 0}
			<p style="padding:30px">По указанному запросу ничего не найдено <sup class="help"><a style="font-size: 9px;" href="/faq/group,27/view/187/?height=500&width=600" rel="nofollow" class="thickbox">?</a></sup></p>
			{include file="catalog/list.trackUser.out_of_media.tpl"}
		{/if}
		
		<ul style="height:{$ULheight+100}px">
			{if $list_tpl}
				{include file="catalog/$list_tpl"}
			{else}
				{include file="catalog/list.goods.tpl"}
			{/if}
        </ul> 

		{*постраничка*}	
		{include file="catalog/list.pagePagination.tpl"}

	</div>

</div>

{if $user && $MobilePageVersion && $USER->user_id == 6199}
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

{if  $TAG && $tag=='nadpis_'}
	<br clear="all"/><br/>
	{include file="catalog/slider.tpl"}
{/if}


{if $module == 'catalog' || $module == 'tag' || $TAG}
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
				$(".b-filter_view a."+$(self).attr('class')).addClass('active');//так как еще есть меню верхнее(аналогичное)
				
				 if ($(self).index() == 1) {
					$('.catalog_goods_list').addClass('neformat');
					//$('.list_wrap').removeClass('futbolki');
					$('.h4_select_color, .b-color-select').hide();
					$('.catalog_goods_list').removeClass('list_trjapki');
					$('.b-catalog_v2').removeClass('b-catalog_v3');
				 }else{ 
					$('.catalog_goods_list').removeClass('neformat');
					//$('.list_wrap').addClass('futbolki');
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