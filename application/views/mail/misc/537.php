<h4>Опись от <?= $date ?></h4>

<? if (count($goods) > 0): ?>
<ol>
	<? foreach ($goods AS $g): ?>
	<li>
		<a href="http://www.maryjane.ru/index_admin.php?module=admin_goods&task=act&id=<?= $g['good_id'] ?>/" target="_blank" style="font-size:16px;">
			 <?= $g['good_name'] ?>
		</a>
		
		от  <?= $g['user_login'] ?>
	</li>
	<? endforeach; ?>
</ol>
<? endif; ?>