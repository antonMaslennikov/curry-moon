{literal}
	<style type="text/css">
	#content {width:100%}	
	</style>
{/literal}
	
{include file="profile.tabz.tpl"}

<div style="width:730px;float:left">
	
	{if !$FORM}
	
		<div class="noaccess error" style="width:99%;text-align: center;padding:120px 0">Извините, но Вам ещё рано писать сообщения в блог.</div>
	
	{else}
		<script type="text/javascript" src="{get_file_modif_date url='/js/tageditor.js'}"></script>
		<script type="text/javascript" src="{get_file_modif_date url='/js/commentForm.js'}"></script>
		
		<link type="text/css" href="{get_file_modif_date url='/css/tageditor.css'}" rel="stylesheet">
		{literal}
			<link type="text/css" href="/css/jquery.autocomplete.css" rel="stylesheet">
			
			
			<script type="text/javascript" src="/js/jquery.autocomplete.pack.js"></script>
			
			<script type="text/javascript">
			$(document).ready(function(){
				$(".faq h4.open").click(function(){
					$(this).next("div.opendiv").slideToggle("fast")
					.siblings("div:visible").slideUp("fast");
					$(this).toggleClass("active");
					$(this).siblings("h4").removeClass("active");
				});
				
				//инициализация класса тегов
				{/literal}
				new tageditor({ tag_input: '.tageditor .in-tag-input', autoSave: {if $post.id > 0}true{else}false{/if}, disableEnter:true, saveUrl: '/{$module}/saveTags/{$post.id}/' });
				{literal}
				$(".tageditor .in-tag-input").autocomplete('/ajax/gettags/blog/', {'cacheLength' : 1, onItemSelect: function(){ debugger; }});
				
				$("#loading")
					.ajaxStart(function(){ $(this).show(); })
					.ajaxComplete(function(){ $(this).hide(); });
			});
				
			</script>
		{/literal}
		
		<div class="moduletable-tabz">
			
			{if $error}
			<div class="noaccess error border">
				<ol>
				{foreach from=$error item="e"}
					<li>{$e}</li>
				{/foreach}
				</ol>
			</div>
			{/if}
			
			<form action="/{$module}/edit/" method="post" id="post_form" enctype="multipart/form-data">
				<p>
					<h4>Тема:<b class="error" title="Обязательно к заполнению">*</b></h4>
					<select name="theme">
						<option value="-1">Другое</option>
						{foreach from=$themes item="t"}
						<option value="{$t.tag_id}" {if $t.tag_id == $post.theme} selected="selected" {/if}>{$t.name}</option>
						{/foreach}
					</select>
				</p>
				<p>
					<h4>Заголовок Вашего сообщения:<b class="error" title="Обязательно к заполнению">*</b></h4>
					<input type="text" name="post_title" id="post_title" value="{$post.post_title}" style="width:98%; font-size:18px;" />
				</p>
				<p style="margin-top:20px">
					<table width="99%">
						<tr>
							<td valign="top">
								<h4>Титульная картинка:</h4>
								<input type="file" name="pic" />
								<br />
								<span class="smallcomment">min 300X200, GIF, JPEG, PNG</span>
							</td>
							<td width="50%">
								{if $post.post_pic > 0}
								<div class="relative border pa10">
									<img src="{$post.post_picture}" />
									{if $post.id > 0}
									<a title="Удалить титульную картинку" style="position: absolute; top: 5px; right: 5px;" href="/{$module}/delete_post_pic/{$post.id}/"><img border="0" alt="Удалить из избранных" src="/images2/icons/delete.png"></a>
									{/if}
								</div>
								{/if}
							</td>
						</tr>
					</table>
				</p>
				<p>
					<h4>Сообщение:<b class="error" title="Обязательно к заполнению">*</b></h4>
					<script language="javascript" type="text/javascript" src="/js/tiny_mce/tiny_mce.js"></script>
					
					{literal}
					<script type="text/javascript">
						tinyMCE.init({
							mode : "exact",
							elements : "post_content",
							theme : "advanced",
							force_br_newlines : true,
							force_p_newlines : false,
							forced_root_block : '',
							plugins : "paste",
							theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifycenter,|,link,mjimage,|,code,paste,pasteword,",
							theme_advanced_buttons2 : "",
			    			theme_advanced_toolbar_location : "top",
			    			theme_advanced_statusbar_location : "bottom",
					        theme_advanced_resizing : true,
							theme_advanced_resize_horizontal : false,
					        theme_advanced_resizing_use_cookie : true,
							paste_remove_styles: true,
							content_css : "/css/tinymce_css.css",
							
							relative_urls : false,
							remove_script_host : false, 
							
							content_css : "/css/tinymce_css.css",
		
							setup : function(ed) {
								ed.addButton('mjimage', {
		                            title : 'Показать форму загрузки',
							        image : '/js/tiny_mce/themes/advanced/img/image.gif',
							        onclick : function() {
		                            tb_show("Загрузить картинку", "#TB_inline?height=230&width=600&inlineId=comment_upload_block");
									}
								})
							}
						});
					</script>
					{/literal}

					<textarea name="post_content" id="post_content" cols="0" rows="0" style="width:98%;" mce_editable="true" >{$post.post_content}</textarea>
					
				</p>
		
				<p>
					<h4 class="margin-bottom:-10px;">Добавить тэг:<b class="error" title="Обязательно к заполнению">*</b></h4>
					<div class="tageditor">
						<div class="tags-wrap">
							<div class="one-tag"></div>
							{if $post.tags|count > 0 && $post.tags != ''}
							{foreach from=$post.tags item="tag" key="tag_id"}								
								<div class="one-tag"><input type="hidden" name="tags[]" value="{$tag}"><i>{$tag}</i><b id="{$tag_id}"></b></div>
							{/foreach}							
							{/if}
							<input type="text" name="tags[]" class="in-tag-input" />
						</div>
					</div>
					<div class="tageditor-error"></div>
					<br clear="all" />
				</p>
				
				
				<p style="margin-top:20px">
					<h4>Опрос:</h4>
					
					<a href="#" id="poll_link" class="dashed" onclick="return poll_form('{$post.poll_id}');">
						{if $post.poll_id == 0}
							добавить
						{else}
							редактировать
						{/if}
					</a>
					
					<input type="hidden" name="poll_id" id="poll_id" value="{$post.poll_id}" />
				</p>
				
				<p style="padding-top:20px;">
					<input type="hidden" name="post_id" value="{$post.id}" />
					<input type="submit" name="post_submit_draft" id="post_submit_draft" value="Сохранить как черновик" class="button" />&nbsp;&nbsp;
					<input type="submit" name="post_submit" id="post_submit" value="Опубликовать" class="button" />&nbsp;&nbsp;
				</p>
			</form>
		</div>
		
	{/if}

</div>

{include file="commentForm.uploader.tpl"}

<div>{include file="/blog/add.sidebar.tpl"}</div>