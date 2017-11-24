<p><b>Текст:</b><?= $review->text ?></p>

<p>Страница: http://www.maryjane.ru<?= $url ?></p>

<p>Имя: <b><?= $review->name ?></b></p>
<p>Email: <b><?= $review->email ?></b></p>
<p>Телефон: <b><?= $review->phone ?></b></p>

<p><a href="http://www.maryjane.ru/index_admin.php?module=reviews&action=edit&id=<?= $review->id ?>">редактировать</a></p>
<p>
    <a href="http://www.maryjane.ru/contact_us/approve/<?= $review->id ?>/" style="color:red">Одобрить комментарий?</a>
</p>
