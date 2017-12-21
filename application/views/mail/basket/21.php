<?php include __DIR__ . '/../header.php' ?>

<p>Приветствуем Вас</p>
<p>Ваш заказ <b>№<?= $order->id ?></b> доставлен в службу доставки <?= $order->user_basket_delivery_type_rus ?></p>
<p>Скоро он прибудет к Вам.</p>
<p>Спасибо за заказ!</p>