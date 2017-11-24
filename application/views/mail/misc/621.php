<p>На счету пользователя <a href="http://www.maryjane.ru/index_admin.php?module=users&task=view&id=<?= $user->id ?>"><?= $user->user_login ?></a> осталось <?= $user->user_bonus ?> бонусов.</p>
<? if ($user->user_bonus <= 0): ?>
<p>Вы больше не можете совершать заказы до пополнения счета.</p>
<? endif; ?>