{if $products|count > 0}
    <h1>{$parentCategory->title}</h1>

    <div class="row">

        {foreach from=$products item="p"}
        <div class="product floatleft width33 vertical-separator">
            <div class="spacer">
               
                {if $p.isNew}
                <div class="bit_badge_new" style="top:270px;left:260px;">
                    <img src="/public/images/new_blue.png" alt="badge_new">
                </div>
                {/if}
                
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
                        <div class="PricesalesPrice vm-display vm-price-value">
                            <span class="vm-price-desc">Цена: </span>
                            <span class="PricesalesPrice">{$p.total_price} руб</span>
                        </div>
                        {if $p.product_price != $p.total_price}
                        <div class="PricebasePrice vm-display vm-price-value">
                           <span class="vm-price-desc">Cтарая цена: </span>
                           <span class="PricebasePrice">{$p.product_price} руб</span>
                        </div>
                        {/if}
                    </div>
                </div>
            </div>
        </div>
        {/foreach}

        <div class="clear"></div>
    </div>

    {include file="pagination.tpl"}
{/if}