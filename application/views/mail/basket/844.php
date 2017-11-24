<p>Мы просчитали итоговую сумму вашего заказа #<?= $order->shortNumber ?>:</p>

<? foreach ($dop as $d => $s): ?>
<p><b><?= ($d == 'embroidery') ? 'Вышивка' : 'Доплата за печать' ?></b>: <?= number_format($s, 1, ',', ' ') ?> руб.</p>
<? endforeach; ?>

<p><b>Итого:</b> <?= number_format($order->basketSum, 1, ', ', ' ') ?> руб.</p>