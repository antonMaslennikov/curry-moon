<p>Пользователь <a href="http://www.maryjane.ru/index_admin.php?module=users&task=view&id=<? echo $user_id ?>"><? echo $user_login ?></a> оставил запрос на вывод денежных средств со своего счёта.</p>

Сумма - <? echo $sum ?> руб.<br />
Способ вывода - <? echo $type ?>.<br />

<? if ($visit_date): ?>
	<span style="font-size:18px;color:red">Дата визита: <? echo $visit_date ?></span><br />
<? endif; ?>

<p>	
	<a href="http://www.maryjane.ru/index_admin.php?module=printshop&task=payments">Рассмотреть заявку</a>
</p>

<? if (!$order): ?>
<h4>Данные по контракту:</h4>
<ul>
	<? foreach ($contract AS $c): ?>
		<li><? echo $c['name'] ?>: <? echo $c['val'] ?>. <? echo $c['filled'] ?></li>
	<? endforeach; ?>
</ul>
<? else: ?>
	<h4 style="color:red">Это оплата заказа <a href="www.maryjane.ru/index_admin.php?module=orders&id=<?= $order->id ?>"><?= $order->id ?></a></h4>
<? endif; ?>

<br />

<hr width="100%" size="1" noshade>
Редкое — наша профессия, <br />
©2003-2009  <a href="http://www.maryjane.ru">Maryjane.ru</a><br /><br />

Привет! Для выплаты гонорара, нам надо с тобой заключить договор. <br />

Пришли следующие документы<br />
1. фио, номер серия паспорта, когда выдан, кем выдан, телефон, адрес по прописке<br />
1.1 1. скан паспорта - 1я страница и прописка<br />
2. Номер ИНН, ПФР (№ свидетельства пенс. страхования СНИЛС)<br />
3. Реквизиты, Номер счета в банке и назначание платежа (иногда требуется указать номер договора)<br />