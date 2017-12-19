{if $pages|count > 1}
<div class="pagination"> 
    <nav class="pagination">
        <ul>
            {if $page > 1}
                <li class="pagination-start"><a title="В начало" href="{$base}" class="pagenav">В начало</a></li>
                <li class="pagination-prev"><a title="Назад" href="{$base}{if $page > 2}?limitstart={$page - 2 * $onpage}{/if}" class="pagenav">Назад</a></li>
            {/if}

            {foreach from=$pages item="p"}
            <li>
               {if $page == $p}
                   <span class="pagenav">{$p}</span>
               {else}
                   <a title="{$page}" href="{$base}{if $p > 1}?limitstart={($p - 1) * $onpage}{/if}" class="pagenav">{$p}</a>
               {/if}
            </li>
            {/foreach}

            {if $page < $pages|count}
                <li class="pagination-next"><a href="{$base}?limitstart={$page * $onpage}" title="Вперёд" class="pagenav">Вперёд</a></li>
                <li class="pagination-end"><a href="{$base}?limitstart={($pages|count - 1) * $onpage}" title="Вперёд" class="pagenav">В конец</a></li>
            {/if}

            <li class="counter">Страница {$page} из {$pages|count}</li>
        </ul>
    </nav> 
</div>
{/if}