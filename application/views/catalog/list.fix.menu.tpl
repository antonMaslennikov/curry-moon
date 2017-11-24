{literal}
<style>
	#button_toTop{top:13px;z-index: 999;}
	@media only screen and (max-width : 1155px){
		#button_toTop{top:70px;}
	}
</style>
{/literal}

<div class="fix-menu {if !$filters.category && $TAG || (($module=='catalog' || $SEARCH || $module == 'search') && !$filters.category) || (!$filters.sex && $filters.category)}{if $Style->category !='patterns' && $Style->category !='patterns-sweatshirts' && $Style->category !='patterns-tolstovki'}notNositel{/if}{/if} {if $user && $user_top_tags|count <= 0 && $TAGS|count <= 0}visibleSearch{/if}   {if !$filters.category && ($TAG.slug == 'nadpis_' || $TAG.slug == 'english')}withTabs{/if} {if $Style->category =='patterns' || $Style->category =='patterns-sweatshirts' || $Style->category =='patterns-tolstovki'}Patterns{/if}">
	<div class="rel clearfix">		

		{if !$filters.category && ($TAG.slug == 'nadpis_' || $TAG.slug == 'english')}
		<div class="tabz clearfix">
			<!--noindex-->
			<a href="/tag/nadpis_/" {if $TAG.slug == 'nadpis_'}class="active"{/if} rel="nofollow">На русском</a>	
			<a href="/tag/english/" {if $TAG.slug == 'english'}class="active"{/if} rel="nofollow">На английском</a>
			<!--/noindex-->
		</div>		
		{/if}
		
		{* выбор пола *}	
		{if $Style->category =='futbolki' || $Style->category =='tolstovki' || $Style->category =='sweatshirts'}
			{if $filters.sex}
				<div class="b-radio-manwomen radio-input small clearfix">
					<a rel="nofollow" {if $filters.category == 'mayki-alkogolichki' || $category == 'sumki' || $filters.category == 'platya'}href="#"{else}href="{if $tag != $module}/{$module}{/if}{if $TAG}/{$TAG.slug}{/if}/{$filters.category}/{if $SEARCH}?q={$SEARCH}{/if}" title="{$L.SIDEBAR_menu_men_sex}"{/if} class="type-select male {if $filters.sex == 'male'}active{/if} {if $filters.category == 'mayki-alkogolichki' || $category == 'sumki' || $filters.category == 'platya'}opacity{/if}" {if $filters.category == 'mayki-alkogolichki' || $category == 'sumki' || $filters.category == 'platya'}onclick="return false;"{/if}></a>
					<a rel="nofollow" href="{if $tag != $module}/{$module}{/if}{if $TAG}/{$TAG.slug}{/if}/{$filters.category}/female/{if $SEARCH}?q={$SEARCH}{/if}" title="{$L.SIDEBAR_menu_female_sex}" class="type-select female {if $filters.sex == 'female' && $category != 'sumki'}active{/if} {if $category == 'sumki'}opacity{/if}" {if $category == 'sumki'}onclick="return false;"{/if}></a>
					<a rel="nofollow" {if $filters.category == 'futbolki' || $filters.category == 'sweatshirts'}href="{if $tag != $module}/{$module}{/if}{if $TAG}/{$TAG.slug}{/if}/{$filters.category}/kids/{if $SEARCH}?q={$SEARCH}{/if}"{else}href="#"{/if} title="{$L.SIDEBAR_menu_kids}" class="type-select kids {if $filters.sex == 'kids'}active{/if} {if $category == 'sumki' ||  $filters.category == 'tolstovki' || $filters.category == 'longsleeve_tshirt' || $filters.category == 'mayki-alkogolichki'}opacity{/if}" {if $category == 'sumki' || $filters.category == 'tolstovki' || $filters.category == 'longsleeve_tshirt' || $filters.category == 'mayki-alkogolichki'}onclick="return false;"{/if}></a>
				</div>	
			{/if}
		{/if}
		
		{if $filters.category != 'poster'}
			{if $filters.category == 'sumki' || (!$filters.category && $TAG) || (($module=='catalog' || $SEARCH || $module == 'search') && !$filters.category) || (!$filters.sex && $filters.category)}
				<div class="body-sumki">
					<!--noindex-->
					<select class="select">				
						{include file="catalog/list.fix.menu.option.tpl"}
					</select>
					<!--/noindex-->
				</div>
			{else}
				<div class="body-odejda">
					<!--noindex-->
					<select class="select" style="width:140px;">
						{include file="catalog/list.fix.menu.option.tpl"}
					</select>
					<!--/noindex-->
				</div>			
				{if $Fsizes|count > 0}
				<div class="body-size">
					<select class="select" style="width:90px;">
						<option value="">{$L.LIST_menu_size}</option>
						{foreach from=$Fsizes item="c"}					
							<option {if $c.class=='on'}selected="selected"{/if} value="{if $tag != $module}/{$module}{/if}{if $TAG}/{$TAG.slug}{/if}/{if $Style}{$Style->category}/{$Style->style_slug}{/if}/{$c.size_name}{if $orderBy == 'new'}/{$orderBy}{/if}/{if $SEARCH}?q={$SEARCH}{/if}">{$c.size_rus} ({$c.size_name})</option>
						{/foreach}
					</select>
				</div>
				{/if}
			{/if}
		{/if}

		{if $MobilePageVersion && $Fcolors|count > 0}
			<div class="body-color">
				<select class="select">	
					{*<option value="">Цвет</option>*}									
					{foreach from=$Fcolors item="c"}
						<option {if $c.class=='on'}selected="selected"{/if} value="{if $tag != $module}/{$module}{/if}{if $TAG}/{$TAG.slug}{/if}{if $task == 'top'}/top{/if}/{$filters.category}/{$c.style_slug}{if $filters.size_name}/{$filters.size_name}{/if}{if $orderBy == 'new'}/{$orderBy}{/if}/{if $SEARCH}?q={$SEARCH}{/if}">{$c.name}</option>
					{/foreach}
				</select>
			</div>
		{else if $Fcolors|count > 0}
			<div class="b-color-select" style="width:50px;margin:13px 0px -10px {if $PAGE->lang!='ru'}-{/if}2px;position: relative;">
				<div class="wrp">
				{if ($module == 'catalog' || $module == 'tag') && $task == 'kids'}
					<div class="one-color on" style="background:url('/images/buttons/bg-random.png');">
						<a title="случайные цвета" href="/catalog/kids/" onclick="$('.fix-menu .b-color-select.none').slideToggle();return false;" rel="nofollow">
							<span class="it random"></span>
						</a>
					</div>
				{/if}
				{foreach from=$Fcolors item="c"}
					<div class="one-color s-{$s.status} {$c.class}" name="{$c.id}" group="{$c.group}" title="цвет &mdash; {$c.name}" id="color{$c.id}" style="{if $c.class!='on'}display: none;{/if}background-color:#{$c.hex};">
						<a {if $c.class=='on'}onclick="$('.fix-menu .b-color-select.none').slideToggle();return false;"{/if} title="цвет &mdash; {$c.name}" href="{if $tag != $module}/{$module}{/if}{if $TAG}/{$TAG.slug}{/if}{if $task == 'top'}/top{/if}/{$filters.category}/{$c.style_slug}{if $filters.size_name}/{$filters.size_name}{/if}{if $orderBy == 'new'}/{$orderBy}{/if}/{if $SEARCH}?q={$SEARCH}{/if}" rel="nofollow">
							<span class="it {$c.name_en}"></span>
							{if $c.status == 'new'}
								<span class="fresh-icon"></span>
							{elseif $c.status == 'few'}
								<span class="sale-icon"></span>
							{/if}
						</a>						
					</div>
				{/foreach}
				{if $Fcolors|count > 1}
					<a href="#" rel="nofollow" class="arr-fix-menu" onclick="$('.fix-menu .b-color-select.none').slideToggle();return false;"></a>
				{/if}
				</div>
				{if $Fcolors|count > 1}					
					<div class="b-color-select none">
						<div style="width:0px;height:0px !important;position: absolute;top:-27px;right:16px;margin:0px;border-color: transparent transparent #EFEFEF transparent;border-style: solid;border-width: 11px 11px;"></div>
						<a href="#" rel="nofollow" class="close" onclick="$(this).parent().hide();return false;" title="закрыть"></a>
					
					{if ($module == 'catalog' || $module == 'tag') && $task == 'kids'}
						<div class="one-color on" style="background:url('/images/buttons/bg-random.png');">
							<a title="случайные цвета" href="/catalog/kids/" rel="nofollow">
								<span class="it random"></span>
							</a>
						</div>
					{/if}					
					{foreach from=$Fcolors item="c"}
						<div class="one-color s-{$s.status} {$c.class}" name="{$c.id}" group="{$c.group}" title="цвет &mdash; {$c.name}" id="color{$c.id}" style="background-color:#{$c.hex};">
							<a title="цвет &mdash; {$c.name}" href="{if $tag != $module}/{$module}{/if}{if $TAG}/{$TAG.slug}{/if}{if $task == 'top'}/top{/if}/{$filters.category}/{$c.style_slug}{if $filters.size_name}/{$filters.size_name}{/if}{if $orderBy == 'new'}/{$orderBy}{/if}/{if $SEARCH}?q={$SEARCH}{/if}" rel="nofollow">
								<span class="it {$c.name_en}"></span>
								{if $c.status == 'new'}
									<span class="fresh-icon"></span>
								{elseif $c.status == 'few'}
									<span class="sale-icon"></span>
								{/if}
							</a>
						</div>
					{/foreach}
					<div style="clear:both"></div>
				</div>
				{literal}
				<script>
					$(document).ready(function() { 	
						$('.fix-menu .b-color-select.none div a').click(function(){
							$(this).parents('.b-color-select.none').hide();
						});	
						
						$(window).bind('click',function(d){ 
							if ($(d.target).parents('.fix-menu').length>0) return;
							$('.fix-menu .b-color-select.none').hide();
						});
					});
				</script>
				{/literal}
				{/if}
				<div class="clear"></div>
			</div>			
		{/if}
			
		{if $user_id &&  $user_top_tags|count > 0}
			<div class="body-tags">
				<select class="select">		
					<option value="">Теги</option>			
					{foreach from=$user_top_tags item="t"}
						<option value="/catalog/{$user.user_login}/tag/{$t.slug}/" {if $TAG.slug == $t.slug}selected="selected"{/if}>{$t.name}</option>
					{/foreach}
				</select>	
			</div>	
		{else if $TAGS|count > 0}			
			<div class="body-tags {if !$MobilePageVersion}NLtags{/if}">
				<select class="select {*tags*}" _category="{$filters.category}" _color="{$filters.color}" _size="{$filters.size}" _sex="{$filters.sex}" _style="{$style_slug}">
					<option value="">{$L.LIST_menu_collections}</option>
					{foreach from=$TAGS item="t"}
						<option value="/tag/{$t.slug}/{$filters.category}/{if $Style}{$Style->style_slug}/{/if}" img="{$t.picture_path}" {if $TAG.slug == $t.slug}selected="selected"{/if}>{$t.name}</option>
					{/foreach}
				</select>	
			</div>	
		{/if}
		
					
		{literal}
		<script>
			$(document).ready(function() { 	
				$('.fix-menu select.select').change(function(){
					if ($(this).val().length > 0)
						location.href =$(this).val(); 
				});
			});
		</script>
		{/literal}	
		
		
		{if $Style->category !="patterns" && $Style->category !="patterns-sweatshirts" && $Style->category !="patterns-tolstovki"}
		<noindex>
		<div class="b-filter_view">
			{if $filters.category == 'poster' || $filters.category == 'cup'}
			<a href="{$mlink}new/ajax/" rel="nofollow" class="view-pipl active" title=""></a>
			<a href="{$blink}ajax/preview/" rel="nofollow" class="view-other" title=""></a>
			{else}
			<a {if $SEARCH && !$filters.category}href="{$mlink}category,futbolki/new/ajax/"{elseif $user && !$filters.category}href="/{$module}/{$user.user_login}/"{else}href="{if $filters.category && $filters.sex}{$mlink}{else}{if $TAG}{if $TAG.slug != $module}/tag{/if}/{$TAG.slug}/category,futbolki/new/{elseif $user}/{$module}/{$user.user_login}/category,futbolki/new/{elseif $module=='catalog' && (!$filters.category || !$filters.sex)}/{$module}/category,futbolki/new/{/if}{/if}ajax/"{/if} rel="nofollow" class="view-pipl {if ($filters.category && $filters.sex) || $user}active{/if}" title=""></a>
			<a href="{if $blink}{$blink}{else}/catalog{if $user}/{$user.user_login}/{else}/new/{/if}{/if}ajax/preview/" rel="nofollow" class="view-other {if (!$filters.category || !$filters.sex) && !$user}active{/if}" title=""></a>
			{/if}
		</div>
		</noindex>
		{/if}
		
		<div class="b-filter_tsh">			
			{if $Style->category =="patterns" || $Style->category =="patterns-sweatshirts" || $Style->category =="patterns-tolstovki"}
				<a href="/{if $TAG && $module == 'tag'}{$module}/{$TAG.slug}{else}catalog{/if}/patterns{if $Style->category =='patterns-sweatshirts'}-sweatshirts{/if}/{$Style->style_slug}/" title="{$L.GOOD_new}" rel="nofollow" class="new-filter {if !$orderBy || $orderBy == 'new'}active{/if}">{*Новое*}</a>
				<a href="/{if $TAG && $module == 'tag'}{$module}/{$TAG.slug}{else}catalog{/if}/patterns{if $Style->category =='patterns-sweatshirts'}-sweatshirts{/if}/{$Style->style_slug}/popular/" rel="nofollow" class="pop-filter {if $orderBy == 'popular'}active{/if}" title="{$L.GOOD_popular}">{*Популярное*}</a>
				<a href="/{if $TAG && $module == 'tag'}{$module}/{$TAG.slug}{else}catalog{/if}/patterns{if $Style->category =='patterns-sweatshirts'}-sweatshirts{/if}/{$Style->style_slug}/grade/" rel="nofollow" class="score-filter {if $orderBy == 'grade'}active{/if}" title="{$L.GOOD_rating}"></a>
			{else if $base_link}
				<a data-sort="new" href="{$base_link}/new/ajax/" rel="nofollow" class="new-filter {if $orderBy == 'new'}active{/if}" title="{$L.GOOD_new}"></a>
				<a data-sort="popular" href="{$base_link}/popular/ajax/" rel="nofollow" class="pop-filter {if $orderBy == 'popular' || $orderBy == ''}active{/if}" title="{$L.GOOD_popular}"></a>
				<a data-sort="grade" href="{$base_link}/grade/ajax/" rel="nofollow" class="score-filter {if $orderBy == 'grade'}active{/if}" title="{$L.GOOD_rating}"></a>
			{/if}			
		</div>
		
		{include file="top_menu_search.tpl"}
		
	</div>			
</div>

{literal}
<script>
	$(document).ready(function() { 	
		//инит красивого селекта
		$('.fix-menu select').selectbox(); 
		
		//появление поиска
		$('.fix-menu .search-top').mouseover(function() {
			$(this).find('.search-input').focus();	
			if($(this).find('.search-input').val()=='' && $('.fix-menu .tags').val()!='')
				$(this).find('.search-input').val($('.fix-menu .tags option:selected').text());	
		});
	});
</script>
{/literal}