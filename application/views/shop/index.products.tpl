{if $products|count > 0}
    <h1>{$parentCategory->title}</h1>

    <div class="row">

        {foreach from=$products item="p"}
        <div class="product floatleft width33 vertical-separator">
            <div class="spacer">
               
                {if $p.product_discount > 0}
                <div id="{$p.id}_bvmpb_com" class="product_badge">
                    <div class="bit_badge_discount" style="top:-12px;left:260px;">
                        <img src="/public/images/sale_red.png" alt="badge_discount">
                    </div>
                </div>
                {/if}
                
                <div>
                    <a title="{$p.product_name}" href="{$base}{$p.slug}-{$p.product_sku|lower}">
                        <img src="{$p.picture_path}" alt="{$p.slug}" class="browseProductImage">					 
                    </a>
                </div>

                <div class="ProdTitleBlock">
                    <h3 class="catProductTitle"><a href="{$base}{$p.slug}-{$p.product_sku|lower}">{$p.product_name}</a></h3>
                    <div class="catProductPrice" id="productPrice{$p.id}">
                        <div class="PricesalesPrice vm-display vm-price-value"><span class="vm-price-desc">Цена: </span><span class="PricesalesPrice">{if $p.product_price != $p.total_price}<s>{$p.product_price}</s>{/if} {$p.total_price} руб</span></div>
                    </div>
                </div>
            </div>
        </div>
        {/foreach}

        <div class="clear"></div>
    </div>

    {if $pages|count > 1}
    <div class="pagination"> 
        <nav class="pagination">
            <ul>
                {if $page > 1}
                    <li class="pagination-start"><a title="В начало" href="{$base}" class="pagenav">В начало</a></li>
                    <li class="pagination-prev"><a title="Назад" href="{$base}{if $page > 2}?limitstart={$page - 1}{/if}" class="pagenav">Назад</a></li>
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
                    <li class="pagination-next"><a href="{$base}?limitstart={$page + 1}" title="Вперёд" class="pagenav">Вперёд</a></li>
                    <li class="pagination-end"><a href="{$base}?limitstart={$pages|count}" title="Вперёд" class="pagenav">В конец</a></li>
                {/if}
                
                <li class="counter">Страница {$page} из {$pages|count}</li>
            </ul>
        </nav> 
    </div>
    {/if}
{/if}