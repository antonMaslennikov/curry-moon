<p>Менеджер <?= $user_login ?> закрыла смену.</p>

<p><b>Обработано:</b></p>

<ul>
	<li>Смет: <?= $data['estimates'] ?>, потрачено - <? echo $data['estimates_min'] ?> мин., среднее время - <? echo round($data['estimates_min'] / $data['estimates'], 1) ?>. Создано новых - <? echo $data['estimates_created']; ?></li>
	<li>Просчётов: <?= $data['checkings'] ?>, потрачено - <?= $data['checkings_min'] ?> мин., среднее время - <?= round($data['checkings_min'] / $data['checkings'], 1) ?>, новых запросов - <?= $data['checkings_new'] ?>, новых запросов менеджер - <?= $data['checkings_new_manager'] ?></li>
	<li>Писем: <?= $data['emails'] ?>, потрачено - <? echo $data['emails_min'] ?> мин., среднее время - <? echo round($data['emails_min'] / $data['emails'], 1) ?></li>
</ul>