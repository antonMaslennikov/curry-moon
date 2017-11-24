<h4>Похожие посты</h4>
<ul>
	{foreach from=$relatedPosts item="rp"}
	<li><!--noindex--><a rel="nofollow" href="/blog/view/{$rp.post_slug}/">{$rp.post_title}</a> {if $rp.user_login}от <a rel="nofollow" href="/profile/{$rp.user_id}/">{$rp.user_login}</a>{/if}<!--/noindex--></li>
	{/foreach}
</ul>