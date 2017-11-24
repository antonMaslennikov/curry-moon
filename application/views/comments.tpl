<script type="text/javascript" src="/js/commentForm.js?v103"></script>
<script type="text/javascript" src="/js/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="/js/commentFormMCE.js"></script>

<link rel="stylesheet" href="/css/comments.css?v106" type="text/css" media="all"/>

<br clear="all" />

<a name="comments" rel="nofollow"></a>

{if $USER->authorized}

	{if $USER->meta->comments_blocked}
	
		<div class="error" style="margin-left:200px">Вы не можете оставлять комментарии к {if $COMMENT_TYPE_FORM == 'blog'}блогам{else}работам{/if}</div>
	
	{else}	

		{if $COMMENT_TYPE_FORM == 'blog' && $post.post_status == 'blocked'}

		{else}	

			{if $USER->user_status != 'banned' && $USER->user_status != 'deleted' && $USER->user_is_fake != 'true'}
			
			<a name="editor" rel="nofollow"></a>
			
			<div id="hudsovet-commentform" style="width:980px; margin: 20px 0;">
				<h4 class="howkon">Ваш комментарий: <span>Комментариев: {$comments|count}</span><span id="comment_count_chars" style="margin-right:16px;">{if !$MobilePageVersion}Символов: 0/3000{/if}</span></h4>
				
				{if !$MobilePageVersion}
				<span class="avatar-wrap clr">{$USER->avatar_medium}</span>
				{/if}
				<div class="w650fl" style="width:600px;float:left;">		
					<form name="commentForm" id="commentForm" method="post" action="/comment/add/{$COMMENT_TYPE_FORM}/{$good.good_id}/" data-avatar="{$USER->avatar_medium_path}">
						<textarea name="commentText" id="commentText" style="width:100%;height:150px" cols="1" rows="1" wrap="virtual"></textarea>
						<input type="hidden" name="comment_id" id="comment_id" value="" />
						<input type="hidden" name="parent" id="parent" value="" />
						<input type="hidden" name="current_userId" value="{$USER->user_id}" />
						<table border="0" cellpadding="0" cellspacing="0" width="100%" class="center-botton-comment-form">
							<tr>
								<td align="left" valign="middle" width="160">
									<input type="submit" value="Комментировать" name="submitComment" class="button" style="float:left;" />
									<input type="hidden" id="comment_subscr" name="" {if $WFT.watchForThisChecked == 1} checked="checked" {/if} />
								</td>
								<td align="center" valign="middle" width="70">
									<a id="a_comment_subscr" href="#!comment_subscr" style="" onclick="watchF('{$WFT.to}', '{$COMMENT_TYPE_FORM}', this);" class="switch-category switch-{if $WFT.watchForThisChecked == 1}on{else}off{/if}" rel="nofollow">Подписаться</a>
								</td>
								<td align="left" valign="middle">	
									<div class="div_comment_subscr">{if $WFT.watchForThisChecked == 1}Вы подписаны{else}Подписаться{/if}</div>		
								</td>
								<td align="right" valign="middle">
									<div class="show-form-sup-left right">
										<a href="#"  {*href="/feedback/?height=550&width=300"*} title="Задайте вопрос" class="showFeedback" rel="nofollow">Задайте вопрос</a> администрации
									</div>
								</td>
							</tr>
						</table>
					</form>
				</div>
				
				<!-- Заметки по работе с редактором -->
				<!--noindex-->
				<div class="editorTipa" style="width: 248px;">
					<p>
						Вы можете использовать теги <br>
						<span class="black">&lt;b&gt;&nbsp;&lt;/b&gt;</span>,<span class="black">&lt;em&gt;&nbsp;&lt;/em&gt;</span>,<span class="black">&lt;s&gt;&nbsp;&lt;/s&gt;</span>
					</p>
					<p>Чтобы вставить ссылку, используйте <br>
						<span class="black">http://www....
					</span></p>
					<a href="#insert_img" id="insImg" rel="nofollow">Вставить изображение</a>
					<a href="/feedback/?height=550&width=300" id="reportImg" class="showFeedback thickbox" rel="nofollow">Пожаловаться</a>
				</div>
				<!--/noindex-->
				<div class="clr clearfix"></div>
				
				{include file="commentForm.uploader.tpl"}
				
			</div>
			{/if}
		{/if}
	{/if}	
{else}
	<div style="position:relative;width:980px" class="wrap-noaccess">
		<div class="moduletable noaccess">
			<!--noindex--><h2>{$L.COMMENT_FORM_you_need_authorized} <a rel="nofollow" title="Авторизация" class="enter qlogin" href="#">{$L.COMMENT_FORM_you_need_authorized_end}</a></h2><!--/noindex-->
		</div>
		<div style="width:242px;float:right"></div>
	</div>	
{/if}

<div class="commentColumn">

	{if $CMNT_PAGES}
		<div class="pages" style="margin-bottom: 20px">
			<div class="listing">
			<div>Страницы: </div> {$CMNT_PAGES}
			</div>
		</div>
	{/if}

	{if $comments|count > 0}
		
		{foreach from=$comments item="c"}
		
			<div id="comment{$c.comment_id}" class="comment clr clearfix {$c.class} {$c.author_comment}">
				
				<!--noindex--><a href="/profile/{$c.user_id}/" title="{$c.user_login}" rel="nofollow" class="avatar-wrap">{$c.user_avatar}</a><!--/noindex-->
				
				<div class="content-wrap">
					<h6><a href="/profile/{$c.user_id}/" title="{$c.user_login}" rel="nofollow">{$c.user_login}</a> {$c.user_designer_level}&nbsp;<!--noindex--><font class="em">{$c.date}</font><!--/noindex--></h6>
					<div class="commentContent">{$c.text}</div>
				</div>
				
				<!--noindex-->{$c.carma}<!--/noindex-->
				
				<div class="actions clearfix">
					<a name="comment{$c.comment_id}" href="#comment{$c.comment_id}"></a>
					<small class="citate"><!--noindex--><a name="comment{$c.comment_id}" href="#cf{$c.comment_id}" data-cid="{$c.comment_id}" class="replay" rel="nofollow">ответить</a><!--/noindex--></small>
					{if  $c.comment_visible == 1 && ($USER->id == $c.user_id || $USER->meta->mjteam == 'super-admin' || $USER->meta->mjteam == 'developer')}
					<h6>
						<a href="#" title="редактировать" style="vertical-align: bottom;" class="modif" onclick="editComment('{$c.comment_id}', '{$type}');$('#hudsovet-commentform').show(); $(this).hide();return false;" rel="nofollow">ред.</a>&nbsp;&nbsp;
						<a href="/comment/delete/{$c.comment_id}/" onclick="return confirm('Вы действительно хотите удалить этот комментарий?');" class="error" title="удалить" rel="nofollow">удалить</a>
					</h6>
					{/if}
				</div>
				
			</div>
			
			{if $c.childrens|count > 0}
				
				{foreach from=$c.childrens item="ch"}
					
					<div id="comment{$ch.comment_id}" class="comment clr clearfix {$ch.class} level-1 {$ch.author_comment}">
						
						<!--noindex--><a href="/profile/{$ch.user_id}/" title="{$ch.user_login}" rel="nofollow" class="avatar-wrap">{$ch.user_avatar}</a><!--/noindex-->
						<div class="content-wrap">
							<h6><a href="/profile/{$ch.user_id}/" title="{$ch.user_login}" rel="nofollow">{$ch.user_login}</a> {$ch.user_designer_level}&nbsp;<!--noindex--><font class="em">{$ch.date}</font><!--/noindex--></h6>
							<div class="commentContent">{$ch.text}</div>
						</div>
						<!--noindex-->{$ch.carma}<!--/noindex-->
						
						<div class="actions clearfix">
							<a name="comment{$ch.comment_id}" href="#comment{$ch.comment_id}"></a>
							<small class="citate"><!--noindex--><a name="comment{$c.comment_id}" href="#cf{$c.comment_id}"data-cid="{$c.comment_id}" class="replay" rel="nofollow">ответить</a><!--/noindex--></small>
							{if  $ch.comment_visible == 1 && ($USER->id == $ch.user_id || $USER->meta->mjteam == 'super-admin' || $USER->meta->mjteam == 'developer')}
							<h6>
								<a href="#" title="редактировать" style="vertical-align: bottom;" class="modif" onclick="editComment('{$ch.comment_id}', '{$type}');$('#hudsovet-commentform').show(); $(this).hide();return false;" rel="nofollow">ред.</a>&nbsp;&nbsp;
								<a href="/comment/delete/{$ch.comment_id}/" onclick="return confirm('Вы действительно хотите удалить этот комментарий?');" class="error" title="удалить" rel="nofollow">удалить</a>
							</h6>
							{/if}
						</div>
						
					</div>
					
				{/foreach}
				
			{/if}
			
			<div class="level-1 miniComment clr clearfix" id="cf{$c.comment_id}">
				<a name="cf{$c.comment_id}"></a>
				{$USER->avatar}
				<form name="commentForm" class="commentForm" data-avatar="{$USER->avatar_medium_path}"  method="post" action="/comment/add/{$COMMENT_TYPE_FORM}/{$good.good_id}/">
					<input name="commentText" />
					<input type="hidden" name="comment_id" id="comment_id" value="" />
					<input type="hidden" name="parent" id="parent" value="{$c.comment_id}" />
					<input type="submit" value="Отправить" />
				</form>			
			</div>
		{/foreach}
		
	{/if}
	
	{if $CMNT_PAGES}
		<div class="ag_paging">
		{$CMNT_PAGES}
		</div>
	{/if}

</div>

{if $module!='blog' && $PAGE->reqUrl.1 == 'view' && !$MobilePageVersion}
	<div class="right">
		{literal}
		<style>
			.soc_div{margin-bottom:15px!important;width:268px!important;}
			.soc_tabs_content{padding:16px!important;}
		</style>
		{/literal}
		
		{include file="catalog/tabs.soc.vk_fb.tpl"}
	
		<div id="loaderN" style="margin-bottom:-16px;float:right;position: relative;right:31px;top:106px;"><img width="208" height="13" title="загрузка" src="/images/reborn/thickbox/loading.gif"></div>
		<div id="noteesblock" class="{$module}" style="float:right;clear:both"></div>
	</div>	
{/if}