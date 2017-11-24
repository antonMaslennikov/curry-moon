<p>Через неделю сдача следующих смет:</p>
<ol>
    <? foreach($estimates AS $e): ?>
    <li><a href="<?= mainUrl ?>/admin/?module=estimate&action=view&id=<?= $e['user_basket_id'] ?>"><?= $e['user_basket_id'] ?>: <?= $e['name'] ?></a></li>
    <? endforeach; ?>
</ol>