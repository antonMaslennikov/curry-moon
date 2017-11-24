<p>Смену закрыл: <b><?= $user->user_login ?></b> (<?= $user->meta->{'open-shift-' . date('Y-m-d')} ?> &mdash; <?= date('H:i') ?>)</p>

<table>
<tr>
	<th></th>
	<th>остаток</th>
	<th>потрачено</th>
	<th>норма расхода</th>
	<th>на сколько позиций хватит</th>
</tr>
<? foreach($remains AS $r): ?>

	<?
		$color = '';
		
		if (($r['color_id'] == 37  && $r['volume'] < 2) || ($r['color_id'] == 109 && $r['volume'] < 3) || ($r['color_id'] != 37 && $r['color_id'] != 109 && $r['volume'] < 0.2))
		{
			$color = 'red';
			$alarm = 'Краски меньше допустимого минимума';
		}
		
		if ($r['volume'] <= 0.2)
		{
			$r['q'] = 0;
		}
	?>
	
	<tr>
		<td width="120" style="color:<?= $color ?>"><?= $r['color'] ?></td>
		<td style="color:<?= $color ?>"><?= $r['volume'] ?> л.</td>
		<td style="color:<?= $color ?>"><?= $r['spent'] ?> мл.</td>
		<td style="color:<?= $color ?>"><?= $r['norma'] ?> мл/шт</td>
		<td style="color:<?= $color ?>"><?= $r['q'] ?> шт.</td>
		<td><?= $alarm; ?></td>
	</tr>
	
<? endforeach; ?>
<tr>
	<td colspan="6">&nbsp;</td>
</tr>
<tr>
	<td width="120">напечатано</td>
	<td><?= $total ?> шт.</td>
</tr>
</table>

<p><a href="http://www.maryjane.ru/admin/index.php?module=admin_orderstats&action=color_remains">Посмотреть отчёт полностью</a></p>
<!-- p>В очереди на момент закрытия смены %inturn% позиций</p -->

<p>-------------------------------------------------------------------------------------------------------------------------</p>

<p>Одежда: <b><?= $wear ?></b></p>
<p>Наклейки: <b><?= $gadgets ?></b></p>
<p>Чехлы: <b><?= count($cases) ?></b></p>

<br />

<ol>
    <? foreach ($printed AS $p): ?>
    
        <li><?= $p['finish_date'] ?>&nbsp;&nbsp; <?= $p['user_login'] ?>:&nbsp;&nbsp;&nbsp; <?= $p['style_name'] ?> <? if ($p['cat_parent'] == 1): ?>(<?= $p['color_name'] ?>, <?= $p['size_name'] ?>)<? endif; ?> - <?= $p['quantity']?> шт.</li>
    
    <? endforeach; ?>
    
    <? if (count($cases) > 0): ?>
    
    <li><b>Чехлы:</b></li>
    
        <? foreach ($cases AS $p): ?>
        
            <li><?= $p['finish_date'] ?>&nbsp;&nbsp; <?= $p['user_login'] ?>:&nbsp;&nbsp;&nbsp; <?= $p['style_name'] ?> <? if ($p['cat_parent'] == 1): ?>(<?= $p['color_name'] ?>, <?= $p['size_name'] ?>)<? endif; ?> - <?= $p['quantity']?> шт.</li>
        
        <? endforeach; ?>
    
    <? endif; ?>
</ol> 