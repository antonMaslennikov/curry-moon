	<div style="width:587px;background-color:#cccccc;">
	<div style="width:100%;height:35px;text-align:right;">
		<div style="padding-right:31px;padding-top:7px;">
			<? if ($user->user_bonus > 0): ?>
				<span style="color:#666;">На вашем бонусном счету </span><a href="http://www.maryjane.ru/bonuses/" style="color:#2f98ce;border:0;margin:0;padding:0;" target="_blank"><?= $user->user_bonus ?> р.</a>
			<? endif; ?>	
		</div>
	</div>
	<div style="background-color:#ffffff;margin:0px 20px;padding:0px 33px 25px 33px;">
		<div style="width:100%;padding-top: 24px;">
			<a href="%quickLoginLink%&next=http://www.allskins.ru" style="border:0;margin:0;padding:0;"><img src="http://www.maryjane.ru/images/order/as/330/logo.jpg" alt="" title="" style="border:0;margin:0;padding:0;" target="_blank"/></a>
		</div>
		<h4 style="margin:17px 0px 7px 0px;font-size:18px;font-family:arial;color:#4a4a4a;">Здравствуйте, %userNameLogin%!</h4>
		<div style="font-size:14px;font-family:arial;">
			<span style="color:#4a4a4a;">Ваш заказ номер: </span><a target="_blank" href="%quickLoginLink%&next=http://www.allskins.ru/orderhistory/<?= $orderNumber ?>/#o<?= $orderNumber ?>" style="color:#e14c1e;text-decoration:underline;"><?= $orderPhoneNumber ?></a>
			<? if ($order->user_basket_delivery_type == 'user'): ?>
			<br/><span style="color:#979797;font-size:15px"><b>Перед приездом дождитесь СМС-оповещения о готовности.</b></span>
			<? endif; ?>
		</div>
		<table cellpadding="0" cellspacing="0" style="margin-top: 17px;padding-bottom:15px;width:100%;border-bottom:1px solid silver;font-size:14px;font-family:arial;color:#8b8b8b;">
			<tr>
				<td style="width:150px;padding: 8px 0px;">Адрес доставки:</td>
				<td><?= $deliveryAddress ?></td>				
			</tr>
			<tr>
				<td style="width:150px;padding: 8px 0px;">Телефон:</td>
				<td><?= $phone ?></td>				
			</tr>
			<tr>
				<td style="width:150px;padding: 8px 0px;">Тип доставки:</td>
				<td><?= $deliveryType ?></td>				
			</tr>
			<tr>
				<td style="width:150px;padding: 8px 0px;">Оплата:</td>
				<td><?= $paymentType ?></td>				
			</tr>
			<tr>
				<td style="width:150px;padding: 8px 0px;">Комментарий:</td>
				<td><?= $comment ?></td>				
			</tr>
		</table>
		<table cellpadding="0" cellspacing="0" style="margin-top: 10px;padding-bottom: 6px;margin-bottom:15px;width:100%;border-bottom:1px solid silver;font-size:14px;font-family:arial;color:#8b8b8b;">
			<tr>
				<td style="width:200px;height: 30px;padding-bottom: 11px;" align="left">Содержимое заказа:</td>			
				<td style="width:50px;height: 30px;padding-bottom: 11px;" align="center">Цена</td>
				<td style="width:50px;height: 30px;padding-bottom: 11px;" align="center">Скидка</td>
				<td style="width:50px;height: 30px;padding-bottom: 11px;" align="center">Кол-во</td>
				<td style="width:50px;height: 30px;padding-bottom: 11px;" align="center">Сумма</td>
			</tr>
			<? foreach ($order->basketGoods AS $g): ?>
			<tr>
				<td style="width:200px;height: 30px;"><b><?= $g['good_name'] ?></b><br /><?= $g['style_name'] ?> <?= $g['size_name'] ?></td>
				<td style="width:50px;height: 30px;" align="center"><?= $g['price'] ?></td>		
				<td style="width:50px;height: 30px;" align="center"><?= $g['discount'] ?></td>				
				<td style="width:50px;height: 30px;" align="center"><?= $g['quantity'] ?></td>
				<td style="width:50px;height: 30px;" align="center"><?= $g['tprice'] ?>&nbsp;руб.</td>
			</tr>
			<? endforeach; ?>
			
			<? foreach ($order->basketGifts AS $g): ?>
			<tr>
				<td style="width:200px;height: 30px;"><?= $g['gift_name'] ?></td>
				<td style="width:50px;height: 30px;" align="center"><?= $g['price'] ?> руб.</td>
				<td style="width:50px;height: 30px;" align="center"><?= $g['discount'] ?></td>				
				<td style="width:50px;height: 30px;" align="center"><?= $g['quantity'] ?></td>
				<td style="width:50px;height: 30px;" align="center"><?= $g['total'] ?>&nbsp;руб.</td>
			</tr>
			<? endforeach; ?>
			
		</table>
		<div style="text-align: right;padding-right: 5px;margin-bottom:10px;width:420px;float: right;font-size:14px;font-family:arial;color:#8b8b8b;">
			<table style="width:100%;" cellpadding="0" cellspacing="0">
				<tr>
					<td align="right" width="135px">Сумма заказа</td>
					<td style="border-bottom: 1px dotted;width:100px;">&nbsp;</td>
					<td style="border-bottom: 1px dotted;width:80px;"><?= $orderSum ?> руб</td>
				</tr>
				<tr>
					<td align="right" width="135px">Доставка</td>
					<td style="border-bottom: 1px dotted;">&nbsp;</td>
					<td style="border-bottom: 1px dotted;width:80px;"><?= $deliveryCost ?> руб</td>
				</tr>
				<tr>
					<td align="right" width="135px">Упаковка</td>
					<td style="border-bottom: 1px dotted;">&nbsp;</td>
					<td style="border-bottom: 1px dotted;width:80px;">0 руб</td>
				</tr>
				<tr>
					<td align="right" width="135px">Оплачено бонусами</td>
					<td style="border-bottom: 1px dotted;">&nbsp;</td>
					<td style="border-bottom: 1px dotted;width:80px;"><?= $particalPay ?> руб</td>
				</tr>
			</table>
		</div>
		<div style="clear:right;border-bottom:2px solid #E14C1E;"></div>
		<div style="float:right;padding-right:3px;padding-top:10px;font-size:14px;font-family:arial;color:#8b8b8b;"><span style="font-weight:bold;color:#E14C1E;">Итого:</span>&nbsp;<span><?= $total ?> руб.</span></div>
		<div style="clear:right;"></div>
		
		<div style="font-size:14px;font-family:arial;color:#8b8b8b;margin-top:20px;">
			<div style=""><span style="font-style:italic;">На вашем счету </span><a target="_blank" href="%quickLoginLink%&next=http://www.allskins.ru/bonuses/" style="color:#e14c1e;text-decoration:underline;">%userBonusesCount% руб.</a></div>

			<h3 style="font-size:18px;font-family:arial;color:#4a4a4a;font-weight: normal;">Оплатить заказ или распечатать квитанцию<br/>
			вы можете на странице&nbsp;&nbsp;<a href="%quickLoginLink%&next=http://www.allskins.ru/orderhistory/" style="border:0px;margin:0px;padding: 0px 0 5px 25px;background-image: url('http://www.maryjane.ru/images/my_orders.jpg'); background-repeat: no-repeat;background-position: 2px 1px;color:#4a4a4a;" target="_blank">Мои заказы</a>
			</h3>


			
			<? if ($wallpappers || count($author_thanks) > 0): ?>
			<hr size="1" color="silver" width=""/>
			<? endif; ?>
			
			<? if ($wallpappers): ?>
				<p>
					Скачать обои на рабочий стол:<br />
					<? foreach($wallpappers AS $w): ?>
						<a href="<?= $w ?>" style="color:#00a952"><?= $w ?></a><br />
					<? endforeach; ?>
				</p>
			<? endif; ?>
			
			<h3 style="font-size:18px;font-family:arial;color:#bfbfbf;">Спасибо за покупку!</h3>
			
			<? if (count($author_thanks) > 0): ?>
				
				<? if (count($author_thanks) > 1): ?>
					<h3 style="font-size:18px;font-family:arial;color:#4a4a4a;margin-bottom: 13px;">Спасибо за поддержку независимых авторов!</h3>
				<? elseif (count($author_thanks) == 1): ?>
					<h3 style="font-size:18px;font-family:arial;color:#4a4a4a;margin-bottom: 13px;">Спасибо за поддержку независимого автора</h3> 
				<? endif; ?>
				
				<? foreach ($author_thanks AS $a): ?>
				<table cellpadding="0" width="100%" cellspacing="0" style="margin:0px;">
					<tbody><tr>
						<td width="60" rowspan="2" align="left">
							<a href="http://www.maryjane.ru/catalog/<?= $a['user_login'] ?>" style="border:0;margin:0;padding:0;" title="<?= $a['user_login'] ?>" target="_blank">
								<img src="http://www.maryjane.ru<?= $a['user_avatar_medium'] ?>" width="50" height="50" alt="<?= $a['user_login'] ?>" title="<?= $a['user_login'] ?>" style="border:0;margin:0;padding:0;vertical-align:middle" />
							</a>
						</td>
						<td align="left" style="font-size:20px;color:#6b6b6b;">
							<a href="http://www.maryjane.ru/catalog/<?= $a['user_login'] ?>" style="border:0;margin:0;padding:0;color:#6b6b6b;text-decoration: none;" title="<?= $a['user_login'] ?>" target="_blank"><?= $a['user_login'] ?></a>
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
							<a href="http://www.maryjane.ru/catalog/<?= $a['user_login'] ?>" style="font-size:14px;border:0;margin:0;padding:0;color:#8b8b8b;font-style: italic;"target="_blank">Оставить автору отзыв</a>
						</td> 
					</tr>
				</tbody></table>
				<br/>
				<? endforeach; ?>
				
			<? endif; ?>
		</div>
	</div>
	<div style="width:100%;margin-top:20px;padding-left:17px;">
		<div style="">
			<img height="63px" width="523px" src="http://www.maryjane.ru/images/order/as/330/footer-icons.gif" alt="" title="" style="border:0;margin:0;padding:0;" />
		</div>
		 <table style="width:551px;margin-top:6px;font-size:11px">
			<tr>
				<td style="width:33%" align="center">
					<span style="color:#666;display:block;width:192px;">Москва,<br>ул. Малая почтовая, д. 12,<br>стр. 3, корп. 20 5-й этаж.</span>
				</td>
				<td style="width:33%" align="center">
					<div style="width:163px">
						<span style="color:#666;display:block;width:60px;overflow:hidden;float:left;white-space:nowrap">Тел...............</span><span style="color:#666;float:left;">(495)229-30-73</span><br/>
						<span style="color:#666;display:block;width:60px;overflow:hidden;float:left;white-space:nowrap">Skype.............</span><span style="color:#666;float:left;">maryjane_ru</span><br/>
						<span style="color:#666;display:block;width:60px;overflow:hidden;float:left;white-space:nowrap">Почта.............</span><span style="color:#666;float:left;">info@allskins.ru</span>
						<div style="clear:left;"></div>
					</div>
				</td>
				<td style="width:33%" align="center">
					<div style="padding-left:0">
						<span style="color:#666;display:block;width:100px;overflow:hidden;float:left;white-space: nowrap;">Пн.-Птн................</span><span style="color:#666;float:left;">%operation_time_1%</span><br/>
						<span style="color:#666;display:block;width:100px;overflow:hidden;float:left;white-space: nowrap;">Сб........................</span><span style="color:#666;float:left;">%operation_time_2%</span><br/>
						<span style="color:#666;display:block;width:100px;overflow:hidden;float:left;white-space: nowrap;">Вс........................</span><span style="color:#666;float:left;">%operation_time_3%</span>
						<div style="clear:left;"></div>
					</div>
				</td>
			</tr>
		</table>
		<div style="padding:0 0 20px 17px;">
			<br />
			<div style="color:#666;max-width:495px;text-align:justify;">«Для нас дизайн-это нечто большее чем просто красивая форма.Ты можешь рисовать и стать автором, а можешь быть наблюдателем. Неважно какой именно путь ты выбрал в любом случае изобразительное искусство - это только начало знакомства с самим собой.»</div>
			<br />
			<div style=""><span style="color:#666;">&copy;&nbsp;</span><a href="http://www.allskins.ru" style="color:#2f98ce;" target="_blank">Магазин виниловых наклеек Allskins.ru</a></div>
		</div>
		</div>
	</div>