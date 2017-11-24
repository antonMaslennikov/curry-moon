<p>От пользователя <? if ($U->id): ?><a href="http://www.maryjane.ru/index_admin.php?module=users&action=view&id=<?= $U->id ?>"><?= $U->user_login ?></a><? endif; ?> поступил заказ на наклейку.</p>

<? if ($data['name'] || $U->id > 0): ?>
	<p>Имя: <b><?= $data['name'] ?><?= $U->user_name ?></b></p> 
<? endif; ?>
<? if ($data['email'] || $U->id > 0): ?>
	<p>Email: <b><?= $data['email'] ?><?= $U->user_email ?></b></p>
<? endif; ?>
<? if ($data['phone'] || $U->id > 0): ?>
	<p>Телефон: <b><?= $data['phone'] ?><?= $U->user_phone ?></b></p>
<? endif; ?>

<p><img src="http://www.allskins.ru<?= $data['img'] ?>" /></p>
<p>Название: <?= $data['title'] ?></p>
<p>Цена: <?= $data['price'] ?><br /></p>
<p>Размер: <?= $data['size'] ?><br /></p>
