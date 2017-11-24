<? if (count($emails) > 0): ?>

    <ol>
    <? foreach ($emails as $k => $v): ?> 
        <li><?= $v['user_email'] ?></li>    
    <? endforeach; ?>
    </ol>

<? else: ?>

    <p>На этой неделе новых подписок не было</p>

<? endif; ?>