<ol>
   <? foreach ($orders AS $o): ?>
   <li>
       <a href="<?= mainUrl ?>/admin/index.php?module=orders&id=<?= $o['user_basket_id'] ?>"><?= $o['user_basket_id'] ?></a> (<?= $o['user_basket_payment_type'] ?>)
       <? if ($o['user_basket_payment_confirm'] == 'false'): ?>
            - ещё не оплачен
       <? else: ?>
            - оплачен в момент доставки <?= $o['user_basket_payment_date'] ?>
       <? endif; ?>
   </li> 
   <? endforeach; ?>
</ol>