<!-- list.sidebar.filters.tpl -->
{literal}
	<style type="text/css">
	.sidebar_filter ul.filter-list.v2 li{font-size: 14px;margin: 0 0 9px 20px;font-weight: normal;}
	.sidebar_filter ul.filter-list.v2 li .f-item {width: auto;font-size: 14px;line-height: 14px;text-decoration:underline;}	
	.sidebar_filter ul.filter-list.v2 li .f-item.line2px{text-decoration: none;border-bottom: 2px solid #A7A7A7;}
	.sidebar_filter ul.filter-list.v2 li .f-item.line2px:hover{border-color:#ffffff;}
	.sidebar_filter ul.filter-list.v2 li.h{font-size:15px;margin: 7px 5px 15px 0px;
	color: #B4B4B4!important;font-weight: normal;}
	.sidebar_filter ul.filter-list.v2 li.h.activ{color:#00a851 !important}
	.sidebar_filter ul.filter-list.v2 select{width:155px;color:#504F4F;margin: 5px 0 5px 20px;}
	</style>
{/literal}

<ul class="filter-list v2">

	<li class="h">{$L.SIDEBAR_menu_st_vinyl}</li>		

	{if $category != 'laptops'}
		<!--noindex-->
		<select class="global list-phones">
			<option value="">Смартфоны</option>
			{include file="select.menu.phones.tpl"}
		</select>
	
		<select class="global list-laptops">
			<option value="">Ноутбук</option>
			{include file="select.menu.laptops.tpl"}
		</select>
	
		<select class="global list-touchpads">
			<option value="">Планшет</option>
			{include file="select.menu.touchpads.tpl"}
		</select>
		
		<select class="global list-ipodmp3">
			{include file="select.menu.ipodmp3.tpl"}
		</select>
		<!--/noindex-->
	{/if}
	
	{if $category=='laptops' || $category=='touchpads' || $category=='ipodmp3'}
		{literal}<script>
		$(".sidebar_filter select.global:eq(0)").before($('.sidebar_filter select.list-{/literal}{$category}{literal}'));
		</script>{/literal}
	{/if}	
	
	{literal}
	<style>
		.sidebar_filter .selectbox .select{width:128px;margin-bottom:5px;z-index: 9!important;}
		.sidebar_filter .selectbox .dropdown{top:29px!important;width:290px;}
		.sidebar_filter .selectbox .dropdownli{color:#231F20!important;}
		.sidebar_filter .selectbox .dropdown li:hover{color:#fff!important;}
	</style>
	{/literal}
	
	<script>
		$('.sidebar_filter select.global').selectbox();
		
		$('.sidebar_filter ul.filter-list.v2 select').change(function(){
			if ($(this).val() != "" && $(this).val() != "-2")
				location.href = "/{if $module == 'tag' && !$TAG}catalog{else}{$module}{/if}{if $TAG && $module == 'tag'}/{$TAG.slug}{/if}{if $user}/{$user.user_login}{/if}/" + $(this).children('option:selected').attr('_category') + '/' + $(this).val() +  "/new/{if $SEARCH}?q={$SEARCH}{/if}";
		});
	</script>
	
	{if $PAGE->url == "/catalog/stickers/" || $user || ($filters.sex && $filters.sex != 'unisex')}
		{if $user || $filters.sex}
			<li class="h">{$L.SIDEBAR_menu_different}</li>
		{else}
			<br clear="all"/><br clear="all"/>
		{/if}
		<li>{if $filters.category == 'auto'}{$L.SIDEBAR_menu_st_auto}{else}<a class="f-item line2px" href="/{if $module == 'tag' && !$TAG}catalog{else}{$module}{/if}{if $TAG && $module == 'tag'}/{$TAG.slug}{/if}/auto/new/preview/{if $SEARCH}?q={$SEARCH}{/if}" rel="nofollow" title="{$L.SIDEBAR_menu_st_auto}">{$L.SIDEBAR_menu_st_auto}</a>{/if}</li>	
		<li><a class="f-item line2px" href="/{$module}/boards/snowboard/" rel="nofollow" title="Наклейки на доски">Наклейки на доски</a></li>	
		{*<li>{if $filters.category == 'moto'}{$L.SIDEBAR_menu_st_moto}{else}<a class="f-item line2px" href="/{if $module == 'tag' && !$TAG}catalog{else}{$module}{/if}{if $TAG && $module == 'tag'}/{$TAG.slug}{/if}/moto/{if $SEARCH}?q={$SEARCH}{/if}" rel="nofollow" title="{$L.SIDEBAR_menu_st_moto}">{$L.SIDEBAR_menu_st_moto}</a>{/if}</li>*}
		<li>{if $module == 'stickerset'}{$L.SIDEBAR_menu_st_stickerset}{else}<a class="f-item line2px" href="/stickerset/" title="{$L.SIDEBAR_menu_st_stickerset}" rel="nofollow">{$L.SIDEBAR_menu_st_stickerset}</a>{/if}</li>
		{*<li>{if $filters.category == 'sumki'}{$L.SIDEBAR_menu_bags}{else}<a class="f-item line2px" href="/{if $module == 'tag' && !$TAG}catalog{else}{$module}{/if}{if $TAG && $module == 'tag'}/{$TAG.slug}{/if}{if $user}/{$user.user_login}{/if}/category,sumki/new/{if $SEARCH}?q={$SEARCH}{/if}" rel="nofollow" title="{$L.SIDEBAR_menu_bags}">{$L.SIDEBAR_menu_bags}</a>{/if}</li>*}
		{if $PAGE->url != "/catalog/stickers/"}
			<li>{if $filters.category == 'poster'}{$L.SIDEBAR_menu_poster_canvas}{else}<a class="f-item line2px" href="/{if $module == 'tag' && !$TAG}catalog{else}{$module}{/if}{if $TAG && $module == 'tag'}/{$TAG.slug}{/if}{if $user}/{$user.user_login}{/if}/poster/poster-frame-vertical-white-40-x-50/{if $SEARCH}?q={$SEARCH}{/if}" style="font-size:14px;" rel="nofollow" title="{$L.SIDEBAR_menu_poster_canvas}">{$L.SIDEBAR_menu_poster_canvas}</a>{/if}</li>	
		{/if}
	{/if}

	{if $user || ($filters.sex && $filters.sex != 'unisex')}
		<li class="h" style="margin-top: 15px;margin-left: 0px;font-size:14px;color: #504f4f!important;"><a class="f-item line2px" id="filter-red" href="/tag/" rel="nofollow" title="{$L.SIDEBAR_menu_tags}" style="font-size:14px;float: none;">{$L.SIDEBAR_menu_tags}</a> ...</li>
		<li class="h" style="margin-top: 5px;margin-left: 0px;font-size:14px;"><a class="f-item line2px" id="filter-" href="/catalog/authors/" rel="nofollow" title="{$L.SIDEBAR_menu_authors}" style="font-size:14px">{$L.SIDEBAR_menu_authors}</a> ...</li>
		
		<li class="h">{$L.SIDEBAR_menu_service}</li>
		<li><a class="f-item line2px" href="/customize/" rel="nofollow" style="font-size:14px;">{$L.SIDEBAR_menu_constructor}</a></li>
		<li><a class="f-item line2px" href="/customize/filter/category,futbolki/" rel="nofollow" title="{$L.SIDEBAR_menu_print_t_shirt}" style="font-size:14px;">{$L.SIDEBAR_menu_print_t_shirt}</a></li>
		<li><a class="f-item line2px" href="/dealer/" rel="nofollow" title="{$L.SIDEBAR_menu_posiv_t_shirt}" style="font-size:14px;">{$L.SIDEBAR_menu_posiv_t_shirt}</a></li>
		<li><a class="f-item line2px" href="/dealer/izgotovlenie-tolstovok.php" rel="nofollow" title="{$L.SIDEBAR_menu_posiv_hoodies}" style="font-size:14px;">{$L.SIDEBAR_menu_posiv_hoodies}</a></li>
	{/if}

</ul>