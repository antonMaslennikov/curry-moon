{literal}
	<style type="text/css">
	#content {width:100%}	
	</style>
{/literal}
	
{include file="profile/tabz.tpl"}

<div style="width:730px;float:left">
	
	{if !$FORM}
	
		<div class="noaccess error" style="width:99%;text-align: center;padding:120px 0">Извините, но Вам ещё рано писать сообщения в блог.</div>
	
	{else}
		<script type="text/javascript" src="/js/tageditor.js"></script>
		<script type="text/javascript" src="/js/commentForm.js"></script>
		
		<link type="text/css" href="/css/tageditor.css" rel="stylesheet">
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
				new tageditor({ tag_input: '.tageditor .in-tag-input', disableSlipSpace: true, disableSpace: true, /*disableEnter:true,*/ autoSave: {if $post.id > 0}true{else}false{/if}, saveUrl: '/{$module}/saveTags/{$post.id}/' });
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
			
			<form action="/{$module}/edit/{if $post.id > 0}{$post.id}/{/if}" method="post" id="post_form" enctype="multipart/form-data">
				<p>
					<h4>Тема:<b class="error" title="Обязательно к заполнению">*</b></h4>
					<select name="theme">
						<option value="0">Другое</option>
						{foreach from=$themes key="id" item="t"}
						<option value="{$id}" {if $id == $post.post_theme} selected="selected" {/if}>{$t.name}</option>
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
						</tr>
						<tr>
							<td>
								{if $post.post_pic > 0}
								<div class="relative">
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
				{if $USER->meta->mjteam}
				<div style="border: 1px dashed orange;">
					<h4>Тизер:</h4>
					<textarea name="post_tizer" style="width:98%;height:60px">{$post.post_tizer}</textarea>
				</div>
				{/if}
				<p>
					<h4>Сообщение:<b class="error" title="Обязательно к заполнению">*</b></h4>
					<script language="javascript" type="text/javascript" src="/js/tiny_mce/tiny_mce.js"></script>
					
					{* if $USER->user_id != 105091 && $USER->user_id != 6199 *}
					
					{literal}
					<script type="text/javascript">
						tinyMCE.init({
							mode : "exact",
							elements : "post_content",
							theme : "advanced",
							force_br_newlines : true,
							force_p_newlines : false,
							forced_root_block : '',
							plugins : "paste,pagebreak",
							theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifycenter,|,link,mjimage,|,code,paste,pasteword,|,pagebreak",
							pagebreak_separator : "<!-- cut -->",

							theme_advanced_buttons2 : "",
			    			theme_advanced_toolbar_location : "top",
			    			theme_advanced_statusbar_location : "bottom",
					        theme_advanced_resizing : true,
							theme_advanced_resize_horizontal : false,
					        theme_advanced_resizing_use_cookie : true,
							paste_remove_styles: true,
							content_css : "/css/tinymce_css.css",
							
							media_strict: false,
							extended_valid_elements : 'object[width|height|classid|codebase|embed|param],param[name|value],embed[param|src|type|width|height|flashvars|wmode]',
							
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

					{* /if *}
	
					<textarea name="post_content" id="post_content" cols="0" rows="0" style="width:98%;" mce_editable="true" >{$post.post_content}</textarea>
					
				</p>
				{if $USER->user_id == 27278 || $USER->user_id == 6199 || $USER->user_id == 105091 || $USER->user_id == 86455}
				<p style="border:1px dashed orange;display:inline-block;">
					Для группировки картинок в слайдер, дописываем класс <b>blog-slider</b><br/>
					<code>
						&lt;p class="blog-slider"&gt;<br/>
						&nbsp;&nbsp;&lt;img src="" /&gt;<br/>
						&nbsp;&nbsp;&lt;img src="" /&gt;<br/>
						&nbsp;&nbsp;&lt;img src="" /&gt;<br/>
						&lt;/p&gt;
					</code>
				</p>
					{if $USER->user_id == 105091}
						<br/>
						<p style="border:1px dashed orange;display:inline-block;">
							Вторая версия <b>sliderZZ</b><br/>
							<code>
								&lt;div class="sliderZZ"&gt;<br/>
									&nbsp;&nbsp;&lt;ul&gt;<br/>
										&nbsp;&nbsp;&nbsp;&nbsp;&lt;li&gt;<br/>
											&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;...<br/>
										&nbsp;&nbsp;&nbsp;&nbsp;&lt;/li&gt;<br/>
										&nbsp;&nbsp;&nbsp;&nbsp;&lt;li&gt;<br/>
											&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;...<br/>
										&nbsp;&nbsp;&nbsp;&nbsp;&lt;/li&gt;<br/>
									&nbsp;&nbsp;&lt;/ul&gt;<br/>
								&lt;/div&gt;
							</code>
						</p>
					{/if}
				{/if}
				<p>
					<h4 class="margin-bottom:-10px;">Добавить тэг (через запятую):<b class="error" title="Обязательно к заполнению">*</b></h4>
					{*
					<div class="tageditor">
						<div class="tags-wrap">
							<div class="one-tag"></div>
							{if $post.tags|count > 0 && $post.tags != ''}
							{foreach from=$post.tags item="tag" key="tag_id"}								
								<div class="one-tag"><input type="hidden" name="tags[]" value="{$tag}"><i>{$tag}</i><b id="{$tag_id}"></b></div>
							{/foreach}							
							{/if}
							<input type="text" name="tags[]" class="in-tag-input" name="tags" />
						</div>
					</div>
					<div class="tageditor-error"></div>
					*}
					<input type="text" name="tags" value="{$post.post_tags}" style="width: 400px" />
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
				
				{if $USER->meta->mjteam && $USER->meta->mjteam != 'fired'}
				<p style="margin-top:35px">
					<h4>показывать на главной:</h4>
					<p><input type="radio" name="post_visible" value="0" style="margin-left:15px;vertical-align:middle" {if $post.post_visible == 0}checked="checked"{/if} /> да</p>
					<div><input type="radio" name="post_visible" value="1" style="margin-left:15px;vertical-align:middle" {if $post.post_visible == 1}checked="checked"{/if} /> нет</div>
				</p>
				{/if}
				
				{if $USER->user_id == 27278 || $USER->user_id == 6199 || $USER->user_id == 86455}
				<p>
					<script>
						var FileAPI = {
							  debug: false
							, pingUrl: false 
							// @required
							, staticPath: '/js/file-upload/' // @default: "./"
							// @optional
							, flashUrl: '/js/file-upload/FileAPI.flash.swf' // @default: FileAPI.staticPath + "FileAPI.flash.swf"
							, flashImageUrl: '/js/file-upload/FileAPI.flash.image.swf' // @default: FileAPI.staticPath + "FileAPI.flash.image.swf"
							//,support: { html5: false }
						};
					</script>
					<script src="/js/file-upload/FileAPI.min.js"></script>
					<script src="/js/file-upload/FileAPI.id3.js"></script>
					<script src="/js/file-upload/FileAPI.exif.js"></script>
					<script src="/js/file-upload/FileAPI.framework.js"></script>
					<link rel="stylesheet" href="/js/gallery/gallery.css" type="text/css" media="all"/>
					<link type="text/css" href="/js/file-upload/FileAPI.css" rel="stylesheet">
					<h4 style="margin-top:37px;">Картинки для галлереи:</h4>
					<div class="block-files-gallery">
						<div id="gallery_uploader_block" class="upload_button" style="font-size:15px;padding:5px 10px;">
							<input type="file" id="gallery_uploader" />
							Загрузить
						</div>
						<div id="total-progress" style="display:none;"><img src="/images/ajax-loader.gif" />&nbsp;&nbsp;подождите...</div>
						<div class="files-gallery" style="margin-top: 6px;">
							{foreach from=$gallery item="g"}
							<div class="img-item" style="position:relative;display: inline-block;">
								<input type="hidden" name="galleryfile[]" value="{$g.id}" hash="{$g.hash}" />
								<img src="{$g.preview}" style="height:100px;border:1px solid silver;margin:3px;padding:5px;"/>
								<img src="/images/delete.png" class="delete" style="position:absolute;cursor:pointer;bottom: 9px;right: 6px;">
							</div>
							{/foreach}
						</div>
						<div class="file-upload-list"></div>
						<script>
							var uploadfiles = 0;
							$(document).ready(function(){
								
								function deleteImageGallery(){
									//if (confirm('Вы уверены, что хотите удалить запись?')) {
										var p = $(this).parents('.img-item');
										$.get('/blog/deletepic/', { id: p.find('input[type=hidden]').val(),hash: p.find('input[type=hidden]').attr('hash') }, function(e){
											p.hide('slide', function(){ $(this).remove(); });
										})
									//}
								}
								
								$('.files-gallery .delete').click(deleteImageGallery);
								
								$('#gallery_uploader').FileAPI({
									url: '/blog/upload/',
									data: { post_id: {$post.id}-0 },
									//hidden: false,
									fileUploadList: '.file-upload-list',
									multiple: true,
									fileExt: '.png,.jpe,.jpg,.jpeg,.ai,.eps,.zip,.rar',
									select: function(file){
										$('#gallery_uploader_block').hide();
										$('#total-progress').show();
										uploadfiles++;
									},
									progress: function(event, file, percent) {
									},
									complete: function(file, response){ 
										uploadfiles--;
										
										if (uploadfiles <= 0) {
											$('#total-progress').hide();
											$('#gallery_uploader_block').show();
										}
										
										if (response == 'canceled') return;
										
										var e = eval('('+response+')');
										if (e && e.length>0)
											for(var i=0;i<e.length;i++) {
												if (e[i].status == 'ok') {
													var ob = $('<div class="img-item" style="position:relative;display: inline-block;"><input type="hidden" name="galleryfile[]" value="'+e[i].id+'" hash="'+e[i].hash+'" /><img src="'+e[i].preview+'" style="height:100px;border:1px solid silver;margin:3px;padding:5px;"/><img src="/images/delete.png" class="delete" style="position:absolute;cursor:pointer;bottom: 9px;right: 6px;"></div>');
													ob.find('.delete').click(deleteImageGallery);
												}
												if (e[i].status == 'error') {  alert(e[i].message); }
												else $('.files-gallery').append(ob);
											}
										//}
									}
								});
							});
						</script>
					</div>
				</p>
				{/if}
				
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

<div>{include file="blog/add.sidebar.tpl"}</div>