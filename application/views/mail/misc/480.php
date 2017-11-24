<p><b>Траты</b>: всего <?= $total_d ?></p>

<ul>
<? foreach ($decs AS $d): ?>
<li><a href="http://www.maryjane.ru/admin/index.php?start=<?= $d['date'] ?>&end=<?= $d['date'] ?>&module=safe"><?= $d['date'] ?></a>: <?= $d['sum'] ?>р. <?= $d['category'] ?> (<?= $d['comment'] ?>)</li>
<? endforeach; ?>
</ul>

<p><b>Инкасации</b>: всего <?= $total_i ?></p>

<ul>
<? foreach ($incas AS $i): ?>
<li><a href="http://www.maryjane.ru/admin/index.php?start=<?= $i['date'] ?>&end=<?= $i['date'] ?>&module=safe"><?= $i['date'] ?></a>: <?= $i['sum'] ?>р.</li>
<? endforeach; ?>
</ul>