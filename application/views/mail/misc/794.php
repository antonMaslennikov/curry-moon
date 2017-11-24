<h4>Обновлены следующие партнёры:</h4>

<ul>
    <? foreach ($updated AS $u): ?>
        <li><a href="<?= mainUrl ?>/admin/index.php?module=users&action=view&id=<?= $u['uid'] ?>"><?= $u['login'] ?></a> &mdash; <?= $u['sum'] ?> руб.</li>
    <? endforeach; ?>
</ul>

<p>
    <a href="http://www.maryjane.ru/promo/partners/?Month=<?= $month ?>&Year=<?= $year ?>">Статистика по правтнёрам за месяц</a>
</p>