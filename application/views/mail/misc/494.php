<p>Вы оставили запрос на вывод денежных средств со своего счёта.</p>

<p>
Сумма - <?= $sum ?> руб.<br />
Способ вывода - <?= $type ?>.
</p>

<p><b>От лица всей команды maryjane.ru</b><br />

Уважаемый Автор, мы рады, что вы являетесь частью, а может только присоединились к нашей команде maryjane.ru. 
10-й год любой желающий может опубликовать свои принты и продавать их.</p>

<p>
Для выплаты гонорара, необходимо заключить договор.<br />
Договор лицензии, оставляет права на работу у Вас, позволяя нам делать копии. Договор может быть расторгнут Вами в любой момент.<br />
Договор отчуждения, заполняется в случае победы в конкурсе и передает все права на работу «ООО МЭРИДЖЕЙН»<br />
Заполните все поля, на странице <a href="http://www.maryjane.ru/contract/">заключения договора</a>.<br /> После чего пришлите 2 копии договора и акты, с Вашей подписью, по адресу "105082 Россия, г. Москва ул. Малая Почтовая д.12 стр.3 «ООО МЭРИДЖЕЙН»".
<br />
Средства будут перечислены после получения оригинала договоров, для ускорения получения средств отправляйте срочным письмом.
</p>

<p><em>* все данные заполняются на русском языке</em></p>

<h4>Какие поля осталось заполнить:</h4>
<ul>
<? foreach ($contract AS $c): ?>
    <li><?= $c['name'] ?>: <?= $c['filled'] ?></li>
<? endforeach; ?>
</ul>


<hr width="100%" size="1" noshade>
Редкое — наша профессия, <br />
©2003-2013  <a href="http://www.maryjane.ru/">Maryjane.ru</a>