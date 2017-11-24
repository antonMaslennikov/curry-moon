<p><?= $data['enduro_order_description'] ?></p>
<p>Имя: <b><?= $data['name']?></b></p>
<p>E-mail: <b><?= $data['email']?></b></p>
<p>Телефон: <b><?= $data['phone']?></b></p>
<p>Комментарий: <b><?= $data['comment']?></b></p>
<? if ($user->authorized): ?>
<p>пользователь: <a href="<?= mainUrl ?>/admin/module=users&id=<?= $user->id ?>"><?= $user->id ?></p>
<? endif; ?>
<p>Источник: <b><?= $data['source'] ?></b></p>
<p>Страница: <b><?= $referer ?></b></p>