краски меньше допустимого значения:
<ul>
	<? foreach ($color AS $c): ?>
	<li><?= $c['name'] ?>: <b><?= $c['volume'] ?></b> л., хватит на <?= $c['plan'] ?> шт.</li>
	<? endforeach; ?>
</ul>

<p><a href=" http://www.maryjane.ru/index_admin.php?module=admin_orderstats&task=color_remains">Отчёт по остаткам краски</a></p>