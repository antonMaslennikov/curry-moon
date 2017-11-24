<ul>
    <? foreach ($styles AS $s): ?>
    <li><a href="<?= mainUrl ?>/admin/?module=stock&action=view&style_id=<?= $s['style_id'] ?>"><?= $s['style_name'] ?></a> - размер <?= $s['size_name'] ?></li>
    <? endforeach; ?>
</ul>