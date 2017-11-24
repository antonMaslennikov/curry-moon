<p>Всего за прошлый месяц (<?= $month ?>) с сайта сделано обменов: <b><?= count($clients) + count($return) ?></b></p>

<p>Общее количество заказов (без обменов): <?= $total ?> шт.</p>

<h4>Обмены:</h4>

<table>
<? foreach ($clients AS $r): ?>
<tr>
	<td><a href="http://www.maryjane.ru/index_admin.php?module=orders&id=<?= $r['basket_id'] ?>"><?= $r['basket_id'] ?></a> (<?= $r['reasone']?>) <em><?= $r['comment'] ?></em></td>
</tr>
<? endforeach; ?>
</table>

<h4>Возвраты:</h4>

<table>
<? foreach ($return AS $r): ?>
<tr>
    <td><a href="http://www.maryjane.ru/index_admin.php?module=orders&id=<?= $r['basket_id'] ?>"><?= $r['basket_id'] ?></a> <em><?= $r['comment'] ?></em></td>
</tr>
<? endforeach; ?>
</table>

<h4>Обменов и возвратов с админки (<b><?= count($admin) ?></b>)</h4>

<table>
<? foreach ($admin AS $r): ?>
<tr>
	<td><a href="http://www.maryjane.ru/index_admin.php?module=orders&id=<?= $r['basket_id'] ?>"><?= $r['basket_id'] ?></a> (<?= $r['reasone']?>) <em><?= $r['comment'] ?></em></td>
</tr>
<? endforeach; ?>
</table>