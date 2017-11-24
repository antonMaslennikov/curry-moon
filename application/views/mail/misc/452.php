<a href="<?= mainUrl ?>/admin/index.php?module=safe&start=<? echo $arr['today'] ?>">Сейф</a>
<br /><br />
<ul>
    <li>
        Приход по кассе: <a href="<?= mainUrl ?>/admin/index.php?module=safe#<?= $arr['today'] ?>"><b><? echo $arr['inc'] ?></b></a>
        <ul>
            <li>Весь кэш: <?= $arr['money']->cash ?></li>
            <li>Безнал: <?= $arr['money']->other ?></li>
            <li>Наложка: <?= $arr['money']->cashon ?></li>
            <li>Возврат бонусы: <?= $arr['money']->returnBonus ?></li>
            <li>Возврат кэш: <?= $arr['money']->returnCash ?></li>
            <li>Доставка (цена для клиента): <?= $arr['money']->deliverycost ?></li>
            <li>Доставка (наши затраты): <?= $arr['money']->boycost_deliveryboy ?></li>
            <li>Кэш: <?= $arr['money']->pprofit ?></li>
            <li>Остаток в кассе (касса - сейф): <?= $arr['money']->totalTodayProfit ?></li>
        </ul>
    </li>
    <li>Расходы за день: <b><? echo $arr['dec'] ?></b>
        <ul>
        <? foreach ($arr['decs'] AS $d): ?>
            <li><? echo $d['sum'] ?> - <? echo $d['comment'] ?></li>
        <? endforeach; ?>
        </ul>
    </li>
    <? if ($arr['zp']): ?>
    <li>Зарплаты:
        <ul>
        <? foreach ($arr['zp'] AS $d): ?>
            <li><? echo $d['sum'] ?> - <? echo $d['name'] ?> (<? echo $d['comment'] ?>)</li>
        <? endforeach; ?>
        </ul>
    </li>
    <? endif; ?>
    <!--li>Траты на логистику (поездки): <b><? echo $arr['logistic'] ?></b></li>
    <li>Прочие траты: <b><? echo $arr['expense'] ?></b>
        <ul>
        <? foreach ($arr['exps'] AS $e): ?>
            <li><? echo $e['sum'] ?> - <? echo $e['comment'] ?></li>
        <? endforeach; ?>
        </ul>
    </li-->
    <li>Инкасация: <b><? echo $arr['incas'] ?></b></li>
    <li></li>
    <li>Бонусов начислено: <a href="<?= mainUrl ?>/admin/index.php?module=printshop&task=bonuses&start=<? echo $arr['today2'] ?>&end=<? echo $arr['today2'] ?>&wait=1&inc=1"><b><? echo $arr['b_inc_all'] ?></b></a> <a href="<?= mainUrl ?>/admin/index.php?module=printshop&task=bonuses&start=<? echo $arr['today3'] ?>&wait=1&inc=1">за месяц</a></li>
    <li>Бонусов потрачено: <a href="<?= mainUrl ?>/admin/index.php?module=printshop&task=bonuses&start=<? echo $arr['today2'] ?>&end=<? echo $arr['today2'] ?>&wait=1&dec=1"><b><? echo $arr['b_dec_all'] ?></b></a> <a href="<?= mainUrl ?>/admin/index.php?module=printshop&task=bonuses&start=<? echo $arr['today3'] ?>&wait=1&dec=1">за месяц</a></li>
    <li></li>
    <li>Бонусов начислено s4et4ik: <a href="<?= mainUrl ?>/admin/index.php?module=printshop&task=bonuses&start=<? echo $arr['today2'] ?>&end=<? echo $arr['today2'] ?>&wait=1&inc=1&user_id=96976"><b><? echo $arr['b_inc_s4et4ik'] ?></b></a></li>
    <li>Бонусов потрачено s4et4ik: <a href="<?= mainUrl ?>/admin/index.php?module=printshop&task=bonuses&start=<? echo $arr['today2'] ?>&end=<? echo $arr['today2'] ?>&wait=1&dec=1&user_id=96976"><b><? echo $arr['b_dec_s4et4ik'] ?></b></a></li>
    <li></li>
    <li>Кэша начислено: <a href="<?= mainUrl ?>/admin/index.php?module=payments&view=flat&start=<? echo $arr['today2'] ?>&end=<? echo $arr['today2'] ?>&wait=1&inc=1"><b><? echo $arr['cash_0'] ?></b></a></li>
    <li>Кэша выведено:  <a href="<?= mainUrl ?>/admin/index.php?module=payments&view=flat&start=<? echo $arr['today2'] ?>&end=<? echo $arr['today2'] ?>&wait=1&dec=1"><b><? echo $arr['cash_1'] ? $arr['cash_1'] : '-'; ?></b></a></li>
    <li></li>
    <li>Кэша начислено (возвраты): <a href="<?= mainUrl ?>/admin/index.php?module=payments&view=flat&start=<? echo $arr['today2'] ?>&end=<? echo $arr['today2'] ?>&wait=1&inc=1"><b><? echo $arr['cash2_0'] ? $arr['cash2_0'] : '-'; ?></b></a></li>
    <li>Кэша выведено (возвраты):  <a href="<?= mainUrl ?>/admin/index.php?module=payments&view=flat&start=<? echo $arr['today2'] ?>&end=<? echo $arr['today2'] ?>&wait=1&dec=1"><b><? echo $arr['cash2_1'] ? $arr['cash2_1'] : '-'; ?></b></a></li>
    <li></li>
    <li>
        Итого в сейфе: <b><? echo $arr['total'] ?></b>
        <ul>
            <li>На начало периода: <?= $arr['onStart'] ?></li>
            <li>Приход: <?= $arr['inc'] ?></li>
            <li>Расход: <?= $arr['dec'] + $arr['total_zp'] ?></li>
            <li>Инкасация: <?= $arr['incas'] ?></li>
            <li>На конец периода: <?= $arr['total'] ?></li>
        </ul>
    </li>
</ul>