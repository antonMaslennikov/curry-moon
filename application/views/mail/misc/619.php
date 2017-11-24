<p><b>Текст:</b><?= $review->text ?></p>

<p>Имя: <b><? if ($user->id > 0): ?><a href="%quickLoginLink%&next=/admin/index.php%3Fmodule=users%26action=view%26id=<?= $review->user_id ?>"><?= $review->name ?></a> (бонусов: <?= $user->user_bonus ?>)<? else: ?><?= $review->name ?><? endif; ?></b></p>
<p>Email: <b><?= $review->email ?></b></p>
<p>Телефон: <b><?= $review->phone ?></b></p>
<p>Оценка: <b><?= $review->rating ?></b></p>
<? if ($review->pic1): ?>
<p>Приложенный файл: <b><?= (strpos($review->pic1, 'http') !== false ? $review->pic1 : mainUrl . $review->pic1) ?></b></p>
<? endif; ?>
<? if ($review->pic2): ?>
<p>Приложенный файл: <b><?= (strpos($review->pic2, 'http') !== false ? $review->pic2 : mainUrl . $review->pic2) ?></b></p>
<? endif; ?>
<? if ($review->pic3): ?>
<p>Приложенный файл: <b><?= (strpos($review->pic3, 'http') !== false ? $review->pic3 : mainUrl . $review->pic3) ?></b></p>
<? endif; ?>
<p>Заказ:<a href="%quickLoginLink%&next=/admin/index.php%3Fmodule=orders%26id=<?= $order ?>"><b><?= $order ?></b></a></p>
<p>Переход из шаблона: <a href="%quickLoginLink%&next=/admin/index.php%3Fmodule=admin_mailtemplates%26action=edit%26id=<?= $review->tpl ?>"><?= $review->tpl ?></a></p>

<p>Планируемые бонусы: <?= $bonuses_wait ?></p>

<p><a href="%quickLoginLink%&next=/admin/index.php%3Fmodule=reviews%26action=edit%26id=<?= $review->id ?>">редактировать</a></p>
<p>
	<a href="%quickLoginLink%&next=/contact_us/approve/<?= $review->id ?>/" style="color:red">Одобрить опрос?</a> |
	<a href="%quickLoginLink%&next=/contact_us/approve/<?= $review->id ?>/dealers/" style="color:red">Одобрить опрос как дилерский?</a>
</p>
<p>
    <a href="%quickLoginLink%&next=/admin/index.php%3Fmodule=users%26action=bonuses_wait%26<? if ($review->user_id > 0): ?>id=<?= $review->user_id ?><? else: ?>email=<?= $review->email   ?><? endif; ?>">Отложить списание бонусов</a>
</p>
