<?php include __DIR__ . '/../header.php' ?>

<p>Приветствуем Вас</p>
<p>Ваш заказ <b>№<?= $order->id ?></b> принят в обработку</p>
<p>Вы заказали:</p>
<ul>
    <? foreach ($order->basketGoods AS $p): ?>
        <li><?= $p['product_name'] ?> <?= $p['quantity'] ?> шт. <?= $p['tprice'] ?> р.</li>
    <? endforeach; ?>
</ul>
<p>Доставка: <b><?= $order->user_basket_delivery_type_rus ?></b></p>
<? if ($order->user_basket_delivery_type != 'user'): ?>
    <p>Адрес доставки: <b><?= $deliveryAddress ?></b></p>
<? endif; ?>
<p>Оплата: <b><?= $order->user_basket_payment_type_rus ?></b></p>
<p>Итого к оплате: <b><?= $order->basketSum ?>р.</b></p>
<p>Наш менеджер скоро свяжется с Вами.</p>
<p>Спасибо за заказ!</p>