<p><b>Период: <?= $start ?> &ndash; <?= $end ?></b></p>

<table>
    <tr>
        <th style="padding:5px;border:1px solid #ccc;background: #F7F7F7">&nbsp;</th>
        <? foreach ($printers AS $kp => $p): ?>
        <th style="padding:5px;border:1px solid #ccc;background: #F7F7F7" colspan="2">
            <?= $p['user_login'] ?>
        </th>
        <? endforeach; ?>
        <th style="padding:5px;border:1px solid #ccc;background: #F7F7F7" rowspan="3">Ещё  в очереди</th>
    </tr>
     <tr>
        <th style="padding:5px;border:1px solid #ccc;background: #F7F7F7">Время работы</th>
        <? foreach ($printers AS $kp => $p): ?>
        <th colspan="2" style="padding:5px;border:1px solid #ccc;background: #F7F7F7">
            <? if ($p['work_time']): ?>
            <?= $p['work_time'] ?> м.<br />
            <?= $p['start_time'] ?> &mdash; <?= $p['end_time'] ?>
            <? else: ?>
            &nbsp;
            <? endif; ?>
        </th>
        <? endforeach; ?>
    </tr>
    <tr>
        <th style="padding:5px;border:1px solid #ccc;background: #F7F7F7">&nbsp;</th>
        <? foreach ($printers AS $kp => $p): ?>
        <th style="padding:5px;border:1px solid #ccc;background: #F7F7F7">напечатано</th>
        <th style="padding:5px;border:1px solid #ccc;background: #F7F7F7">брак</th>
        <? endforeach; ?>
    </tr>
    <tr>
        <th style="padding:5px;border:1px solid #ccc;background: #F7F7F7">Итого</th>
        <? foreach ($printers AS $kp => $p): ?>
        <th style="padding:5px;border:1px solid #ccc;background: #F7F7F7">
            <?= $p['total_q'] ?>
        </th>
        <th style="padding:5px;border:1px solid #ccc;background: #F7F7F7">
            <?= $p['total_bad'] ?>
        </th>
        <? endforeach; ?>
        <th style="padding:5px;border:1px solid #ccc;background: #F7F7F7"><?= $inTurn ?></th>
    </tr>
    <? foreach ($categorys AS $k => $c): ?>
    <tr>
        <td style="padding:5px;border:1px solid #ccc">
            <?= $c['title']?>
        </td>
        <? foreach ($c['printers'] AS $kp => $p): ?>
        <td style="padding:5px;border:1px solid #ccc;text-align: center">
            <?= $p['q'] ?>
        </td>
        <td style="padding:5px;border:1px solid #ccc;text-align: center">
            <a href="http://www.maryjane.ru/admin/?module=printturn&action=bad&start=<?= $start_date ?>&end=<?= $end_date ?>"><?= $p['bad'] ?></a>
        </td>
        <? endforeach; ?>
        <td style="padding:5px;border:1px solid #ccc;text-align: center"><?= $c['inTurn'] ?></td>
    </tr>
    <? endforeach; ?>
    <tr>
        <th style="padding:5px;border:1px solid #ccc;background: #F7F7F7">Итого</th>
        <? foreach ($printers AS $kp => $p): ?>
        <th style="padding:5px;border:1px solid #ccc;background: #F7F7F7">
            <?= $p['total_q'] ?>
        </th>
        <th style="padding:5px;border:1px solid #ccc;background: #F7F7F7">
            <?= $p['total_bad'] ?>
        </th>
        <? endforeach; ?>
        <th style="padding:5px;border:1px solid #ccc;background: #F7F7F7"><?= $inTurn ?></th>
    </tr>
</table>

<p><a href="http://www.maryjane.ru/admin/index.php?module=printturn&action=report1">http://www.maryjane.ru/admin/index.php?module=printturn&action=report1</a></p>