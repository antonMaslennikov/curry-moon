{*include file="profile.quickinfo.tpl"*}
{include file="profile/tabz.tpl"}

<style type="text/css">
{literal}
#content{width:100%}
.tags em a { color: #00A851; text-decoration:none;font-size:11px; }

div.moduletable {padding-top:0px;}
.mt10 {margin-top:0px!important;}
{/literal}
</style>

<div class="moduletable clearfix">
	{*выне с в провиле tabz*}
	{*if $drafts > 0 && ($USER->user_id == $user_id || $USER->user_id == 27278 || $USER->user_id == 6199)}
        <a href="/{$module}/user/{$user.user_login}/drafts/" style="position:relative;top: -14px;left:544px;" rel="nofollow">Черновики ({$drafts})</a>
    {/if*}

	
	{if !$new_post_link_ok && $USER->user_id == $user_id}
		<div class="center">
			<h2 class="noaccess error border" style="padding:10px">&rarr; Извините, но писать ещё рано, почитайте пока что пишут другие, оставьте ваши комментарии к работам, попробуйте написать свой пост после этого.</h2>
			<br clear="all" />
		</div>	
	{/if}
	
	{if $posts}
		{include file="blog/list.post_row.tpl"}
	{else}
		<p class="center">Не написано ещё ни одной заметки</p>
	{/if}
</div>

{if $tpages > 1}
<div class="pages">
	<div class="listing">
		<div>Страницы: </div>{$PAGES}
	</div>
</div>
{/if}