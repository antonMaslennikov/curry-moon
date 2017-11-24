<p>За день (<?= $date ?>) поступило предзаказов:</p>

<ol>
    <? foreach($preorders AS $p): ?>
    <li>
        <? if ($p['user_id'] > 0): ?>
        <a href="<?= mainUrl ?>/admin/?module=users&action=view&id=<?= $p['user_id'] ?>">
        <? endif; ?>
        <?= $p['user_email'] ?>
        <? if ($p['user_id'] > 0): ?>
        </a>
        <? endif; ?>
        <br />
        <b>Дизайн:</b> <a href="<?= mainUrl ?>/admin/?module=goods&action=view&id=<?= $p['good_id'] ?>"><?= $p['good_name'] ?></a><br />
        <b>Носитель:</b> <?= $p['style_name'] ?><br />
        <b>Размер:</b> <?= $p['size']['size_name'] ?><br />
        <b>Время:</b> <?= $p['time'] ?><br />
    </li>
    <? endforeach; ?>
</ol>