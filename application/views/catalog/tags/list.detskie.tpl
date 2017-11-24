{literal}
<script type="text/javascript">
	//window.ULheight = {/literal}{$ULheight+50};{literal}
	
	$(document).ready(function(){ 
		$('.list_wrap ul:first').css('height', {/literal}{$ULheight+100}{literal} +'px');
	
		//инициализируем лайканье для работ
		$('.vote').each(function(){
			if (!this.vote) this.vote = new vote({ div: this, id: parseInt($(this).attr('_id')) });
		});
		
		if(!ismobiledevice){
			//показ цены при наведение на работу
			$('.m12').hover(
				function(){ 
					var btn = $(this).find('.btn_editing').css('visibility','visible').show();				
					if (btn.length == 0) {
						var d = $(this).find('.price');
						if (!d.hasClass('gold'))
							d.css('visibility','visible').show(); 
					}
				}, 
				function(){ 
					$(this).find('.btn_editing').css('visibility','hidden').hide();
					var d = $(this).find('.price');
						if (!d.hasClass('gold'))
							d.css('visibility','hidden').hide();
				}
			);
			
			$('.m12 span.list-img-wrap').hover(function(){ $(this).parents('.m12').find('.zoom').show(); },function(a){ if ($(a.toElement?a.toElement:a.relatedTarget).hasClass('zoom')) return; $(this).parents('.m12').find('.zoom').hide(); });
			
			$('.m12 .zoom').unbind('click').bind('click', function(){
				tb_show('', $(this).attr('href')+'?height=520&width=910');
				return false;
			});
		}
	
		//инициализируем "Показать ещё"
		initShowMore();
	
	});
</script>
{/literal}



{foreach from=$goods item="g" key="k"}
	{if $g.good_status == "new" || $g.good_status == "deny" || $g.good_visible == "modify"}
		<li class="m12" style="left:{$g.x}px;top:{$g.y}px;height:{$g.h}px;">
			<a rel="nofollow" class="item" title="{$g.good_name} - футболки на заказ " href="{if $g.link}{$g.link}{else}/catalog/{$g.user_login}/{$g.good_id}/{if $filters.category}category,poster/{/if}{/if}">
				<span class="list-img-wrap">
					<img title="{$g.good_name} - футболки на заказ " alt="{$g.good_name} - футболки на заказ " style="background-color: #{$g.bg};" src="{$g.picture}" />
				</span>
			</a>						
			<div class="infobar">
				<div class="vote_count vote_count_id_{$g.good_id} {if $g.place > 0}red_vote{/if}"><span>0</span></div>
				<div class="preview_count">0</div>
				{if $USER->user_id == $user_id || $USER->user_id == 27278 || $USER->user_id == 6199}
				<a class="btn_editing" href="/senddrawing.design/{$g.good_id}/"></a>
				{/if}
			</div>
			<div class="item">
				<span class="title">{$g.good_name}</span>
				<span class="author" style="color:#F00; font-style:italic; font-size:11px; font-weight:bold">
                    {if $USER->authorized && (($g.good_status == "new" && $g.good_visible == "true") || $g.good_visible == "modify")}
                        работа на худсовете ещё {$g.timetoend}&nbsp;ч.
                    {elseif $USER->authorized && $g.good_status == "deny" && ($USER->user_id == $user_id || $USER->user_id == 27278 || $USER->user_id == 6199)}
                        работа отклонена <sup class="help"><a class="help thickbox" rel="nofollow" href="/faq/163/?height=500&amp;width=600">?</a></sup>
                    {else}
                        {if $USER->user_id == $user_id || $USER->user_id == 27278 || $USER->user_id == 6199}
                        работа не дооформлена <sup class="help"><a class="help thickbox" rel="nofollow" href="/faq/162/?height=500&amp;width=600">?</a></sup>
                        {/if}
                    {/if}
                </span>
			</div>		
		</li>
	{else}	
		<li class="m12" style="left:{$g.x}px;top:{$g.y}px;height:{$g.h}px;">
			<a rel="nofollow" class="item" title="{$g.good_name} - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" href="/catalog/{$g.user_login}/{$g.good_id}/category,futbolki;sex,kids;color,64;size,23/">
				<span class="list-img-wrap">
					<img title="{$g.good_name} - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" alt="{$g.good_name} - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if} " style="background-color: #{$g.bg};" src="{$g.picture}" />
				</span>
			</a>
			{if ($module == 'catalog' || $module == 'tag') && $filters.category != 'poster'}
				<a rel="nofollow" href="/catalog/{$g.user_login}/{$g.good_id}/zoom/category,futbolki;sex,kids;color,64;size,23/" class="zoom"></a>
			{/if}
			<div class="infobar">
				{if $g.good_status != "voting"}
				<div class="vote_count vote_count_id_{$g.good_id} {if $g.place > 0}red_vote{/if}" title="{if $g.liked}Мне нравится{/if}">
					<span>{$g.likes}</span>
				</div>
				{/if}
				{if $USER->user_id == $g.user_id && $g.good_status == "voting"}
					<div class="help_for_author">
						<span class="help"><a href="/faq/168/?height=500&amp;width=600&amp;positionFixed=true" rel="nofollow" class="help thickbox">?</a></span>
					</div>
				{/if}
				<div class="preview_count">{$g.visits_catalog}</div>
				{if $USER->authorized && ($USER->user_id == $user_id || $USER->user_id == 27278 || $USER->user_id == 6199 || $USER->user_id == 105091)}
					<a class="btn_editing" href="/senddrawing.design/{$g.good_id}/" rel="nofollow"></a>
					{if $USER->user_id == 27278 || $USER->user_id == 6199 || $USER->user_id == 105091}
	                <a class="btn_editing krest" href="#" onclick="tb_show('', '/senddrawing.design/{$g.good_id}/stickermize{if $filters.category}/{$filters.category}{/if}/?width=930&height=620{if $filters.sex}&sex={$filters.sex}{/if}');return false;"></a>
	                {/if}
				{/if}
			</div>
			<div class="vote{if $g.liked} select{/if} {if $g.place > 0} heart_red {/if}" _id="{$g.good_id}"><span>{if $g.good_status != "voting"}{$g.likes}{else}-{/if}</span></div>
			
			<div class="item">
				<a href="/catalog/{$g.user_login}/{$g.good_id}/category,futbolki;sex,kids;color,64;size,23/" class="title">{$g.good_name}</a>
				<!--зелёный блок-->
				<!--noindex--><span style="background-color:#00a851;" class="price">{if $g.price}{if $g.price_old > $g.price}<strike>{$g.price_old}</strike>{/if} <span>{$g.price}&nbsp;руб.</span>{else}<strike>1090</strike> <span>790&nbsp;руб.</span>{/if}</span><!--/noindex-->
				{if $g.good_discount > 0}
					<!--золотистый блок-->
					<!--noindex--><span style="background-color:#a87400; display:block; visibility: visible;" class="price gold">{if $g.price}{if $g.price_old > $g.price}<strike>{$g.price_old}</strike>{/if} <span>{$g.price}&nbsp;руб.</span>{else}<strike>1090</strike> <span>790&nbsp;руб.</span>{/if}</span><!--/noindex-->
				{/if}
				{if !$user} 
				<!--noindex--><span class="author"><a rel="nofollow" title="Дизайнер {$g.user_login}" href="/catalog/{$g.user_login}/">{$g.user_login}</a> {$g.user_designer_level}</span><!--/noindex-->
				{/if}
				
				{if $g.disabled && ($USER->user_id == $user_id || $USER->user_id == 27278 || $USER->user_id == 6199)}
				<div><span class="author" style="color:#F00; font-style:italic; font-size:11px; font-weight:bold">работа отключена</span></div>
				{/if}
				
				{* if $g.no_src && ($USER->user_id == $user_id || $USER->user_id == 27278 || $USER->user_id == 6199)}
				<div><span class="author" style="color:#F00; font-style:italic; font-size:11px; font-weight:bold">отсутствует исходник</span></div>
				{/if *}
				
				{*if $g.place}
				<a rel="nofollow" href="/blog/view/1511/" class="b-ordensml">
					<img alt="Победитель принтшопа" src="/images/icons/bg-orden-sml.gif">
				</a>
				{/if*}
				
				<!--noindex-->{if $g.city_name && !$user}<div class="autor-city">{$g.city_name}</div>{/if}<!--/noindex-->
				
				
				{if $USER->user_id == 27278 || $USER->user_id == 6199 || $USER->user_id == 105091}
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
	{/if}
{/foreach}

{if !$user && $rest > 0}
<div style="position:absolute;left:50px;bottom:0px;width:100%;" parentlist="true">
	<a class="show-more-link" id="show-more-link"  href="{$link}/page/{$page + 1}/" rel="nofollow" style="height:200px;">Ещё {$rest} {if $total}из {$total}{/if}</a><div class="allpager" title="Включить автолистание"></div>
</div>
{/if}