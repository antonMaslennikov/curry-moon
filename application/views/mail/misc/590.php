<p>ОТЧЁТ ПО КАРМЕ ЗА МЕСЯЦ</p>

<h4>Плюс</h4>

<ol>
	<? foreach ($report['plus'] as $k => $u): ?>
		<li>
			<a href="http://www.maryjane.ru/index_admin.php?module=users&task=view&id=<?= $u['user_id'] ?>"><?= $u['user_login'] ?></a>: 

			<span style="color:<? if ($u['carma'] < 0): ?> red <? endif; ?>"><?= $u['carma'] ?></span> (<?= $u['carma_type'] ?>)
			
			<span>итого: <?= (intval($u['user_carma']) + $u['carma']) ?></span>
			
			<? if ($u['carma'] < 0 && $u['user_carma'] - $u['carma'] < 0): ?>
				&nbsp;&nbsp;&nbsp;<span style="color:red">Ушёл в минус!</span>
			<? endif; ?>
		</li>
	<? endforeach; ?>
</ol>

<h4>Минус</h4>

<ol>
	<? foreach ($report['minus'] as $k => $u): ?>
		<li>
			<a href="http://www.maryjane.ru/index_admin.php?module=users&task=view&id=<?= $u['user_id'] ?>"><?= $u['user_login'] ?></a>: 

			<span style="color:<? if ($u['carma'] < 0): ?> red <? endif; ?>"><?= $u['carma'] ?></span> (<?= $u['carma_type'] ?>)
			
			<span>итого: <?= (intval($u['user_carma']) + $u['carma']) ?></span>
			
			<? if ($u['carma'] < 0 && $u['user_carma'] - $u['carma'] < 0): ?>
				&nbsp;&nbsp;&nbsp;<span style="color:red">Ушёл в минус!</span>
			<? endif; ?>
		</li>
	<? endforeach; ?>
</ol>

<hr />

<ul>
	<? foreach (carma::$options as $k => $c): ?>
		<li>
			<?= '(<b>' . $k . '</b>): ' . $c['name'] . ': '. $c['carma']?>
		</li>
	<? endforeach; ?>
</ul>

<p>Минус в карму можно получить если ТЫ ставишь в карму кому-то, при этом вычитается из твоей кармы столько же</p>