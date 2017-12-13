<div id="payment_html">
    <div class="payment_inner_html" rel="force_show_payments">
        <div style="clear: both;">
            <div id="opc_payment_wrap_3" class="opc_payment_wrap opc_payment_wrap_3 selected">
               <input type="radio" autocomplete="off" data-dynamic-update="1" name="paymentmethod_id" id="payment_id_3" value="yamoney" checked="checked">
                <label for="payment_id_3"><span class="vmpayment"><img align="middle" src="/public/images/payment/visa-mc-yandex.png" alt="visa-mc-yandex">  <span class="vmpayment_name">Яндекс.Деньги</span></span></label>
            </div>
        </div>
        <div style="clear: both;">
            <div id="opc_payment_wrap_5" class="opc_payment_wrap opc_payment_wrap_5">
                <input type="radio" autocomplete="off" data-dynamic-update="1" name="paymentmethod_id" id="payment_id_5" value="alfa">
                <label for="payment_id_5"><span class="vmpayment"><span class="vmCartPaymentLogo"><img align="middle" src="/public/images/payment/alfabank-white.png" alt="alfabank-white"></span>  <span class="vmpayment_name">Карта Альфа-Банка</span><span class="vmpayment_description vmpayment_description_5">Номер карты для оплаты отправляется на email</span></span></label>
            </div>
        </div>
        <div style="clear: both;">
            <div id="opc_payment_wrap_6" class="opc_payment_wrap opc_payment_wrap_6">
                <input type="radio" autocomplete="off" data-dynamic-update="1" name="paymentmethod_id" id="payment_id_6" value="sberbank">
                <label for="payment_id_6"><span class="vmpayment"><span class="vmCartPaymentLogo"><img align="middle" src="/public/images/payment/sberbank.png" alt="sberbank"></span>  <span class="vmpayment_name">Карта Сбербанка</span><span class="vmpayment_description vmpayment_description_6">Номер карты для оплаты отправляется на email</span></span></label>
            </div>
        </div>
        <div style="clear: both;">
            <div id="opc_payment_wrap_8" class="opc_payment_wrap opc_payment_wrap_8" {if $USER->user_country_id != 838}style="display:none"{/if}>
                <input type="radio" autocomplete="off" data-dynamic-update="1" name="paymentmethod_id" id="payment_id_8" value="cash">
                <label for="payment_id_8"><span class="vmpayment"><span class="vmCartPaymentLogo"><img align="middle" src="/public/images/payment/cash_rub.png" alt="cash_rub"></span>  <span class="vmpayment_name">Наличные (при самовывозе)</span></span></label>
            </div>
        </div>
    </div>
</div>
<div id="payment_extra_outside_basket"></div>
<br style="clear: both;">