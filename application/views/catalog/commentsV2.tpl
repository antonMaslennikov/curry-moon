<!--catalog/comments.tpl-->
<script type="text/javascript">
	function setSubscrComment(el, b){
		//if ($(el).attr('class').replace('switch-category ', '') == 'switch-on') { // выкл
		var d = $('#comment_subscr');
		if (d.length == 0) return false;
		if (!d[0].checked || (typeof b == 'boolean' && b)) { // вкл
			$(el).removeClass('switch-off').addClass('switch-on');
			$('.div_comment_subscr').text('Вы подписаны'+((MobilePageVersion == true) ? '' : ' на комментарии'));			
		} else { // выкл
			$(el).removeClass('switch-on').addClass('switch-off');
			$('.div_comment_subscr').text('Подписаться'+((MobilePageVersion == true) ? '' : ' на комментарии'));				
		}
		d[0].checked = $(el).hasClass('switch-on');
		return false;
	}
	
	$(document).ready(function(){
		$('#a_comment_subscr').click(function(){
			setSubscrComment(this);
			return false;
		});
		
		//initAjaxLoadComments();
		
		$('#commentText').keyup(function(){ setSubscrComment($('#a_comment_subscr'), true); });
	});
</script>
{if !$page || $page == 0}

	{literal}
	<!-- script type="text/javascript" src="/js/tiny_mce/tiny_mce.js"></script-->
	<script type="text/javascript">
	
	tinyMCE.init({
		mode : "exact",
		elements : "commentText",
		theme : "advanced",
		force_br_newlines : true,
		force_p_newlines : false,
		forced_root_block : '',
		theme_advanced_buttons1 : "", //"bold,italic,underline, | ,link,mjimage",
		theme_advanced_buttons2 : "",
		theme_advanced_toolbar_location : "none",
		//theme_advanced_statusbar_location : "bottom",
		theme_advanced_statusbar_location : "none",
		theme_advanced_resizing : true,
		theme_advanced_resize_horizontal : false,
		relative_urls : false,
		remove_script_host : false, 
		content_css : "/css/tinymce_css.css",
		setup : function(ed) {
			ed.onKeyDown.add(function(ed, e) {
				var textarea = tinyMCE.get('commentText').getContent(); 			
				var body = tinymce.get('commentText').getBody(), text = tinymce.trim(body.innerText || body.textContent);
				if(text.length>3000)
				{ //debugger;
					//tinyMCE.activeEditor.setContent(textarea.substring(0,3000));
					//textarea = tinyMCE.get('commentText').getContent(); 
					//$('#comment_count_chars').html('Символов: 3000/3000');
					if (typeof alertmax == 'undefined')
						alert('Вы привысели допустимый лимит на длину сообщения');
					
					alertmax = true;
					return false;
				}
				$('#comment_count_chars').html('Символов: '+text.length+'/3000');
			}),
			
			ed.onKeyUp.add(function(ed, e) {
				setSubscrComment($('#a_comment_subscr'), true);
				
				var textarea = tinyMCE.get('commentText').getContent(); 			
				var body = tinymce.get('commentText').getBody(), text = tinymce.trim(body.innerText || body.textContent);
				if(text.length>3000)
				{ //debugger;
					//tinyMCE.activeEditor.setContent(textarea.substring(0,3000));
					//textarea = tinyMCE.get('commentText').getContent(); 
					//$('#comment_count_chars').html('Символов: 3000/3000');
					if (typeof alertmax == 'undefined')
						alert('Вы привысели допустимый лимит на длину сообщения');
					alertmax = true;
					return false;
				}
				$('#comment_count_chars').html('Символов: '+text.length+'/3000');
			})	
		}
	});
	
	$(document).ready(function(){
				
		$("#insImg").click(function(){
			tb_show("Загрузить картинку", "#TB_inline?height="+((MobilePageVersion == true) ? 260 : 400)+"&width="+((MobilePageVersion == true) ? 265 : 600)+"&inlineId=comment_upload_block");
			return false;
		});
		
		var plusMinuse = ".carmaBlock .carmaPR, .carmaBlock .carmaMR, .carmaBlock .carmaPU, .carmaBlock .carmaMU";
		
		$(plusMinuse).css({visibility:'hidden'});
		
		$(".carmaBlock").hover(
			function () {
				$(this).children("span").css({visibility:'visible'})
			},
			function () {
				$(plusMinuse).css({visibility:'hidden'});			
			}
		);	
	});
	
	function watchF(id, type, obj) {
		if (authorized) {
			$.get('/ajax/?action=watchForThis', { 'to': id,	'type': type }, function() {});
		} else {
			alert('Авторизуйтесь, чтобы следить комментариями');
		}
	}
	
	function citate(id, type)
	{
		if ( window.tinyMCE != undefined)
		{
			tinyMCE.activeEditor.focus();
		}
		else
		{
		}
		$('#parent').val(id);
		$('.comments-content #hudsovet-commentform').show();
		$('.comments-content .btn-show-commform').hide();
		location.href = "#editor";
		return false;
	} 
	</script>
	{/literal}

	<script type="text/javascript" src="/js/commentForm.js"></script>

	<br clear="all" />
	<a name="comments" rel="nofollow"></a>

	<div class="clearfix clr" style="margin:20px 2px;display:none">
		{if $WFT}
			<!--noindex--><div class="left" style="font-size:11px;"><a href="#"><img src="/images/upload_form/watch{$WFT.watchForThisChecked}.gif" alt="" border="0" id="watchForThis" class="ico" onclick="return wft('{$WFT.to}', '{$WFT.type}', this);" rel="nofollow"></a> &mdash; подписаться на комментарии </div>
			<div class="clr"></div><!--/noindex-->
		{/if}
	</div>

	{if $USER->authorized}

		{if $USER->meta->comments_blocked}
		
			<!--noindex--><div class="error" style="margin-left:150px">Вы не можете оставлять комментарии к работам</div><!--/noindex-->
		
		{else}	

			
			{if $USER->user_status != 'banned' && $USER->user_status != 'deleted' && $USER->user_is_fake != 'true'}
		
				<a name="editor" rel="nofollow"></a>
			
				<a href="#show-comment-form" class="btn-show-commform" onclick="$('#hudsovet-commentform').show(); $(this).hide(); return false;" rel="nofollow">+Добавить комментарий</a>
				
				<div id="hudsovet-commentform" style="width:100%; margin: 0; display:none;">
				
					{if $USER->user_carma < 0}
					
						<div class="moduletable noaccess">Поскольку ваша карма отрицательна, Вы не можете оставлять комментарии чаще чем раз в час.</div>
					
					{else}
					
						<h4 class="howkon">Ваш комментарий: <span>Комментариев: {$comments|count}</span><span id="comment_count_chars" style="margin-right:16px;"></span></h4>
						
						<div style="width:100%; max-width1:650px; margin-bottom: 9px; float:left;">		
							<form name="commentForm" id="commentForm" class="commentForm"  method="post" action="/comment/add/{$type}/{$good.good_id}/">
								<textarea name="commentText" id="commentText" style="width:100%; height:150px" cols="1" rows="1" wrap="virtual"></textarea>
								<input type="hidden" name="comment_id" id="comment_id" value="" />
								<input type="hidden" name="parent" id="parent" value="" />
								<input type="hidden" name="current_userId" value="{$USER->user_id}" />
								<table border="0" cellpadding="0" cellspacing="0" width="100%" class="center-botton-comment-form">
									<tr>
										<td align="left" valign="middle" width="160" style="position:relative">
											<input type="button" value="Комментировать" name="submitComment" class="button" onclick="return reciveComment()"/>
										</td>
										{if $WFT}
											<td align="center" valign="middle" width="70">
												<input type="hidden" id="comment_subscr" name="" {if $WFT.watchForThisChecked == 1} checked="checked" {/if} />
												<a id="a_comment_subscr" rel="nofollow" href="#!comment_subscr" onclick="watchF('{$WFT.to}', '{$COMMENT_TYPE_FORM}', this);" class="switch-category switch-{if $WFT.watchForThisChecked == 1}on{else}off{/if}">Подписаться на комментарии</a>
											</td>
											<td align="left" valign="middle" style="text-align: left;">
												<div class="div_comment_subscr">{if $WFT.watchForThisChecked == 1}Вы подписаны <font class="mobilenone">на комментарии</font>{else}Подписаться <font class="mobilenone">на комментарии</font>{/if}</div>
											</td>
										{/if}
										<td align="right">
											<a href="#insert_comment_img"  id="insImg" rel="nofollow">Вставить изображение</a>
										</td>
									</tr>
								</table>
								{*<div style="margin-top:10px;">
									<input type="button" value="Комментировать" name="submitComment" class="button" onclick="return reciveComment()" style="float:left;" />
									{if $WFT}
									<input type="hidden" id="comment_subscr" name="" {if $WFT.watchForThisChecked == 1} checked="checked" {/if} />
									<a id="a_comment_subscr" rel="nofollow" href="#!comment_subscr" style="float:left;margin-left:5px;margin-top:7px;" onclick="watchF('{$WFT.to}', '{$COMMENT_TYPE_FORM}', this);" class="switch-category switch-{if $WFT.watchForThisChecked == 1}on{else}off{/if}">Подписаться на комментарии</a>
									<div style="float:left;margin-left:5px;margin-top:5px;" class="div_comment_subscr">{if $WFT.watchForThisChecked == 1}Вы подписаны на комментарии{else}Подписаться на комментарии{/if}</div>
									{/if}
									<a href="#insert_comment_img"  style="margin-top: 5px;" id="insImg" rel="nofollow">Вставить изображение</a>
								</div>
								<div style="clear:both;"></div>
								*}
							</form>
						</div>
						
						<div class="clr clearfix"></div>
						
									
						{include file="commentForm.uploader.tpl"}							
					
					
					{/if}
				
				</div>
		
			{/if}
		{/if}
			
	{else}
		<!--noindex-->
		<div class="comment-noaccess noaccess">
			<h2>{if $module=='catalog.v2'}<br replaces="Чтобы оставлять комментарии вы должны быть"/>{else}Чтобы оставлять комментарии вы должны быть{/if} <a rel="nofollow" title="Авторизация" class="enter qlogin" href="#">{if $module=='catalog.v2'}<br replaces="авторизованы"/>{else}авторизованы{/if}</a></h2>
		</div>
		<!--/noindex-->
	{/if}
	
{/if}


<div class="commentColumn">
	
	{foreach from=$comments item="c"}
	
		<div id="comment{$c.comment_id}" class="comment clr {$c.class} {$c.author_comment}">
			
			<table border="0" width="100%" class="tlf">
			<tr valign="top">
				<td width="60" valign="top"><!--noindex--><a href="/profile/{$c.user_id}/" title="{$c.user_login}" rel="nofollow">{$c.user_avatar}</a><!--/noindex--></td>
				<td style="overflow:hidden">
					<h6 style="margin-top:0px;"><a href="/profile/{$c.user_id}/" title="{$c.user_login}" rel="nofollow">{$c.user_login}</a> {$c.user_designer_level}&nbsp;
					<!--noindex--><font class="em">{$c.date}</font><!--/noindex-->
					</h6>
					<div class="commentContent">{$c.text}</div>
				</td>
				<td width="70"><!--noindex-->{$c.carma}<!--/noindex--></td>
			</tr>
			</table>
			
			<div style="font-size:10px;" class="actions">
				<a name="comment{$c.comment_id}" href="#comment{$c.comment_id}" style="color:#999; text-decoration:none"></a>
				<small class="citate"><!--noindex--><a name="comment{$c.comment_id}" href="#cf{$c.comment_id}" onclick="$('.miniComment').hide(); $('#cf{$c.comment_id}').show();" rel="nofollow">ответить</a><!--/noindex--></small>
				{if  $c.comment_visible == 1 && ($USER->id == $c.user_id || $USER->meta->mjteam == 'super-admin' || $USER->meta->mjteam == 'developer')}
				<h6 style="float:right;padding: 0 80px 0 0;margin:0;">
					<a href="#" title="редактировать" style="vertical-align: bottom;" class="modif" onclick="editComment('{$c.comment_id}', '{$type}');$('#hudsovet-commentform').show(); $(this).hide();return false;" rel="nofollow">ред.</a>&nbsp;&nbsp;
					<a href="/comment/delete/{$c.comment_id}/" onclick="return confirm('Вы действительно хотите удалить этот комментарий?');" class="error" title="удалить" rel="nofollow">удалить</a>
				</h6>
				{/if}
				<br clear="all" />
			</div>
			
		</div>
		
		{if $c.childrens|count > 0}
			
			{foreach from=$c.childrens item="ch"}
				
				<div id="comment{$ch.comment_id}" class="comment clr {$ch.class} level-1 {$ch.author_comment}">
					<table border="0" width="100%" class="tlf">
					<tr valign="top">
						<td width="60" valign="top"><!--noindex--><a href="/profile/{$ch.user_id}/" title="{$ch.user_login}" rel="nofollow">{$ch.user_avatar}</a><!--/noindex--></td>
						<td style="overflow:hidden">
							<h6 style="margin-top:0px;"><a href="/profile/{$ch.user_id}/" title="{$ch.user_login}" rel="nofollow">{$ch.user_login}</a> {$ch.user_designer_level}&nbsp;
							<!--noindex--><font class="em">{$ch.date}</font><!--/noindex-->
							</h6>
							<div class="commentContent">{$ch.text}</div>
						</td>
						<td width="70"><!--noindex-->{$ch.carma}<!--/noindex--></td>
					</tr>
					</table>
					
					<div style="font-size:10px;" class="actions">
						<a name="comment{$ch.comment_id}" href="#comment{$ch.comment_id}" style="color:#999; text-decoration:none"></a>
						<small class="citate"><!--noindex--><a name="comment{$c.comment_id}" href="#cf{$c.comment_id}" onclick="$('.miniComment').hide(); $('#cf{$c.comment_id}').show();" rel="nofollow">{if $module=='catalog.v2'}<br replaces="ответить"/>{else}ответить{/if}</a><!--/noindex--></small>
						{if  $ch.comment_visible == 1 && ($USER->id == $ch.user_id || $USER->meta->mjteam == 'super-admin' || $USER->meta->mjteam == 'developer')}
						<h6 style="float:right;padding: 0 80px 0 0;margin:0;">
							<a href="#" title="редактировать" style="vertical-align: bottom;" class="modif" onclick="editComment('{$ch.comment_id}', '{$type}');$('#hudsovet-commentform').show(); $(this).hide();return false;" rel="nofollow">ред.</a>&nbsp;&nbsp;
							<a href="/comment/delete/{$ch.comment_id}/" onclick="return confirm('Вы действительно хотите удалить этот комментарий?');" class="error" title="удалить" rel="nofollow">удалить</a>
						</h6>
						{/if}
						<br clear="all" />
					</div>
				</div>
				
			{/foreach}
			
		{/if}
		
		<div class="level-1 miniComment clearfix" id="cf{$c.comment_id}">
			<a name="cf{$c.comment_id}"></a>
			{$USER->avatar}
			<form name="commentForm" id="commentForm" class="commentForm"  method="post" action="/comment/add/{$type}/{$good.good_id}/">
				<input name="commentText" />
				<input type="hidden" name="comment_id" id="comment_id" value="" />
				<input type="hidden" name="parent" id="parent" value="{$c.comment_id}" />
				<input type="submit" value="Отправить" />
			</form>			
		</div>
	{/foreach}

</div>

{literal}
<style>
	div.level-1 {
	    margin-left: 60px;
	    width: auto;
	    padding-left: 11px;
	}
	
	div.comment {
		border:0px;
	}
	
	div.author {
		background:none;
	}
	
	.clearfix:before {
	    clear: both;
	    content: ".";
	    display: block;
	    font-size: 0;
	    height: 0;
	    line-height: 0;
	    visibility: hidden;
	}
	
	.clearfix:after {
	    clear: both;
	    content: ".";
	    display: block;
	    font-size: 0;
	    height: 0;
	    line-height: 0;
	    visibility: hidden;
	}
	
	div.miniComment {
		position: relative;
		display:none;
	}
	
	div.miniComment img.avatar {
		display: block;
		width:25px;
		height: 25px;
		margin-right:10px;
		float:left;
	}
	
	div.miniComment form {
	    position: absolute;
	    left: 45px;
	    width: 95%;
   }
   
   div.miniComment form input[name=commentText] {
   		width:89%;
   		height:19px;
   }
   
   div.miniComment form input[type=submit] {
   		height: 25px;
    	padding-left: 5px;
    	padding-right: 5px;
   }
       
   
   div.commentContent p {
   	margin:0;
   }
</style>
{/literal}
