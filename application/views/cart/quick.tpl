<div class="box ">
   <div class="content">
        <div class="vmGkCartModule ">
            <h3>Моя корзина</h3>
            
            {foreach from=$basket->basketGoods item="p"}
            <div class="vmGkCartProducts">
                <div>
                    <img src="{$p.picture_path}" alt="">
                    <div>
                        <h3>
                            <span>{$p.quantity}×</span>
                            <a href="/ru/shop/openproduct/{$p.good_id}">{$p.product_name}</a>
                        </h3>
                        <div class="customProductData"><div class="vm-customfield-mod"></div></div>
                        <span class="gkPrice num1">{$p.tprice} руб</span>
                    </div>
                </div>
            </div>
            {/foreach}
            
            <div class="gkTotal"> Всего <strong>{$basket->basketSum} руб</strong> </div>
            <div class="gkShowCart"> <a style="float:right;" href="/ru/cart" rel="nofollow">Показать корзину</a> </div>
            <noscript>
            Пожалуйста, подождите          
            </noscript>
        </div>
    </div>
</div>