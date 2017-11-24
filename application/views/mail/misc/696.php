<p>
	Добавлен <a href="http://www.maryjane.ru/index_admin.php?module=dealer&action=documents&filter[type]=1&filter[nonpayed]=on&filter[doc_id]=<?= $doc->id ?>">новый документ 
	<? if ($doc->name): ?> "<?= $doc->name ?>" <? endif; ?> на сумму <?= number_format($doc->sum, 2, ', ', ' ') ?> руб.</a>
</p>
<p>
	организация "<?= $doc->user->meta->{'rekv-name'} ?>"
</p>