<p>Всего просмотров страниц раздела /dealer/ — <?= $views['all'] ?></p>
<p>Уникальных просмотров страниц раздела /dealer/ — <?= $views['unique'] ?></p> 
<p>Заявок — <?= $requests['contacts'] ?></p>
<p>Скачивание прайс-листов — <?= $requests['pricelist'] ?></p>

<p>--------------------------------------------------------------</p>

<p>Всего звонков — <?= $calls['total'] ?></p>
<p>Из них успешных (больше 6 сек.) — <?= $calls['moreThan6sec'] ?></p>
<p>Всего разговоров больше одной минуты — <?= $calls['moreThan1min'] ?></p> 
<p>Общая продолжительность разговоров — <?= $calls['totalRecordingTime'] ?> мин. (<?= $calls['totalRecordingTime_formated'] ?>)</p> 

<p><a href="http://www.maryjane.ru/admin/index.php?module=dealers_requests&action=report1">http://www.maryjane.ru/admin/index.php?module=dealers_requests&action=report1</a></p>