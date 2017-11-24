{literal}
<script type="text/javascript">
	
	

	$(document).ready(function(){ 
		
		$('.list_wrap ul:first').css('height', {/literal}{$ULheight+100}{literal} +'px');
		
		//добавим лайканье на новые элементы
		$('.vote').each(function(){
			this.vote = new vote({ div: this, id: parseInt($(this).attr('_id')), style_id: parseInt($(this).attr('_style_id')) });
		});

		// если Избранное, то обновим обработчик для лайканья
		if (typeof addHandlerToVote == 'function') addHandlerToVote();
		
		$('.m12').hover(
			function(){ 
				var btn = $(this).find('.btn_editing').css('visibility','visible').show();				
				if (btn.length == 0)
					$(this).find('.price').css('visibility','visible').show(); 
			}, 
			function(){ $(this).find('.btn_editing, .price').css('visibility','hidden').hide(); }
		);
	
		initShowMore();
		//инициализируем "експресс голосование"
		initExpressVote();
	});
</script>

{/literal}

{foreach from=$goods item="g" key="k"}

	{if $g.good_status == "new"}
	 
		<li class="m12" style="left:{$g.x}px;top:{$g.y}px;">				
			<div class="wrap">
			<a {if $k > 2}rel="nofollow"{/if} class="item" title="{$g.good_name} - футболки на заказ " href="{if $g.link}{$g.link}{else}/catalog/{$g.user_login}/{$g.good_id}/{/if}">
				<span class="list-img-wrap"><img alt="{$g.good_name} - футболки на заказ " style="background-color: #{$g.bg};" src="{$g.picture}" /></span>
			</a>
			<div class="infobar">
				<div class="vote_count vote_count_id_{$g.good_id}"><span>0</span></div>
				<div class="preview_count">0</div>
				{if $USER->user_id == $g.user_id || $USER->user_id == 27278 || $USER->user_id == 6199}
				<a class="btn_editing" href="/senddrawing.design/{$g.good_id}/"></a>
				{/if}
			</div>
			<div class="item">
				<span class="title">{$g.good_name}</span>
				<span class="author">работа на худсовете ещё {$g.timetoend} ч.</span>
			</div>	
			</div>		
		</li>
		
	{else}
	
		<li class="m12 {if $g.disliked}dis_liked_sha{/if} shadowm12" good_id="{$g.good_id}" style="left:{$g.x}px;top:{$g.y}px;">				
			<div class="wrap">
			<a {if $k > 2}rel="nofollow"{/if} class="item" title="{$g.good_name} - футболки на заказ " href="{if $g.link}{$g.link}{else}/catalog/{$g.user_login}/{$g.good_id}/{/if}">
				<span class="list-img-wrap"><img alt="{$g.good_name} - футболки на заказ " style="background-color: #{$g.bg};" src="{$g.picture}" /></span>
			</a>
			<div class="infobar">
				{if $g.good_status != "voting"}
				<div class="vote_count vote_count_id_{$g.good_id}">
					<span>{$g.likes}</span>
				</div>
				{/if}
				<div class="preview_count">{$g.visits_catalog}</div>
				{if $USER->user_id == $g.user_id || $USER->user_id == 27278 || $USER->user_id == 6199}
				<a class="btn_editing" href="/senddrawing.design/{$g.good_id}/"></a>
				{/if}
			</div>
			<div class="vote{if $g.liked} select{/if} {if $g.place > 0} heart_red {/if} {if $g.disliked}off{/if}" _id="{$g.good_id}" _style_id="{$g.style_id}"><span>{if $g.good_status != "voting"}{$g.likes}{/if}</span></div>
			<div class="item">
				<span class="title">{$g.good_name}</span>
				<!--зелёный блок-->
				<!--noindex--><span style="background-color:#00a851; color: #fff;" class="price"><strike>1090</strike> 790&nbsp;руб.</span><!--/noindex-->
				{if !$user} 
				<!--noindex--><span class="author"><a rel="nofollow" title="Дизайнер {$g.user_login}" href="/catalog/{$g.user_login}/">{$g.user_login}</a></span><!--/noindex-->
				{/if}
				
				{*if $g.place}
				<a rel="nofollow" href="/blog/view/1511/" class="b-ordensml">
					<img alt="Победитель принтшопа" src="/images/icons/bg-orden-sml.gif">
				</a>
				{/if*}
			</div>
			<div class="express">
				<a href="#!dislike" rel="nofollow" title="" class="dis_like{if $g.disliked} active{/if}"></a>
				<a href="#!like" rel="nofollow" title="" class="good_like{if $g.liked} active{/if}"></a>
				<div style="clear:left;"></div>
			</div>
			</div>	
		</li>
	{/if}
{/foreach}

{*if $rest > 0}
	<a class="show-more-link" id="show-more-link"  href="{$link}/page/{$page + 1}/" rel="nofollow" style="height:200px;font-size: 13px;position: absolute;left: 143px;	bottom: 0px;">показать ещё {$rest}</a>
{/if*}

{if $rest > 0}
<div class="div-show-more" style="position:absolute;left:125px;bottom:0px;" parentlist="true">
	<a class="show-more-link" id="show-more-link"  href="{$link}/page/{$page + 1}/" rel="nofollow" style="height:200px;font-size: 13px;">показать ещё {$rest}</a>{*<div class="allpager" title="Включить автолистание"></div>*}
</div>
{/if}

