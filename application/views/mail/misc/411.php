<h4>Печатники:</h4>
<ul>
<? foreach ($printer AS $p): ?>
	<li>
		<b><? 
		  if ($p['type'] == 'wear') echo '<a href="http://www.maryjane.ru/admin/?module=printturn">Одежда</a>'; 
		  if ($p['type'] == 'gadget') echo '<a href="http://www.maryjane.ru/admin/?module=printturn&allskins=true">Наклейки</a>'; 
		  if ($p['type'] == 'cases') echo '<a href="http://www.maryjane.ru/admin/?module=printturn&allskins=true">Чехлы</a>'; 
        ?></b>:
        <ul>
    		<li>Печатник <?= $p['user_login'] ?></li> 
    		<li>напечатано - <?= $p['printed'] ?></li>
    		<li>брак - <?= $p['brak'] ?></li>
    		<li>скорость - <?= $p['speed'] ?></li> 
    		<li>в очереди ещё - <?= $p['notprinted'] ?> (Печатать ещё <?= $p['printPrognoz'] ?> ч.)</li>
    		<li>первая добавленная в очередь позиция за сегодня - <?= $p['firstAddedToday'] ?></li>
		</ul>
	</li>
<? endforeach; ?>
</ul>