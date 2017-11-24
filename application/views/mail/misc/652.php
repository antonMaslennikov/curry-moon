<? if ($idBasket > 0): ?>
<p>Оформлен заказ <a href="www.maryjane.ru/index_admin.php?module=orders&id=<?= $idBasket ?>"><?= $idBasket ?></a>.</p>
<? endif; ?>

<p>Ссылки на файлы:</p>
<ul>
	<li><a href="<?= $urlExcel ?>">Excel</a></li>
</ul>

<? if (!empty($comment)): ?>
<p>
	<b>Комментарий:</b> <?= $comment ?>
</p>
<? endif; ?>