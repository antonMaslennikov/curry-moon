{literal}
<style>
#button_toTop{top:13px;z-index: 999;}
@media only screen and (max-width : 1155px){
	#button_toTop{top:70px;}
}
</style>
{/literal}

<div class="fix-menu gadgets {if $task == 'auto' || $task == '1color' || $task == 'stickers' || $category == 'moto' || $category == 'bag' || $style_slug == 'case-iphone-4' || $style_slug == 'case-iphone-5' || $style_slug == 'case-iphone-6' || $style_slug == 'case-iphone-6-plus' || $style_slug == 'case-iphone-4-glossy' || $style_slug == 'case-iphone-5-glossy' || $style_slug == 'case-iphone-6-glossy' || $style_slug == 'case-iphone-6-plus-glossy' ||  $style_slug == 'case-galaxy-s5' || 
$style_slug == 'iphone-6-bumper' || $style_slug == 'iphone-5-bumper' || ($category == 'touchpads' && $style_slug == 'case-ipad-mini') || $style_slug == 'iphone-4-resin' || $style_slug == 'iphone-5-resin' || $filters.category=='boards' || $filters.category=='cup'}notSelectModel{/if}  {if $MobilePageVersion &&($filters.category == 'phones'||$filters.category == 'laptops' ||$filters.category == 'ipodmp3' || $filters.category=='touchpads')}notNositel{/if} {if $MobilePageVersion &&($filters.category == 'auto')}withSwitType{/if}">
	<div class="rel">

		<div class="body-nositel">
			{if $category != 'laptops'}
			<!--noindex-->
			<select class="select">
				{include file="catalog/list.fix.menu.option.tpl"}
			</select>
			<!--/noindex-->
			{/if}
		</div>
		
		<div class="body-model">
			<!--noindex-->
			<select class="select {if $task != 'auto' &&  $task != '1color'}global list-{$category}{/if}">
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
				
				{if $task == "auto" ||  $task == '1color'}
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
		
		
		{*для чехлов второй селек делали активным*}
		{*if $style_slug == 'case-iphone-4' || $style_slug == 'case-iphone-5'}
			{literal}
			<script>
				$('.fix-menu .body-model select option').eq(0).remove();
				$('.fix-menu .body-model select option[value="iphone-'+parseInt("{/literal}{$style_slug}{literal}".replace(/\D+/g,""))+'"]').attr('selected', 'selected');
			</script>
			{/literal}
		{/if*}
		
		{literal}
		<script>
			$(document).ready(function() { 	
				$('.fix-menu select.select').change(function(){
					if ($(this).val().length > 0 && $(this).val() != "" && $(this).val() != "-2"){
						if($(this).parent().hasClass('body-model'))
							location.href = '/catalog/' + $(this).children('option:selected').attr('_category') + '/' + $(this).val() + '/new/'; 
						else
							location.href =$(this).val(); 					
					}
				});				
				/*$('.fix-menu .body-model select').change(function(){
					if ($(this).val() != "" && $(this).val() != "-2")
						location.href = '/catalog/' + $(this).children('option:selected').attr('_category') + '/' + $(this).val() + '/new/'; 
				});*/
			});			
		</script>
		{/literal}
				
		<div class="body-tags {if !$MobilePageVersion}NLtags{/if}">
			<select class="tags" _category="{$filters.category}" _color="{$filters.color}" _size="{$filters.size}" _sex="{$filters.sex}" _style="{$style_slug}">
				<option value="">{$L.LIST_menu_collections}</option>
                {foreach from=$TAGS item="t"}
					<option value="{$t.slug}" img="{$t.picture_path}" {if $TAG.slug == $t.slug}selected="selected"{/if} _category="{$filters.category}" _style="{$style_slug}">{$t.name}</option>
				{/foreach}
			</select>	
		</div>	
		{literal}
		<script>
			$(document).ready(function() { 	
				$('.fix-menu select.tags').change(function(){
					if ($(this).val().length > 0) {
						var sid = $(this).children('option:selected').attr('_style');
						
						location.href ='/tag/'+ $(this).val() + '/' + $(this).children('option:selected').attr('_category') + '/' + ((sid.length > 0) ? sid + '/' : '');
					} 
				});
			});
		</script>
		{/literal}			
		
		{if $task == "auto"}
			{literal}<style>.fix-menu.gadgets.notSelectModel .body-nositel .selectbox .select{width: 367px;}.fix-menu.gadgets.notSelectModel .body-nositel .selectbox .dropdown{width: 411px;}.en .fix-menu.gadgets.notSelectModel .body-nositel .selectbox .select {width: 375px;}.en .fix-menu.gadgets.notSelectModel .body-nositel .selectbox .dropdown {width: 420px;}</style>{/literal}				
			<div class="b-filter_view" style="margin:13px {if $PAGE->lang=='en'}1{/if}8px 0 -3px;">
				<a href="/{$module}/{$task}/new/" rel="nofollow" class="view-pipl {if $mode != 'preview'}active{/if}" title=""></a>
				<a href="/{$module}/{$task}/preview/" rel="nofollow" class="view-other {if $mode == 'preview'}active{/if}" title=""></a>
			</div>
		{/if}
				
		<div class="b-filter_tsh {$PAGE->lang}">
			<a href="{$base_link}/new/" title="{$L.GOOD_new}" rel="nofollow" class="new-filter {if $orderBy == 'new'}active{/if}" data-sort="new">{*Новое*}</a>
			<a href="{$base_link}/popular/" title="{$L.GOOD_popular}" rel="nofollow" class="pop-filter {if $orderBy == 'popular' || $orderBy == ''}active{/if}" data-sort="popular">{*Популярное*}</a>
			<a href="{$base_link}/grade/" rel="nofollow" class="score-filter {if $orderBy == 'grade'}active{/if}" title="{$L.GOOD_rating}" data-sort="grade">{*По оценке*}</a>
		</div>
		
		{include file="top_menu_search.tpl"}		
		
		<div style="clear:both"></div>
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