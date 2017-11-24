{literal}
<script type="text/javascript">
	
	$(document).ready(function(){ 
		//инициализируем лайканье для работ
		$('.vote, .vote-small').each(function(){ 
			if (!this.vote) this.vote = new vote({ div: this, id: parseInt($(this).attr('_id')), style_id: {/literal}{$style_id}{literal} });
		});
		
		if(!ismobiledevice){
			//показ цены при наведение на работу
			$('.m12').hover(
				function(){ 
					var btn = $(this).find('.btn_editing').css('visibility','visible').show();				
					//if (btn.length == 0)
						//$(this).find('.price').css('visibility','visible').show(); 
				}, 
				function(){ $(this).find('.btn_editing, .price1').css('visibility','hidden').hide(); }
			);
		}
		
		$('.m12 span.list-img-wrap').hover(function(){$(this).parents('.m12').find('.zoom').show(); },function(a){ if ($(a.toElement?a.toElement:a.relatedTarget).hasClass('zoom')) return; $(this).parents('.m12').find('.zoom').hide(); });	
		
		$('.m12 .zoom').unbind('click').bind('click', function(){
										
			trackUser('Zoom_catalog_list','вызов окна Зумa','');//трек гугл аналитик
			tb_show('', $(this).attr('href')+'?KeepThis=true&height=520&width=910');		
						
			return false;
		});
		
		
		
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
<div class="catItem">
	<img src="{$item.picture_path}" alt="Наклейка на {$item.style_name} - {$item.good_name}" />
	<div class="catDesc"><span><i>{$item.style_name}<br /> <!--noindex-->работа - &laquo;<!--/noindex-->{$item.good_name}&raquo;<br /><!--noindex-->автор -<!--/noindex--> {$item.user_login}</i><div class="star"><a href="#" class="selected {if $item.selected} active {/if}" id="g{$item.skin_id}" rel="nofollow"></a></div></span><a href="/{$module}/{$item.user_login}/{$item.good_id}/category,ipodmp3/#cases" title="Наклейка на {$item.style_name} - {$item.good_name}"  rel="nofollow"><img src="{$item.picture_path}" alt="Наклейка на {$item.style_name} - {$item.good_name}" title="Наклейка на {$item.style_name} - {$item.good_name}" /></a>
		<b class="catPrice"><!--noindex-->{$item.price}.-<!--/noindex--></b>
	</div>      
</div>
*}
{foreach from=$goods item="g" key="k"}	
		<li class="m12 {if $g.hidden && $g.user_id != $USER->id}hidden{/if}" page="{$page}">
			<a rel="nofollow" class="item" title="{$g.good_name} - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" href="{if $g.link}{$g.link}{else}/catalog/{$g.user_login}/{$g.good_id}/category,ipodmp3/#{$style_slug}{/if}">
				<span class="list-img-wrap"><img title="{$g.good_name} - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" alt="{$g.good_name} - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if} " style="background-color: #{$g.bg};" src="{$g.picture_path}" /></span>
			</a>	

			<a rel="nofollow" href="/catalog/{$g.user_login}/{$g.good_id}/{$style_slug}/zoom/" class="zoom"></a>
			
			{if $USER->authorized && ($USER->user_id == $user_id || $USER->user_id == 27278 || $USER->user_id == 6199  || $USER->user_id == 105091)}
				<a class="btn_editing" href="/senddrawing.design/{$g.good_id}/" rel="nofollow"></a>
			{/if}
			<div class="vote{if $g.liked} select{/if} {if $g.place > 0} heart_red {/if}" {* like-name="phones" *} _id="{$g.good_id}"><span>{if $g.good_status != "voting"}{$g.likes}{else}-{/if}</span></div>
			
			<div class="infobar">
				{if $g.good_status != "voting"}
				<div class="vote-small vote_count vote_count_id_{$g.good_id} {if $g.liked} select{/if} {if $g.place > 0}red_vote{/if}" _id="{$g.good_id}">
					<span>{$g.likes}</span>
				</div>
				{/if}
				<div class="preview_count">{$g.visits_catalog}</div>				
			</div>
			<div class="item">
				<a {if $k > 2}rel="nofollow"{/if} class="title" title="{$g.good_name} - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" href="{if $g.link}{$g.link}{else}/catalog/{$g.user_login}/{$g.good_id}/category,ipodmp3/#{$style_slug}{/if}">{$g.good_name}</a>
				
				{if !$user} 
				<!--noindex--><span class="author">автор&nbsp;&mdash;&nbsp;<a rel="nofollow" title="Дизайнер {$g.user_login}" href="/catalog/{$g.user_login}/">{$g.user_login}</a> {$g.user_designer_level}</span><!--/noindex-->
				{/if}
				
				{if ($g.disabled || $g.hidden) && ($USER->user_id == $user_id || $USER->user_id == 27278 || $USER->user_id == 6199)}
				<span class="author" style="color:#F00; font-style:italic; font-size:11px; font-weight:bold">работа отключена</span>
				<span class="help"><a href="http://www.maryjane.ru/blog/view/5041/" rel="nofollow" class="help">?</a></span>
				{/if}
				
				{if $g.no_src && ($USER->user_id == $user_id || $USER->user_id == 27278 || $USER->user_id == 6199)}
				<span class="author" style="color:#F00; font-style:italic; font-size:11px; font-weight:bold">отсутствует исходник</span>
				{/if}
				
				{*if $g.place}
				<a rel="nofollow" href="/blog/view/1511/" class="b-ordensml">
					<img alt="Победитель принтшопа" src="/images/icons/bg-orden-sml.gif">
				</a>
				{/if*}
				
				{*<!--noindex-->{if $g.city_name && !$user}<div class="autor-city">{$g.city_name}</div>{/if}<!--/noindex-->*}
				<br/>
				<!--зелёный блок-->
				<!--noindex--><span class="price">{$g.price}&nbsp;{$L.CURRENT_CURRENCY}</span><!--/noindex-->
			</div>			
			{if $USER->user_id == 27278 || $USER->user_id == 6199}
				<div class="r-m12-menu">
					<a href="#" title="" rel="nofollow" class="edittags" _id="{$g.good_id}">теги</a>
					<span>|</span>
					<a href="#" title="" class="hideDesign" _id="{$g.good_id}" _style="{$style_id}" rel="nofollow">{if $g.hidden}показать{else}скрыть{/if}</a>
					<span>|</span>
					<a href="#" title="" class="disableDesign" _id="{$g.good_id}" _style="{$style_id}" _category="{$filters.category}" rel="nofollow">{if $g.hidden}вкл.{else}выкл.{/if}</a>
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
		</li>
{/foreach}

{if $rest > 0}
<div style="position:relative;float:left;width:100%;left:0px;" parentlist="true">
<a class="show-more-link withFont" id="show-more-link"  href="{$link}/page/{$page + 1}/ajax/{if $SEARCH}?q={$SEARCH}{/if}" rel="nofollow"><font>Показать ещё {*$rest*}</font></a><div class="allpager" title="Включить автолистание"></div>
</div>
{/if}