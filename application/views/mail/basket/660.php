<p>Поуступил новый заказ на наклеку на весь мотоцикл</p>

<? if ($user_id > 0): ?>
<p><a href="<?= mainUrl ?>/index_admin.php?module=users&action=view&id=<?= $user_id ?>"><?= $user_login ?></a></p>
<? endif; ?>

<p>Email: <b><?= $user_email ?></b></p>
<p>Телефон: <b><?= $user_phone ?></b></p>

<p><img src="<?= mainUrl . $img ?>"></p>