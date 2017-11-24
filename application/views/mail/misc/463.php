<ul>
	<? foreach($comments AS $l): ?>
		<li>
			<p><b>http://www.maryjane.ru<?= $l['link'] ?></b></p>
			
			<table>
			<? foreach($l['comments'] AS $c): ?>
			<tr>
			    <td></td>
			    <td>
			        <em><b><?= $c['comment_date'] ?></b></em> <?= $c['user_login'] ?>:<br />
			        <a href="http://www.maryjane.ru<?= $c['link'] ?>#comment<?= $c['comment_id'] ?>" style="text-decoration: none"><?= $c['comment_text'] ?></a><br /><br /></td>
			   	</td>
			</tr>
			<? endforeach; ?>
			</table>
		</li>
	<? endforeach; ?>
</ul>