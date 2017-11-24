<p>
	<span style="color:#ff0000">красным</span> - если найдено 0 и по этому запросу искали более одного раза,<br>
	<span style="color:#AE9205">оранжевым</span> - если найдено 0 иищут первый раз
</p>

<table style="border-collapse: collapse">
<tr>
	<th style="border:1px solid #ccc"></th>
	<th style="border:1px solid #ccc">найдено</th>
	<th style="border:1px solid #ccc">хитов</th>
	<th style="border:1px solid #ccc">Пользователь</th>
	<th style="border:1px solid #ccc">ip</th>
	<th style="border:1px solid #ccc"></th>
	<th style="border:1px solid #ccc"></th>
	<th style="border:1px solid #ccc"></th>
</tr>
<? if (count($new) > 0): ?>
	<tr>
		<th colspan="8" align="left" style="border:1px solid #ccc"><h4 style="text-align: left">Новые запросы за сегодня</h4></th>
	</tr>
	<? foreach ($new as $p): ?>
	<tr>
		<td style="border:1px solid #ccc;color:#<?= $p['color'] ?>"><a style="color:#<?= $p['color'] ?>" href="http://www.maryjane.ru/search/?q=<?= $p['phrase_encoded'] ?>"><?= $p['phrase'] ?></a></td>
		<td style="border:1px solid #ccc"><?= $p['founded'] ?></td>
		<td style="border:1px solid #ccc"><?= $p['hits'] ?></td>
		<td style="border:1px solid #ccc"><? if ($p['user_id'] > 0): ?><a href="<?= mainUrl ?>/index_admin.php?module=users&action=view&id=<?= $p['user_id'] ?>"><?= $p['user_id'] ?></a><? endif; ?></td>
		<td style="border:1px solid #ccc"><?= $p['user_ip'] ?></td>
		<td style="border:1px solid #ccc"><a href="http://www.maryjane.ru/index_admin.php?module=admin_search&id=<?= $p['id'] ?>"><em>добавить синоним</em></a></td>
		<td style="border:1px solid #ccc"><a href="http://yandex.ru/search/?text=<?= $p['phrase'] ?>&lr=41&site=www.maryjane.ru&to_date_full=<?= date("d.m.Y") ?>">В Яндексе</a></td>
		<td style="border:1px solid #ccc"><a href="https://www.google.ru/search?as_q=<?= $p['phrase'] ?>&as_qdr=all&as_sitesearch=http://www.maryjane.ru&as_occt=any&safe=images">В Google</a></td>
	</tr>
	<? endforeach; ?>
<? endif; ?>

<? if (count($sq) > 0): ?>
	<tr>
		<th colspan="8" align="left" style="border:1px solid #ccc"><h4 style="text-align: left">Многосоставные запросы</h4></th>
	</tr>
	<? foreach ($sq as $p): ?>
	<tr>
		<td style="border:1px solid #ccc;color:#<?= $p['color'] ?>"><a style="color:#<?= $p['color'] ?>" href="http://www.maryjane.ru/search/?q=<?= $p['phrase_encoded'] ?>"><?= $p['phrase'] ?></a></td>
		<td style="border:1px solid #ccc"><?= $p['founded'] ?></td>
		<td style="border:1px solid #ccc"><?= $p['hits'] ?></td>
		<td style="border:1px solid #ccc"><? if ($p['user_id']): ?><a href="<?= mainUrl ?>/index_admin.php?module=users&action=view&id=<?= $p['user_id'] ?>"><?= $p['user_id'] ?></a><? endif; ?></td>
		<td style="border:1px solid #ccc"><?= $p['user_ip'] ?></td>
		<td style="border:1px solid #ccc"><a href="http://www.maryjane.ru/index_admin.php?module=admin_search&id=<?= $p['id'] ?>"><em>добавить синоним</em></a></td>
		<td style="border:1px solid #ccc"><a href="http://yandex.ru/search/?text=<?= $p['phrase'] ?>&lr=41&site=www.maryjane.ru&to_date_full=<?= date("d.m.Y") ?>">В Яндексе</a></td>
		<td style="border:1px solid #ccc"><a href="https://www.google.ru/search?as_q=<?= $p['phrase'] ?>&as_qdr=all&as_sitesearch=http://www.maryjane.ru&as_occt=any&safe=images">В Google</a></td>
	</tr>
	<? endforeach; ?>
<? endif; ?>

<? if (count($q) > 0): ?>
	<tr>
		<th colspan="8" align="left" style="border:1px solid #ccc"><h4 style="text-align: left">Остальные запросы</h4></th>
	</tr>
	<? foreach ($q as $p): ?>
	<tr>
		<td style="border:1px solid #ccc;color:#<?= $p['color'] ?>"><a style="color:#<?= $p['color'] ?>" href="http://www.maryjane.ru/search/?q=<?= $p['phrase_encoded'] ?>"><?= $p['phrase'] ?></a></td>
		<td style="border:1px solid #ccc"><?= $p['founded'] ?></td>
		<td style="border:1px solid #ccc"><?= $p['hits'] ?></td>
		<td style="border:1px solid #ccc"><? if ($p['user_id']): ?><a href="<?= mainUrl ?>/index_admin.php?module=users&action=view&id=<?= $p['user_id'] ?>"><?= $p['user_id'] ?></a><? endif; ?></td>
		<td style="border:1px solid #ccc"><?= $p['user_ip'] ?></td><td><a href="http://www.maryjane.ru/index_admin.php?module=admin_search&id=<?= $p['id'] ?>"><em>добавить синоним</em></a></td>
		<td style="border:1px solid #ccc"><a href="http://yandex.ru/search/?text=<?= $p['phrase'] ?>&lr=41&site=www.maryjane.ru&to_date_full=<?= date("d.m.Y") ?>">В Яндексе</a></td>
		<td style="border:1px solid #ccc"><a href="https://www.google.ru/search?as_q=<?= $p['phrase'] ?>&as_qdr=all&as_sitesearch=http://www.maryjane.ru&as_occt=any&safe=images">В Google</a></td>
	</tr>
	<? endforeach; ?>
<? endif; ?>
</table>