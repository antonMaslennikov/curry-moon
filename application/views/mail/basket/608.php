<div style="width:681px;background-color:#ccc;padding:0;margin:0;font-family:arial;font-size:13px">
	<table border="0" cellpadding="0" cellspacing="0" height="33" width="100%" style="margin:0;">
		<tbody>
			<tr>
				<td nowrap="nowrap" align="left" style="padding-left:25px;">
					<div style="color:#666;font-size:12px;height:15px;white-space:nowrap;">Кажется Вы не доделали заказ.</div>
				</td>
				<td nowrap="nowrap" align="center" width="30%">
					<span style="color:#666;font-size:11px;"><a rel="nofollow" href="http://www.maryjane.ru/faq/" title="Остался вопрос?" style="border:0;margin:0;padding:0;color:#666;font-size:11px;display:inline-block;" target="_blank">Остался вопрос?</a> <a rel="nofollow" href="http://www.maryjane.ru/faq/" title="" style="border:0;margin:0;padding:0;color:#666;font-size:11px;text-decoration:none;display:inline-block;" target="_blank">тел. +7(495) 229-30-73</a></span>
				</td>
				<td style="padding-right:25px;white-space:nowrap" width="37%" align="right" nowrap="nowrap">%userBonusesText%</td>
			</tr>
		</tbody>
	</table>
	<div style="background-color:#ffffff;margin:0 18px 0 18px;padding:8px 0 0 0;">
		<table border="0" cellpadding="0" cellspacing="0" width="645" style="margin:0;table-layout:fixed;min-width:645px">
			<tbody>
				<tr>
					<td style="font-size:0;padding-left:8px;" width="70" valign="top" align="left">
						<a href="%quickLoginLink%&next=http://www.maryjane.ru" style="border:0;margin:0;padding:0;" target="_blank"><img src="http://www.maryjane.ru/images/mt/logo2013.jpg" width="70" height="80" alt="maryjane.ru" title="maryjane.ru" style="border:0;margin:0;padding:0;" /></a>
					</td>	
					<td width="41" style="min-width:41px">&nbsp;</td>
					<td align="left" width="526">
						<br/>
						<span style="color:#4a4a4a;font-size:18px;line-height:19px;">Здравствуйте, %userNameLogin%,<br/>мы сохранили Вашу корзину для Вас <font color="red">на 3 дня</font>. Вы сможете продолжить оформление заказа позже, до момента пока их не купит кто-то другой.</span>
						<br/><br/>
						
						<div style="color:#a9a9a9;font-size:13px;margin:0">
						
							<table border="0" cellpadding="0" cellspacing="0" height="" width="494" style="margin:0;color:#a9a9a9;font-size:13px;">
								<tbody>
									<tr>
										<td align="left" valign="bottom" width="268" style="border-top:1px solid #BDBBBC;border-left:1px solid #BDBBBC;">
											&nbsp;&nbsp;&nbsp;<span>Продолжить покупки можно тут</span>
										</td>
										<td align="right" rowspan="2">
											<a href="http://www.maryjane.ru<?= $link; ?>" style="border:0;margin:0;padding:0;display:block;height:46px;max-height:46px;" target="_blank"><img src="http://www.maryjane.ru/images/order/mj/406/order-bot2.gif" width="226" height="46" alt="оформить заказ" title="оформить заказ" style="border:0;margin:0;padding:0;"></a>
										</td>
									</tr>
									<tr>
										<td align="left" valign="top" style="border-bottom:1px solid #BDBBBC;border-left:1px solid #BDBBBC;">
											&nbsp;&nbsp;&nbsp;<a href="http://www.maryjane.ru<?= $link; ?>" style="color:#2f98ce;border:0;margin:0;padding:0;" target="_blank">www.maryjane.ru/basket/</a>
										</td>
									</tr>
								</tbody>
							</table>
							
							
							<? if (count($order->basketGoods) + count($order->basketGifts) > 0): ?>
							
							<table cellpadding="0" width="500" cellspacing="0" style="margin:10px 0 0 0;font-size:14px;font-family:arial;color:#8b8b8b;min-width:500px;">
								<tr>
									<td style="height:30px;padding-bottom:11px;" colspan="4">&nbsp;</td>
								</tr>
								
								<? foreach ($order->basketGoods AS $g): ?>			
								<tr>
									<td width="116" valign="top" style="min-width:116px">
										<? if ($g['good_status'] != 'customize'): ?>
											<a href="http://www.maryjane.ru<?= $g['link'] ?>">
												<img src="<?= $g['imagePath'] ?>" style="width:85px!important;max-width:85px" width="85"/>
											</a>
										<? else: ?>
											<a href="http://www.maryjane.ru/basket/">
												<img src="<?= $g['imagePath'] ?>" style="width:85px!important;max-width:85px" width="85"/>
											</a>
											<? if ($g['imageBackPath']): ?>
												<a href="http://www.maryjane.ru/basket/">
													<img src="<?= $g['imageBackPath'] ?>" style="width:85px!important;max-width:85px" width="85"/>
												</a>
											<? endif; ?>
										<? endif; ?>
									</td>
									<td width="233" style="height:30px;white-space:normal;max-width:233px">
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
									<td width="67" align="center">
										<? if (!empty($g['size_name']) || !empty($g['size_rus'])): ?>
										<div style="text-align:center;min-width:50px;padding:10px; margin-bottom:5px;border:1px solid silver;"><? if ($g['size_rus']): ?><?= $g['size_rus'] ?><br/><? endif; ?><span style="font-size:9px;"><?= $g['size_name'] ?></span></div>
										<? endif; ?>
									</td>
									<td width="83" style="height:30px;" align="center"><?= $g['quantity'] ?>шт.<br /><?= $g['tprice'] ?>&nbsp;руб.</td>
								</tr>
								<tr>
									<td colspan="4">&nbsp;</td>
								</tr>
								
								<?
									if ($g['good_status'] != 'customize' && $g['good_user'] != $order->user_id)
									{
										if (!$author_thanks[$g['good_user']]) {
											$author_thanks[$g['good_user']] = array(
												'user_login' => $g['user_login'],
												'user_city_name' => $g['user_city_name'],
												'user_avatar' => $g['user_avatar'],
												'user_avatar_medium' => $g['user_avatar_medium'],
											);
										}
										
										$author_thanks[$g['good_user']]['author_payment'] += $g['author_payment'];
									}
									
									$tprice += $g['tprice']; 
								?>
								
								<? endforeach; ?>
								
								<? foreach ($order->basketGifts AS $g): ?>
								<tr>
									<td width="116" style="min-width:116px">
										<img src="<? if (strpos($g['picture_path'], 'http')): echo  mainUrl; endif; ?><?= $g['picture_path'] ?>" alt="<?= $g['gift_name'] ?>" style="width:85px!important;max-width:85px;" width="85"/>
									</td>
									<td width="233" style="height:30px;white-space:normal;max-width:233px"><?= $g['gift_name'] ?></td>
									<td width="67" align="center"></td>							
									<td width="83" style="height:30px;" align="center"><s><?= $g['tprice']?></s> <?= $g['tprice'] - round($g['tprice'] / 100 * $discount) ?>&nbsp;руб.</td>
								</tr>
								
								<? $tprice += $g['tprice']; ?>
								
								<? endforeach; ?>							
							</table>
							
							<? endif; ?>							
						</div>					
					</td>
				</tr>
			</tbody>
		</table>
		<br/>
		<a href="%quickLoginLink%&next=http://www.maryjane.ru/" style="border:0;margin:0;padding:0;display:block;height:57px;max-height:57px;text-decoration:none;" target="_blank"><img src="http://www.maryjane.ru/images/order/mj/406/4dost2.gif" width="645" height="57" alt="Достоинства" style="border:0;margin:0;padding:0;"></a>
	</div>		
	<div style="width:681px;margin:0;padding:30px 0 0 0;background-color:#ccc">
		<table width="681" border="0" cellpadding="0" cellspacing="0" style="margin:0">
			<tbody>
				<tr>
					<td align="center" style="color:#666;font-size:12px;min-width:681px">Подружиться с нами:<br/></td>
				</tr>
				<tr>
					<td height="1" style="font-size:5px">&nbsp;</td>
				</tr>
				<tr>
					<td align="center">
						<a rel="nofollow" href="http://www.maryjane.ru/jointous/facebook/" style="border:0;margin:0;padding:0;" target="_blank"><img src="http://maryjane.ru/images/mt/social_silver_fb.gif" width="26" height="26" alt="Facebook" title="Facebook" style="border:0;margin:0;padding:0;"/></a>
						<a rel="nofollow" href="http://www.maryjane.ru/jointous/vk/" style="border:0;margin:0;padding:0;" target="_blank"><img src="http://maryjane.ru/images/mt/social_silver_vk.gif" width="26" height="26" alt="ВКонтакте" title="ВКонтакте" style="border:0;margin:0;padding:0;"/></a>
						<a rel="nofollow" href="http://www.maryjane.ru/jointous/odnoklassniki/" style="border:0;margin:0;padding:0;" target="_blank"><img src="http://maryjane.ru/images/mt/social_silver_odno.gif" width="26" height="26" alt="Одноклассники" title="Одноклассники" style="border:0;margin:0;padding:0;"/></a>
						<a rel="nofollow" href="http://www.maryjane.ru/jointous/twitter/" style="border:0;margin:0;padding:0;" target="_blank"><img src="http://maryjane.ru/images/mt/social_silver_tw.gif" width="26" height="26" alt="twitter" title="twitter" style="border:0;margin:0;padding:0;"/></a>
						<a rel="nofollow" href="http://www.maryjane.ru/jointous/google/" style="border:0;margin:0;padding:0;" target="_blank"><img src="http://maryjane.ru/images/mt/social_silver_google.gif" width="26" height="26" alt="google+" title="google+" style="border:0;margin:0;padding:0;"/></a>
						<a rel="nofollow" href="http://www.maryjane.ru/jointous/instagram/" style="border:0;margin:0;padding:0;" target="_blank"><img src="http://maryjane.ru/images/mt/social_silver_inst.gif" width="26" height="26" alt="Instagram" title="Instagram" style="border:0;margin:0;padding:0;"/></a>
					</td>
				</tr>
			</tbody>
		</table>
		<table border="0" cellpadding="0" cellspacing="0" width="681" style="margin:0;font-size:12px">
			<tr>
				<td style="min-width:681px;padding-bottom:10px" colspan="3" align="center">				
					<img src="http://www.maryjane.ru/images/mt/footer.jpg" height="60" width="511" alt="изображение" style="border:0;margin:0;padding:0;"/>
				</td>
			</tr>			
			<tr>
				<td width="33%" align="center">
					<span style="color:#666;display:block;width:210px;">Москва,<br>ул. Малая почтовая, д. 12,<br>стр. 3, корп. 20 5-й этаж.</span>
				</td>
				<td width="33%" align="center">
					<table nowrap border="0" cellpadding="0" cellspacing="0" width="163" style="color:#666;margin:0 0 0 20px;font-size:12px;">
						<tbody>
							<tr nowrap>
								<td style="overflow:hidden;" height="15" width="55" align="left"><span style="display:block;height:15px;width:55px;overflow:hidden;white-space:nowrap;">Тел...............</span></td>
								<td align="left" width="108"><span style="white-space:nowrap;">(495)229-30-73</span></td>
							</tr>
							<tr nowrap>
								<td style="overflow:hidden;" height="15" align="left"><span style="display:block;height:15px;width:55px;overflow:hidden;white-space:nowrap;">Skype.............</span></td>
								<td align="left"><span style="white-space:nowrap;">maryjane_ru</span></td>
							</tr>
							<tr nowrap>
								<td style="overflow:hidden;" height="15" align="left"><span style="display:block;height:15px;width:55px;overflow:hidden;white-space:nowrap;">Почта.............</span></td>
								<td align="left"><span style="white-space:nowrap;">info@maryjane.ru</span></td>
							</tr>
						</tbody>
					</table>
				</td>
				<td width="33%" align="center">
					<table nowrap border="0" cellpadding="0" cellspacing="0" style="color:#666;margin:0;font-size:12px">
						<tbody>
							<tr nowrap>
								<td nowrap style="overflow:hidden" height="15" width="90" align="left"><span style="display:block;height:15px;width:90px;overflow:hidden;white-space:nowrap">Пн.-Птн...............</span></td>
								<td align="left"><span style="display:block;white-space:nowrap;">%operation_time_1%</span></td>
							</tr>
							<tr nowrap>
								<td style="overflow:hidden" height="15" align="left"><span style="display:block;height:15px;width:90px;overflow:hidden;white-space:nowrap">Сб........................</span></td>
								<td align="left"><span style="display:block;white-space:nowrap;">%operation_time_2%</span></td>
							</tr>
							<tr nowrap>
								<td style="overflow:hidden" height="15" align="left"><span style="display:block;height:15px;width:90px;overflow:hidden;white-space:nowrap">Вс........................</span></td>
								<td align="left"><span style="display:block;white-space:nowrap;">%operation_time_3%</span></td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
		</table>
		<br/><br/>
		<div align="left" style="padding-left:30px">
			<div style=""><span style="color:#666">Вы всегда можете&nbsp;</span><a href="http://www.maryjane.ru/unsubscribe/%userId%/%unsubscribeCode%/" style="color:#2f98ce" target="_blank">отписаться</a></div>
			<br/><div style="color:#666;max-width:610px;text-align:justify;">«Для нас дизайн-это нечто большее чем просто красивая форма.Ты можешь рисовать и стать автором, а можешь быть наблюдателем. Неважно какой именно путь ты выбрал в любом случае изобразительное искусство - это только начало знакомства с самим собой.»</div><br/>
			<div><span style="color:#666">&copy;&nbsp;</span><a href="http://www.maryjane.ru" style="color:#2f98ce" target="_blank">Магазин футболок №1 Maryjane.ru</a></div>
			<br/><br/><br/>
		</div>
	</div>
	
	<small>#<?= $order->id ?></small>
</div>