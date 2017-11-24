<p>За неделю 585ый шаблон "ответ помощника выбора способа печати" был отправлен <?= count($mails) ?> раз(а).</p>

<ul>
    <? foreach ($mails AS $m): ?>
    <li><?= $m['mail_message_email'] ?> <em><?= $m['time'] ?></em></li>
    <? endforeach; ?>
</ul>