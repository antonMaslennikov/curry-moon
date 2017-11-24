<div style="width:681px;background-color:#ccc;padding:0px;margin:0px;font-family:arial;font-size:13px;">
	<table border="1" cellpadding="0" cellspacing="0" height="33" width="100%" style="margin:0;border-color:red;">
		<tbody>
			<tr>
				<td align="left" style="padding-left:26px;">
					<div style="color:#666;font-size:12px;height:15px;white-space: nowrap;">Воспользуйтесь возможностью сегодня</div>
				</td>
				<td nowrap="nowrap" align="right" style="padding-right:26px;white-space: nowrap">
					<span style="color:#666;font-size:11px;"><a rel="nofollow" href="http://www.maryjane.ru/faq/" title="Остался вопрос?" style="border:0px;margin:0px;padding:0px;color:#666;font-size:11px;" target="_blank">Остался вопрос?</a> <a rel="nofollow" href="http://www.maryjane.ru/faq/" title="" style="border:0px;margin:0px;padding:0px;color:#666;font-size:11px;text-decoration: none;" target="_blank">тел. +7(495) 229-30-73</a></span>
				</td>
			</tr>
		</tbody>
	</table>
	<div style="background-color:#ffffff;margin:0px 18px 0 18px;padding:8px 0px 0px 0px;">
		<table border="1" cellpadding="0" cellspacing="0" width="645" style="margin:0;table-layout:fixed;border-color:red;">
			<tbody>
				<tr>
					<td style="font-size:0;padding-left:8px;" width="111" valign="top" align="left">
						<a href="http://www.maryjane.ru" style="border:0px;margin:0px;padding:0px;" target="_blank"><img src="http://www.maryjane.ru/images/mt/logo2013.jpg" width="70" height="80" alt="" title="" style="border:0px;margin:0px;padding:0px;" /></a>
					</td>
					<td align="left" width="526">
						<br/>
						<span style="color:#4a4a4a;font-size: 18px;line-height: 19px;">Здравствуйте, %userNameLogin%,<br/>оформите заказ в течении сегодняшнего дня<br/><font color="red">со скидкой 10%!</font></span>
						<br/><br/>						

						<div style="color:#a9a9a9;margin:0px;">
							<a href="http://www.maryjane.ru<?= $link; ?>" style="border:0px;margin:0px;padding:0px" target="_blank"><img src="http://www.maryjane.ru/images/order/mj/406/order-bot3.gif" width="460" height="46" alt="Ваша скидка 10%. Оформить заказ" title="Ваша скидка 10%. Оформить заказ" style="border:0px;margin:0px;padding:0px;"></a>
							<br/><br/><br/>
							
							<? if (count($order->basketGoods) + count($order->basketGifts) > 0): ?>
							
							<table cellpadding="0" cellspacing="0" style="margin-top: 10px;width:95%;font-size:14px;font-family:arial;color:#8b8b8b;">
								<tbody>		
									<? foreach ($order->basketGoods AS $g): ?>			
									<tr>
										<td width="90" valign="top">
											<? if ($g['good_status'] != 'customize'): ?>
												<a href="http://www.maryjane.ru<?= $g['link'] ?>">
													<img src="<?= $g['imagePath'] ?>" style="max-width: 85px" width="85"/>
												</a>
											<? else: ?>
												<a href="http://www.maryjane.ru/basket/">
													<img src="<?= $g['imagePath'] ?>" style="max-width: 85px" width="85"/>
												</a>
												<? if ($g['imageBackPath']): ?>
													<a href="http://www.maryjane.ru/basket/">
														<img src="<?= $g['imageBackPath'] ?>" style="max-width: 85px" width="85"/>
													</a>
												<? endif; ?>
											<? endif; ?>
										</td>
										<td style="width:180px;height: 30px;">
											<? if ($g['good_status'] != 'customize'): ?>
												<a href="http://www.maryjane.ru<?= $g['link'] ?>">
													<?= $g['good_name'] ?>
												</a>
											<? else: ?>
												<a href="http://www.maryjane.ru/basket/">
													<?= $g['good_name'] ?>
												</a>
											<? endif; ?>
											<br /><?= $g['style_name'] ?>
										</td>
										<td align="center">
											<? if (!empty($g['size_name']) || !empty($g['size_rus'])): ?>
											<div style="text-align:center;width:50px;height:40px;padding-top:10px; margin-bottom:5px;border:1px solid silver;"><?= $g['size_rus'] ?><br/><span style="font-size:9px;"><?= $g['size_name'] ?></span></div>
											<? endif; ?>
										</td>
										<td style="width:50px;height: 30px;" align="center">
											<s><?= $g['tprice']?></s> <?= $g['tprice'] - round($g['tprice'] / 100 * $discount) ?>&nbsp;руб.
										</td>
									</tr>
									<tr>
										<td colspan="4">&nbsp;</td>
									</tr>								
									<? endforeach; ?>
									
									<? foreach ($order->basketGifts AS $g): ?>
									<tr>
										<td>
											<img src="<?= $g['picture_path'] ?>" alt="<?= $g['gift_name'] ?>" style="width: 85px!important;max-width: 85px;" width="85"/>
										</td>
										<td style="height:30px;"><?= $g['gift_name'] ?></td>
										<td align="center"></td>							
										<td style="height: 30px;" align="center">
											<s><?= $g['tprice']?></s> <?= $g['tprice'] - round($g['tprice'] / 100 * $discount) ?>&nbsp;руб.
										</td>
									</tr>	
									<tr>
										<td colspan="4">&nbsp;</td>
									</tr>	
									<? endforeach; ?>	
								</tbody>
							</table>
							
							<? endif; ?>							
						</div>					
					</td>
				</tr>
			</tbody>
		</table>
	</div>		
	<div style="width:100%;margin:0;padding:30px 0 0 0;background-color: #ccc;">		
		<div align="center">
			<img src="http://www.maryjane.ru/images/mt/footer.jpg" height="60" width="511" alt="" title="" style="border:0px;margin:0px;padding:0px;"/>
		</div>		
		<span style="color:#666;">Вы всегда можете&nbsp;</span>
		<a href="http://www.maryjane.ru/unsubscribe/%userId%/%unsubscribeCode%/" style="color:#2f98ce;" target="_blank">отписаться</a>		
	</div>
	
	<small>#<?= $order->id ?></small>
</div>