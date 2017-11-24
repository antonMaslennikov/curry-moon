{foreach from=$posts item="p"}
	{if $POST_CONTROL}
	<!--noindex--><a href='#' onclick="$('#post{$p.id}').show();$(this).hide();return false;" class="error" style="display:block;border-bottom:1px solid #ccc;padding:10px 0;" rel="nofollow">Показать</a><!--noindex-->
	{/if}

	<div class="mt10 {$p.class}" id="post{$p.id}" style="display:{$p.visible}; border:0px;">
	
	<table width="100%" class="td5" style="border-bottom:1px solid #ccc;">
	
	<tr>
        <td>
            {if $p.path}
            <a class="artic-imglink" href="/{$module}/view/{$p.post_slug}/" rel="nofollow">
                <span class="img-wrap-white"><img src="{$p.path}" width="100" /></span>
                {* <span class="comm-num">{$p.comments}</span> *}
            </a>
            {/if}
        </td>
		<td valign="top" style="width:100%">
			{if $p.post_status != 'draft'}
				<div>{*<span style="padding-left:10px;"><!--{$p.post_theme}--></span>*}<span style="padding-left:10px;font-style:italic">{$p.post_date}</span> <span style="padding-left:10px;"><a href="/blog/user/{$p.user_login}/">{$p.user_login}</a> &nbsp;&nbsp; </span> Комментариев: <strong>{$p.comments}</strong> </div>
			{/if}
			<div style="margin:25px 0 10px;font-size:26px;" class="sizeMob16">
				<a href="/blog/view/{$p.post_slug}/" class="{$p.post_class}" style=" font-size:26px; font-weight:bolder font-style:italic; color:#333333;{$p.hide_post};">{$p.post_title}</a>
				{if $p.post_status == 'draft'}
					<em style="font-style:italic;color:#918F8F">(черновик)</em>
				{elseif $p.post_status == 'blocked' && ($USER->user_id == 27278 || $USER->user_id == 6199 || $USER->user_id == $p.post_author)}
					<em style="font-style:italic;color:red">(тема закрыта)</em>
				{/if}
			</div>
			
			<div class="tags" style="padding-bottom:10px; font-size:11px; padding-top:10px;color:#CCCCCC;"><em><!--noindex-->{$p.post_tags}<!--/noindex--></em></div>
		</td>
	
		<td width="100" valign="top">
			{*if $p.post_status != 'draft'}
				{$p.postCarma}
			{/if*}
		</td>
	</tr>
	
	{if $USER->user_id == $p.post_author || $USER->user_id == 6199} 
	<tr>
		<td colspan="5" align="right" style="font-size:10px">
			{if $p.post_status == 'draft'}
				<a class="modif" href='/blog/publish/{$p.id}/' rel="nofollow" style="color:#000"><strong>Опубликовать</strong></a> |
			{/if}
			<a href='/blog/edit/{$p.id}/' class="modif">ред.</a> |
			<a href='/blog/delete/{$p.id}/' class="error" onclick='return confirm("Вы действительно хотите удалить этот пост?")'>удалить</a>
		</td>
	</tr>
	{/if}
	</table>
	</div>

	{if $POST_CONTROL}
	<!--/noindex-->
	{/if}
	
{/foreach}