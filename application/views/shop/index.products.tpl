{if $products|count > 0}
    <h1>{$parentCategory->title}</h1>

    <div class="row">

        {foreach from=$products item="p"}
        <div class="product floatleft width33 vertical-separator">
            <div class="spacer">
                <div style="display:none;" id="{$p.id}_bvmpb_com" class="product_badge"></div>
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

    <div class="pagination"> 
        <nav class="pagination"><ul><li class="pagination-start"><a title="В начало" href="/ru/shop/jewellery/silver?limitstart=0" class="pagenav">В начало</a></li><li class="pagination-prev"><a title="Назад" href="/ru/shop/jewellery/silver?limitstart=0" class="pagenav">Назад</a></li><li><a title="1" href="/ru/shop/jewellery/silver?limitstart=0" class="pagenav">1</a></li><li><span class="pagenav">2</span></li><li class="pagination-next"><span class="pagenav">Вперёд</span></li><li class="pagination-end"><span class="pagenav">В конец</span></li><li class="counter">Страница 2 из 2</li></ul></nav> 
    </div>
{/if}