<div class="blog-sidebar">
	{literal}
	<style>
	.blogroll li {list-style-type: none;font-size: 12px !important;		margin: 5px 0 !important;background: none !important;	}
	.blogroll li a {color: #2F76BD;text-decoration: underline;}
	.blogroll li a:hover {color: #00be75;}
	
	.blogroll li a.year-active {font-weight: bolder;color:#00be75;text-decoration:none}
	
	.top10tags {float:left; width:280px; overflow:hidden; margin:20px 0 0 4px;}
	.top10tags .wrap {width:270px;white-space: nowrap}
	.top10tags .tag {float:left; padding:4px 11px; margin:0 7px 11px 0; background-color:#c9c9c9; color:white; text-decoration:none;}
	</style>
	{/literal}	

	{if $USER->authorized && !$SEARCH}	
		{if $PAGE->url != "/blog/" && $PAGE->reqUrl.1!='news'}
		<div class="butt_plus_post">
			<a href="/blog/new/" rel="nofollow">Добавить пост</a>
		</div>
		{/if}
		
		<div class="blog-feed-btn"  style="border:none; margin:0 0 5px 5px;">
			{if $subscribe}
			<a href="/{$module}/subscribe/" rel="nofollow" class="feedbtn">Подписаться</a>
			{else}
			<a href="/{$module}/unsubscribe/" rel="nofollow" class="feedbtn unsubscribe_feed">Отписаться</a>
			{/if}		
		</div>
	{/if}

	{if !$year}
        {*<ul id="comment-switcher" class="b-comment-switcher">
			<li class="b-litem active"><a onclick="return GWH(this, 'com_blog', 8, 'b-comment', 'blog');" href="#">в блогах</a></li>
			<li class="b-litem"><a onclick="return GWH(this, 'com_goods', 8, 'b-comment', 'blog');" href="#">на голосовании</a></li>
			<li class="b-litem"><a onclick="return GWH(this, 'com_pictures', 8, 'b-comment', 'blog');" href="#">к фото</a></li>
		</ul>*}
	
		{if !$SEARCH}
		<div class="b-comment-sidebar" id="b-comment">	
			{* include file="blog/news.sidebar.comments.tpl" *}	
		</div>
		{/if}
	
		{*<!--div class="b-sidebar-tagcloud">
			{$topBlogTags}
		</div-->*}
		<div class="top10tags">
			<div class="wrap">
				{* foreach from=$topBlogTags item="t"}
				<a title="{$t.count}" class="tag" href="/tag/posts/{$t.slug}/">{$t.name}</a>
				{/foreach *}
			</div>
		</div>	
	{/if}
	<div style="clear:both"></div>
	<div style="width:286px;margin:25px 0 0 0px;border:1px solid #EDEDED">
		<div style="color:#696869;font-size:16px;font-weight:bold;line-height:18px;padding: 20px 20px 0 20px">Архив</div>
		<ul class="blogroll" style="margin:10px 0">
			{* foreach from=$years item="y"}
			<li><a href="/blog/year/{$y.y}/" {if $y.y == $year}class="year-active"{/if}>{$y.y}</a></li>
			{/foreach *}
		</ul>
	</div>	
</div>

{literal}
<script>
$(document).ready(function($){
	
	$('.b-comment-sidebar').addClass('loading');
	$.get('/ajax/gwh/com_goods/5/?tpl=com_blog', function(responce){ $('.b-comment-sidebar').html(responce).removeClass('loading'); });
	
	$('.top10tags .wrap').addClass('loading');
	$.get('/ajax/getBlogPopularTags/', function(responce){ $('.top10tags .wrap').html(responce).removeClass('loading'); });
	
	$('.blogroll').addClass('loading');
	$.get('/blog/archive/', function(responce){ $('.blogroll').html(responce).removeClass('loading'); });
		
});

var subscribe_link = '/blog/subscribe/';
var unsubscribe_link = '/blog/unsubscribe/';
	$('.feedbtn').click(function () {
		
		// урлы всётаки разные 
		// для подписки отписки - '/{$module}/subscribe/'
		// для подписки отписки - '/{$module}/unsubscribe/'
		/*
		var url 		= $(this).attr("href");
		*/
		var unsubscribe = $(this).hasClass('unsubscribe_feed');
		var curr_link	= $(this);
		
		if (unsubscribe) {
			var url = unsubscribe_link;
		} else {
			var url = subscribe_link;
		}
		
		$(this).addClass('active');
		setTimeout(function(){$(curr_link).removeClass('active');}, 1000);
		
		$.get(url, function() {
			if (!unsubscribe) { 
				$(curr_link).addClass('unsubscribe_feed').attr("href", unsubscribe_link);
			} else {
				$(curr_link).removeClass('unsubscribe_feed').arttr("href", subscribe_link) ;
			}
		});
		
		return false;
	});
</script>
{/literal}