<div class="b-catalog_v2 moveSidebarToLeft">
	
	{literal}
	<style type="text/css">
		.tags-page{width:980px}
		.tags-page_all-tags {float:right; width:765px}
		.tags-page_tags-title {float:left; width:100%; font-family: 'MyriadPro-CondIt'; color:#504F4F; font-size:28px;margin:1px 0 5px;}
		.tags-page .tags-list .li {list-style:none; padding:0;  display: inline; margin: 0 7px 7px 0;}
		.tags-list .li .designer {display: inline; line-height: 1.5em; margin:0; padding: 1px 3px; vertical-align: baseline; color: #999;}
		.tags-list .li .designer:hover {color:#FFF; text-decoration: none; background:#00A851;background-color: #00A851;border-radius: 2px; cursor: pointer;}
		.b-catalog_v2.moveSidebarToLeft .sidebar_filter {margin-top: -8px;}
	</style>
	{/literal}
	
	<div class="tags-page">
		<div class="tabz" style="float:left;">
			<a href="/blog/tag/" rel="nofollow">теги постов</a>
			<a href="#" class="active" rel="nofollow">все авторы</a>
			<a href="/tag/"rel="nofollow">теги работ</a>			
		</div>
		
		<h1 class="tags-page_tags-title" style="width: auto;">{if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if}</h1> 
	
		
		<br clear="all">
		<!-- Фильтр в правом сайдбаре -->
		{include file="catalog/list.sidebar.tpl"}
	
		<div id="tags" class="tags-page_all-tags">
			<ul class="tags-list">
				{foreach from=$authors item="item"}
				<li class="li">
					<a href="/{$module}/{$item.user_login}/" class="designer" title="Дизайнер - {$item.user_login} | Работ: {$item.gCount}" rel="nofollow" style="font-size:{$item.font}px; white-spance:nowrap"><span><nobr>{$item.user_login} ({$item.user_carma})</nobr></span></a>	
				</li>
				{/foreach}
	        </ul>
		</div>
	
		<p style="clear: both"><a href="/allauthors/" style="color:#CCC">все авторы</a></p>
	
	</div>
	

	
</div>