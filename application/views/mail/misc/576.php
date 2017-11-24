<p>Добавились новые топ-теги:</p>

<p>
	<ul>
	<? foreach($new_top_tags AS $ntt): ?>
	
	<li><img src="<?= $ntt['img']; ?>" /> <?= $ntt['name']; ?> (<?= $ntt['count']; ?>)</li>
	
	<? endforeach; ?>
	</ul>
</p>

<p><a href="http://www.maryjane.ru/tag/">все теги</a></p>
