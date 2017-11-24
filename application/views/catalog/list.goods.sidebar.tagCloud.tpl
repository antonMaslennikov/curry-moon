{foreach from=$tags item="t"}
	<a class="tag" rel="nofollow" href="/tag/{$t.slug}/">{$t.name}</a>
{/foreach}