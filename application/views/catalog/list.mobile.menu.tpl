<div class="list-mobile-menu {if !$filters.category && $TAG || (($module=='catalog' ||$module=='catalog.dev' ||$module=='catalog.v3') && !$filters.category)}notNositel{/if}">
	<!--noindex-->
	
	{if  $filters.category == 'poster'}	
		{include file="catalog/list.sidebar.filters.poster.tpl"}	
	{else if $category == 'sumki' || (!$filters.category && $TAG) || (($module=='catalog' ||$module=='catalog.dev' || $module=='catalog.v3') && !$filters.category)}
		<div class="body-sumki">
			<select class="select">
				{include file="catalog/list.fix.menu.option.tpl"}
			</select>
		</div>
	{else}
		{if !$filters.sex && $category != 'textile'}
			<div class="body-odejda">
				<select class="select">
					{include file="catalog/list.fix.menu.option.tpl"}
				</select>
			</div>
		{/if}

		{if $Fsizes|count > 1}
		<div class="body-size">
			<select class="select">
				<option value="">{$L.LIST_menu_size}</option>
				{foreach from=$Fsizes item="c"}					
					<option {if $c.class=='on'}selected="selected"{/if} value="{if $tag != $module}/{$module}{/if}{if $tag}/{$tag}{/if}/category,{$filters.category}{if $filters.color};color,{$filters.color}{/if};size,{$c.size_id}{if $filters.sex != 'male'}/{$filters.sex}{/if}{if $orderBy == 'new'}/{$orderBy}{/if}/">{$c.size_rus} ({$c.size_name})</option>
				{/foreach}
			</select>
		</div>
		{/if}
		
		{if $Fcolors|count > 1}					
			<div class="body-color">
				<select class="select">	
					{*<option value="">Цвет</option>*}									
					{foreach from=$Fcolors item="c"}
						<option {if $c.class=='on'}selected="selected"{/if} value="{if $tag != $module}/{$module}{/if}{if $tag}/{$tag}{/if}{if $task == 'top'}/top{/if}/category,{$filters.category}{if !$fsize_not_selected};size,{$filters.size}{/if};color,{$c.id}{if $filters.sex != 'male'}/{$filters.sex}{/if}{if $orderBy == 'new'}/{$orderBy}{/if}/{if $SEARCH}?q={$SEARCH}{/if}">{$c.name}</option>
					{/foreach}
				</select>
			</div>		
		{/if}
		
	{/if}
	
	
	{*if $user_id &&  $user_top_tags|count > 0}
		<div class="body-tags">
			<select class="select">		
				<option value="">Теги</option>			
				{foreach from=$user_top_tags item="t"}
					<option value="/catalog/{$user.user_login}/tag/{$t.slug}/" {if $TAG.slug == $t.slug}selected="selected"{/if}>{$t.name}</option>
				{/foreach}
			</select>	
		</div>	
	{else if $TAGS|count > 0}
		<!--коллекции вместе с новое популярное и тп-->
		<div class="body-tags">
			<select class="select">
				<option value="">{$L.LIST_menu_collections}</option>
				{foreach from=$TAGS item="t"}
					<option {if $filters.category=='poster'|| $filters.category == 'cup' || $filters.category == 'bag' || $filters.category =='patterns-sweatshirts' || $filters.category== 'patterns'}value="/tag/{$t.slug}/{$filters.category}/{if $style_slug}{$style_slug}/{/if}"{else}value="/tag/{$t.slug}/{if $filters.category}category,{$filters.category}{if $filters.size};size,{$filters.size}{/if}{if $filters.color};color,{$filters.color}{/if}/{if $filters.sex!= 'male'}{$filters.sex}/{/if}{/if}"{/if} img="{$t.picture_path}" {if $TAG.slug == $t.slug}selected="selected"{/if}>{$t.name}</option>
				{/foreach}
			</select>	
		</div>}
	{/if*}
	
<!--/noindex-->	
	
	{literal}
	<script>
		$(document).ready(function() { 	
			$('.list-mobile-menu select.select').change(function(){
				if ($(this).val().length > 0)
					location.href =$(this).val(); 
			});
			$('.list-mobile-menu select').selectbox(); //инит красивого селекта
		});
	</script>
	{/literal}

	<div style="clear:both"></div>		
</div>