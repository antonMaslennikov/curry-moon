<p>Перезвоните мне по телефону <a href="<?= mainUrl ?>/admin/?module=dealers_requests&action=contacts&user_id=<?= $request->contact_id ?>"><b><?= $phone ?></b></a></p>

<p>Контакт: <a href="<?= mainUrl ?>/admin/?module=dealers_requests&action=contacts&user_id=<?= $request->contact_id ?>"><b><?= $request->contact_id ?></b></a></p>
<p>Имя: <a href="<?= mainUrl ?>/admin/?module=dealers_requests&action=contacts&user_id=<?= $request->contact_id ?>"><b><?= $name ?></b></a></p>
<p>Email: <a href="<?= mainUrl ?>/admin/?module=dealers_requests&action=contacts&user_id=<?= $request->contact_id ?>"><b><?= $email ?></b></a></p>
<p>Ip: <b><?= $ip ?></b></p>
<p>Последний заказ: <b><a href="http://www.maryjane.ru/index_admin.php?module=orders&id=<?= $order['user_basket_id'] ?>"><?= $order['user_basket_id'] ?></b></a></p>
<p>Задание на дизайн: <b><a href="http://www.maryjane.ru/design/view/<?= $tender['id'] ?>/"><?= $tender['id'] ?></b></a></p>
<p>Отправлено со страницы: <a href="<?= $source ?>"><?= urldecode($source) ?></a></p>
<p>Трэк: <? if ($request->referer): ?><?= urldecode($request->referer) ?><? else: ?>не известно<? endif; ?></p>
<? if ($sergey): ?>
<p style="color:red"><b>Для сергея! со страницы "dealer-дизайн конкурс"</b></p>
<? endif; ?>