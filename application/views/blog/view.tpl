{literal}
<style type="text/css">
.tagscloud a{color:#00A851}
h3{font-size:30px;text-align:center;color:#000;}
</style>

<!--слайдер картинок-->
<script type="text/javascript" src="/js/blog-slider.js"></script>
<!--script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script-->
{/literal}

{if $post.id == 5120 && $MobilePageVersion}	
	<link rel="stylesheet" href="/css/glissegallery.css" type="text/css" media="all"/>
	<script type="text/javascript" src="/js/glissegallery.js"></script>
	{literal}
	<script>
		$(document).ready(function() {
			$(".sliderZZ img").each(function () {
				$(this)
					.attr('data-big',$(this).attr('src'))					
					.attr('rel','group')
					.addClass('glisse');
				
				//.wrap( "<a class='glisse' href='"+$(this).attr('src')+"' rel='group'></a>" )
			});
			
			//$('.glisse').glisse({speed: 500, changeSpeed: 550, effect:'roll', fullscreen: false,mobile:true}); 
			$('.glisse').glisse({mobile:true}); 
		});			
	</script>
	{/literal}
{else}
	<script type="text/javascript" src="/js/blog-sliderZZ.js"></script>
{/if}

<div class="moduletable post_id{$post.id}">
	{if $post.post_status == 'draft' && ($post.post_author == $USER->user_id || $USER->user_id == 6199 || $USER->user_id == 27278)}
	<div style="background-color:#CCCCCC; color:#000000; padding:7px">Черновик. <strong><a class="modif" href='/{$module}/publish/{$post.id}/' rel="nofollow" style="color:#000">Опубликовать</a></strong> </div>
	{/if}
	
	{if $post.post_status == 'draft' && $post.post_author == $USER->user_id && $post.post_date == '0000-00-00 00:00:00'}
	<div style="background-color:#CCCCCC; color:#000000; padding:7px">Черновик. <strong>Ожидает модерации</strong></div>
	{/if}
		
	<div class="blog-title">
		{if $USER->authorized && $post.post_status != 'deleted' && ($USER->user_id == $post.post_author || $USER->user_id == 27278 || $USER->user_id == 6199 || $USER->user_id == 105091)}
			<ul class="edit-block">
				<li><a class="modif" href='/{$module}/edit/{$post.id}/'>Редактировать</a></li>
				<li class="delim">|</li>
				{if $USER->id == 6199}
					{if $post.post_status != 'blocked'}						
					<li><a href='/{$module}/block/{$post.id}/'>Закрыть тему</a></li>
					<li class="delim">|</li>
					{else}						
					<li><a href='/{$module}/block/{$post.id}/' style='font-size:16px; color:#F00'>Открыть тему</a></li>
					<li class="delim">|</li>
					{/if}							
				{/if}
				
				<li><a class="error" href='/{$module}/delete/{$post.id}/' onclick='return confirm("Вы действительно хотите удалить этот пост?")'>Удалить</a></li>
			</ul>
		{/if}

		<div class="title-middle">
			<h1 class="pagetitle">{$post.post_title}</h1>
			
			
			{if $USER->country!='RU'}
				<div id="ytWidget">{*yandex перводчик*}</div>
			{else}
				<div class="newSelect51 {if $remove_from_selected}yes{/if}" style="margin:8px 0 0;float: right;width:136px;">
					{if $remove_from_selected}
						<a href="{if $USER->authorized}/selected/add/{$post.user_id}/{else}#{/if}" class="selected51 ico selectedAjax" title="Удалить автора из избранных" rel="nofollow">Уведомления</a>
					  {else}
						<a href="{if $USER->authorized}/selected/add/{$post.user_id}/{else}#{/if}" class="selected51no ico selectedAjax" title="Подписаться" rel="nofollow">Подписаться</a>
					{/if}
					<span class="favoriteCount{if !$remove_from_selected}No{/if}"><i></i>{$post.selected}</span>
					<div class="hint2">
						<div class="wi"><div class="i"></div></div>
						<ul>	
							<li class="{if $USER->subscriptions.2 < 0}{else}a{/if}"><a href="/subscribe/{$USER->id}/{$subscribe_code}/{if $USER->subscriptions.2 < 0}2{else}-2{/if}/"rel="nofollow">Новые работы</a></li>
							{* <li class="{if $USER->subscriptions.10 < 0}{else}a{/if}"><a href="/subscribe/{$USER->id}/{$subscribe_code}/{if $USER->subscriptions.10 < 0}10{else}-10{/if}/"rel="nofollow">Победы</a></li> *}
							<li class="{if $USER->subscriptions.4 < 0}{else}a{/if}"><a href="/subscribe/{$USER->id}/{$subscribe_code}/{if $USER->subscriptions.4 < 0}4{else}-4{/if}/"rel="nofollow">Посты</a></li>
						</ul>	
					</div>						
				</div>
			{/if}	
			{*<!--noindex-->
			<div class="soc_btns">			
				<div class="sharing-btn">
					<script type="text/javascript" src="//yandex.st/share/share.js" charset="utf-8"></script>
					<div class="yashare-auto-init" data-yashareL10n="ru" data-yashareType="link" data-yashareQuickServices=""></div> 					
				</div>			
			</div>
			<!--/noindex-->*}	
			
			{*<!-- Блок с кармой -->
			<div class="blog-post-carma">
			{$post.postCarma}
			</div>*}
			
		</div>
		
		<div class="annnotation">
			<span style="padding-left:5px;"><a rel="nofollow" href="/blog/user/{$post.user_login}/">{$post.post_author_name}</a> {$post.user_designer_level}</span> 
			
				
            <!--noindex-->	
			{if $post.post_status != 'draft'}<span style="padding-left:10px;">{$post.post_date}</span>{/if}
			<span style="padding-left:10px;" class="mobVisits">Просмотров: <font>{$post.visits}</font></span>
			<span style="padding-left:10px;" class="mobComments">Комментариев: <font>{$post.comments}</font></span>
            <!--/noindex-->
		</div>
	</div>


	{if $post.poll_id > 0}
	<div style="float:right;width:28%; border-left:1px solid gray" class="ext-poll center" id="{$post.poll_id}"></div>
	{/if}
	
	<div class="dark moduletable wp clearfix" style="padding-left:10px;{if $post.poll_id > 0}width:70%;{/if}overflow:hidden; float:left; font-size:14px">

		{if $post.post_picture != '' && $post.id != 5012 && $post.id != 5013 && $post.id <= 5080}
		<p>
			{if $post.id == 1593}
			<a href="/catalog/category,tolstovki/">
			{/if}
			<img src="{$post.post_picture}" alt="{$post.post_title}" title="{$post.post_title}"/>
			{if $post.id == 1593}
			</a>
			{/if}
		</p>
		{/if}
	
		{$post.post_content}
		
		{if $gallery|count > 0 && ($USER->user_id == 27278 || $USER->user_id == 6199 || $USER->user_id == 105091 || $USER->user_id == 86455)}
			<div class="gallery" style="border:1px dashed orange">
				<div class="preview">
					<div class="control_btn mini control_prev"></div>
					<div class="control_btn mini control_next"></div>
					<div class="viewport">
						<div class="items" style="left: 0px;">
							{foreach from=$gallery item="g" name="gallery_foreach"}
							<img alt="" class="item {if $smarty.foreach.gallery_foreach.first}active{/if}" data-image="{$g.path}" rel="image_src" src="{$g.preview}">
							{/foreach}
						</div>
					</div>
				</div>	
				<div class="photo">
					<div class="control-placeholder control-placeholder_left">
						<div class="control_btn control_prev"></div>
					</div>
					<div class="control-placeholder control-placeholder_right">
						<div class="control_btn control_next"></div>
					</div>
					<img class="image" src="{$gallery.0.path}" style="opacity:1; margin-top:0" alt="">
				</div>	
			</div>
			<script type="text/javascript" src="/js/gallery/gallery.js" charset="utf-8"></script>
			<link rel="stylesheet" href="/js/gallery/gallery.css" type="text/css" media="all"/>
		{/if}
		
	</div>
	
	{if $post.id == 5118 || $post.id == 5130|| $post.id == 5149}
		{if $MobilePageVersion}
			{literal}<script type="text/javascript">				
				$(function(){					
					$('.gamma-container').html( $('.gamma-container').find('noscript').text() );
				});
			</script>{/literal}
		{else}
			<link rel="stylesheet" type="text/css" href="/js/gamma-gallery/gamma-gallery.css"/>
			<script src="/js/gamma-gallery/modernizr.custom.70736.js"></script>			
			<script src="/js/gamma-gallery/jquery.masonry.min.js"></script>
			<script src="/js/gamma-gallery/jquery.history.js"></script>
			<script src="/js/gamma-gallery/js-url.min.js"></script>
			<script src="/js/gamma-gallery/jquerypp.custom.js"></script>
			<script src="/js/gamma-gallery/gamma.js"></script>
		{literal}
		<script type="text/javascript">
				
				$(function() {

					$('.gamma-container .aLoadMore[href="#"]').click(function(){
						$('.gamma-container.overflowMore').removeClass('overflowMore');
						$(this).remove();
						return false;
					});				
				
					var GammaSettings = {
							// order is important!
							viewport : [ {
								width : 1200,
								columns : 4
							}, {
								width : 900,
								columns : 4
							}, {
								width : 500,
								columns : 4
							}, { 
								width : 320,
								columns : 1
							}, { 
								width : 0,
								columns : 2
							} ]
					};
					//Gamma.init(((MobilePageVersion == true) ? {'columns':1} :'' ));
					Gamma.init();
					//Gamma.init( GammaSettings, fncallback);


					// Example how to add more items (just a dummy):

					
					var page = 0,
						items = ['<li><div data-alt="FACESLACES2014" data-description="<h3>Sky high</h3>" data-max-width="960" data-max-height="720"><div data-src="/J/blog_images/5118/FACESLACES2014.jpg"></div>				<noscript><img src="/J/blog_images/5118/FACESLACES2014.jpg" alt="FACESLACES2014"/></noscript></div></li>']

					function fncallback() {
						$( '#loadmore' ).show().on( 'click', function() {
							++page;
							var newitems = items[page-1]
							if( page <= 1 ) {							
								Gamma.add( $( newitems ) );
							}
							if( page === 1 ) {
								$( this ).remove();
							}
						} );
					}
					
				});

			</script>	
			{/literal}
		{/if}
	{/if}
	
	
	<div class="clr clearfix"></div>
	<br clear="all" />
	<!--div style="margin-top: 35px">Тема: <em><a href="/{$module}/news/{$post.post_theme_slug}/" rel="nofollow">{$post.post_theme}</a></em></div-->
	<div style="margin:15px 0;color:#999" class="tagscloud"><span class="ico-tags"></span><em>{$post.post_tags}</em></div>
	
{if !$MobilePageVersion}
	<div style="">
		
		{if $USER->user_id == 27278}
			{literal}<style>.share42init.light{border-bottom: 1px dashed orange;}</style>{/literal}
			
			{include file="voting/logout.voting.tpl"}
						
		{else}		
			<!-- FACEBOOK -->
			{literal}
			<div class="left">
				<div class="fb-like" style="margin-right:4px;" data-href="http://www.maryjane.ru/blog/view/{/literal}{$post.id}{literal}/" data-send="false" data-layout="button_count" data-width="450" data-show-faces="true"></div>
				<div id="fb-root" style="display:none;"></div>
				<script>(function(d, s, id) {
				  var js, fjs = d.getElementsByTagName(s)[0];
				  if (d.getElementById(id)) return;
				  js = d.createElement(s); js.id = id;
				  js.src = "//connect.facebook.net/en_EN/all.js#xfbml=1&appId=192523004126352";
				  fjs.parentNode.insertBefore(js, fjs);
				}(document, 'script', 'facebook-jssdk'));</script>			
			</div>
			{/literal}
			

		{literal}
		<!-- ВКОНТАКТЕ --><noindex >
		<div class="left" style="width:80px;margin-left:10px;margin-top:-1px;">
			<!-- Put this script tag to the <head> of your page -->
			<script type="text/javascript" src="http://vk.com/js/api/share.js?11" charset="windows-1251"></script>
			<!-- Put this script tag to the place, where the Share button will be -->
			<script type="text/javascript"><!--
			document.write(VK.Share.button('{/literal}http://www.maryjane.ru/blog/view/{$post.id}{literal}/',{type: "round", text: "+"}));
			--></script>
		</div></noindex >		
		<div class="left" style="width:65px;margin-left:10px;">
			<!-- Google Разместите этот тег в теге head или непосредственно перед закрывающим тегом body -->
			<script type="text/javascript" src="https://apis.google.com/js/plusone.js">{lang: 'ru'}</script><!-- Разместите этот тег в том месте, где должна отображаться кнопка +1 --><g:plusone size="medium" href="{/literal}http://www.maryjane.ru/blog/view/{$post.id}{literal}/"></g:plusone>
		</div>
		{/literal}
	{/if}
	
		{literal}<!-- twitter -->
		<div class="left">
			<a href="http://twitter.com/share" class="twitter-share-button" data-count="horizontal" data-url="{/literal}http://www.maryjane.ru/blog/view/{$post.id}{literal}/" rel="nofollow">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
		</div>		
		{/literal}
	</div>
{/if}	
	
	{literal}
	<style type="text/css">
	#related_posts {/*float:left;*/clear: both;width: 637px;padding:20px; border:2px solid #d7d7d7; border-width:2px 0;}
	.related_posts {float:left;clear: both; }
	.related_posts h4 {float:left; width:100%; margin:0 0 25px; text-transform:none;}
	.related_posts ul li {float:left; width:100%; margin:0 0 10px;}	
	</style>
	{/literal}
	
	<div id="related_posts">
		<div class="related_posts">			
			{*include file="blog/view.related.tpl"*}			
		</div>
		<br clear="all" />
		<p style="clear:both;margin-top:15px">
			<a href="/blog/view/{$prev.post_slug}/"  title="{$prev.post_title}">предыдущий пост</a> |
			<a href="/blog/view/{$next.post_slug}/" title="{$next.post_title}">следующий пост</a>
		</p>
	</div>	
	
	
	{literal}
	<script type="text/javascript">
		function blog_related(blog_id){			
			$.post('/blog/related/'+blog_id+'/', function(resp) {
				$('#related_posts .related_posts').html(resp);			
			});
		}		
		$(document).ready(function(){ 		
			blog_related({/literal}{$post.id}{literal});
		});
	</script>
	{/literal}
	
	
	
</div>

{include file="commentForm.voting.tpl"}