<!-- Отчёт о 400ой ошибке -->

<ol>
	<? foreach ($errors AS $e): ?>
	<li><?= $e; ?></li>
	<? endforeach; ?>
</ol>