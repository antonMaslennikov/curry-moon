{literal}
<style type="text/css">
	.tags-page_all-tags {float:right;width:765px}
	.tags-page_tags-title {float: none;clear: both;width:100%; font-family: 'MyriadPro-CondIt'; color:#504F4F; font-size:28px; margin:15px 0 5px;}
	.tags-page_all-tags .tags-list li {width: 125px;height: 150px;list-style:none; padding:0;float: left;margin: 0 35px 10px 0;}
	.tags-page_all-tags .tags-list li .title{color: #666;font-size: 12px;text-decoration: none;}
	
	.b-catalog_v2.moveSidebarToLeft .sidebar_filter {margin-top: -8px;}
</style>
{/literal}	
	
<div class="b-catalog_v2 moveSidebarToLeft ">
	<div class="tabz" style="float:left;">
		<a href="/blog/tag/" rel="nofollow">теги постов</a>			
		<a href="/catalog/authors/" rel="nofollow">все авторы</a>
		<a href="#" class="active" rel="nofollow">теги работ</a>			
	</div>
	
	{if $USER->user_id == 27278 || $USER->user_id == 6199 || $USER->user_id == 105091}
		<div class="filter_view_tags" style="border-bottom: 1px dashed orange;">
			<a href="/tag/" rel="nofollow" class="view-o active" title=""></a>
			<a href="/tagall/" rel="nofollow" class="view-t " title=""></a>
		</div>
	{/if}		
	
	
	<h1 class="tags-page_tags-title" style="width:auto">{$PAGE->title}</h1> 
	
	<!-- Фильтр в левом сайдбаре -->
	{include file="catalog/list.sidebar.tpl"}
	
	<div id="tags" class="tags-page_all-tags">
		<ul class="tags-list" style="width: 800px;">
			{foreach from=$TAGS item="item"}
			<li>
				<a href="/tag/{$item.slug}/new/" class="wrap-img" rel="nofollow">
					<img src="{$item.picture_path}" width="125" height="125" alt="{$item.name}" title="{$item.name}">
				</a>
				
				<a href="/tag/{$item.slug}/new/" class="title" rel="nofollow" title="{$item.name}">{$item.name}</a>
				
				{if $USER->meta->mjteam == "super-admin"}
				(<a href="#" class="regeneratePic" data-tag="{$item.tag_id}" style="border-bottom: 1px dashed orange;text-decoration: none">обновить</a>)
				{/if}
			</li>
			{/foreach}
			<br clear="all">
		</ul>
		<p style="clear: both;"><a href="/tagall/" rel="nofollow" style="color:#CCC;">все теги</a></p>
	</div>
	<br clear="all">
</div>

{literal}
<script>
	$('.regeneratePic').click(function(){
		
		if ($(this).data('tag') > 0)
		{
			var img = $(this).prev().prev().children('img');
			var old = $(img).attr('src');
			$(img).attr('src', '/images/loading2.gif');
			
			$.getJSON('/tag/regenerate/' + $(this).data('tag') + '/', function(r) {
				if (!r.error) {
					$(img).attr('src', r.path);
				} else {
					alert(r.error);
					$(img).attr('src', old);
				}
				
			});
		}
		
		return false;
	});
</script>
{/literal}	
