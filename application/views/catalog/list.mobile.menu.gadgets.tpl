<div class="list-mobile-menu gadgets {if $task == 'auto' || $task == '1color' || $task == 'stickers' || $category == 'moto' || $style_slug == 'case-iphone-4'|| $style_slug == 'case-iphone-5' || $style_slug == 'case-iphone-6' || $style_slug == 'case-iphone-6-plus' || $style_slug == 'case-iphone-4-glossy' || $style_slug == 'case-iphone-5' || $style_slug == 'case-iphone-5-glossy' || $style_slug == 'case-iphone-6-glossy' || $style_slug == 'case-iphone-6-plus-glossy' ||  $style_slug == 'case-galaxy-s5' || 
$style_slug == 'iphone-6-bumper' || $style_slug == 'iphone-5-bumper' ||($category == 'touchpads' && $style_slug == 'case-ipad-mini' || $style_slug == 'iphone-4-resin' || $style_slug == 'iphone-5-resin') || $filters.category=='boards'}notSelectModel{/if}">
	{if $task != 'auto' && $task != '1color'}
		<div class="body-nositel">
			<!--noindex-->
			<select class="select">
				{include file="catalog/list.fix.menu.option.tpl"}
			</select>
			<!--/noindex-->
		</div>
		
		<div class="body-model">
			<!--noindex-->
			<select class="select {if $task != 'auto'  &&  $task != '1color'}global list-{$category}{/if}">
				{if $category == "laptops"}
					<option value="">{$L.SIDEBAR_menu_st_laptop}</option>
					{include file="select.menu.laptops.tpl"}
				{/if}
				{if $category == "touchpads"}
					<option value="">{$L.SIDEBAR_menu_st_touchpads}</option>
					{include file="select.menu.touchpads.tpl"}
				{/if}
				{if $category == "phones" || $category == "cases"}
					<option value="">{$L.SIDEBAR_menu_st_pohes}</option>
					{include file="select.menu.phones.tpl"}
				{/if}				
				{if $category == "ipodmp3"}
					{include file="select.menu.ipodmp3.tpl"}
				{/if}				
				
				{if $task == "auto" || $task == '1color'}
					<option value="">{$L.SIDEBAR_menu_st_auto}</option>
				{/if}
				{if $category == 'moto'}
					<option value="">Наклейки на мотоцикл</option>
				{/if}
				{if $task == "stickers"}
					<option value="">{$L.SIDEBAR_menu_st_vinyl}</option>
				{/if}
			</select>
			<!--/noindex-->
		</div>
	{/if}
	
	{*<div class="body-tags">
		<select class="select">
			<option value="">{$L.LIST_menu_collections}</option>
			{foreach from=$TAGS item="t"}
				<option value="/tag/{$t.slug}/{$filters.category}/{if $style_slug}{$style_slug}/{/if}" {if $TAG.slug == $t.slug}selected="selected"{/if}>{$t.name}</option>
			{/foreach}
		</select>	
	</div>*}
	
	{literal}
	<script>
		$(document).ready(function() { 	
			$('.list-mobile-menu select.select').change(function(){
				if ($(this).val().length > 0)
					location.href =$(this).val(); 
			});
			
			$('.list-mobile-menu .body-model select').change(function(){
				if ($(this).val() != "" && $(this).val() != "-2")
					location.href = '/catalog/' + $(this).children('option:selected').attr('_category') + '/' + $(this).val() + '/new/'; 
			});
			
			$('.list-mobile-menu select').selectbox(); //инит красивого селекта
		});			
	</script>
	{/literal}				
	
	<div style="clear:both"></div>		
</div>