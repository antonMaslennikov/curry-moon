{literal}
<style type="text/css">
#content{width:100%}
.tagscloud a {color:#00A851}

.blog-title {float:left; width:100%; border-bottom:2px solid #D7D7D7; padding:0 0 10px; margin:0 0 20px;}

.edit-block {float:left; width:100%; margin:3px 0; padding:0;}
.edit-block li {float:left; list-style:none; padding:0 3px; margin:0; color:#f22069; font-size:11px;}
.edit-block li a {color:#f22069; font-size:11px;}

.blog-title .title-middle {float:left; width:100%; position:relative;}
/*.title-middle .pagetitle {float:left; width:575px; font-size:250%; padding:5px 0 0; margin:0;}
.title-middle .soc_btns {position:absolute; z-index:150; margin:13px 0 0 0; height:25px; width:275px; left:580px;}*/
.title-middle .pagetitle {float:left; width:718px; font-size:250%; padding:5px 0 0; margin:0;}
.title-middle .soc_btns {position:absolute; z-index:150; margin:13px 0 0 0; height:25px; width:111px; left:715px;}

.blog-title .annnotation {float:left; width:100%; font-size:11px;}

.blog-title .blog-post-carma {float:right; border-radius:10px; background-color:#f1f1f1; width:120px; height:28px; padding:4px 0 12px;}

.blog-title .sharing-btn .b-share__handle { margin-left: -25px !important;padding-left: 25px !important; }
.blog-title .sharing-btn { height: 28px; width:85px; padding: 0px 0px 0px 23px; font-size:14px; color: #504F4F; background: url(/images/catalog/icons-author-page.png) no-repeat 0 -59px; }
.blog-title .sharing-btn .b-share {
	font: 86%/1.4545em Arial, sans-serif;
	display: inline-block;
	vertical-align: middle;
	padding: 1px 3px 1px 4px;
}

</style>
{/literal}

<!-- 
include file="profile.quickinfo.tpl"
include file="profile.tabz.tpl"
 -->
<div class="moduletable">
	{if $post.post_status == 'draft' && $post.post_author == $currentuser.user_id}
	<div style="background-color:#CCCCCC; color:#000000; padding:7px">Черновик. <strong><a class="modif" href='/{$module}/publish/{$post.id}/' rel="nofollow" style="color:#000">Опубликовать</a></strong> </div>
	{/if}
		
	<div class="blog-title">
		{if $authorized && $post.post_status != 'deleted' && ($currentuser.user_id == $post.post_author || $currentuser.user_id == 6199 || $currentuser.user_id == 61429)}
			<ul class="edit-block">
				<li><a class="modif" href='/{$module}/edit/{$post.id}/'>Редактировать</a></li>
				<li class="delim">|</li>
				{if $currentuser.user_id == 6199 || $currentuser.user_id == 61429}
					{if $post.post_status != 'blocked'}						
					<li><a href='/{$module}/block/{$post.id}/'>Закрыть тему</a></li>
					<li class="delim">|</li>
					{else}						
					<li><a href='/{$module}/block/{$post.id}/'>Открыть тему</a></li>
					<li class="delim">|</li>
					{/if}							
				{/if}
				
				<li><a class="error" href='/{$module}/delete/{$post.id}/' onclick='return confirm("Вы действительно хотите удалить этот пост?")'>Удалить</a></li>
			</ul>
		{/if}

		<div class="title-middle">
			<h1 class="pagetitle">{$post.post_title}</h1>
			
			<!--noindex-->
			<div class="soc_btns">
			
				<div class="sharing-btn">
					<script type="text/javascript" src="//yandex.st/share/share.js" charset="utf-8"></script>
					<div class="yashare-auto-init" data-yashareL10n="ru" data-yashareType="link" data-yashareQuickServices=""></div> 					
				</div>
			
			{*
				<div id="fb-root"></div>
				{literal}		
				<script>
				  window.fbAsyncInit = function(){FB.init({appId  : '192523004126352', status : true, cookie : true, xfbml: true });};
				  (function() {
					var fbe = document.createElement('script');
					fbe.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
					fbe.async = true;
					document.getElementById('fb-root').appendChild(fbe);
				  }());
				</script>
	
				<script type="text/javascript">				
					if($.browser.msie && $.browser.version == '7.0'){
						document.write("<iframe src=\"http://www.facebook.com/plugins/like.php?href=layout=button_count&amp;show_faces=false&amp;width=80&amp;action=like&amp;font&amp;colorscheme=light&amp;height=21&amp;image=http://www.maryjane.ru/images/new/logo.png\" scrolling=\"no\" frameborder=\"0\" style=\"border:none; overflow:hidden; width:150px; height:21px;\" allowTransparency=\"true\"><\/iframe>");
					}else{
						document.write("<fb:like layout=\"button_count\" show_faces=\"false\" width=\"80\" font=\"arial\" image=\"http://www.maryjane.ru/images/new/logo.png\" action=\"Like\"><\/fb:like>");
					}
				</script>
				<style>	
					.fb_edge_widget_with_comment {float:left;}	
					.twitter-share-button {width:90px!important;}
				</style>
				<a href="http://twitter.com/share" class="twitter-share-button" data-count="horizontal" rel="nofollow">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
				<!-- Разместите этот тег в теге head или непосредственно перед закрывающим тегом body -->
				<script type="text/javascript" src="https://apis.google.com/js/plusone.js">{lang: 'ru'}</script><!-- Разместите этот тег в том месте, где должна отображаться кнопка +1 --><g:plusone size="medium"></g:plusone>
				<!--/noindex-->
				{/literal}
			*}
			</div>

			<!-- Блок с кармой -->
			<div class="blog-post-carma">
			{$post.postCarma}
			</div>
			
		</div>
		
		<div class="annnotation">
			<span style="padding-left:5px;"><a href="/profile/{$post.user_id}/">{$post.post_author_name}</a> {$post.user_designer_level}</span> 
            <!--noindex-->	
			{if $post.post_status != 'draft'}<span style="padding-left:10px;">{$post.post_date}</span>{/if}
			<span style="padding-left:10px;">Просмотров: {$post.visits}</span>
			<span style="padding-left:10px;">Комментариев: {$post.comments}</span>
            <!--/noindex-->
		</div>
	</div>


	{if $post.poll_id > 0}
	<div style="float:right;width:28%; border-left:1px solid gray" class="ext-poll center" id="{$post.poll_id}"></div>
	{/if}
	
	<div class="dark moduletable wp clearfix" style="padding-left:10px;width:70%;overflow:hidden; float:left; font-size:12px">
	
		{if $post.post_pic > 0}
		<p>
			<img src="{$post.post_picture}" alt="{$post.post_title}" title="{$post.post_title}"/>
		</p>
		{/if}
	
		{$post.post_content}
	</div>
	
	<div class="clr clearfix"></div>
	<br clear="all" />
	<div style="margin-top: 35px">Тема: <em><a href="/{$module}/news/{$post.post_theme_slug}/" rel="nofollow">{$post.post_theme}</a></em></div>
	<div style="margin: 15px 0;color:#999999" class="tagscloud"><span class="ico-tags"></span> <em>{$post.post_tags}</em></div>
	
	
	{literal}
	<style type="text/css">
	.related_posts {float:left; width:670px; padding:20px; border:2px solid #d7d7d7; border-width:2px 0;}
	.related_posts h4 {float:left; width:100%; margin:0 0 25px; text-transform:none;}
	.related_posts ul li {float:left; width:100%; margin:0 0 10px;}	
	</style>
	{/literal}
	
	<div class="related_posts">
		<h4>Похожие посты</h4>
		<ul>
			{foreach from=$relatedPosts item="rp"}
			<li><a href="/blog/view/{$rp.id}/">{$rp.post_title}</a> от <a href="/profile/{$rp.user_id}/">{$rp.user_login}</a></li>
			{/foreach}
		</ul>
		<br clear="all" />
		<p style="clear:both;margin-top:15px">
		<a href="/blog/view/{$prev.id}/" title="{$prev.post_title}">предыдущий пост</a> |
		<a href="/blog/view/{$next.id}/" title="{$next.post_title}">следующий пост</a>
		</p>
	</div>
	
	
</div>

{include file="commentForm.voting.dev.tpl"}