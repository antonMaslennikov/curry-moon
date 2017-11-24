<p>За вчера (<?= $date ?>) произведены следующие возвраты:</p>

<table>
<tr>
<th colspan="5"></th>
<th>вернули</th>
<th>комментарий</th>
<th>причина</th>
</tr>
<? foreach ($rows AS $row): ?>
<tr>
<td width="15"><?= $row['i'] ?></td>
<td width="135" style="padding:5px"><img width="135" src="<?= $row['picture_path'] ?>"></td>
<td width="250"><?= $row['good_name'] ?></td>
<td><?= $row['style_name'] ?> (<?= $row['style_sex'] ?>, <?= $row['name'] ?>, <?= $row['size_name'] ?>)</td>
<td width="80"align="center"><a href="http://www.maryjane.ru/index_admin.php?module=orders&id=<?= $row['user_basket_id'] ?>"><?= $row['user_basket_id'] ?></a></td>
<td><?= $row['type'] ?></td>
<td><?= $row['was_delivered'] ?></td>
<td align="center"><?= $row['comment'] ?></td>
<td align="center"><?= $row['exreasone'] ?></td>
</tr>
<? endforeach; ?>
</table>