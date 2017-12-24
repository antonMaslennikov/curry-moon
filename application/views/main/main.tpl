{$sPage->text}

<section id="gkBottom1" class="gkPage">
    <div class="gkCols6">
        <div class="box  bigtitle products gkmod-1">
            <h3 class="header">НОВИНКИ</h3>
            <div class="content">
                <div class="vmgroup bigtitle products">
                    <div class="gkCols4 gkNoMargin">

                        <div style="margin: 0 -10px">
                            
                            {foreach from=$products item="p"}
                            <div class="box banner gkmod-4">
                                <div class="spacer">
                                    <a href="/ru/shop/openproduct/{$p.id}" title="{$p.product_name}">
                                        <img src="{$p.picture_path}" alt="{$p.slug}" class="featuredProductImage" border="0" />
                                    </a>
                                    <div class="clear"></div>					
                                    <h3 class="catProductTitle">
                                        <a href="#">{$p.product_name}</a>
                                    </h3>        
                                    <div class="clear"></div>
                                    <div class="PricesalesPrice vm-display vm-price-value" ><span class="PricesalesPrice" >{if $p.product_price != $p.total_price}<s>{$p.product_price}</s>{/if} {$p.total_price} руб</span></div>				
                                </div>
    
                            </div>
                            {/foreach}

                        </div>

                        <br style='clear:both;'/>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>