<table>
	<tr>
		<th>#</th>
		<th>Дизайн</th>
		<th>Носитель</th>
		<th>Цвет</th>
		<th>Размер</th>
		<th>Пользователь</th>
		<th>Время</th>
	</tr>
	<? foreach ($preorders AS $k => $p): ?>
	<tr>
		<td><?= ($k + 1) ?></td>
		<td><a href="http://www.maryjane.ru/index_admin.php?module=admin_goods&id=<?= $p['good_id'] ?>"><?= $p['good_name'] ?></a></td>
		<td><a href="http://www.maryjane.ru/index_admin.php?module=styles&action=editstyle&style_id=<?= $p['style_id'] ?>"><?= $p['style_name'] ?></a></td>
		<td><?= $p['color'] ?></td>
		<td><?= $p['size'] ?></td>
		<td><a href="http://www.maryjane.ru/index_admin.php?module=users&task=list&search=<?= $p['user_email'] ?>"><?= $p['user_email'] ?></a> (<?= $p['user_login'] ?>)</td>
		<td><?= $p['time'] ?></td>
	</tr>
	<? endforeach; ?>
</table>