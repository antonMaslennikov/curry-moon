{literal}
<script type="text/javascript">
    $(document).ready(function(){
    	
    	//инициализируем лайк для работ
        $('.vote, .vote-small').each(function(){
            if (!this.vote) this.vote = new vote({ div: this, id: parseInt($(this).attr('_id')), style_id: '{/literal}{if $Style}{$Style->id}{else}{/if}{literal}' });
        });
        
		if(!ismobiledevice){
			$('.m12 .zoom').unbind('click').bind('click', function(){
				trackUser('Zoom_catalog_list','вызов окна Зумa','');
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
	{literal}
	<script type="text/javascript">
		var itWasTheLastLoading =true;//это была последняя подгрузка
		$(document).ready(function(){$(window).unbind('scroll',pScroll);  });
	</script>
	{/literal}
{/if}

{foreach from=$goods item="g"}				
	<li class="m13 {if $g.hidden}hidden{/if}" {if $g.good_status != 'archived'}page="{$page}"{/if}>
		
		<a rel="nofollow" class="item" title="{$g.good_name}" href="/catalog/{$g.user_login}/{$g.good_id}/{$Style->style_slug}/">
			<span class="list-img-wrap"><img title="{$g.good_name}" alt="{$g.good_name}" src="{$g.picture_path}" /></span>
			
			<span class="price"><small>цена:</small> {$g.price}&nbsp;руб.</span>
			
			<div class="item-description">
				{$Style->style_name}
			</div>
		</a>
		
		{if $USER->authorized && ($USER->id == $g.user_id || $USER->id == 27278 || $USER->id == 6199 || $USER->id == 52426)}
			<a class="btn_editing" href="/senddrawing.pattern/{$g.good_id}/"></a>
		{/if}
		
		{if ($datetime.year == 2017 && $datetime.month == 11 && ($datetime.day == 23 || $datetime.day == 24 || $datetime.day == 25)) || $USER->id == 27278}
			<img src="/images/catalog/miniblack.gif" class="miniblack" />
		{/if}
		
		<div class="item">
			
			<div class="one_click_order_block">
				<a href="#" data-good_id="{$g.good_id}" _href="/order.v3/" rel="nofollow">Купить быстро</a>
			</div>
			
			<a href="/catalog/{$g.user_login}/{$g.good_id}/{$Style->style_slug}/" class="title" title="{$g.good_name}">{$g.good_name|truncate:28:'...'}</a>						

			<!--noindex-->
			<a href="/catalog/{$g.user_login}/" rel="nofollow" class="mini-avatar" title="{$g.user_login}">{$g.user_avatar}</a>						
			<span class="author">							
				<a rel="nofollow" title="{$g.user_login}" href="/profile/{$g.user_id}/">{$g.user_login}</a>{$g.user_designer_level}
			</span>
			{if $g.city_name && !$MobilePageVersion}					
				<div class="autor-city">{$g.city_name}</div>
			{/if}
			<!--/noindex-->
			
			{if $g.good_status != "voting" && !$MobilePageVersion}
			<div class="infobar">
                <div class="vote_count vote-small vote_count_id_{$g.good_id} {if $g.liked} select{/if} {if $g.place > 0}red_vote{/if}" _id="{$g.good_id}" _style_id="{$Style->id}">
                    <span>{$g.likes}</span>
                </div>
        	</div>
        	{/if}	
								
			<div style="clear:both"></div>
		</div>
		
		{if $USER->meta->mjteam == "super-admin"}
			<div class="r-m12-menu">
				<a href="#" title="" rel="nofollow" class="edittags" _id="{$g.good_id}">теги</a>
				<span>|</span>
				<a href="#" title="" class="hideDesign" _id="{$g.good_id}" _style="{$Style->id}" rel="nofollow">{if $g.hidden}показать{else}скрыть{/if}</a>
				<span>|</span>
				<a href="#" title="" class="disableDesign" _id="{$g.good_id}"_style="{$Style->id}" _category="{$filters.category}" rel="nofollow">{if $g.hidden}вкл.{else}выкл.{/if}</a>
				{if $g.good_status == 'archived'}
				<span>|</span>
				<a href="#" title="" class="promote2" _id="{$g.good_id}" _to="pretendent" rel="nofollow">победитель</a>
				{/if}
				{if $g.good_status == 'pretendent'}
				<span>|</span>
				<a href="#" title="" class="promote2" _id="{$g.good_id}" _to="archived" rel="nofollow">архив</a>
				{/if}
				
				<span>|</span>
				<a href="/ajax/setsex/" title="" class="setsex {if $g.good_sex == 'male' || $g.good_sex_alt == 'male'}sex-active{/if}" data-sex="male" _id="{$g.good_id}" rel="nofollow">муж.</a>
				<span>|</span>
				<a href="/ajax/setsex/" title="" class="setsex {if $g.good_sex == 'female' || $g.good_sex_alt == 'female'}sex-active{/if}" data-sex="female" _id="{$g.good_id}" rel="nofollow">жен.</a>
				<span>|</span>
				<a href="/ajax/setsex/" title="" class="setsex {if $g.good_sex == 'kids' || $g.good_sex_alt == 'kids'}sex-active{/if}" data-sex="kids" _id="{$g.good_id}" rel="nofollow">дет.</a>
			</div>
		{/if}
	</li>
{/foreach}


{if $rest > 0}
<div class="div-show-more" style="position:relative;float:left;width:100%" parentlist="true">
    <a class="show-more-link withFont" id="show-more-link" href="{$link}/page/{$page + 1}/{if $SEARCH}?q={$SEARCH}{/if}" rel="nofollow"><font>Ещё {$rest} из {$total}</font></a><div class="allpager" style="display:none;margin-left:20px" title="Включить автолистание"></div>
</div>
{/if}

{if ($rest <= 0 || !$rest) && $rest_archived > 0}
	<a href="{$link}{if $filters.good_status}/page/{$page + 1}{/if}/{if !$filters.good_status}archived/{/if}{if $SEARCH}?q={$SEARCH}{/if}" class="archived-catalog pos_inherit" rel="nofollow">Показать работы из архива</a>
{/if}