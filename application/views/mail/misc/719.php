<p>
	Отменена оптала счёта №<?= $doc->number ?> 
	<a href="http://www.maryjane.ru/index_admin.php?module=dealer&action=documents&filter[type]=1&filter[nonpayed]=on&filter[doc_id]=<?= $doc->id ?>"><? if ($doc->name): ?><?= $doc->name ?><? else: ?><?= $doc->id ?><? endif; ?> <? if ($doc->estimate_name): ?>(<?= $doc->estimate_name ?>)<? endif; ?></a>
	на сумму <?= $doc->sum ?> руб. 
</p>

<? if ($doc->user->meta->{'rekv-name'}): ?>
<p>
	организация "<?= $doc->user->meta->{'rekv-name'} ?>"
</p>
<? endif; ?>

