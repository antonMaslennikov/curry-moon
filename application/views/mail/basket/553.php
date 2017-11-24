<p>На этой неделе:</p>

<p>"Пожалуйста оцените качество":</p>

<table border="1">
<tr>
<th></th>
<th></th>
<th align="center">1*</th>
<th align="center">2**</th>
<th align="center">3***</th>
<th></th>
<th>Предпоследний заказ</th>
</tr>
<? foreach ($rows AS $a): ?>
<tr>
<td width="20"><?= $a['i'] ?></td>
<td width="80"><a href="http://www.maryjane.ru/index_admin.php?module=orders&id=<?= $a['basket_id'] ?>"><?= $a['basket_id'] ?></a></td>
<td width="20" align="center"><?= $a['a270'] ?></td>
<td width="20" align="center"><?= $a['a271'] ?></td>
<td width="20" align="center"><?= $a['a272'] ?></td>
<td width="160" align="center"><?= $a['date'] ?></td>
<td width="80"><a href="http://www.maryjane.ru/index_admin.php?module=orders&id=<?= $a['prev_order'] ?>"><?= $a['prev_order'] ?></a></td>
</tr>
<? endforeach; ?>
<tr>
<td colspan="2"><b>Итого:</b></td>
<td align="center"><b><?= $t1 ?></b></td>
<td align="center"><b><?= $t2 ?></b></td>
<td align="center"><b><?= $t3 ?></b></td>
<td align="center"></td>
<td align="center"></td>
</tr>
</table>

<p>* да, все круто спасибо</p>
<p>** не устроил размер</p>
<p>*** не понравилась печать</p>