<h4>Личный счёт</h4>
<ol>
    <? foreach ($ls AS $u): ?>
    <li><? if ($u['user_partner_status'] > 0): ?>П<? endif; ?> <? if ($u['user_goods'] > 0): ?>Д<? endif; ?> <a href="<?= mainUrl ?>/admin/index.php?module=users&action=view&id=<?= $u['user_id'] ?>"><?= $u['user_login'] ?></a> оплатил <?= abs($u['price']) ?> с личного счёта в заказе <a href="<?= mainUrl ?>/admin/index.php?module=orders&id=<?= $u['user_basket_id'] ?>"><?= $u['user_basket_id'] ?></a> <?= datefromdb2textdate($u['add_date'], 1) ?></li>
    <? endforeach; ?>
</ol>

<h4>Бонусы</h4>
<ol>
    <? foreach ($bonuses AS $u): ?>
    <li><? if ($u['user_partner_status'] > 0): ?>П<? endif; ?> <? if ($u['user_goods'] > 0): ?>Д<? endif; ?> <a href="<?= mainUrl ?>/admin/index.php?module=users&action=view&id=<?= $u['user_id'] ?>"><?= $u['user_login'] ?></a> оплатил <?= abs($u['price']) ?> с бонусного счёта в заказе <a href="<?= mainUrl ?>/admin/index.php?module=orders&id=<?= $u['user_basket_id'] ?>"><?= $u['user_basket_id'] ?></a> <?= datefromdb2textdate($u['add_date'], 1) ?></li>
    <? endforeach; ?>
</ol>