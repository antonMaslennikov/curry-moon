{foreach from=$topBlogTags item="t"}
<a title="{$t.count}" class="tag" href="/tag/posts/{$t.slug}/">{$t.name}</a>
{/foreach}