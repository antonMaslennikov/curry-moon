<p><b>За <?= $date ?>, <?= $day ?> <?= date('Y') ?>г.</b></p>

<p>
    <b>1. Заказов розница: </b><?= $total ?>, на сумму <?= $total_sum ?> руб,<br /> 
    <b>Звонков: </b><?= $call_in_rozn ?>/<?= $call_out_rozn ?> (входящие/исходящие)
</p>
<p>
    <b>2. Оптовые сметы: </b><?= $estimates ?>, на сумму <?= $estimates_sum ?> руб<br />
    <b>Звонков: </b><?= $call_in_opt ?>/<?= $call_out_opt ?> (входящие/исходящие)
    <b>Текстовых запросов: </b><?= $requests_count ?>/<?= $requests_count_processed ?> (принято/обработано)<br />
</p>

<p>--------------------------------------------------------------------------</p>

За <?= $yestoday ?> всего оформлено заказов: <?= $total ?>

<p>Процент заказов из Москвы: <b><?= $moscow_percent ?></b>%</p>
<h4>Заказы с Maryjane.ru: итого <?= $mjtotal ?></h4>

<ul>
	<? foreach ($orders AS $o): ?>
	<li>
		<a href="http://www.maryjane.ru/admin/index.php?module=orders&id=<?= $o['user_basket_id'] ?>"><?= $o['user_basket_id'] ?></a> - 
		<?= $o['city_name'] ?> 
		<? if ($o['user_city']): ?>(<?= $o['user_city'] ?>)<? endif; ?>. 
		<? if ($o['user_basket_source']): ?>(источник <?= $o['user_basket_source'] ?>)<? endif; ?>. 
		<? if ($o['informer_action_comment']): ?><a href="http://www.maryjane.ru/admin/index.php?module=informers&userid=<?= $o['user_id'] ?>"><?= $o['informer_action_comment']?></a><? endif; ?>
	</li>
	<? endforeach; ?>
</ul>

<b>Заказы s4et4ik</b>

<ul>
    <? if (count($orders_s4et4ik) > 0): ?>
	<? foreach ($orders_s4et4ik AS $o): ?>
	<li>
		<a href="http://www.maryjane.ru/admin/index.php?module=orders&id=<?= $o['user_basket_id'] ?>"><?= $o['user_basket_id'] ?></a> - 
		<?= $o['city_name'] ?> 
		<? if ($o['user_city']): ?>(<?= $o['user_city'] ?>)<? endif; ?>. 
		<? if ($o['informer_action_comment']): ?><a href="http://www.maryjane.ru/admin/index.php?module=informers&userid=<?= $o['user_id'] ?>"><?= $o['informer_action_comment']?></a><? endif; ?>
	</li>
	<? endforeach; ?>
	<? endif; ?>
</ul>


<b>Заказы Skyzelik</b>

<ul>
    <? if (count($orders_Skyzelik) > 0): ?>
    <? foreach ($orders_Skyzelik AS $o): ?>
    <li>
        <a href="http://www.maryjane.ru/admin/index.php?module=orders&id=<?= $o['user_basket_id'] ?>"><?= $o['user_basket_id'] ?></a> - 
        <?= $o['city_name'] ?> 
        <? if ($o['user_city']): ?>(<?= $o['user_city'] ?>)<? endif; ?>. 
        <? if ($o['informer_action_comment']): ?><a href="http://www.maryjane.ru/admin/index.php?module=informers&userid=<?= $o['user_id'] ?>"><?= $o['informer_action_comment']?></a><? endif; ?>
    </li>
    <? endforeach; ?>
    <? endif; ?>
</ul>

<h4>Заказы с Allskins.ru: итого <?= $astotal ?></h4>

<ul>
    <? if (count($ordersA) > 0): ?>
	<? foreach ($ordersA AS $o): ?>
	<li>
		<a href="http://www.maryjane.ru/admin/index.php?module=orders&id=<?= $o['user_basket_id'] ?>"><?= $o['user_basket_id'] ?></a> - 
		<?= $o['city_name'] ?> 
		<? if ($o['user_city']): ?>(<?= $o['user_city'] ?>)<? endif; ?>. 
		<? if ($o['informer_action_comment']): ?><a href="http://www.maryjane.ru/admin/index.php?module=informers&userid=<?= $o['user_id'] ?>"><?= $o['informer_action_comment']?></a><? endif; ?>
	</li>
	<? endforeach; ?>
	<? endif; ?>
</ul>

<h4>Заказы из админки: итого <?= $atotal ?></h4>

<ul>
    <? if (count($aorders) > 0): ?>
	<? foreach ($aorders AS $o): ?>
	<li><a href="http://www.maryjane.ru/admin/index.php?module=orders&id=<?= $o['user_basket_id'] ?>"><?= $o['user_basket_id'] ?></a></li>
	<? endforeach; ?>
	<? endif; ?>
</ul>

<h4>Задания на поездку:</h4>

<ul>
    <? if (count($deliveryOrders) > 0): ?>
    <? foreach ($deliveryOrders AS $o): ?>
    <li><a href="http://www.maryjane.ru/admin/index.php?module=orders&id=<?= $o['user_basket_id'] ?>"><?= $o['user_basket_id'] ?></a> <?= $o['result'] ?> (<?= $o['user_basket_delivery_boy_cost'] ?> руб.)</li>
    <? endforeach; ?>
    <? endif; ?>
</ul>

<h4>Дилерские запросы: </h4>

<p><a href="www.maryjane.ru/admin/index.php?module=dealers_requests&pricelist=1">Все скачанные прайс-листы</a></p>
<p><a href="www.maryjane.ru/admin/index.php?module=dealers_requests&call=1">Все входящие звонки</a></p>

<h4>Текстовые запросы:</h4>

<ul>
    <? if (count($requests) > 0): ?>
	<? foreach ($requests AS $o): ?>
	<li><a href="http://www.maryjane.ru/admin/index.php?module=dealers_requests&action=contacts&user_id=<?= $o['contact_id'] ?>"><?= $o['id'] ?>: <?= $o['email'] ?></a> (источник: <?= $o['referer'] ?>)</li>
	<? endforeach; ?>
	<? endif; ?>
</ul>

<h4>Звонки:</h4>

<ul>
    <? if (count($calls) > 0): ?>
    <? foreach ($calls AS $phone => $c): ?>
    <li><a href="http://www.maryjane.ru/admin/index.php?module=dealers_requests&action=contacts&user_id=<?= $c['contact_id'] ?>"><?= $c['phone'] ?></a> на номер <?= $c['referer_phone'] ?> <? if ($c['count'] > 1): ?> - <?= $c['count'] ?> (раз)<? endif; ?></li>
    <? endforeach; ?>
    <? endif; ?>
</ul>

<h4>Скачаны прайс-листы:</h4>

<ul>
    <? if (count($pricelists) > 0): ?>
    <? foreach ($pricelists AS $pl): ?>
    <li><a href="http://www.maryjane.ru/admin/index.php?module=dealers_requests&action=contacts&user_id=<?= $pl['contact_id'] ?>"><?= $pl['email'] ?></a> <?= $pl['text'] ?> <? if ($pl['referer']): ?>(<?= $pl['referer'] ?>)<? endif; ?></li>
    <? endforeach; ?>
    <? endif; ?>
</ul>

<br />
Analitycs<br/>
<a href="https://www.google.com/analytics/web/?#report/conversions-ecommerce-overview/a2491544w4519370p4648926/%3F_u.date00%3D20131210%26_u.date01%3D20131210%26overview-graphOptions.compareConcept%3Danalytics.transactions/">Отчет по конверсиям (сменить дату на <?= $yestoday ?>)</a><br/>
<a href="https://www.google.com/analytics/web/?#report/content-event-events/a2491544w4519370p4648926/%3F_u.date00%3D20140609%26_u.date01%3D20140609%26explorer-table.plotKeys%3D%5B%5D%26_r.drilldown%3Danalytics.eventCategory%3AOrder.v3/">Отчет по нажатию на кнопку подтвердить заказ (сменить дату на <?= $yestoday ?>)</a>


<p>-----------------------------------------------------------------------------------------------------------------------------------------</p>

<p>
    <span style="color:#ff0000">красным</span> - если найдено 0 и по этому запросу искали более одного раза,<br>
    <span style="color:#AE9205">оранжевым</span> - если найдено 0 иищут первый раз
</p>

<table style="border-collapse: collapse">
<tr>
    <th style="border:1px solid #ccc"></th>
    <th style="border:1px solid #ccc">найдено</th>
    <th style="border:1px solid #ccc">хитов</th>
    <th style="border:1px solid #ccc">Пользователь</th>
    <th style="border:1px solid #ccc">ip</th>
    <th style="border:1px solid #ccc"></th>
    <th style="border:1px solid #ccc"></th>
    <th style="border:1px solid #ccc"></th>
</tr>
<? if (count($new) > 0): ?>
    <tr>
        <th colspan="8" align="left" style="border:1px solid #ccc"><h4 style="text-align: left">Новые запросы за сегодня</h4></th>
    </tr>
    <? foreach ($new as $p): ?>
    <tr>
        <td style="border:1px solid #ccc;color:#<?= $p['color'] ?>"><a style="color:#<?= $p['color'] ?>" href="http://www.maryjane.ru/search/?q=<?= $p['phrase_encoded'] ?>"><?= $p['phrase'] ?></a></td>
        <td style="border:1px solid #ccc"><?= $p['founded'] ?></td>
        <td style="border:1px solid #ccc"><?= $p['hits'] ?></td>
        <td style="border:1px solid #ccc"><? if ($p['user_id'] > 0): ?><a href="<?= mainUrl ?>/admin/index.php?module=users&action=view&id=<?= $p['user_id'] ?>"><?= $p['user_id'] ?></a><? endif; ?></td>
        <td style="border:1px solid #ccc"><?= $p['user_ip'] ?></td>
        <td style="border:1px solid #ccc"><a href="http://www.maryjane.ru/admin/index.php?module=admin_search&id=<?= $p['id'] ?>"><em>добавить синоним</em></a></td>
        <td style="border:1px solid #ccc"><a href="http://yandex.ru/search/?text=<?= $p['phrase'] ?>&lr=41&site=www.maryjane.ru&to_date_full=<?= date("d.m.Y") ?>">В Яндексе</a></td>
        <td style="border:1px solid #ccc"><a href="https://www.google.ru/search?as_q=<?= $p['phrase'] ?>&as_qdr=all&as_sitesearch=http://www.maryjane.ru&as_occt=any&safe=images">В Google</a></td>
    </tr>
    <? endforeach; ?>
<? endif; ?>

<? if (count($sq) > 0): ?>
    <tr>
        <th colspan="8" align="left" style="border:1px solid #ccc"><h4 style="text-align: left">Многосоставные запросы</h4></th>
    </tr>
    <? foreach ($sq as $p): ?>
    <tr>
        <td style="border:1px solid #ccc;color:#<?= $p['color'] ?>"><a style="color:#<?= $p['color'] ?>" href="http://www.maryjane.ru/search/?q=<?= $p['phrase_encoded'] ?>"><?= $p['phrase'] ?></a></td>
        <td style="border:1px solid #ccc"><?= $p['founded'] ?></td>
        <td style="border:1px solid #ccc"><?= $p['hits'] ?></td>
        <td style="border:1px solid #ccc"><? if ($p['user_id']): ?><a href="<?= mainUrl ?>/admin/index.php?module=users&action=view&id=<?= $p['user_id'] ?>"><?= $p['user_id'] ?></a><? endif; ?></td>
        <td style="border:1px solid #ccc"><?= $p['user_ip'] ?></td>
        <td style="border:1px solid #ccc"><a href="http://www.maryjane.ru/admin/index.php?module=admin_search&id=<?= $p['id'] ?>"><em>добавить синоним</em></a></td>
        <td style="border:1px solid #ccc"><a href="http://yandex.ru/search/?text=<?= $p['phrase'] ?>&lr=41&site=www.maryjane.ru&to_date_full=<?= date("d.m.Y") ?>">В Яндексе</a></td>
        <td style="border:1px solid #ccc"><a href="https://www.google.ru/search?as_q=<?= $p['phrase'] ?>&as_qdr=all&as_sitesearch=http://www.maryjane.ru&as_occt=any&safe=images">В Google</a></td>
    </tr>
    <? endforeach; ?>
<? endif; ?>

<? if (count($q) > 0): ?>
    <tr>
        <th colspan="8" align="left" style="border:1px solid #ccc"><h4 style="text-align: left">Остальные запросы</h4></th>
    </tr>
    <? foreach ($q as $p): ?>
    <tr>
        <td style="border:1px solid #ccc;color:#<?= $p['color'] ?>"><a style="color:#<?= $p['color'] ?>" href="http://www.maryjane.ru/search/?q=<?= $p['phrase_encoded'] ?>"><?= $p['phrase'] ?></a></td>
        <td style="border:1px solid #ccc"><?= $p['founded'] ?></td>
        <td style="border:1px solid #ccc"><?= $p['hits'] ?></td>
        <td style="border:1px solid #ccc"><? if ($p['user_id']): ?><a href="<?= mainUrl ?>/admin/index.php?module=users&action=view&id=<?= $p['user_id'] ?>"><?= $p['user_id'] ?></a><? endif; ?></td>
        <td style="border:1px solid #ccc"><?= $p['user_ip'] ?></td><td><a href="http://www.maryjane.ru/admin/index.php?module=admin_search&id=<?= $p['id'] ?>"><em>добавить синоним</em></a></td>
        <td style="border:1px solid #ccc"><a href="http://yandex.ru/search/?text=<?= $p['phrase'] ?>&lr=41&site=www.maryjane.ru&to_date_full=<?= date("d.m.Y") ?>">В Яндексе</a></td>
        <td style="border:1px solid #ccc"><a href="https://www.google.ru/search?as_q=<?= $p['phrase'] ?>&as_qdr=all&as_sitesearch=http://www.maryjane.ru&as_occt=any&safe=images">В Google</a></td>
    </tr>
    <? endforeach; ?>
<? endif; ?>
</table>