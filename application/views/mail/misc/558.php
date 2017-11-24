<table id="report4-table" cellspacing="0" style="border-collapse:collapse;width:99%">
	<? foreach ($orders AS $k => $row): ?>
	<tr>
		<td style="padding:5px;border:1px solid #ccc"><?= $k ?></td>
		<td style="padding:5px;border:1px solid #ccc"><a href="http://www.maryjane.ru/index_admin.php?module=orders&id=<?= $row['user_basket_id'] ?>"><?= $row['user_basket_id'] ?></a></td>
		<td style="padding:5px;border:1px solid #ccc"><?= $row['mark'] ?></td>
	</tr>
	<? endforeach; ?>
</table>