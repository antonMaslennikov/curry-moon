<div style="width:681px;background-color:#cccccc;padding:0px;margin:0px;font-family:arial;font-size:13px;">
	<table border="0" cellpadding="0" cellspacing="0" height="33" width="100%" style="margin:0;">
		<tbody>
			<tr>
				<td style="padding-right:27px;" align="right">%userBonusesText%</td>
			</tr>
		</tbody>
	</table>
	<div style="background-color:#ffffff;margin:0px 20px;padding:0px 33px 15px 33px;">
		<div style="width:100%;">
			<a href="http://www.maryjane.ru" style="border:0px;margin:0px;padding:0px;"><img src="http://www.maryjane.ru/images/mt/logo.gif" width="55" height="55" alt="maryjane" title="maryjane.ru" style="border:0px;margin:0px;padding:0px;" /></a>
			<span style="padding-left:15px;font-size:18px;font-family:arial;color:#a5a5a5;">Вам начислены деньги</span>
		</div>
		<h4 style="margin:17px 0px 7px 0px;font-size:18px;font-family:arial;color:#4a4a4a;">Здравствуйте, %userNameLogin%!</h4>
		<div style="font-size:16px;font-family:arial;"><span style="color:#4a4a4a;">Вам выплачено <?= $bonus ?> руб., за заказ </span><a href="%quickLoginLink%&next=http://www.maryjane.ru/orderhistory/<?= $order->id ?>/#o<?= $order->id ?>" style="color:#4a4a4a;text-decoration:underline;"><?= $orderPhoneNumber ?></a></div>

		<br/><br/>							
		<a href="http://www.maryjane.ru/bonuses/" style="border:0px;margin:0px;padding:0px;" target="_blank"><img src="http://www.maryjane.ru/images/mt/259/viplacheno2.jpg" width="575" height="116" alt="выплачено" title="" style="border:0px;margin:0px;padding:0px;"></a>
		<br/><br/>	
		
		<? $tprice = 0; ?>
		
		<table cellpadding="0" cellspacing="0" style="margin-top: 10px;padding-bottom: 6px;margin-bottom:15px;width:100%;border-bottom:1px solid silver;font-size:14px;font-family:arial;color:#8b8b8b;">
			<tr>
				<td style="width:270px;height: 30px;padding-bottom: 11px;" align="left" colspan="2">Содержимое заказа:</td>
				<td style="width:50px;height: 30px;padding-bottom: 11px;" align="center">Размер</td>				
				<td style="width:50px;height: 30px;padding-bottom: 11px;" align="center">Цена</td>
				<td style="width:40px;height: 30px;padding-bottom: 11px;" align="center">Скидка</td>
				<td style="width:40px;height: 30px;padding-bottom: 11px;" align="center">Кол-во</td>
				<td style="width:50px;height: 30px;padding-bottom: 11px;" align="center">Сумма</td>
			</tr>
			<? foreach ($order->basketGoods AS $g): ?>
			<tr>
				<td width="90" valign="top">
					<img src="<?= $g['imagePath'] ?>" style="max-width: 85px" />
				</td>
				<td style="width:180px;height: 30px;"><?= $g['good_name'] ?><br /><?= $g['style_name'] ?>
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
									<img src="http://www.maryjane.ru/images/order/mj/329/bg_sity.png" width="7" height="11" style="vertical-align:middle;margin-left:10px" title="<?= $g['user_city_name'] ?>"/>
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
		<div style="text-align: right;padding-right: 5px;margin-bottom:10px;width:420px;float: right;font-size:14px;font-family:arial;color:#8b8b8b;">
			<table style="width:100%;" cellpadding="0" cellspacing="0">
				<tr>
					<td align="right" width="135px">Сумма заказа</td>
					<td style="border-bottom: 1px dotted;width:100px;">&nbsp;</td>
					<td style="border-bottom: 1px dotted;width:80px;"><?= $tprice ?> руб</td>
				</tr>
				<tr>
					<td align="right" width="135px">Доставка</td>
					<td style="border-bottom: 1px dotted;">&nbsp;</td>
					<td style="border-bottom: 1px dotted;width:80px;"><?= $order->user_basket_delivery_cost ?> руб</td>
				</tr>
				<tr>
					<td align="right" width="135px">Упаковка</td>
					<td style="border-bottom: 1px dotted;">&nbsp;</td>
					<td style="border-bottom: 1px dotted;width:80px;">0 руб</td>
				</tr>
				<tr>
					<td align="right" width="135px">Оплачено бонусами</td>
					<td style="border-bottom: 1px dotted;">&nbsp;</td>
					<td style="border-bottom: 1px dotted;width:80px;"><?= $order->user_basket_payment_partical ?> руб</td>
				</tr>
			</table>
		</div>
		<div style="clear:right;border-bottom:2px solid #8b8b8b;"></div>
		<div style="float:right;padding-right:3px;padding-top:10px;font-size:14px;font-family:arial;color:#8b8b8b;"><span style="font-weight:bold;">Итого:</span>&nbsp;<span><?= $tprice + $order->user_basket_delivery_cost - $order->user_basket_payment_partical ?> руб.</span></div>
		<div style="clear:right;"></div>
		
		<div style="font-size:14px;font-family:arial;color:#8b8b8b;margin-top:20px;">
			<div style=""><span style="font-style:italic;">На вашем счету </span><a href="%quickLoginLink%&next=http://www.maryjane.ru/bonuses/" style="color:#00a952;text-decoration:underline;">%userBonusesCount% руб.</a></div>
			<div style=""><span style="font-style:italic;">За этот заказ, Вам было начислено <?= $bonus ?> руб. </span></div>
			<br/><br/>
			<hr size="1" color="silver" width=""/>
			
			<? if ($wallpappers): ?>
				<p><?= $wallpappers ?></p>
			<? endif; ?>

			<? if (count($author_thanks) > 0): ?>
				
				<? if (count($author_thanks) > 1): ?>
					<h3 style="font-size:18px;font-family:arial;color:#4a4a4a;margin-bottom: 13px;">Этой покупкой Вы поддерживаете независимых авторов!</h3>
				<? else: ?>
					<h3 style="font-size:18px;font-family:arial;color:#4a4a4a;margin-bottom: 13px;">Этой покупкой Вы поддерживаете независимого автора!</h3> 
				<? endif; ?>
					
				<? foreach ($author_thanks AS $a): ?>
				<table cellpadding="0" width="100%" cellspacing="0" style="margin:0px;">
					<tbody><tr>
						<td width="60" rowspan="2" align="left">
							<a href="http://www.maryjane.ru/catalog/<?= $a['user_login'] ?>" style="border:0px;margin:0px;padding:0px;" title="<?= $a['user_login'] ?>">
								<img src="http://www.maryjane.ru<?= $a['user_avatar_medium'] ?>" width="50" height="50" alt="<?= $a['user_login'] ?>" title="<?= $a['user_login'] ?>" style="border:0px;margin:0px;padding:0px;vertical-align:middle" />
							</a>
						</td>
						<td align="left" style="font-size:20px;color:#6b6b6b;">
							<a href="http://www.maryjane.ru/catalog/<?= $a['user_login'] ?>" style="border:0px;margin:0px;padding:0px;color:#6b6b6b;text-decoration: none;" title="<?= $a['user_login'] ?>"><?= $a['user_login'] ?></a>
							<img src="http://www.maryjane.ru/images/order/mj/329/bg_sityB.png" width="11" height="14" style="vertical-align:middle;margin-left:10px" title="<?= $a['user_city_name'] ?>"/> <font style="font-size:12px;"><?= $a['user_city_name'] ?></font>
						</td> 
					</tr>						
					<tr>
						<td align="left" style="font-size:14px;color:#8b8b8b;">
							<? if ($a['author_payment']): ?>
								<i>Автор получит <?= $a['author_payment'] ?> руб.</i>
							<? endif; ?>								
						</td> 
					</tr>
					<tr>
						<td align="left" colspan="2" style="padding-top: 12px;">
							<a href="http://www.maryjane.ru/catalog/<?= $a['user_login'] ?>" style="font-size:14px;border:0px;margin:0px;padding:0px;color:#8b8b8b;font-style: italic;">Оставить автору отзыв</a>
						</td> 
					</tr>
				</tbody></table>
				<br/>
				<? endforeach; ?>
				
			<? endif; ?>
		
			<br/>
			<hr size="2" color="silver"/>
			<br/>
			<div  style="padding:15px 0 15px 15px;border:2px solid #F3F3F3;display:inline-block;">
				<table width="370" border="0" cellpadding="0" cellspacing="0">
					<tbody><tr style="">
						<td width="70" height="" valign="" align="left">
							<img src="http://www.maryjane.ru/images/order/mj/400/heart.jpg" width="50" height="47" alt="" title="" style="border:0px;margin:0px;padding:0px;" />
						</td>
						<td valign="" align="left">
							<span style="padding: 3px 0;color:#8f8c8b;font-size: 14px;line-height:19px;">&nbsp;Мы постоянно улучшаем наш сервис&nbsp;<br/>&nbsp;и будем бесконечно признательны,&nbsp;<br/>&nbsp;если Вы поможете нам в этом.&nbsp;</span>
						</td>
					</tr></tbody>
				</table>
			</div>
			<br/><br/>
			<span style="color:#1C1B26;font-size:14px;">—&nbsp;Пожалуйста, оцените товар</span>	
			<form target="_blank" method="GET" action="http://www.maryjane.ru/polls/vote/">
				<br/>
				&nbsp;&nbsp;<label><input type="radio" name="answers[]" value="259" style="margin:0;"><span style="padding-left:5px;font-size:14px;color:#1C1B26;">Все отлично, как в день покупки</span></label>
				<br/>
				&nbsp;&nbsp;<label><input type="radio" name="answers[]" value="260" style="margin:0;"><span style="padding-left:5px;font-size:14px;color:#1C1B26;">Нанесение, тускнеет</span></label>
				<br/>
				&nbsp;&nbsp;<label><input type="radio" name="answers[]" value="261" style="margin:0;"><span style="padding-left:5px;font-size:14px;color:#1C1B26;">Изделие деформировалось</span></label>
				<br/>
				&nbsp;&nbsp;<label><input type="radio" name="answers[]" value="262" style="margin:0;"><span style="padding-left:5px;font-size:14px;color:#1C1B26;">Не знаю, подарил</span></label>
				<br/>
				&nbsp;&nbsp;<label><input type="radio" name="answers[]" value="263" style="margin:0;"><span style="padding-left:5px;font-size:14px;color:#1C1B26;">Размер не подходит</span></label>
				<br/>
				<input type="submit" value="Оценить" style="margin: 13px 0 0 3px;">
				<input type="hidden" value="44" name="poll_id">
				<input type="hidden" value="<?= $order->user_id ?>" name="user_id">
				<input type="hidden" value="<?= $order->id ?>" name="order">
				<input type="hidden" value="%unsubscribeCode%" name="code">
			</form>
			<h3 style="font-size:17px;font-family:arial;color:#d6d5d5;margin-bottom: 13px;">Спасибо за Вашу помощь и поддержку!</h3>
			<hr size="2" color="silver" width=""/>
			<br/>
			<span style="color:#979797;font-size:16px;">Ваша персональная скидка</span>	
			<br/><br/>
			<table width="575" border="0" cellpadding="0" cellspacing="0">
				<tbody>
					<tr>
						<td align="left">
							<a href="http://www.maryjane.ru/bonuses/" style="border:0px;margin:0px;padding:0px;" title="">
								<img src="http://www.maryjane.ru/images/mt/259/0<? if ($buyerDiscount == 0) echo 'a' ?>.png" width="54" height="67" alt="0%" title="0%" style="border:0px;margin:0px;padding:0px;vertical-align:middle" />
							</a>
						</td>
						<td align="left">
							<a href="http://www.maryjane.ru/bonuses/" style="border:0px;margin:0px;padding:0px;" title="">
								<img src="http://www.maryjane.ru/images/mt/259/5<? if ($buyerDiscount == 5) echo 'a' ?>.png" width="54" height="67" alt="5%" title="5%" style="border:0px;margin:0px;padding:0px;vertical-align:middle" />
							</a>
						</td>
						<td align="left">
							<a href="http://www.maryjane.ru/bonuses/" style="border:0px;margin:0px;padding:0px;" title="">
								<img src="http://www.maryjane.ru/images/mt/259/7<? if ($buyerDiscount == 7) echo 'a' ?>.png" width="54" height="67" alt="7%" title="7%" style="border:0px;margin:0px;padding:0px;vertical-align:middle" />
							</a>
						</td>
						<td align="left">
							<a href="http://www.maryjane.ru/bonuses/" style="border:0px;margin:0px;padding:0px;" title="">
								<img src="http://www.maryjane.ru/images/mt/259/9<? if ($buyerDiscount == 9) echo 'a' ?>.png" width="54" height="67" alt="9%" title="9%" style="border:0px;margin:0px;padding:0px;vertical-align:middle" />
							</a>
						</td>
						<td align="left">
							<a href="http://www.maryjane.ru/bonuses/" style="border:0px;margin:0px;padding:0px;" title="">
								<img src="http://www.maryjane.ru/images/mt/259/10<? if ($buyerDiscount == 10) echo 'a' ?>.png" width="54" height="67" alt="10%" title="10%" style="border:0px;margin:0px;padding:0px;vertical-align:middle" />
							</a>
						</td>
						<td align="left">
							<a href="http://www.maryjane.ru/bonuses/" style="border:0px;margin:0px;padding:0px;" title="">
								<img src="http://www.maryjane.ru/images/mt/259/15<? if ($buyerDiscount >= 15) echo 'a' ?>.png" width="54" height="67" alt="15%" title="15%" style="border:0px;margin:0px;padding:0px;vertical-align:middle" />
							</a>
						</td>
					</tr>
				</tbody>
			</table>
			<br/>
			<span style="color:#979797;font-size:16px;">Списание бонусов происходит после года неиспользования.</span>	
			<br/><br/>
		</div>
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