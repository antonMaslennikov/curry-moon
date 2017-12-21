<?php include __DIR__ . '/../header.php' ?>

<p>Приветствуем Вас</p>
<p>Ваш заказ <b>№<?= $order->id ?></b> был отменён</p>
<? if ($reasone): ?>
<p>по причине "<?= $reasone ?>"</p>
<? endif; ?>