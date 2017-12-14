<section id="gkMainbody" style="font-size: 100%;">

    <div class="vm-wrap vm-order-done">
        <h3>Спасибо за Ваш заказ!</h3>
        
        <div class="post_payment_payment_name" style="width: 100%">
            <span class="post_payment_payment_name_title">Способ оплаты </span>
            <span class="vmCartPaymentLogo"><img align="middle" src="{$order->user_basket_payment_type_ico}" alt="{$order->user_basket_delivery_type}"></span>  
            <span class="vmpayment_name">{$order->user_basket_delivery_type_rus} ({$order->user_basket_payment_type_rus})</span><br>
        </div>

        <div class="post_payment_order_number" style="width: 100%">
            <span class="post_payment_order_number_title">Номер заказа </span> {$order->id}
        </div>

        <div class="post_payment_order_total" style="width: 100%">
            <span class="post_payment_order_total_title">Всего </span> {$order->basketSum} руб
        </div>

        <a class="vm-button-correct" href="/ru/orders/{$order->id}">Показать ваш заказ</a>
    </div>
    
    {literal}
    <script> 
    if (typeof sessMin == 'undefined') var sessMin = 15; 
    if (typeof typeof jQuery.fancybox == 'undefined') var sessMin = 15;
    if ((typeof jQuery != 'undefined') && (typeof jQuery.fn.chosen == 'undefined')) jQuery.fn.chosen = function() {;}; 
    if ((typeof jQuery != 'undefined') && (typeof jQuery.fn.facebox == 'undefined')) jQuery.fn.facebox = function() {;}; 
    if ((typeof jQuery != 'undefined') && (typeof jQuery.fn.fancybox == 'undefined')) jQuery.fn.fancybox = function() {;}; 

    </script>
    
{/literal}
</section>