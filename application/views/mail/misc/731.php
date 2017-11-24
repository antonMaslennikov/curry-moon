<? if (count($orders) > 0): ?>
<ol>
	<? foreach ($orders AS $o): ?>
	<li><a href="http://www.maryjane.ru/index_admin.php?module=orders&id=<?= $o['user_basket_id'] ?>"><?= $o['user_basket_id'] ?></a> от <?= $o['user_basket_date'] ?> покупатель <a href="http://www.maryjane.ru/index_admin.php?module=users&action=view&id=<?= $o['user_id'] ?>"><?= $o['user_login'] ?></a></li>
	<? endforeach; ?>
</ol>
<? else: ?>

Заказов за сегодня не было

<? endif; ?>