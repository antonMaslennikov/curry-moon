<div style="width:681px;background-color:#cccccc;padding:0px;margin:0px;font-family:arial;font-size:13px;">
	<table border="0" cellpadding="0" cellspacing="0" height="33" width="100%" style="margin:0;">
		<tbody>
			<tr>
				<td style="padding-right:27px;" align="right">%userBonusesText%</td>
			</tr>
		</tbody>
	</table>
	<div style="background-color:#ffffff;margin:0px 18px 0 18px;padding:8px 0px 0px 0px;">
		<table border="0" cellpadding="0" cellspacing="0" width="645" style="margin:0;">
			<tbody>
				<tr>
					<td style="font-size:0;padding-left:8px;" width="111" valign="top" align="left">
					<a href="%quickLoginLink%&next=http://www.maryjane.ru" style="border:0px;margin:0px;padding:0px;" target="_blank"><img src="http://www.maryjane.ru/images/mt/logo2013.jpg" width="70" height="80" alt="" title="" style="border:0px;margin:0px;padding:0px;" /></a>
				</td>
				<td align="left">
					<br/>
					<span style="color:#4a4a4a;font-size: 18px;line-height: 19px;">Здравствуйте, %userNameLogin%,<br/>мы заметили, что Вы наполнили корзину,<br/>но не сделали заказ =(</span>
					<br/><br/>
					<a href="%quickLoginLink%&next=http://www.maryjane.ru/basket/" style="border:0px;margin:0px;padding:0px;" target="_blank"><img src="http://www.maryjane.ru/images/order/mj/406/empty_basket.jpg" width="328" height="237" alt="Ваша корзина грустит" title="" style="border:0px;margin:0px;padding:0px;"></a>
					<div style="margin:30px 0 0px 5px;border-left: 2px solid #DD3737;padding-left: 10px;">
						<span style="color:#4a4a4a;font-size: 14px;line-height: 16px;">Мы любезно напоминаем Вам об этом,<br/>пока кто-то другой не купил выбранный Вами товар.</span>
					</div>
					<br/>
					<div style="color:#a9a9a9;font-size: 13px;margin:0px 0 0px 0px;">
						<span>Продолжить покупки можно тут <a href="%quickLoginLink%&next=http://www.maryjane.ru/basket/" style="color:#2f98ce;border:0px;margin:0px;padding:0px;" target="_blank">www.maryjane.ru/basket/</a></span>
						<br/><br/>
						<!--span style="line-height: 18px;">
						Если Вы откроете эту ссылку на том же компьютере, то в<br/>
						корзине наверняка окажутся футболки<br/>
						<a href="%quickLoginLink%&next=http://www.maryjane.ru/basket/" style="color:#2f98ce;border:0px;margin:0px;padding:0px;" target="_blank">www.maryjane.ru/basket/</a></span-->
						
						<? if (count($order->basketGoods) + count($order->basketGifts) > 0): ?>
						
						<table cellpadding="0" cellspacing="0" style="margin-top: 10px;padding-bottom: 6px;margin-bottom:15px;width:90%;border-bottom:1px solid silver;font-size:14px;font-family:arial;color:#8b8b8b;">
						<tr>
							<td style="width:170px;height: 30px;padding-bottom: 11px;" align="left" colspan="2">Содержимое заказа:</td>
							<td style="width:50px;height: 30px;padding-bottom: 11px;" align="center">Размер</td>				
							<td style="width:50px;height: 30px;padding-bottom: 11px;" align="center">Цена</td>
							<td style="width:40px;height: 30px;padding-bottom: 11px;" align="center">Скидка</td>
							<td style="width:40px;height: 30px;padding-bottom: 11px;" align="center">Кол-во</td>
							<td style="width:50px;height: 30px;padding-bottom: 11px;" align="center">Сумма</td>
						</tr>
						
						<? foreach ($order->basketGoods AS $g): ?>
						<tr>
							<td width="90" valign="top">
								<? if ($g['good_status'] != 'customize'): ?>
									<a href="http://www.maryjane.ru/catalog/<?= $g['user_login'] ?>/<?= $g['good_id'] ?>/">
										<img src="<?= $g['imagePath'] ?>" style="max-width: 85px" />
									</a>
								<? else: ?>
									<a href="http://www.maryjane.ru/basket/">
										<img src="<?= $g['imagePath'] ?>" style="max-width: 85px" />
									</a>
									<? if ($g['imageBackPath']): ?>
										<a href="http://www.maryjane.ru/basket/">
											<img src="<?= $g['imageBackPath'] ?>" style="max-width: 85px" />
										</a>
									<? endif; ?>
								<? endif; ?>
							</td>
							<td style="width:180px;height: 30px;">
								<? if ($g['good_status'] != 'customize'): ?>
									<a href="http://www.maryjane.ru/catalog/<?= $g['user_login'] ?>/<?= $g['good_id'] ?>/">
										<?= $g['good_name'] ?>
									</a>
								<? else: ?>
									<a href="http://www.maryjane.ru/basket/">
										<?= $g['good_name'] ?>
									</a>
								<? endif; ?>
								<br /><?= $g['style_name'] ?>
								<? if ($g['good_status'] != 'customize'): ?> 
								<table cellpadding="0" width="100%" cellspacing="0" style="margin:0px;color:#8b8b8b;">
									<tr>
										<td width="30" rowspan="2" align="left">
											<a href="http://www.maryjane.ru/catalog/<?= $g['user_login'] ?>" style="border:0px;margin:0px;padding:0px;" title="<?= $g['user_login'] ?>">
												<img src="http://www.maryjane.ru<?= $g['user_avatar'] ?>" width="25" height="25" alt="" title="" style="border:0px;margin:0px;padding:0px;">
											</a>
										</td>
										
										<td align="left" style="font-size:10px;">
											<?= $g['user_login'] ?>
											
											<? if ($g['user_city_name']): ?>
												<?= $g['user_city_name'] ?>
											<? endif; ?>
										</td> 
									</tr>						
									<tr>
										<td align="left" style="font-size:9px;">
											<? if ($g['author_payment']): ?>
												Автор получит <?= $g['author_payment'] ?> руб.
											<? endif; ?>
										</td> 
									</tr>
									
								</table>
								<? endif; ?>				
							</td>
							<td align="center">
								<? if (!empty($g['size_name']) || !empty($g['size_rus'])): ?>
								<div style="text-align:center;width:50px;height:40px;padding-top:10px; margin-bottom:5px;border:1px solid silver;"><?= $g['size_rus'] ?><br/><span style="font-size:9px;"><?= $g['size_name'] ?></span></div>
								<? endif; ?>
							</td>
							<td style="width:50px;height: 30px;" align="center"><?= $g['price'] ?></td>		
							<td style="width:40px;height: 30px;" align="center"><?= $g['discount'] ?></td>				
							<td style="width:40px;height: 30px;" align="center"><?= $g['quantity'] ?></td>
							<td style="width:50px;height: 30px;" align="center"><?= $g['tprice'] ?>&nbsp;руб.</td>
						</tr>
						<tr>
							<td colspan="7">&nbsp;</td>
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
							<td style="width:150px;height: 30px;"><?= $g['gift_name'] ?></td>
							<td align="center"></td>
							<td style="width:50px;height: 30px;" align="center"><?= $g['price'] ?> руб.</td>
							<td style="width:50px;height: 30px;" align="center"><?= $g['discount'] ?></td>				
							<td style="width:50px;height: 30px;" align="center"><?= $g['quantity'] ?></td>
							<td style="width:50px;height: 30px;" align="center"><?= $g['total'] ?>&nbsp;руб.</td>
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
	<br/><br/><br/><br/><br/>
</div>		
<div style="width:100%;margin:0;padding:30px 0 0 0;">
		<table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin:0;">
			<tbody>
				<tr>
					<td align="center" style="color:#666666;font-size:12px;">Подружиться с нами:<br/></td>
				</tr>
				<tr>
					<td height="1" style="font-size:5px;">&nbsp;</td>
				</tr>
				<tr>
					<td align="center" >
						<a rel="nofollow" href="http://www.maryjane.ru/jointous/facebook/" style="border:0px;margin:0px;padding:0px;" target="_blank"><img src="http://maryjane.ru/images/mt/social_silver_fb.gif" width="26" height="26" alt="Facebook" title="Facebook" style="border:0px;margin:0px;padding:0px;"/></a>
						<a rel="nofollow" href="http://www.maryjane.ru/jointous/vk/" style="border:0px;margin:0px;padding:0px;" target="_blank"><img src="http://maryjane.ru/images/mt/social_silver_vk.gif" width="26" height="26" alt="ВКонтакте" title="ВКонтакте" style="border:0px;margin:0px;padding:0px;"/></a>
						<a rel="nofollow" href="http://www.maryjane.ru/jointous/odnoklassniki/" style="border:0px;margin:0px;padding:0px;" target="_blank"><img src="http://maryjane.ru/images/mt/social_silver_odno.gif" width="26" height="26" alt="Одноклассники" title="Одноклассники" style="border:0px;margin:0px;padding:0px;"/></a>
						<a rel="nofollow" href="http://www.maryjane.ru/jointous/twitter/" style="border:0px;margin:0px;padding:0px;" target="_blank"><img src="http://maryjane.ru/images/mt/social_silver_tw.gif" width="26" height="26" alt="twitter" title="twitter" style="border:0px;margin:0px;padding:0px;"/></a>
						<a rel="nofollow" href="http://www.maryjane.ru/jointous/google/" style="border:0px;margin:0px;padding:0px;" target="_blank"><img src="http://maryjane.ru/images/mt/social_silver_google.gif" width="26" height="26" alt="google+" title="google+" style="border:0px;margin:0px;padding:0px;"/></a>
						<a rel="nofollow" href="http://www.maryjane.ru/jointous/instagram/" style="border:0px;margin:0px;padding:0px;" target="_blank"><img src="http://maryjane.ru/images/mt/social_silver_inst.gif" width="26" height="26" alt="Instagram" title="Instagram" style="border:0px;margin:0px;padding:0px;"/></a>
					</td>
				</tr>
			</tbody>
		</table>
		<div align="center">
			<img src="http://www.maryjane.ru/images/mt/footer.jpg" height="60" width="511" alt="" title="" style="border:0px;margin:0px;padding:0px;"/>
		</div>
		<table border="0" cellpadding="0" cellspacing="0" width="681" style="margin:10px 0 0 0;font-size:12px;">
			<tr>
				<td width="33%" align="center">
					<span style="color:#666;display:block;width:210px;">Москва,<br>ул. Малая почтовая, д. 12,<br>стр. 3, корп. 20 5-й этаж.</span>
				</td>
				<td width="33%" align="center">
					<table nowrap border="0" cellpadding="0" cellspacing="0" width="163" style="color:#666;margin:0px 0 0 20px;font-size:12px;">
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
					<table nowrap border="0" cellpadding="0" cellspacing="0" style="color:#666;margin:0px;font-size:12px;">
						<tbody>
							<tr nowrap>
								<td nowrap style="overflow:hidden;" height="15" width="90" align="left"><span style="display:block;height:15px;width:90px;overflow:hidden;white-space:nowrap;">Пн.-Птн...............</span></td>
								<td align="left"><span style="display:block;white-space:nowrap;">%operation_time_1%</span></td>
							</tr>
							<tr nowrap>
								<td style="overflow:hidden;" height="15" align="left"><span style="display:block;height:15px;width:90px;overflow:hidden;white-space:nowrap;">Сб........................</span></td>
								<td align="left"><span style="display:block;white-space:nowrap;">%operation_time_2%</span></td>
							</tr>
							<tr nowrap>
								<td style="overflow:hidden;" height="15" align="left"><span style="display:block;height:15px;width:90px;overflow:hidden;white-space:nowrap;">Вс........................</span></td>
								<td align="left"><span style="display:block;white-space:nowrap;">%operation_time_3%</span></td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
		</table>
		<br/><br/>
		<div align="left" style="padding-left:30px;">
			<div style=""><span style="color:#666;">Вы всегда можете&nbsp;</span><a href="http://www.maryjane.ru/unsubscribe/%userId%/%unsubscribeCode%/" style="color:#2f98ce;" target="_blank">отписаться</a></div>
			<br/><div style="color:#666;max-width:610px;text-align:justify;">«Для нас дизайн-это нечто большее чем просто красивая форма.Ты можешь рисовать и стать автором, а можешь быть наблюдателем. Неважно какой именно путь ты выбрал в любом случае изобразительное искусство - это только начало знакомства с самим собой.»</div><br/>
			<div><span style="color:#666;">&copy;&nbsp;</span><a href="http://www.maryjane.ru" style="color:#2f98ce;" target="_blank">Магазин футболок №1 Maryjane.ru</a></div><br/><br/><br/>
		</div>
	</div>
</div>