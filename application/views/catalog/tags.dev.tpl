<link rel="stylesheet" href="{get_file_modif_date url='/css/catalog/styles.css'}" type="text/css" media="all"/>

<div class="b-catalog_v2">
	
	{literal}
	<style type="text/css">
	.tags-page, .tags-page_all-tags {float:left;width:765px}
	.tags-page_tags-title {float:left; width:100%; font-family: 'MyriadPro-CondIt'; color:#504F4F; font-size:28px; margin:15px 0;}
	.tags-page .tags-list li {width: 125px;height: 150px;list-style:none; padding:0;float: left;margin: 0 35px 10px 0;}
	.tags-list li .title{color: #666;font-size: 12px;text-decoration: none;}
	</style>
	{/literal}
	
	<div class="tags-page">
	
		<h1 class="tags-page_tags-title" style="width: auto;">Теги работ</h1> 
		
		<div class="tabz" style="clear: none;float: right;width: 340px;">
			<a href="/tag/" class="">теги постов</a>
			<a href="#" class="active">теги работ</a>
			<a href="/catalog/authors/" class="">все авторы</a>
		</div>
		
		<div id="tags" class="tags-page_all-tags">
		<ul class="tags-list" style="width: 800px;">
	        {foreach from=$TAGS item="item"}
			<li>
				<a href="/tag/{$item.slug}/" class="wrap-img" rel="nofollow">
					<img src="{$item.picture_path}" width="125" height="125" alt="{$item.name}" title="{$item.name}">
				</a>
				<a href="/tag/{$item.slug}/" class="title" rel="nofollow" title="{$item.name}">{$item.name}</a>
		    </li>
			{/foreach}
			<br clear="all">
	        </ul>
		</div>
	
		<p style="clear: both;"><a href="/alltag/" rel="nofollow" style="color:#CCC;">все теги</a></p>
	
	</div>
	
	<!-- Фильтр в правом сайдбаре -->
	{include file="catalog/list.sidebar.tpl"}
	
</div>