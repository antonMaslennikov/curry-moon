автор <a href="http://www.maryjane.ru/profile/<?= $user_id ?>"><?= $user_login ?></a> прислал новую работы <a href="http://www.maryjane.ru/senddrawing.design/<?= $good_id ?>/"><?= $good_name ?></a> с наклейкой на тачку или стикерсетом.

<? if ($stickerset_preview): ?>
<p>
	<a href="http://www.maryjane.ru/hudsovet/good/<?= $good_id ?>/stickerset/"><img src="http://www.maryjane.ru<?= $stickerset_preview ?>"></a>
</p>
<? endif; ?>

<? if ($auto_preview): ?>
<p>
	<a href="http://www.maryjane.ru/hudsovet/good/<?= $good_id ?>/auto/"><img src="http://www.maryjane.ru<?= $auto_preview ?>"></a>
</p>
<? endif; ?>