<section id="gkMainbody" style="font-size: 100%;">
    <div itemscope="" itemtype="http://schema.org/Product">
        <div class="productdetails-view productdetails">
            <meta itemprop="sku" content="{$product->product_sku}">

            <div class="productDetails">
                <div class="productDetailsLeft">
                    <div class="main-image">
                        <a title="{$product->slug}" data-rokbox="" data-rokbox-album="ProdImage" href="{$product->pictures.0.orig_path}">
                            <img itemprop="image" src="{$product->pictures.0.thumb_path}" alt="{$product->slug}">
                        </a>
                    </div>
                    <span itemprop="brand" itemscope="" itemtype="http://schema.org/Brand"><meta itemprop="name" content="CurryMoon"></span>
                    <meta itemprop="description" content="{$product->description_short}">
                    <div class="additional-images">
                        {foreach from=$product->pictures key="k" item="p"}
                        <a href="{$p.orig_path}" class="product-image image-{$k}" title="{$product->slug}" data-rokbox="" data-rokbox-album="ProdImage">       
                            <img src="{$p.thumb_path}" alt="{$product->slug}" class="product-image" style="cursor: pointer">
                        </a>
                        {/foreach}
                    </div>	   	
                </div>
                <div class="productDetailsRight">
                    <h1><span itemprop="name">{$product->product_name}</span></h1>

                    <div class="product-short-description">
                        {$product->description_short}
                    </div>

                    <div class="vm-product-details-container">
                        <div class="spacer-buy-area">
                            <br>				
                            <div class="product-price" id="productPrice389">
                                <div class="PricesalesPrice vm-display vm-price-value">
                                    <span class="vm-price-desc">Цена </span>
                                    <span class="PricesalesPrice">
                                        <span itemprop="offers" itemscope="" itemtype="http://schema.org/Offer">
                                            <span itemprop="price">{if $product->total_price != $product->product_price}<s>{$product->product_price}</s>{/if} {$product->total_price} руб</span>
                                            <meta itemprop="priceCurrency" content="RUB">
                                            <meta itemprop="availability" content="http://schema.org/InStock">
                                        </span>
                                    </span>
                                </div>
                                <span class="price-crossed"></span>				
                            </div>

                            {if $product->quantity > 0}
                                <div class="addtocart-area">
                                    <form method="post" class="product js-recalculate" id="add2cartForm" action="/cart/add">
                                        
                                        <div class="addtocart-bar">
                                            <label for="quantity389" class="quantity_box">Кол-во: </label>
                                            <span class="quantity-box">
                                                <input type="text" class="quantity-input js-recalculate" name="quantity" value="1" maxlength="3" init="1" data-avalible="{$product->quantity}" step="1">
                                            </span>
                                            <span class="quantity-controls js-recalculate">
                                                <input type="button" value="+" class="quantity-controls quantity-plus">
                                                <input type="button" value="-" class="quantity-controls quantity-minus">
                                            </span>

                                            <span class="addtocart-button">
                                                <input type="submit" name="addtocart" class="addtocart-button" value="Добавить в корзину" title="Добавить в корзину">
                                            </span>
                                            <noscript>&lt;input type="hidden" name="task" value="add"/&gt;</noscript> 			
                                        </div>
                                        <input type="hidden" name="product_id" value="{$product->id}">
                                        <input type="hidden" class="pname" value="{$product->product_name}">
                                    </form>
                                </div>
                                <div class="availability 2">Товар в наличии</div>
                            {else}
                                <div class="availability 2">Товар закончился</div>
                            {/if}

                            <div class="uptolike">
                                <script type="text/javascript">(function(w,doc) {
                                    if (!w.__utlWdgt ) {
                                    w.__utlWdgt = true;
                                    var d = doc, s = d.createElement('script'), g = 'getElementsByTagName';
                                    s.type = 'text/javascript'; s.charset='UTF-8'; s.async = true;
                                    s.src = ('https:' == w.location.protocol ? 'https' : 'http')  + '://w.uptolike.com/widgets/v1/uptolike.js';
                                    var h=d[g]('body')[0];
                                    h.appendChild(s);
                                    }})(window,document);
                                </script>
                                <div data-background-alpha="0.0" data-buttons-color="#FFFFFF" data-counter-background-color="#ffffff" data-share-counter-size="12" data-top-button="false" data-share-counter-type="disable" data-share-style="1" data-mode="share" data-like-text-enable="false" data-hover-effect="scale" data-mobile-view="true" data-icon-color="#ffffff" data-orientation="horizontal" data-text-color="#000000" data-share-shape="rectangle" data-sn-ids="fb.vk.tw.ok.gp.ps." data-share-size="30" data-background-color="#ffffff" data-preview-mobile="false" data-mobile-sn-ids="fb.vk.tw.ok.gp." data-pid="1379675" data-counter-background-alpha="1.0" data-following-enable="false" data-exclude-show-more="true" data-selection-enable="false" class="uptolike-buttons no-touch innerHTML" data-utl_buttons-installed="true" id="__utl-buttons-1" data-id="__uptolike_widgets_settings___utl-buttons-1">
                                    <div class="uptl_container uptl_container-share uptlw-container uptl_container-horizontal uptlk_wdgt_u15D9">
                                       <div class="uptl_toolbar uptl_toolbar_simple __utl-reset uptl_toolbar_share"><ul class="horizontal style-1 size-30 horizontal"><li data-uptlwtype="share-button" data-snid="fb" data-clicks="0" data-style="1" data-size="30" class="utl-icon-num-0 share-style-1 utl-icon-fb effect-3 __zero-count" title="Поделиться Facebook"><a data-snid="fb" class="sn-icon"></a></li><li data-uptlwtype="share-button" data-snid="vk" data-clicks="0" data-style="1" data-size="30" class="utl-icon-num-1 share-style-1 utl-icon-vk effect-3 __zero-count" title="Поделиться ВКонтакте"><a data-snid="vk" class="sn-icon"></a></li><li data-uptlwtype="share-button" data-snid="tw" data-clicks="0" data-style="1" data-size="30" class="utl-icon-num-2 share-style-1 utl-icon-tw effect-3 __zero-count" title="Поделиться Twitter"><a data-snid="tw" class="sn-icon"></a></li><li data-uptlwtype="share-button" data-snid="ok" data-clicks="0" data-style="1" data-size="30" class="utl-icon-num-3 share-style-1 utl-icon-ok effect-3 __zero-count" title="Поделиться Одноклассники"><a data-snid="ok" class="sn-icon"></a></li><li data-uptlwtype="share-button" data-snid="gp" data-clicks="0" data-style="1" data-size="30" class="utl-icon-num-4 share-style-1 utl-icon-gp effect-3 __zero-count" title="Поделиться Google+"><a data-snid="gp" class="sn-icon"></a></li><li data-uptlwtype="share-button" data-snid="ps" data-clicks="0" data-style="1" data-size="30" class="utl-icon-num-5 share-style-1 utl-icon-ps effect-3 __zero-count" title="Поделиться Pinterest"><a data-snid="ps" class="sn-icon"></a></li></ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <ul id="product-tabs">
                <li data-toggle="product-description" class="active">Описание</li>
                <li data-toggle="customer-reviews">Отзывы</li>
            </ul>

            <div id="product-tabs-content" class="active">
                <div class="product-description gk-product-tab active" style="display: none;">
                    <div class="product-description">
                        {$product->description_long}
                    </div>
                </div>

                <div class="customer-reviews gk-product-tab" style="display: none;">
                    <div class="customer-reviews">	
                        <div class="list-reviews">
                            <span class="step">Еще нет отзывов об этом товаре.</span>
                        </div>
                    </div> 		
                </div>
            </div>


            <div class="product-related-products">
                <h4>Сопутствующие товары</h4>

                <div class="product-field product-field-type-R"> 
                    <span class="product-field-display">
                        <div class="product-field-display" itemprop="isRelatedTo" itemscope="" itemtype="http://schema.org/Product">
                            <span itemprop="name">
                                <a href="/ru/shop/jewellery/silver/klassicheskoe-koltco-iz-serebra-s-lunnym-kamnem-sr006" title="Классическое кольцо из серебра с лунным камнем" itemprop="url">
                                    <img src="/images/shop/product/resized/silver-ring-moon-stone_250x325.gif" alt="serebryanoe-kolco-s-lunnim-kamnem"> Классическое кольцо из серебра с лунным камнем
                                </a>
                            </span>
                        </div>
                    </span>
                </div>
                
            </div>

            <script id="popups_js" type="text/javascript">//<![CDATA[ 
                
                jQuery("a[rel=vm-additional-images]").fancybox({
                    "titlePosition" 	: "inside",
                    "transitionIn"	:	"elastic",
                    "transitionOut"	:	"elastic"
                });
                
                jQuery(".additional-images a.product-image.image-0").removeAttr("rel");
                jQuery(".additional-images img.product-image").click(function() {
                    jQuery(".additional-images a.product-image").attr("rel","vm-additional-images" );
                    jQuery(this).parent().children("a.product-image").removeAttr("rel");
                    var src = jQuery(this).next("a.product-image").attr("href");
                    jQuery(".main-image img").attr("src",src);
                    jQuery(".main-image img").attr("alt",this.alt );
                    jQuery(".main-image a").attr("href",src );
                    jQuery(".main-image a").attr("title",this.alt );
                    jQuery(".main-image .vm-img-desc").html(this.alt);
                }); 
            
                jQuery(document).ready(function() {
                    
                    jQuery('a.ask-a-question, a.printModal, a.recommened-to-friend, a.manuModal').click(function(event){
                        event.preventDefault();
                        jQuery.facebox({
                            ajax: $(this).attr('href'),
                            rev: 'iframe|550|550'
                        });
                    });
                    
                    var tabs = jQuery('#product-tabs');
                    // if tabs exists
                    if(tabs.length) {
                        // initialization
                        tabs.find('li').first().addClass('active');
                        var contents = jQuery('#product-tabs-content');
                        contents.children('div').css('display', 'none');
                        contents.first('div').addClass('active');
                        // add events to the tabs
                        tabs.find('li').each(function(i, tab) {
                            tab = jQuery(tab);
                            tab.click(function() {
                                var toggle = tab.attr('data-toggle');
                                contents.children('div').removeClass('active');
                                contents.find('.' + toggle).addClass('active');
                                tabs.find('li').removeClass('active');
                                tab.addClass('active');		
                            });
                        });
                    }
                    
                    jQuery('.addtocart-button').click(function() {
                    
                        var f = jQuery('#add2cartForm');
                        var q = f.find('input[name=quantity]').val();
                        
                        if (q.length == 0) {
                            alert('Укажите количество'); return false;
                        }
                        
                        if (q > f.find('input[name=quantity]').data('avalible')) {
                            alert('Максимально доступно к заказу ' + f.find('input').data('avalible') + ' шт.'); return false;
                        }
                        
                        jQuery.post(f.attr('action'), f.serialize(), function(r) {
                            
                            r = jQuery.parseJSON(r);
                            
                            jQuery.facebox(r.message);
                        });
                        
                        return false;
                        
                    });

                }); 
                
                //]]>
            </script>
        </div><!-- .productDetails -->
    </div>
</section>