{foreach from=$years item="y"}
<li><a href="/blog/year/{$y.y}/" {if $y.y == $year}class="year-active"{/if}>{$y.y}</a></li>
{/foreach}