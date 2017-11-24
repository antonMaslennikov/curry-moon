<p>Отчёт составлен за позапрошлый месяц</p>

<p><a href="http://www.maryjane.ru/index_admin.php?module=admin_orderstats&action=paymenttype">http://www.maryjane.ru/index_admin.php?module=admin_orderstats&action=paymenttype</a></p>

<table>
<? foreach ($rows AS $row): ?>
<tr>
	<td style="padding:5px"><a href="http://www.maryjane.ru/index_admin.php?module=orders&id=<?= $row['user_basket_id'] ?>"><?= $row['user_basket_id'] ?></a></td>
	<td width="180"><?= $row['user_basket_delivery_type'] ?></td>
	<td><?= $row['total_price'] ?> руб.</td>
	<td style="padding:5px;color:<?= $o['user_basket_payment_confirm'] == 'true' ? 'white' : 'black' ?>;background:<?= $o['user_basket_payment_confirm'] == 'true' ? 'green' : 'red' ?>"><?= $o['user_basket_payment_confirm'] == 'true' ? 'Оплачено' : 'Не оплачено' ?></td>
	<td style="padding:5px;">доставлен: <?= $row['user_basket_delivered_date'] ?></td>
	<td>
		<? if ($row['user_basket_payment_approved']): ?>
		<input type="checkbox" checked="checked" disabled="disabled" /> подтверждено
		<? endif; ?>
	</td>
</tr>
<? endforeach; ?>
</table>