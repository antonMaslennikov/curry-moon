<p>За сегодня дилерских заказов: <?= $count ?> шт.</p>

<table>
<? foreach ($orders AS $o): ?>
<tr>
	<td><?= $o['i'] ?></td>
	<td><a href="http://www.maryjane.ru/index_admin.php?module=orders&id=<?= $o['user_basket_id'] ?>"><?= $o['user_basket_id'] ?></a></td>
	<td><a href="http://www.maryjane.ru/index_admin.php?module=users&task=view&id=<?= $o['user_id'] ?>"><?= $o['user_login'] ?></a></td>
	<td><?= $o['s'] ?> руб.</td>
</tr>
<? endforeach; ?>
<tr>
	<td colspan="3" style="text-align: right">Итого:</td>
	<td><?= $total ?> руб.</td>
</tr>
</table>