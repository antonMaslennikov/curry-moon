{literal}
<script type="text/javascript">
	
	$(document).ready(function(){ 
		//инициализируем лайканье для работ
		$('.vote').each(function(){ 
			if (!this.vote) this.vote = new vote({ div: this, id: parseInt($(this).attr('_id')), style_id: {/literal}{$style_id}{literal} });
		});
		
		if(!ismobiledevice){
			//показ цены при наведение на работу
			$('.m12').hover(
				function(){ 
					var btn = $(this).find('.btn_editing').css('visibility','visible').show();				
					//if (btn.length == 0)
						$(this).find('.price').css('visibility','visible').show(); 
				}, 
				function(){ $(this).find('.btn_editing, .price').css('visibility','hidden').hide(); }
			);
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


{foreach from=$goods item="g" key="k"}	
		<li class="m12 {if $g.hidden && $g.user_id != $USER->id}hidden{/if}" page="{$page}">
			<a rel="nofollow" class="item" title="{$g.good_name} - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" href="{if $g.link}{$g.link}#{$style_slug}{else}/catalog/{$g.user_login}/{$g.good_id}/{$style_slug}/#{$style_slug}{/if}">
				<span class="list-img-wrap"><img title="{$g.good_name} - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" alt="{$g.good_name} - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if} " style="background-color: #{$g.bg};" src="{$g.picture_path}" /></span>
			</a>
			<div class="infobar">
				{if $g.good_status != "voting"}
				<div class="vote_count vote_count_id_{$g.good_id} {if $g.place > 0}red_vote{/if}">
					<span>{$g.likes}</span>
				</div>
				{/if}
				<div class="preview_count">{$g.visits_catalog}</div>

				{if $USER->user_id == $user_id || $USER->user_id == 27278 || $USER->user_id == 6199}
				<a class="btn_editing" href="/senddrawing.design/{$g.good_id}/" rel="nofollow"></a>
				{/if}
			</div>
			<div class="vote{if $g.liked} select{/if} {if $g.place > 0} heart_red {/if}" {* like-name="phones" *} _id="{$g.good_id}"><span>{if $g.good_status != "voting"}{$g.likes}{else}-{/if}</span></div>
			{if $g.price_old > $g.price}
				<div class="red-sale-stiker"><strike>{$g.price_old} pуб</strike><span>{$g.price} pуб</span></div>
			{/if}
			<div class="item">
				<a {if $k > 2}rel="nofollow"{/if} class="title" title="{$g.good_name} - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" href="{if $g.link}{$g.link}{else}/catalog/{$g.user_login}/{$g.good_id}/category,boards/#{$style_slug}{/if}">{$g.good_name}</a>
				<!--зелёный блок-->
				<!--noindex--><span style="background-color:#00a851; color: #fff;" class="price">{if $g.price_old > $g.price}<strike>{$g.price_old}</strike>{/if} 				
					{if $g.price>0} 
						{$g.price}
					{else}от&nbsp;
						{if $style_slug == 'skateboard'}920
						{elseif $style_slug == 'longboard'}1380
						{elseif $style_slug == 'snowboard'}1990 
						{elseif $style_slug == 'ski'}1500
						{elseif $style_slug == 'kite'}2760
						{elseif $style_slug == 'serf'}5150{/if}
					{/if}&nbsp;руб.</span><!--/noindex-->
				{if !$user} 
				<!--noindex--><span class="author"><a rel="nofollow" title="Дизайнер {$g.user_login}" href="/catalog/{$g.user_login}/">{$g.user_login}</a> {$g.user_designer_level}</span><!--/noindex-->
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
				
				<!--noindex-->{if $g.city_name && !$user}<div class="autor-city">{$g.city_name}</div>{/if}<!--/noindex-->
				
				
				{if $USER->user_id == 27278 || $USER->user_id == 6199}
					<div class="r-m12-menu">
						<a href="#" title="" class="edittags" _id="{$g.good_id}" rel="nofollow">теги</a>
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
			</div>		
		</li>
{/foreach}

{if $rest > 0}
<div style="position:relative;float:left;width:100%;left:0px;" parentlist="true">
<a class="show-more-link withFont" id="show-more-link"  href="{$link}/page/{$page + 1}/ajax/{if $SEARCH}?q={$SEARCH}{/if}" rel="nofollow"><font>Показать ещё {*$rest*}</font></a><div class="allpager" title="Включить автолистание"></div>
</div>
{/if}

{if !$user && ($rest <= 0 || !$rest) && $rest_archived > 0 && ($TAG || $SEARCH)}
	<a href="{$link}{if $filters.good_status}/page/{$page + 1}{/if}/{if !$filters.good_status}archived/{/if}{if $SEARCH}?q={$SEARCH}{/if}" class="archived-catalog pos_inherit" rel="nofollow">Показать работы из архива</a>
{/if}