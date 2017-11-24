<p>
	{foreach from=$TAGS item="t"}
		<a class="tag" rel="nofollow" href="/tag/{$t.slug}/{if $Style->id > 0}{if $Style->category == "futbolki" || $Style->category == "mayki-alkogolichki" || $Style->category == "sweatshirts" || $Style->category == "tolstovki"}category,{/if}{$Style->category}{if $filters.size && !$fsize_not_selected};size,{$filters.size}{/if}{if $filters.color && !$fcolor_not_selected};color,{$filters.color}{/if}{if $Style->category != "futbolki" && $Style->category != "mayki-alkogolichki" && $Style->category != "sweatshirts" && $Style->category != "tolstovki"}/{$Style->style_slug}{/if}/{/if}">{$t.name}</a>
	{/foreach}
</p>