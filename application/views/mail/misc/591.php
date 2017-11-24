<p>Одежда: <b><?= $wear ?></b></p>
<p>Наклейки и чехлы: <b><?= $gadgets ?></b></p>

<ol>
	<? foreach ($printed AS $p): ?>
	
		<li><?= $p['finish_date'] ?>&nbsp;&nbsp; <?= $p['user_login'] ?>:&nbsp;&nbsp;&nbsp; <?= $p['style_name'] ?> <? if ($p['cat_parent'] == 1): ?>(<?= $p['color_name'] ?>, <?= $p['size_name'] ?>)<? endif; ?></li>
	
	<? endforeach; ?>
</ol>
