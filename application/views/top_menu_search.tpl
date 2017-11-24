{if $PAGE->module != 'basket' && $PAGE->module != 'order.v3' && $PAGE->module != 'orderhistory'}
<div class="search-top {if !$MobilePageVersion}Big{/if}">
	<form action="{if $PAGE->module == 'blog'}/blog{/if}/search/{if isset($filters) && $filters.category && $filters.category != 'enduro' && $filters.category != 'jetski' && $filters.category != 'atv' && $filters.category != 'snowmobile'}{if $filters.category == 'phones' || $filters.category == 'laptops' || $filters.category == 'touchpads' || $filters.category == 'poster' || $filters.category == 'ipodmp3' || $filters.category == 'cases' || $filters.category == 'auto' || $filters.category == 'moto' || $filters.category == 'patterns' || $filters.category == 'patterns-sweatshirts' || $filters.category == 'patterns-bag'}{$filters.category}{else}category,{$filters.category}{if $filters.color};color,{$filters.color}{/if}{if $filters.size};size,{$filters.size}{/if}{/if}{if $style_slug}/{$style_slug}{/if}/{/if}{if isset($filters.sex) && $filters.sex == 'female'}female/{/if}{if isset($filters.sex) && $filters.sex == 'kids'}kids/{/if}" class="searchform">
		{if $PAGE->module == 'blog'}
			<input type="text" name="q" class="search-input" placeholder="{$L.HEADER_find_everything_blog}" _placeholder="{$L.HEADER_find_everything_blog}">
		{else}
			<input type="text" name="q" class="search-input" placeholder="{if ($PAGE->module == 'catalog' || $PAGE->module == 'search') && $filters.category}{$L.HEADER_find_everything_catalog}{if $filters.sex == 'male'} муж.{elseif $filters.sex == 'female'} жен.{elseif $filters.sex == 'kids'} дет.{/if} {$razdel} {else}{$L.HEADER_find_everything}{/if}" _placeholder="{if $PAGE->module == 'catalog' && $filters.category}{$L.HEADER_find_everything_catalog} {if $filters.sex == 'male'}муж.{elseif $filters.sex == 'female'}жен.{elseif $filters.sex == 'kids'}дет.{/if} {$razdel}{else}{$L.HEADER_find_everything}{/if}" {if $PAGE->module == 'catalog' && $filters.category}style="_width: 157px"{/if}>
		{/if}
		<input  type="submit" value="" class="search-submit">
	</form>
</div>
{/if}