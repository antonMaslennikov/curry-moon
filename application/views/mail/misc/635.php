<p>Добавлена новая надпись</p>
<p><b>Текст:</b><em><?= $text ?></em></p>
<p>
	<? if ($User->authorized): ?>
		Пользователь: <a href="http://www.maryjane.ru/profile/<?= $User->id ?>/"><?= $User->user_login ?></a>
	<? else: ?>
		Пользователь не авторизован
	<? endif; ?>
</p>
<p>
	<a href="%quickLoginLink%&next=/notees/approve/<?= $id ?>/" style="color:green">Утвердить</a> |
	<a href="%quickLoginLink%&next=/notees/deny/<?= $id ?>/" style="color:red">Отклонить</a>
</p>
