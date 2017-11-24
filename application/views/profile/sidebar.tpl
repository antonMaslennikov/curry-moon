{literal}
<script type="text/javascript">
	function ch_photo(pic_id, obj) {
		$('.pics').hide();
		$('#pic_' + pic_id).show();
		$('.pic_buttons').removeClass('activeFilter');
		$(obj).addClass('activeFilter');
		return false;
	}
</script>
{/literal}

<div class="sidebar240" style="float:right;width:230px">
	{*
	<div class="moduletable">
		{if $USER->meta->mjteam != 'fired'}
			<b>Комментарий администратора:</b> {$User.admin_comment}
		{/if}		
		{foreach from=$PHOTOS item="pic" name="fphotos"}
			<div id="pic_{$pic.user_picture_id}" style="{if !$smarty.foreach.fphotos.first}display:none{/if}" class="relative pics">
				<img src="{$pic.picture_path}" alt="Загружено автором" />
				{if $USER->user_id == $User.user_id}
				<a href="/editprofile/deleteUserPhoto/{$pic.user_picture_id}/" style="position:absolute; top:10px; left:198px;" title="Удалить фотографию"><img src="/images2/icons/delete.png" alt="Удалить из избранных" border="0"></a>
				{/if}
			</div>
		{/foreach}		
		<div align="center">
			<div id="paginator" class="center">
				{foreach from=$PHOTOS item="pic" name="fphotospage"}
					<a href="#" class="pic_buttons {if $smarty.foreach.fphotospage.first}activeFilter{/if}" style="display:block; width:13px; height:17px;float:left;margin-bottom:3px;text-align:center" onclick="return ch_photo('{$pic.user_picture_id}', this);">{$smarty.foreach.fphotospage.iteration}</a>
				{/foreach}
			</div>
		</div>
		<!--noindex-->
		{if $USER->user_id == $User.user_id}
		<br />
		<div class="center clr"><a href='/#TB_inline?height=200&width=300&inlineId=addUserPhoto' class='thickbox green dashed'>Добавить изображение в профиль</a></div>
		<div style="display:none;" id="addUserPhoto">
			<div style="margin-top:60px;"></div>
			<form action="/editprofile/saveUserPhoto/" method="post" enctype="multipart/form-data">
				<input type="file" size="30" name="userpicture">
				<input type="hidden" name="next" value="profile" />
				<h6 class="smallcomment">PNG, GIF, JPEG, 300 Кб.</h6>
				<h5><input type="submit" value="Добавить" alt="Добавить" /></h5>
			</form>
		</div>
		{/if}
		<!--/noindex-->	
	</div>	
	<br clear="all" />
	*}
	
	{if $aboutMe}
	<div class="moduletable">
		{$aboutMe}
	</div>
	{/if}
	
	{if $interview}
	<div style="margin-top:5px; margin-bottom:5px; background:#ededed;padding:10px;"><strong>Интервью:</strong> <a href="{$interview}">Читать в блоге</a></div>
	{/if}

	{if $USER->user_id == $User.user_id}
	<div style="border:1px dotted gray; padding-left:15px; padding-right:15px;">
		<h4 class="left"><a href="/promo/">10%&nbsp;c&nbsp;продаж</a></h4> <sup class="help right" style="padding-top:10px;"><a style="font-size: 9px;" href="http://www.maryjane.ru/faq/group,15/?TB_iframe=true&height=500&width=600" rel="nofollow" class="thickbox">?</a></sup>
		<br clear="all">
		<input type="text" name="promocode" value="http://www.maryjane.ru{$promoUrl}" style="width:95%; margin-top:10px;" onclick="javascript: $(this).focus().select();" />
		<span style="font-size:11px; font-style:italic">Вставьте Вашу персональную ссылку на страницы Вашего блога или сайта, </span>
		<div style="border-bottom: 1px solid rgb(222, 222, 222);border-top: 1px solid rgb(222, 222, 222);padding: 5px 0;margin-top:10px"><a href="/stat/promo/">Статистика</a></div>
		<div style="padding:5px 0;"><a href="/payback/">Личный счет</a></div>
	</div>
	{/if}
	
	{literal}
	<style>
	ul#comment-switcher.b-comment-switcher {margin: 5px 0 12px;}
	ul#comment-switcher.b-comment-switcher li.b-litem {
		float: left;
		width: auto;
		border: none;
		list-style: none;
		padding: 0;
		margin: 0 0 0 12px;
		background: none;
	}
	ul#comment-switcher.b-comment-switcher li.b-litem a {
		text-decoration: none;
		color: #504f4f;
		font-size: 10px;
	}
	ul#comment-switcher.b-comment-switcher li.active a {color: #00a851;}
	 
	.b-comment-sidebar {width: 229px;}
	.b-comment-sidebar .o-sideb-comm {width: 217px;}
	</style>
	
	<script>
		$(document).ready(function(){
			return GWH(this, 'com_goods', 8, 'b-comment', 'blog');
		})
	</script>
	
	{/literal}
	{*<!--коментарии-->
	<ul id="comment-switcher" class="b-comment-switcher">
		<li class="b-litem"><a onclick="return GWH(this, 'com_blog', 8, 'b-comment', 'blog');" href="#">в блогах</a></li>
		<li class="b-litem active"><a onclick="return GWH(this, 'com_goods', 8, 'b-comment', 'blog');" href="#">на голосовании</a></li>
		<li class="b-litem"><a onclick="return GWH(this, 'com_pictures', 8, 'b-comment', 'blog');" href="#">к фото</a></li>
	</ul>*}
	<br/><br/>
	<div class="b-comment-sidebar" id="b-comment">		
		<div style="text-align: center;margin:40px 0"><img src="/images/loading2.gif" /></div>
	</div>
</div>