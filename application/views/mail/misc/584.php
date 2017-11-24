<p>
    <h4>
        Переходов по партнерке всего <?= $total_clicks ?>, 
        Регистраций <?= $total_regs ?> шт. (CTR - <?= round($total_regs * 100 / $total_clicks, 1) ?>%), 
        заказов <?= $total_orders ?> шт. (CTR - <?= round($total_orders * 100 / $total_clicks, 1) ?> %), 
        возвратов <?= $total_returns ?> шт. (CTR - <?= round($total_returns * 100 / $total_orders, 1) ?>%)
    </h4>
</p>

<h4>Клики:</h4>

<? if (count($clicks) > 0): ?>

	<table>
	    <tr>
	        <th>Пользователь</th>
	        <th>Переходы</th>
	        <th>Заказов</th>
	    </tr>
		<? foreach ($clicks as $uid => $u): ?>
		<tr>
		    <td>
			    <a href="http://www.maryjane.ru/index_admin.php?module=users&action=view&id=<?= $uid  ?>"><?= $u['user_login'] ?></a>
			</td>
			<td>
			    <?= $u['clicks'] ?>
			</td>
			<td>
                <?= $u['orders'] ?>
            </td>
			<!-- ul -->
				<? /*foreach ($u['clicks'] as $k => $c): ?>
				<li>
					id: <a href="http://www.maryjane.ru/index_admin.php?module=informers&action=promo&id=<?= $c['informer_id'] ?>"><?= $c['informer_id'] ?></a>
					<? if (in_array($c['informer_id'], $partners_inf)): ?><b style="color:red">Партнёр</b><? endif; ?>, 
					кликов: <?= $c['clicks'] ?>,
					<? if ($c['informer_comment']): ?>
						(<?= $c['informer_comment'] ?>)
					<? endif; ?>
					<a href="http://www.maryjane.ru/index_admin.php?module=informers&userid=<?= $c['user_id']  ?>">рефереры</a>,
					<? if ($c['informer_created_time'] != '0000-00-00 00:00:00'): ?>
						создан - <?= $c['created_time'] ?>
					<? endif; ?>
				</li>
				<? endforeach; */ ?>
			<!-- /ul -->
		</tr>
		<? endforeach; ?>
	</table>

	<p>Итого: <b><?= $total_clicks ?> кликов</b></p>

<? else: ?>
	
	<p>отсутствуют</p>
	
<? endif; ?>

<h4>Заказы:</h4>

<? if (count($orders) > 0): ?>

	<ul>
		<? foreach ($orders as $k => $l): ?>
		<li>
			id: <a href="http://www.maryjane.ru/index_admin.php?module=informers&action=promo&id=<?= $l['informer_id'] ?>"><?= $l['informer_id']?></a>
			<? if (in_array($c['informer_id'], $partners_inf)): ?><b style="color:red">Партнёр</b><? endif; ?>, 
			хозяин: <a href="http://www.maryjane.ru/index_admin.php?module=users&action=view&id=<?= $l['user_id']  ?>"><?= $l['user_login'] ?></a>,
			<? if ($l['informer_comment']): ?>
				(<?= $l['informer_comment'] ?>)
			<? endif; ?>
			<a href="http://www.maryjane.ru/index_admin.php?module=informers&userid=<?= $l['user_id']  ?>">рефереры</a>
			<? if ($l['informer_created_time'] != '0000-00-00 00:00:00'): ?>
				создан - <?= $l['created_time'] ?>
			<? endif; ?>
			
			<ul>
				<? foreach ($l['orders'] as $o): ?>
				<li>
					<a href="http://www.maryjane.ru/index_admin.php?module=orders&id=<?= $o['user_basket_id']  ?>"><?= $o['user_basket_id']?></a>, (<?= basket::$orderStatus[$o['user_basket_status']] ?>, <?= $o[$o['user_basket_status']] ? $o[$o['user_basket_status']] : $o['ordered'] ?>) 
				</li>
				<? endforeach; ?>
			</ul>
		</li>
		<? endforeach; ?>
	</ul>

<? else: ?>
	
	<p>отсутствуют</p>
	
<? endif; ?>

<h4>Регистрации:</h4>

<? if (count($regs) > 0): ?>

	<ul>
		<? foreach ($regs as $k => $reg): ?>
		<li>
			id: <a href="http://www.maryjane.ru/index_admin.php?module=informers&action=promo&id=<?= $reg['informer_id']  ?>"><?= $reg['informer_id']?></a>, хозяин: <a href="http://www.maryjane.ru/index_admin.php?module=users&action=view&id=<?= $reg['user_id']  ?>"><?= $reg['user_login'] ?></a>, регистраций: <?= $reg['registrations'] ?>
			<? if ($reg['informer_comment']): ?>
				(<?= $reg['informer_comment'] ?>)
			<? endif; ?>
			,
			<a href="http://www.maryjane.ru/index_admin.php?module=informers&userid=<?= $reg['user_id']  ?>">рефереры</a>
			<? if ($reg['informer_created_time'] != '0000-00-00 00:00:00'): ?>
				создан - <?= $reg['created_time'] ?>
			<? endif; ?>
		</li>
		<? endforeach; ?>
	</ul>

<? else: ?>
	
	<p>отсутствуют</p>
	
<? endif; ?>

<h4>Промо-коды:</h4>

<? if (count($codes) > 0): ?>

	<ul>
		<? foreach ($codes as $k => $l): ?>
		<li>
			<a href="http://www.maryjane.ru/index_admin.php?module=certifications&action=view&id=<?= $l['certification_id']  ?>"><?= $l['certification_password']?></a> (<?= $l['certification_value'] ?>%)
			
			<ul>
				<? foreach ($l['orders'] as $o): ?>
				<li>
					<a href="http://www.maryjane.ru/index_admin.php?module=orders&id=<?= $o['user_basket_id']  ?>"><?= $o['user_basket_id']?></a>, (<?= basket::$orderStatus[$o['user_basket_status']] ?>), реферер: <?= $o['referer'] ?> 
				</li>
				<? endforeach; ?>
			</ul>
		</li>
		<? endforeach; ?>
	</ul>


<? else: ?>
	
	<p>отсутствуют</p>
	
<? endif; ?>


<h4>Регистрации с главной:</h4>

<p>Регистраций <b><?= $givegifts['c'] ?></b> шт., заказов <b><?= $givegifts_orders['c'] ?></b> шт., CTR - <b><?= round($givegifts_orders['c'] * 100 / $givegifts['c'], 1) ?> %</b></p>

<? /*if (count($givegifts) > 0): ?>

	<ul>
		<? foreach ($givegifts as $u): ?>
		<li>
			<a href="http://www.maryjane.ru/index_admin.php?module=users&action=view&id=<?= $u['user_id']  ?>"><?= $u['user_login'] ?></a> <?= $u['user_register_date'] ?>
		</li>
		<? endforeach; ?>
	</ul>

<? else: ?>
	
	<p>отсутствуют</p>
	
<? endif; */ ?>

<!--h4>Заказы после регистрации с главной:</h4-->

<? /*if (count($givegifts_orders) > 0): ?>

	<ul>
		<? foreach ($givegifts_orders as $o): ?>
		<li>
			<a href="http://www.maryjane.ru/index_admin.php?module=orders&id=<?= $o['user_basket_id'] ?>"><?= $o['user_login'] ?></a> на сумму <?= $o['sum'] ?> руб. от <?= $o['user_basket_date'] ?> <a href="http://www.maryjane.ru/index_admin.php?module=users&action=view&id=<?= $o['user_id']  ?>"><?= $o['user_login'] ?></a>
		</li>
		<? endforeach; ?>
	</ul>

<? else: ?>
	
	<p>отсутствуют</p>
	
<? endif; */ ?>

<p><a href="http://www.maryjane.ru/index_admin.php?module=informers&action=promo">КОНВЕРСИЯ</a></p>