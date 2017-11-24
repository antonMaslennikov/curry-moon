{literal}
<script type="text/javascript">

	$(document).ready(function(){ 
	
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



{foreach from=$shopwindow key="good_id" name="swforeach" item="good"}
	<li class="m12 {*if $good.style_id==629}monochrome{/if*} {if $USER->authorized && $good.good_visible != 'true' && ($USER->id == $user_id || $USER->id == 27278 || $USER->id == 6199)}visibleFalse{/if}" {*if $smarty.foreach.swforeach.iteration mod 3 == 0}_mod3="true"{/if*}>
		<a href="{if ($good.good_status == "new" || $good.good_status == "deny" || $good.good_visible == "modify") && $USER->id != $g.user_id && !$USER->meta->mjteam}#{else}{$good.link}{/if}" class="item" rel="nofollow"
		{*if $good.good_status == "new" || $good.good_status == "deny" || $good.good_visible == "modify"}class="item" rel="nofollow"{else}class="cloud-zoom voting-good-link no-select item" _href="{if $good.category == 'poster'}{$good.zoom}{elseif $good.style_id == 629}{$good.preview}{else}{$good.picture_path}{/if}" _zoom="{$good.cache}" rel="position:'inside',showTitle:false,zoomWidth:'648',zoomHeight:'518'"{/if*}  title="{$good.style_name} - {$good.good_name}"   style="background-color:#{$good.style_color}">
			<span class="list-img-wrap">
				<img title="{$good.style_name} - {$good.good_name}" alt="{$good.style_name} - {$good.good_name}" src="{$good.picture_path}">
			</span>
			{if $good.style_id==270 || $good.style_id==276  || $good.style_id==315 || $good.style_id==333 || $good.style_id==351 || $good.style_id==354 || $good.style_id==360 || $good.style_id==537 || $good.style_id==630 || $good.style_id==633 || $good.style_id==643 || $good.style_id==644 || $good.style_id==720 || $good.style_id==734}<span class="bg"></span>{/if}
		</a>	
		
		<div class="infobar">
			{if $good.good_status != "voting"}
				<div class="vote_count vote-small vote_count_id_{$good.good_id} {if $good.liked} select{/if} {if $good.place > 0}red_vote{/if}" _id="{$good.good_id}" _style_id="{$style_id}">
					<span>{$good.likes}</span>
				</div>
			{/if}
			<div class="preview_count">{$good.visits}</div>    
			{if $USER->authorized && ($USER->id == $user_id || $USER->id == 27278 || $USER->id == 6199)}
				<a class="btn_editing round thickbox" href="/catalog/setdefaultstyle/{$good.good_id}/" title="" _id="{$good.good_id}" rel="nofollow"></a>
				<a class="btn_editing" href="/senddrawing.design/{$good.good_id}/" rel="nofollow"></a>
			{/if}
		</div>
		
		<div class="item">
			<a rel="nofollow" class="title" title="{$good.style_name} - {$good.good_name}" {*href="#"*}>{$good.style_name}</a>
			
			{if $USER->id == 27278 || $USER->id == 6199}
				<a href="#" class="disableDesign" _id="{$good.good_id}" _style="{$good.style_id}" _category="{$good.category}" rel="nofollow" style="color:orange">{if $good.good_visible == 'false'}вкл.{else}выкл.{/if}</a>
				<span>|</span>
			{/if}
			
			<!--noindex-->
			<span class="price {if $good.price_old > $good.price}gold{/if} {if $good.good_status == 'archived' && $good.good_vote_visible == 0}archived{elseif $good.good_status == 'new' || $good.good_status == 'deny' || $good.good_visible == 'modify'}attention{/if}">
			{if $good.good_status == "new" ||$good.good_status == "voting" || $good.good_status == "deny" || $good.good_visible == "modify" || ($good.good_status == "archived" && $good.good_vote_visible == 0)}
			
				{if $good.good_status == "archived" && $good.good_vote_visible == 0}архив <span class="help"><a class="help" rel="nofollow" href="/faq/group/21/">?</a></span>
				{elseif $USER->authorized && (($good.good_status == "new" && $good.good_visible == "true") || $good.good_visible == "modify") && $good.good_status != "deny"}
					работа на худсовете
				{elseif $USER->authorized && $good.good_status == "deny" && ($USER->id == $user_id || $USER->id == 27278 || $USER->id == 6199)}
					работа отклонена <span class="help"><a class="help thickbox" rel="nofollow" href="/ajax/showCancelReasone/{$good.good_id}/?height=300&width=600">?</a></span>
				{elseif $USER->authorized && $good.good_status == "voting" && ($USER->id == $user_id || $USER->id == 27278 || $USER->id == 6199)}
					работа на голосовании <span class="help"><a class="help" rel="nofollow" href="/faq/group/5/">?</a></span>
				{else}
				{if $USER->id == 27278}{$good.good_status}{/if}
					{if $USER->id == $user_id || $USER->id == 27278 || $USER->id == 6199}
					работа не дооформлена <span class="help"><a class="help thickbox" rel="nofollow" href="/faq/162/?height=500&width=600">?</a></span>
					{/if}
				{/if}
			
			{else}
			
					{if $good.category == "boards"}
						от&nbsp;920&nbsp;руб.
					{else}
						{if $good.price}
							{$good.price}&nbsp;руб.
						{/if}
					{/if}
				
			{/if}
			</span>
			{if $good.good_status == "archived" && $good.good_vote_visible == 0 && $good.price}
				<span class="price">{$good.price}&nbsp;руб.</span>
			{/if}
			<!--/noindex-->			
		</div>	

		
		{if $USER->id == 27278 || $USER->id == 6199 || $USER->id == $designer->id}
			
			{if $USER->id == 27278 || $USER->id == 6199}
				<div class="r-m12-menu onlyAdmin">	
					{if $good.good_status == 'archived'}
						<a href="#" title="" class="promote2" _id="{$good.good_id}" _to="pretendent" rel="nofollow" style="color:orange">победитель</a>
					{/if}
					{if $good.good_status == 'pretendent'}
						{*<span>|</span>*}
						<a href="#" title="" class="promote2" _id="{$good.good_id}" _to="archived" rel="nofollow" style="color:orange">архив</a>
					{/if}
				</div>
			{/if}
			
			<div class="r-m12-menu">
				<a href="#" title="" rel="nofollow" class="edittags" _id="{$good.good_id}">теги</a>
				
				<span>|</span>
				<a href="#" title="" class="hideDesign" _id="{$good.good_id}" _style="{$good.style_id}" rel="nofollow">{if $good.hidden}показать{else}скрыть{/if}</a>
				
				<span>|</span>
				<a href="#" title="" class="disableDesign" _id="{$g.good_id}" _category="{$filters.category}" rel="nofollow">{if $g.good_visible == "false"}вкл.{else}выкл.{/if}</a>

				<span>|</span>
				<a href="/ajax/setsex/" title="" class="setsex {if $good.good_sex == 'male' || $good.good_sex_alt == 'male'}sex-active{/if}" data-sex="male" _id="{$good.good_id}" rel="nofollow">муж.</a>
				<span>|</span>
				<a href="/ajax/setsex/" title="" class="setsex {if $good.good_sex == 'female' || $good.good_sex_alt == 'female'}sex-active{/if}" data-sex="female" _id="{$good.good_id}" rel="nofollow">жен.</a>
				<span>|</span>
				<a href="/ajax/setsex/" title="" class="setsex {if $good.good_sex == 'kids' || $good.good_sex_alt == 'kids'}sex-active{/if}" data-sex="kids" _id="{$good.good_id}" rel="nofollow">дет.</a>
				<sup class="help"><a href="/faq/183/?height=500&amp;width={if $MobilePageVersion}260{else}600{/if}&amp;positionFixed=true" rel="nofollow" class="help thickbox">?</a></sup>				
			</div>
		{/if}	
		
	</li>
{/foreach}

{if $rest > 0}

	<div class="div-show-more absolut" parentlist="true">
		<a class="show-more-link withFont" id="show-more-link" href="{$link}/page/{$page + 1}/" rel="nofollow"><font>Ещё {$rest} {if $total}из {$total}{/if}</font></a>
		<div class="allpager" title="Включить автолистание"></div>
	</div>

{/if}