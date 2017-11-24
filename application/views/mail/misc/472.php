<p>Печатник <b><?= $printer ?></b> отправил позицию в брак по причине:</p>

<p><b><?= $reasone ?></b></p>

<? if ($printer_name): ?>
	<p>Принтер: <b><?= $printer_name ?></b></p>
<? endif; ?>

<p>Заказ №<b><?= $order ?></b></p>
<p><a href="http://www.maryjane.ru<?= $src ?>">исходник</a></p>
<p><a href="http://www.maryjane.ru/senddrawing.design/<?= $good_id ?>/">сенддровинг</a></p>
<p><img src="http://www.maryjane.ru<?= $preview ?>" /></p>