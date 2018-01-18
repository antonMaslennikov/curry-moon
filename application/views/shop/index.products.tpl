<div class="clearfix">

    <h1>{$parentCategory->title}</h1>

    {if $products|count > 0 || $filters}
   
    {include file="shop/index.sidebar.tpl"}

    <div class="row">

        {foreach from=$products item="p"}
        <div class="product floatleft width33 vertical-separator">
            <div class="spacer">

                <a title="{$p.product_name}" href="{$base}{$p.slug}-{$p.product_sku|lower}" style="position:relative;display:block">
                    <img src="{$p.picture_path}" alt="{$p.slug}" class="browseProductImage">

                    {if $p.isNew}
                    <div class="bit_badge_new" style="top:270px;right: 0px;">
                        <img src="/public/images/new_blue.png" alt="badge_new">
                    </div>
                    {/if}

                    {if $p.product_discount > 0}
                    <div class="bit_badge_discount" style="top:-12px;right: 0px;">
                        <img src="/public/images/sale_red.png" alt="badge_discount">
                    </div>
                    {/if}				 
                </a>

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
        {foreachelse}
        
        <p>
            Товаров по выбранным параметрам не найдено
        </p>
        
        {/foreach}

        <div class="clear"></div>
    </div>

    {include file="pagination.tpl"}
    {/if}
</div>
