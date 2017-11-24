{literal}

    <script type="text/javascript">

        // инициализация подгрузки страницы аджаксом
        function initShowMore(){
            $("#show-more-link").unbind('click').bind('click',function(){ showMore(); return false; });

            var sc = $.cookie('catalog_autoscroll');
            if (sc === '1') $('.allpager').hide();
            $('.allpager').unbind('click').bind('click',function(){
                $.cookie('catalog_autoscroll',1, { expires: 1 });
                pScroll();
                return false;
            });
        }

        $(document).ready(function(){
            //инициализируем лайканье для работ
            $('.vote').each(function(){
                if (!this.vote) this.vote = new vote({ div: this, id: parseInt($(this).attr('_id')) });
            });
			
			if(!ismobiledevice){
				//показ цены при наведение на работу
				$('.m12').hover(
						function(){
							var btn = $(this).find('.btn_editing').css('visibility','visible').show();
							/*if (btn.length == 0)
								$(this).find('.price').css('visibility','visible').show();*/
						},
						function(){ $(this).find('.btn_editing, .price1').css('visibility','hidden').hide(); }
				);

				$('.m12 span.list-img-wrap, .m12 span.list-img-wrap-hidden').hover(function(){ $(this).parents('.m12').find('.zoom').show(); },function(a){ if ($(a.toElement?a.toElement:a.relatedTarget).hasClass('zoom')) return; $(this).parents('.m12').find('.zoom').hide(); });

				$('.m12 .zoom').unbind('click').bind('click', function(){
					trackUser('Zoom_catalog_list','вызов окна Зумa','');//трек гугл аналитик
					tb_show('', $(this).attr('href')+'?KeepThis=true&height=520&width=910');
					return false;
				});
			}
            //инициализируем "Показать ещё"
            initShowMore();
        });
    </script>
{/literal}

{if $rest <= 0 || !$rest}
	{literal}<script type="text/javascript">
		var itWasTheLastLoading =true;//это была последняя подгрузка
		$(document).ready(function(){$(window).unbind('scroll',pScroll);  });
	</script>{/literal}
{/if}

{*
<div class="b-one-item">
	<div class="title">
		<span>"{$g.good_name}"<br/>
			автор <a class="author" href="/profile/{$g.user_id}/"  rel="nofollow">{$g.user_login}</a>
		</span>
		<div class="star">			
			<a class=" {if $g.selected} selected {/if}" href="/ajax/add_to_selected/{$g.good_id}/"  rel="nofollow"></a>
		</div>
	</div>			
	<a href="/nakleiki/{if $auto}auto{elseif $posters}poster{elseif $stickers}sticker{else}phones/apple/iphone-4{/if}/{$g.good_id}/"  rel="nofollow" title="{$g.good_name} - {if $auto}наклейки на авто{elseif $posters}постер на заказ{elseif $stickers}стикеры{else}наклейки на iphone{/if}"><img class="preview" src="http://www.maryjane.ru{$g.picture_path}" width="180" height="184" alt="{$g.good_name} - {if $auto}наклейки на авто{elseif $posters}постер на заказ{elseif $stickers}стикеры{else}наклейки на iphone{/if}" style="background: #{$g.hex}" /></a>	
	{if $g.oncar}
	<a href="/nakleiki/auto/{$g.good_id}/" rel="nofollow"><div class="available4auto">
		Наклейка для Вашей автомашины
	</div></a>		
	{/if}	
</div>
*}
{foreach from=$goods item="g" key="k"}
    <li class="m12" page="{$page}">
        <a rel="nofollow" class="item" title="{$g.good_name} - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" href="{if $g.link}{$g.link}{else}/catalog/{$g.user_login}/{$g.good_id}/auto/{/if}">
            <span class="list-img-wrap">
				{if $mode == 'preview'}			
					<img title="{$g.good_name} - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" alt="{$g.good_name} - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" style="background-color: #{$g.bg};" src="{$g.picture_path}" width="230" height="235"/>
				{else}
					<img title="{$g.good_name} от {$g.user_login} - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" alt="{$g.good_name} от {$g.user_login} - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" style="background-color: #{$g.bg};" src="{$g.preview}" width="230" height="235" />
				{/if}
			</span>
            <span class="list-img-wrap-hidden" >		
				{if $mode == 'preview'}	
					<img title="{$g.good_name} от {$g.user_login} - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" alt="{$g.good_name} от {$g.user_login} - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" style="background-color: #{$g.bg};" src="{$g.preview}" width="230" height="235" />
				{else}
					<img title="{$g.good_name} - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" alt="{$g.good_name} - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" style=	"background-color: #{$g.bg};" src="{$g.picture_path}" width="230" height="235"/>
				{/if}			
			</span>
        </a>
        <a rel="nofollow" {if $mode != 'preview'}style="left:201px;top:212px;"{/if} href="{if $g.zoomlink}{$g.zoomlink}{else}{if $g.link}{$g.link}zoom/{else}/catalog/{$g.user_login}/{$g.good_id}/zoom/category,auto/{/if}{/if}" class="zoom"></a>

        <div class="infobar">
            {if $g.good_status != "voting"}
                <div class="vote_count vote_count_id_{$g.good_id} {if $g.place > 0}red_vote{/if}">
                    <span>{$g.likes}</span>
                </div>
            {/if}
            <div class="preview_count">{$g.visits_catalog}</div>
            {if $USER->authorized && ($USER->user_id == $user_id || $USER->user_id == 27278 || $USER->user_id == 6199 || $USER->user_id == 196640)}
                <a class="btn_editing" href="/senddrawing.design/{$g.good_id}/" rel="nofollow"></a>
            {/if}			
			
			{if !$MobilePageVersion && $USER->user_id != $user_id}	
				<a rel="nofollow" href="{if $g.zoomlink}{$g.zoomlink}{else}{if $g.link}{$g.link}zoom/{else}/catalog/{$g.user_login}/{$g.good_id}/zoom/category,auto/{/if}{/if}" class="zoom-orang"></a>
			{/if}
        </div>
        <div class="vote{if $g.liked} select{/if} {if $g.place > 0} heart_red {/if}" _id="{$g.good_id}" like-url="/ajax/like/{$g.good_id}/ps_src/" ><span>{if $g.good_status != "voting"}{$g.likes}{else}-{/if}</span></div>
        <div class="item">
            <a {if $k > 2}rel="nofollow"{/if} class="title" title="{$g.good_name} - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" href="{if $g.link}{$g.link}{else}/catalog/{$g.user_login}/{$g.good_id}/auto/{/if}">{$g.good_name}</a>			
			
            <!--зелёный блок-->
            <!--noindex--><span style="background-color:#00a851;color:#fff;" class="price">от 55{*if $task == "1color"}55{else}120{/if*}&nbsp;руб.</span> 	<!--/noindex-->
            {if !$user}
                <!--noindex--><span class="author"><a rel="nofollow" title="Дизайнер {$g.user_login}" href="/catalog/{$g.user_login}/">{$g.user_login}</a> {$g.user_designer_level}</span><!--/noindex-->
            {/if}

            {if ($g.disabled || $g.hidden) && ($USER->user_id == $user_id || $USER->user_id == 27278 || $USER->user_id == 6199)}
				<span class="author" style="color:#F00; font-style:italic; font-size:11px; font-weight:bold">работа отключена</span>
				<span class="help"><a href="/faq/171/?height=500&amp;width=600&amp;positionFixed=true" rel="nofollow" class="help thickbox">?</a></span>
			{/if}

            {if $g.no_src && ($USER->user_id == $user_id || $USER->user_id == 27278 || $USER->user_id == 6199)}
                <span class="author" style="color:#F00; font-style:italic; font-size:11px; font-weight:bold">отсутствует исходник</span>
            {/if}

            {*if $g.place}
            <a rel="nofollow" href="/blog/view/1511/" class="b-ordensml">
                <img alt="Победитель принтшопа" src="/images/icons/bg-orden-sml.gif">
            </a>
            {/if*}
			
			<!--noindex-->{if $g.city_name && !$user}<div class="autor-city">{$g.city_name}</div>{/if}<!--/noindex-->
			
			
			{if $USER->user_id == 27278 || $USER->user_id == 6199}
				<div class="r-m12-menu">
					<a href="#" title="" rel="nofollow">теги</a>
					<span>|</span>
					<a href="#" title="" class="hideDesign" _id="{$g.good_id}" _style="{$style_id}" rel="nofollow">{if $g.hidden}показать{else}скрыть{/if}</a>
					{if $g.good_status == 'archived'}
					<span>|</span>
					<a href="#" title="" class="promote2" _id="{$g.good_id}" _to="pretendent" rel="nofollow">победитель</a>
					{/if}
					{if $g.good_status == 'pretendent'}
					<span>|</span>
					<a href="#" title="" class="promote2" _id="{$g.good_id}" _to="archived" rel="nofollow">архив</a>
					{/if}
				</div>
			{/if}
        </div>
    </li>
{/foreach}

{if $rest > 0}
    <div class="div-show-more" style="position:relative;height:119px;clear:both;width:{if $task == 'auto'}978{else}772{/if}px;left:0;border-bottom:1px solid #d7d7d7;border-right:2px solid #d7d7d7;" parentlist="true">
        <a class="show-more-link withFont" id="show-more-link" href="{$link}/page/{$page + 1}/{if $SEARCH}?q={$SEARCH}{/if}" rel="nofollow"><font>Ещё {$rest} из {$total}</font></a><div class="allpager" style="left: 61%;" title="Включить автолистание"></div>
    </div>
{/if}