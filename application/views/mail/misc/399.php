<p><b>cookies</b>:<br /> <?= $cookie ?></p>

<p><b>ip:</b> <?= $ip ?></p>

<p><b>_SERVER:</b></p>
<? foreach ($_SERVER as $key => $value): ?>
  [<?= $key ?>] => <?= $value ?><br />
<? endforeach; ?>