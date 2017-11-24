{literal}
<style>
.catalog_goods_list { width: 769px; }
.top-banner {ldelim}display:none{rdelim}
.author_username { float: none; }
.selected51no {	background:url(/images/to-vote-min-gray.png) no-repeat 0 -22px !important;}
.selected51no:hover {	background:url(/images/to-vote-min-gray.png) no-repeat 0 -22px !important;}
.selected51 {background:url(/images/to-vote-min.png) no-repeat 0 -22px !important;}
.selected51:hover {background:url(/images/to-vote-min.png) no-repeat 0 -22px !important;}
.m12 .item .price {	bottom: 123px !important;}
.m12 .item .b-ordensml img { width:auto !important; }

{/literal}{if !$TAG}.sidebar_filter{ margin-top:-10px!important }{/if}{literal}
</style>
{/literal}


<div class="b-catalog_v2 moveSidebarToLeft {if $category != 'moto' && $filters.category != 'boards'}b-catalog_v3{/if}">	
	
	<div class="pageTitle table">
		
		{if $PAGE->title}
			<h1>{if $SEARCH}
					{include file="catalog/list.search-filter.tpl"}
				{else}
					{if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}
				{/if}</h1>
		{/if}
		
		<div style="clear: both;"></div>		
	</div>	
	
	{if !$MobilePageVersion}
		<!-- Фильтр в левом сайдбаре -->
		{include file="catalog/list.sidebar.tpl"}
	{/if}
	
	<div class="catalog_goods_list list_gadgets">
		
		<script type="text/javascript">
			var countElements = {if $count}{$count}{else}0{/if};
			var pageLoaded = {$page};
			var REQUEST_URI = '{$PAGE_URI}';
			{if $page > 1}var autoscrol_count = 0;{/if}
		</script>		
		</script>
		
		<div class="list_wrap manufacturer_catalog {$category}{if $category_style != $category_def_style}_{$category_style}{/if}_catalog {$category}_catalog  matched_{$category_style}_{$category_def_style}" id="{$category}_catalog">
			
			<ul>
				{foreach from=$models item="m" key="k"}	
					<li class="m12" page="{$page}">
						<a rel="nofollow" class="item" title="{$m.style_name} - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" href="{if $m.link}{$m.link}{else}/catalog/{$m.user_login}/{$m.good_id}/{$style_slug}/{/if}">
							<span class="list-img-wrap"><img title="{$m.style_name} - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" alt="{$m.style_name} - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if} " style="background-color: #{$m.bg};" src="{$m.picture_path}" /></span>
						</a>
						
						{if $m.price_old > $m.price}
							<div class="red-sale-stiker"><strike>{$m.price_old} {$L.CURRENT_CURRENCY}</strike><span>{$m.price} {$L.CURRENT_CURRENCY}</span></div>
						{/if}
							
						
						<div class="item">
							<br /><br />
							<a {if $k > 2}rel="nofollow"{/if} class="title" title="{$m.style_name} - {if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}" href="/catalog/{$filters.category}/{$m.style_slug}/new/">{$m.style_name}</a>			
						</div>
						
					</li>
				{/foreach}
			</ul>
			
		</div>
		
	</div>
	
	
	{if !$user_id}
		<!-- Кнопка ВВерх -->
		<div id="button_toTop"><div>Наверх</div></div>
	{/if}
	
</div>

<br clear="all" /><br />
<div class="list_seotexts">
{include file="catalog/list.seotexts.gadgets.tpl"}
</div>