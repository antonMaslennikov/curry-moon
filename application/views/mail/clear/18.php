<?php
/* @var feedback $feedback*/
use admin\application\models\feedback;
?>
<p>Здравствуйте, <?=$feedback->feedback_name?></p>

<p>На ваш Вопрос <blockquote>"<?=$feedback->feedback_text?>"</blockquote> поступил ответ менеджера:</p>

<p><?=$feedback->feedback_reply?></p>

<p>Спасибо.</p>