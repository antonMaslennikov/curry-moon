<div id="ajaxshipping" style="min-height: 30px;">
    <div class="opc_ship_wrap opc_ship_wrap_1" id="opc_ship_wrap_1">
        <input type="radio" data-dynamic-update="1" name="shipmentmethod_id" id="shipment_id_1" _price="{$deliveryTypes.post.price}" value="post">
        <label for="shipment_id_1">
            <span class="vmshipment">
                <span class="vmCartShipmentLogo">
                    <img align="middle" src="/public/images/shipment/Russian_Post.png" alt="Russian_Post">
                </span>  
                <span class="vmshipment_name">Почта России (РФ)</span>
                <span class="vmshipment_cost fee">(Фиксированная стоимость: +{$deliveryTypes.post.price} руб)</span>
            </span>
        </label>
    </div><!-- opc_ship_wrap end -->
    <br>
    <div class="opc_ship_wrap opc_ship_wrap_2 selected" id="opc_ship_wrap_2">
        <input type="radio" data-dynamic-update="1" autocomplete="off" name="shipmentmethod_id" _price="{$deliveryTypes.major.price}" id="shipment_id_2" checked="checked" value="major">
        <label for="shipment_id_2">
            <span class="vmshipment">
                <span class="vmCartShipmentLogo">
                    <img align="middle" src="/public/images/shipment/major.png" alt="major">
                </span>
            <span class="vmshipment_name">Major Express</span>
            <span class="vmshipment_description">(Оплата доставки при получении. <a href="http://www.major-express.ru/calculator.aspx" target="_blank">Калькулятор стоимости.</a>)
            </span>
            </span>
        </label>
    </div><!-- opc_ship_wrap end -->
    <br>
    <div class="opc_ship_wrap opc_ship_wrap_6" id="opc_ship_wrap_6" {if $USER->user_country_id != 838}style="display:none"{/if}>
        <input type="radio" data-dynamic-update="1" name="shipmentmethod_id" id="shipment_id_6" _price="{$deliveryTypes.user.price}" value="user">
        <label for="shipment_id_6">
            <span class="vmshipment">
                <span class="vmCartShipmentLogo">
                    <img align="middle" src="/public/images/shipment/pick-point.png" alt="pick-point">
                </span>  
                <span class="vmshipment_name">Самовывоз</span>
                <span class="vmshipment_description">(Бесплатно, ст.м.Парк Культуры)</span>
            </span>
        </label>
    </div><!-- opc_ship_wrap end -->
    <br>
</div>