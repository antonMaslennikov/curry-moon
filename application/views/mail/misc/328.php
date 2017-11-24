<p>Господа, вашему вниманию! На <a href="%quickLoginLink%&next=/hudsovet/">Худсовет</a> прислали новые работы</p>

<table>
<? foreach($goods AS $g): ?>
<tr>
<td width="240"><a href="%quickLoginLink%&next=/hudsovet/good/<?= $g['good_id'] ?>/"><img src="<?= $g['picture_path'] ?>"></a></td>
<td valign="top">
<p>#<?= $g['good_id'] ?> "<a href="%quickLoginLink%&next=/hudsovet/good/<?= $g['good_id'] ?>/"><?= $g['good_name'] ?></a>".</p>
</td>
</tr>
<? endforeach; ?>
</table>

<p><a href="%quickLoginLink%&next=/hudsovet/">Оцените их тут пожалуйста</a>.</p>