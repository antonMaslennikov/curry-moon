<p>
	По вашему запросу сообщаем, <?= $style['style_name'] ?> 
	
	<? if ($style['cat_parent'] == 1): ?>
		цвет <?= $style['color_name'] ?> размер <?= $style['size_name'] ?> поступили на склад и <a href="http://www.maryjane.ru/catalog/category,<?= $style['cat_slug'] ?>;color,<?= $style['style_color'] ?>;size,<?= $style['size_id'] ?>/<?= $style['style_sex'] ?>/new/">доступны для заказа.</a>
	<? else: ?>
		поступили на склад и <a href="http://www.maryjane.ru/catalog/<?= $style['category'] ?>/<?= $style['style_slug'] ?>/new/">доступны для заказа.</a>
	<? endif; ?>
</p>