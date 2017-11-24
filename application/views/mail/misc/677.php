<? if ($doc): ?>
	<p>Счёт №<?= $doc->number ?> 
		"
		<? if ($user == 63250): ?>
		    <? if ($doc->direction == 1): ?>
		        <a href="http://www.maryjane.ru/index_admin.php?module=documents&filter[type]=1&filter[doc_id]=<?= $doc->id ?>"><? if ($doc->name): ?><?= $doc->name ?><? else: ?><?= $doc->id ?><? endif; ?> <? if ($doc->estimate_name): ?>(<?= $doc->estimate_name ?>)<? endif; ?></a>
		    <? else: ?>
                <a href="http://www.maryjane.ru/index_admin.php?module=dealer&action=documents&filter[type]=1&filter[nonpayed]=on&filter[doc_id]=<?= $doc->id ?>"><? if ($doc->name): ?><?= $doc->name ?><? else: ?><?= $doc->id ?><? endif; ?> <? if ($doc->estimate_name): ?>(<?= $doc->estimate_name ?>)<? endif; ?></a>
		    <? endif; ?>
		<? else: ?>
			<? if ($doc->name): ?><?= $doc->name ?><? else: ?><?= $doc->id ?><? endif; ?> <? if ($doc->estimate_name): ?>(<?= $doc->estimate_name ?>)<? endif; ?>
		<? endif; ?>
		"
		на сумму <?= $doc->sum ?> руб. <? if ($percent): ?> оплачен на <?= round($doc->sum / 100 * $percent, 1) ?> руб.<? endif; ?>
	</p>

	<? if ($doc->user->meta->{'rekv-name'}): ?>
	<p>
		организация "<?= $doc->user->meta->{'rekv-name'} ?>"
	</p>
	<? endif; ?>

<? endif; ?>

<? if ($numbers): ?>
	<p>Счета проставлены полностью оплаченными:</p>
	<ul>
		<? foreach ($numbers AS $n): ?>
		<li><a href="http://www.maryjane.ru/index_admin.php?module=dealer&action=documents&filter[nonpayed]=on&filter[type]=1&filter[doc_id]=<?= $n ?>"><?= $n ?></a></li>
		<? endforeach; ?>
	</ul>
<? endif; ?>