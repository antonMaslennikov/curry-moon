{literal}
<script type="text/javascript">
	
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
		
			$('.m12 a.item').hover(function(){ $(this).parents('.m12').find('.zoom').show(); },function(a){ if ($(a.toElement?a.toElement:a.relatedTarget).hasClass('zoom')) return; $(this).parents('.m12').find('.zoom').hide(); });
			
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


{foreach from=$goods item="g" key="k"}
	<li class="m12" style="left:{$g.x}px;top:{$g.y}px;height:{$g.h}px;">
		<a {if $k > 2}rel="nofollow"{/if} href="/catalog/{$g.user_login}/{$g.good_id}/" title="Футболки - {$g.good_name}" class="item">
			<img src="{$g.picture}" style="background-color: #{$g.bg};" alt="" />
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
		<a rel="nofollow" href="/catalog/{$g.user_login}/{$g.good_id}/category,futbolki;sex,male;color,37;size,3/zoom/" class="zoom"></a>
		<div class="vote{if $g.liked} select{/if} {if $g.place > 0} heart_red {/if}" _id="{$g.good_id}"><span>{if $g.good_status != "voting"}{$g.likes}{else}-{/if}</span></div>
		
		<div class="item">
			<span class="title">{$g.good_name}</span>
			<!--noindex--><span class="price" style="bottom:112px!important;">{if $g.price}{if $g.price_old > $g.price}<strike>{$g.price_old}</strike>{/if} {$g.price}&nbsp;{$L.CURRENT_CURRENCY}{else}<strike>1090</strike> 790&nbsp;руб.{/if}</span><!--/noindex-->

			<!--noindex--><span class="author"><a href="http://www.maryjane.ru/profile/{$g.user_id}/" title="Дизайнер {$g.user_login}" rel="nofollow">{$g.user_login}</a></span><!--/noindex-->
		</div>
	</li>
	
{/foreach}

{if !$user && $rest > 0}
<div style="position:absolute;left:50px;bottom:0px;width:100%;" parentlist="true" class="navigation-page">
	<a class="show-more-link" id="show-more-link"  href="{$link}/page/{$page + 1}/" rel="nofollow" style="height:200px;">Ещё {($rest*3)} из {$total}</a><div class="allpager" title="Включить автолистание"></div>
</div>
{/if}