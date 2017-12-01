-- phpMyAdmin SQL Dump
-- version 4.7.3
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Дек 01 2017 г., 23:14
-- Версия сервера: 5.5.57-log
-- Версия PHP: 5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `shop.loc`
--

-- --------------------------------------------------------

--
-- Структура таблицы `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `comment_text` text NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `object_id` int(11) NOT NULL DEFAULT '0',
  `object_type` enum('good','newgood','blog','gallery') NOT NULL,
  `comment_parent` int(11) NOT NULL DEFAULT '0',
  `comment_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `comment_ip` varchar(20) NOT NULL,
<<<<<<< HEAD
  `comment_visible` enum('-1','hudsovet','1') NOT NULL DEFAULT '1',
  PRIMARY KEY (`comment_id`),
  KEY `object_id` (`object_id`,`object_type`,`comment_date`),
  KEY `user_id` (`user_id`,`object_id`)
) ENGINE=MyISAM AUTO_INCREMENT=123864 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comments`
--

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `feedback`
--

DROP TABLE IF EXISTS `feedback`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `feedback_status` varchar(20) NOT NULL DEFAULT 'new',
  `feedback_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `feedback_reply_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `feedback_name` varchar(255) NOT NULL,
  `feedback_email` varchar(255) NOT NULL,
  `basket_id` int(11) NOT NULL DEFAULT '0',
  `feedback_topic` text NOT NULL,
  `feedback_text` longtext NOT NULL,
  `feedback_reply` longtext NOT NULL,
  `feedback_user` varchar(255) NOT NULL DEFAULT '0',
  `feedback_replay_user` int(11) NOT NULL,
  `feedback_error` varchar(100) NOT NULL,
  `feedback_pict` varchar(255) NOT NULL DEFAULT '0',
  `feedback_webclient` varchar(100) NOT NULL COMMENT 'браузер автора вопроса',
  PRIMARY KEY (`id`),
  KEY `feedback_reply_date` (`feedback_reply_date`),
  KEY `feedback_date` (`feedback_date`)
) ENGINE=MyISAM AUTO_INCREMENT=12551 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `feedback`
--

LOCK TABLES `feedback` WRITE;
/*!40000 ALTER TABLE `feedback` DISABLE KEYS */;
INSERT INTO `feedback` VALUES (12547,'new','2017-12-01 17:00:05','0000-00-00 00:00:00','aaa','anton.maslennikov@gmail.com',0,'asdasd','asdasdasd','','0',0,'','0',''),(12548,'new','2017-12-01 17:24:58','0000-00-00 00:00:00','aaa','anton.maslennikov@gmail.com',0,'asdasd','asdasdasd','','0',0,'','0',''),(12549,'new','2017-12-01 17:28:08','0000-00-00 00:00:00','a','anton.maslennikov@gmail.com',0,'aaa','assdasdasd','','0',0,'','0',''),(12550,'new','2017-12-01 17:31:22','0000-00-00 00:00:00','aaa','anton.maslennikov@gmail.com',0,'asda','asdsad','','0',0,'','0','');
/*!40000 ALTER TABLE `feedback` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `geo__base`
--

DROP TABLE IF EXISTS `geo__base`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `geo__base` (
  `long_ip1` bigint(20) NOT NULL,
  `long_ip2` bigint(20) NOT NULL,
  `ip1` varchar(16) NOT NULL,
  `ip2` varchar(16) NOT NULL,
  `country` varchar(2) NOT NULL,
  `city_id` int(10) NOT NULL,
  KEY `INDEX` (`long_ip1`,`long_ip2`)
=======
  `comment_visible` enum('-1','hudsovet','1') NOT NULL DEFAULT '1'
>>>>>>> dd7b84b29c6d2c2773fd83cab80b7603c33829c7
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `object_id` (`object_id`,`object_type`,`comment_date`),
  ADD KEY `user_id` (`user_id`,`object_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=123864;COMMIT;

<<<<<<< HEAD
DROP TABLE IF EXISTS `mail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mail` (
  `mail_message_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `mail_message_template_id` smallint(6) NOT NULL DEFAULT '0' COMMENT 'номер шаблона',
  `mail_message_subject` varchar(255) NOT NULL DEFAULT '',
  `mail_message_text` longtext NOT NULL,
  `mail_message_attachments` varchar(500) NOT NULL,
  `user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `mail_message_email` varchar(50) NOT NULL DEFAULT '',
  `mail_message_status` varchar(45) NOT NULL DEFAULT 'awaiting',
  `mail_message_viewed` enum('0','1') NOT NULL DEFAULT '0',
  `mail_message_clicked` datetime NOT NULL COMMENT 'время последнего прехода по ссылке из письма',
  `click_endpoint` varchar(100) NOT NULL COMMENT 'пункт назначения перехода',
  `raiting` tinyint(3) unsigned NOT NULL DEFAULT '10' COMMENT 'определяет "важность" письма и очерёдность его отправки',
  `from` varchar(30) NOT NULL DEFAULT 'info@maryjane.ru' COMMENT 'от кого',
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`mail_message_id`),
  KEY `msg_status` (`mail_message_status`),
  KEY `user_id` (`user_id`),
  KEY `raiting` (`raiting`),
  KEY `mail_message_template_id` (`mail_message_template_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mail`
--

LOCK TABLES `mail` WRITE;
/*!40000 ALTER TABLE `mail` DISABLE KEYS */;
INSERT INTO `mail` VALUES (1,1,'Подтверждение регистрации','<p>Ссылка для активации: <a href=\"http://www.curry-moon.loc/ru/users/activate/?userid=21&key=bac0bcc7e708ad9323b27fd40e742012\">http://www.curry-moon.loc/ru/users/activate/?userid=21&key=bac0bcc7e708ad9323b27fd40e742012</a></p>\r\n<p>Логин для входа: anton.maslennikov@gmail.com</p>\r\n<p>Пароль для входа: 34116b2e13</p><p style=\"color:#ccc\"><small>#1</small></p>','',21,'anton.maslennikov@gmail.com','sent','0','0000-00-00 00:00:00','',10,'info@xxx.ru','2017-11-30 22:44:32'),(2,1,'Подтверждение регистрации','<p>Ссылка для активации: <a href=\"http://www.curry-moon.loc/ru/users/activate/?userid=22&key=dab0a116a73181aa8d8fa1417bed5e48\">http://www.curry-moon.loc/ru/users/activate/?userid=22&key=dab0a116a73181aa8d8fa1417bed5e48</a></p>\r\n<p>Логин для входа: anton.maslennikov@gmail.com</p>\r\n<p>Пароль для входа: 364c4fb4f3</p><p style=\"color:#ccc\"><small>#1</small></p>','',22,'anton.maslennikov@gmail.com','sent','0','0000-00-00 00:00:00','',10,'info@xxx.ru','2017-12-01 15:31:10');
/*!40000 ALTER TABLE `mail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mail__subscribers`
--

DROP TABLE IF EXISTS `mail__subscribers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mail__subscribers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `user_email` varchar(100) NOT NULL COMMENT 'мыло подписчика, для неавторизованных пользователей',
  `user_ip` int(10) NOT NULL,
  `mail_list_id` smallint(3) NOT NULL DEFAULT '0',
  `phone_notify` tinyint(4) NOT NULL DEFAULT '-1' COMMENT 'уведомлён ли пользователь по телефону',
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'время подписки',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique` (`user_id`,`user_email`,`mail_list_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=191548 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mail__subscribers`
--

LOCK TABLES `mail__subscribers` WRITE;
/*!40000 ALTER TABLE `mail__subscribers` DISABLE KEYS */;
/*!40000 ALTER TABLE `mail__subscribers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mail__templates`
--

DROP TABLE IF EXISTS `mail__templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mail__templates` (
  `mail_template_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `mail_template_subject` varchar(255) NOT NULL DEFAULT '',
  `mail_template_file` varchar(100) NOT NULL DEFAULT '',
  `mail_template_name` varchar(100) NOT NULL,
  `mail_template_order` enum('affiliates','actions','basket','clear','compred','dealers','forDesigners','good','hudsovet','misc','news','newsCatalog','report','tenders','registration') NOT NULL,
  `mail_template_send` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'количество отправок',
  `mail_template_views` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT 'открытия',
  `mail_template_clicks` mediumint(9) NOT NULL COMMENT 'переходов из письма',
  `mail_template_orders` smallint(5) unsigned NOT NULL COMMENT 'оформлено заказов после перехода',
  `mail_template_orders_sum` smallint(5) unsigned NOT NULL COMMENT 'сумма оформленных заказов',
  `mail_template_parent` smallint(6) NOT NULL DEFAULT '0' COMMENT 'родительский шаблон (взамен какой создан)',
  `mail_template_comment` varchar(500) NOT NULL,
  `mail_template_trigger` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'является ли шаблон отслеживаемым',
  PRIMARY KEY (`mail_template_id`)
) ENGINE=MyISAM AUTO_INCREMENT=875 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mail__templates`
--

LOCK TABLES `mail__templates` WRITE;
/*!40000 ALTER TABLE `mail__templates` DISABLE KEYS */;
INSERT INTO `mail__templates` VALUES (1,'Подтверждение регистрации','registration/1.php','Подтверждение регистрации','registration',0,0,0,0,0,0,'','0');
/*!40000 ALTER TABLE `mail__templates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pictures`
--

DROP TABLE IF EXISTS `pictures`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pictures` (
  `picture_id` int(11) NOT NULL AUTO_INCREMENT,
  `picture_path` varchar(250) NOT NULL,
  PRIMARY KEY (`picture_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pictures`
--

LOCK TABLES `pictures` WRITE;
/*!40000 ALTER TABLE `pictures` DISABLE KEYS */;
/*!40000 ALTER TABLE `pictures` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `session_id` varchar(100) CHARACTER SET latin1 NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '-1',
  `session_time` int(9) NOT NULL DEFAULT '0',
  `session_logged` tinyint(1) NOT NULL DEFAULT '0',
  `session_short` tinyint(1) NOT NULL DEFAULT '0',
  `user_basket_id` int(11) NOT NULL DEFAULT '0',
  UNIQUE KEY `session_id` (`session_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MEMORY DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('8746653b3b3326df6e2b26a3bfa21a6d',22,1514731688,1,1,0);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `static_pages`
--

DROP TABLE IF EXISTS `static_pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `static_pages` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(200) CHARACTER SET latin1 NOT NULL,
  `h1_ru` varchar(100) NOT NULL,
  `h1_en` varchar(100) NOT NULL,
  `text_ru` text NOT NULL,
  `text_en` text NOT NULL,
  `meta_keywords` varchar(255) NOT NULL,
  `meta_description` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `static_pages`
--

LOCK TABLES `static_pages` WRITE;
/*!40000 ALTER TABLE `static_pages` DISABLE KEYS */;
INSERT INTO `static_pages` VALUES (1,'about','О нас','','<p>Привет.</p>\n<p>Мысль о создании интернет-магазина родилась давно, в наше первое путешествие по Индии. Встречая в дорогах необычные вещи, <a href=\"/ru/shop/clothing\">красивую самобытную одежду</a>, <a href=\"/ru/shop/tippets\">этнические палантины</a>, <a href=\"/ru/shop/scarves\">яркие шарфы</a>, стильные, непохожие на другие <a href=\"/ru/shop/bags\">сумки</a>, <a href=\"/ru/shop/jewellery/bijouterie\">кольца</a> с нереальными по красоте натуральными камнями, выполненные вручную <a href=\"/ru/shop/jewellery/silver\">украшения из серебра</a>, и много других удивительных аксессуаров, нам всегда хотелось все забрать с собой. &nbsp;</p>\n<p>И вот, наконец, 2015 год стал годом рождения проекта <a href=\"/\">Curry Moon</a>, в котором мы смогли воплотить в жизнь все наши идеи, желания и представления об интернет-магазине, где сегодня мы смело делимся с Вами нашими находками, которые отражают весь колорит, самобытность, свободу и энергетику невероятной Индии, и конечно же, рассказываем интересные истории из странствий.</p>\n<p>Каждый раз, попадая на шумные восточные базары, в маленькие мастерские или «самодельные» шоу-румы, мы отбираем самые вдохновляющие и оригинальные по дизайну и расцветке товары. Практически все модели у нас представлены в единственном экземпляре. Мы относимся к покупке очень тщательно, выбирая, как для себя, проверяя надежность материалов и фурнитуры, поэтому можем поручиться за качество каждого изделия.</p>\n<p><a href=\"/ru/blog\">Путешествуйте с нами</a>, покупая на нашем сайте. Подписывайтесь на обновления и следуйте за нами в социальных сетях. Спасибо!</p>\n<p><img src=\"/public/images/aboutus.jpg\" alt=\"\"></p>\n<p>С уважением, Ольга и Елена - команда Curry Moon</p>','','','','2017-11-29 17:23:46'),(2,'discount','Скидки и бонусы','','<h3>Условия получения скидки или бонуса</h3>\n<ul class=\"gkBullet2\">\n<li>Каждый покупатель может получить&nbsp;скидку, совершая покупку товара, который участвует в <a href=\"/ru/aktcii-i-skidki\">акции</a> или распродаже.</li>\n<li>Для&nbsp;постоянных покупателей&nbsp;у нас действует&nbsp;накопительная система скидок:</li>\n</ul>\n<p>Совершившив покупку на сумму от 2000 рублей, Вы получаете скидку на следующую покупку в размере 5%. Скидка отправляется на Вашу электронную почту в виде промо-кода. Данный промо-код бессрочный, и может быть применен к любой из следующих покупок. При накоплении следующей фиксированной суммы, скидка возрастает. Промо-код, дающий право на большую скидку, так же отправляется на Ваш электронный адрес.</p>\n<ul class=\"gkBullet2\">\n<li>от 2000р&nbsp;- скидка 5%</li>\n<li>от 15000р&nbsp;- скидка 7%</li>\n<li>от 30000р&nbsp;- скидка 10%</li>\n</ul>\n<p>Внимание! Персональные скидки не комбинируются с другими скидками и не распространяются на стоимость доставки.</p>','','','','2017-11-29 17:25:27'),(3,'payment','Оплата','','<h3>Оформление заказа</h3>\n<p>Для оформления покупки Вам необходимо:</p>\n<ul class=\"gkBullet2\">\n<li>Выбрать понравившийся товар</li>\n<li>Добавить его в корзину</li>\n<li>Выбрать удобный <a href=\"/ru/delivery\">способ доставки</a></li>\n<li>Подтвердить заказ</li>\n</ul>\n<p>Особые пожелания указываются в графе \"Комментарии к заказу\"</p>\n<p>После подтверждения заказа Вы попадете на страницу оплаты, где выбираете удобный способ оплаты, вводите необходимые данные, и подтверждаете операцию оплаты. Менеджер магазина свяжется с Вами посредством электронной почты для уточнения удобного для Вас времени и даты доставки.</p>\n<p>Если по окончании оформления и подтверждения заказа Вы захотите внести в него изменения или отменить его, напишите письмо на электронный адрес <span id=\"cloak3df6e2f88e47e3bb5757e31623039cbd\"><a href=\"mailto:info@curry-moon.com\">info@curry-moon.com</a></span><script type=\"text/javascript\">\n				document.getElementById(\'cloak3df6e2f88e47e3bb5757e31623039cbd\').innerHTML = \'\';\n				var prefix = \'&#109;a\' + \'i&#108;\' + \'&#116;o\';\n				var path = \'hr\' + \'ef\' + \'=\';\n				var addy3df6e2f88e47e3bb5757e31623039cbd = \'&#105;nf&#111;\' + \'&#64;\';\n				addy3df6e2f88e47e3bb5757e31623039cbd = addy3df6e2f88e47e3bb5757e31623039cbd + \'c&#117;rry-m&#111;&#111;n\' + \'&#46;\' + \'c&#111;m\';\n				var addy_text3df6e2f88e47e3bb5757e31623039cbd = \'&#105;nf&#111;\' + \'&#64;\' + \'c&#117;rry-m&#111;&#111;n\' + \'&#46;\' + \'c&#111;m\';document.getElementById(\'cloak3df6e2f88e47e3bb5757e31623039cbd\').innerHTML += \'<a \' + path + \'\\\'\' + prefix + \':\' + addy3df6e2f88e47e3bb5757e31623039cbd + \'\\\'>\'+addy_text3df6e2f88e47e3bb5757e31623039cbd+\'<\\/a>\';\n		</script>, и сообщие о желаемых изменениях, указав номер заказа и ваши ФИО. Менеджер магазина внесет корректировки в заказ и сообщит Вам об этом.</p>\n<p>Обращаем Ваше внимание, что отмена или изменение заказа возможны только при условии, что товар не был отправлен Вам почтой или курьером. В случае, если Вы решили отказаться от покупки, или изменить заказ, в момент, когда он уже был отправлен, выбранным вами способом, это также не страшно. Процедура обмена или возврата товара будет осуществляться согласно <a href=\"/ru/terms-and-conditions\">Условиям обслуживания</a>.</p>\n<h3>Оплата заказа</h3>\n<ul class=\"gkBullet2\">\n<ul class=\"gkBullet2\">\n<ul class=\"gkBullet2\">\n<ul class=\"gkBullet2\">\n<ul class=\"gkBullet2\">\n<ul class=\"gkBullet2\">\n<li><strong>Банковской картой</strong><br>Оплата банковской картой производится путем перечисления денежных средств на карту продавца (Сбербанк, Альфа-банк). Номер карты с данными высылается на Вашу электронную почту по завершении оформления заказа на сайте.&nbsp;<span style=\"line-height: 28px;\">После поступления денежных средств, купленный Вами товар по указанному адресу.</span><br> Мы принимаем к оплате: Visa / Maestro / MasterCard.</li>\n<li><strong>Электронным платежом Яндекс.Деньги</strong><br>Оплата производится через платежную систему Яндекс.Деньги.<br>В данном случае оплата заказа производится на платежном шлюзе Яндекс.Деньги. <br>Яндекс.Деньги не передает данные Вашей карты магазину и иным третьим лицам. Безопасность платежей с помощью банковских карт обеспечивается технологиями защищенного соединения HTTPS и двухфакторной аутентификации пользователя 3D Secure.<br>В соответствии с ФЗ «О защите прав потребителей» в случае, если Вам оказана услуга или реализован товар ненадлежащего качества, платеж может быть возвращен на банковскую карту, с которой производилась оплата. Порядок возврата средств уточняйте у администрации интернет-магазина.<br> Сайт платежной системы Яндекс.Деньги: <a href=\"http://money.yandex.ru/\" target=\"_blank\">money.yandex.ru</a></li>\n<li><strong>Наличными</strong></li>\n</ul>\n</ul>\n</ul>\n</ul>\n</ul>\n</ul>\n<p>Оплата наличными возможна только при получении заказа самовывозом.</p>                     ','','','','2017-11-29 17:27:00'),(4,'delivery','Доставка','','<h3>Доставка товара при заказе от 10 000 рублей бесплатная</h3>\n<p>Бесплатная доставка осуществляется после 100% предоплаты заказа по Москве и в регионы России следующими службами:</p>\n<ul class=\"gkBullet2\">\n<li>курьерская доставка по Москве</li>\n<li>доставка Почтой России в регионы</li>\n</ul>\n<p>Доставка по Московской области и в страны СНГ рассчитывается по тарифам курьерской службы или Почты России.</p>\n<h3>Доставка товара при заказе на сумму менее 10 000 рублей</h3>\n<p>Доставка осуществляется после 100% предоплаты заказа по всем городам России, странам СНГ и всему миру следующими службами:</p>\n<h3>Почта России (1 класс)</h3>\n<p><img src=\"/public/images/delivery/Russian_Post.png\" alt=\"Russian Post\"></p>\n<p>Доставка заказа осуществляется в ближайшее к Вам почтовое отделение. После оплаты заказа мы высылаем посылку и номер почтового идентификатора, по которому Вы сможете отслеживать отправление. По указанному в заявке адресу приходит почтовое уведомление о том, что Вам необходимо прийти в почтовое отделение и получить свой заказ.</p>\n<ul class=\"gkBullet2\">\n<li>Стоимость доставки для жителей России фиксированная - 400 руб. (взимается сразу при оплате заказа)</li>\n<li>Стоимость доставки для жителей СНГ расчитывается индивидуально менеджером магазина по тарифам Почты России (оплата за доставку взимается сразу при оплате заказа)</li>\n<li>Стоимость доставки за рубеж – расчитывается индивидуально менеджером магазина по тарифам Почты России (<span id=\"cloake4f3bb36632cc4be4dabe0b434f8cf6b\"><a href=\"mailto:info@curry-moon.com\">info@curry-moon.com</a></span><script type=\"text/javascript\">\n				document.getElementById(\'cloake4f3bb36632cc4be4dabe0b434f8cf6b\').innerHTML = \'\';\n				var prefix = \'&#109;a\' + \'i&#108;\' + \'&#116;o\';\n				var path = \'hr\' + \'ef\' + \'=\';\n				var addye4f3bb36632cc4be4dabe0b434f8cf6b = \'&#105;nf&#111;\' + \'&#64;\';\n				addye4f3bb36632cc4be4dabe0b434f8cf6b = addye4f3bb36632cc4be4dabe0b434f8cf6b + \'c&#117;rry-m&#111;&#111;n\' + \'&#46;\' + \'c&#111;m\';\n				var addy_texte4f3bb36632cc4be4dabe0b434f8cf6b = \'&#105;nf&#111;\' + \'&#64;\' + \'c&#117;rry-m&#111;&#111;n\' + \'&#46;\' + \'c&#111;m\';document.getElementById(\'cloake4f3bb36632cc4be4dabe0b434f8cf6b\').innerHTML += \'<a \' + path + \'\\\'\' + prefix + \':\' + addye4f3bb36632cc4be4dabe0b434f8cf6b + \'\\\'>\'+addy_texte4f3bb36632cc4be4dabe0b434f8cf6b+\'<\\/a>\';\n		</script>)</li>\n</ul>\n<p>Сроки доставки составляют от 7 до 20 дней, в зависимости от удаленности Вашего города.</p>\n<p>Официальный сайт Почты России: <a href=\"http://www.russianpost.ru\" target=\"_blank\" rel=\"noopener noreferrer\">www.russianpost.ru</a>.</p>\n<h3>Доставка курьером</h3>\n<p><img src=\"/public/images/delivery/major-express.gif\" alt=\"major express\"></p>\n<p>Экспресс-доставка Major Express. Доставка заказов по России и странам СНГ осуществляется курьером с понедельника по пятницу с 9:00 до 18:00.</p>\n<p>Преимуществами этого способа доставки являются скорость и удобство получения, так как Вы получаете свой заказ по удобному для вас адресу, лично в руки.</p>\n<p>Сроки доставки составляют от 1 до 6 дней, в зависимости от удаленности Вашего города.</p>\n<p>Калькулятор стоимости доставки Major Express - <a href=\"http://www.major-express.ru\" target=\"_blank\" rel=\"noopener noreferrer\">www.major-express.ru</a></p>\n<p>Оплата доставки взимается курьером в момент передачи заказа.</p>\n<h3>Самовывоз (бесплатно)</h3>\n<p>Вы можете забрать свой заказ самостоятельно от станции метро Парк Культуры, Смоленская или Арбатская. После подтверждения заказа менеджер магазина свяжется с Вами для уточнения даты и времени передачи Вашей покупки.</p>','','','','2017-11-29 17:28:11'),(5,'sotrudnichestvo','Сотрудничество','','','','','','2017-11-29 15:38:40'),(6,'terms-and-conditions','Условия обслуживания','','<div class=\"vendor-description\">\n		<p>Команда интернет-магазина Curry-Moon.com будет рада помочь подобрать Вам именно тот товар, который удовлетворит Ваши потребности, будет радовать Вас или Ваших близких. Если товар Вам все же не подойдет - мы предлагаем удобную схему обмена, а также 14 дней на возврат товара!</p>\n<h3>Условия обмена товара:</h3>\n<ul class=\"gkBullet1\">\n<li>Упаковка товара не была повреждена</li>\n<li>Товар не был в употреблении, сохранены товарный вид и потребительские свойства, не подвергался стирке, химчистке, глажке и т.д.</li>\n<li>Обмен товара возможен на товар аналогичной или большей стоимости с соответствующей доплатой со стороны Клиента. При обмене товара с меньшей стоимостью Продавец возвращает Клиенту остаток денежных средств.</li>\n<li>При обмене товара Клиент полностью оплачивает транспортные расходы, связанные с операцией обмена товара.</li>\n</ul>\n<h3>Процедура обмена товара:</h3>\n<p>Если приобретенный Вами товар не подошел по размеру или по другим параметрам, и Вы хотели бы его обменять, Вам необходимо написать письмо на электронный адрес <span id=\"cloakdcba48cfda964f865f8d4e46928cbb87\"><a href=\"mailto:info@curry-moon.com\">info@curry-moon.com</a></span><script type=\"text/javascript\">\n				document.getElementById(\'cloakdcba48cfda964f865f8d4e46928cbb87\').innerHTML = \'\';\n				var prefix = \'&#109;a\' + \'i&#108;\' + \'&#116;o\';\n				var path = \'hr\' + \'ef\' + \'=\';\n				var addydcba48cfda964f865f8d4e46928cbb87 = \'&#105;nf&#111;\' + \'&#64;\';\n				addydcba48cfda964f865f8d4e46928cbb87 = addydcba48cfda964f865f8d4e46928cbb87 + \'c&#117;rry-m&#111;&#111;n\' + \'&#46;\' + \'c&#111;m\';\n				var addy_textdcba48cfda964f865f8d4e46928cbb87 = \'&#105;nf&#111;\' + \'&#64;\' + \'c&#117;rry-m&#111;&#111;n\' + \'&#46;\' + \'c&#111;m\';document.getElementById(\'cloakdcba48cfda964f865f8d4e46928cbb87\').innerHTML += \'<a \' + path + \'\\\'\' + prefix + \':\' + addydcba48cfda964f865f8d4e46928cbb87 + \'\\\'>\'+addy_textdcba48cfda964f865f8d4e46928cbb87+\'<\\/a>\';\n		</script> с темой письма \"обмен товара\", и указать тот товар, на который Вы хотели бы обменять купленный прежде.</p>\n<p>Мы свяжемся с Вами для уточнения возможности обмена на выбранный товар и сообщим почтовый адрес, на который вы сможете вернуть неподходящий товар.</p>\n<p>После того, как товар будет получен, мы отправляем Вам новый товар, который Вы выбрали на замену.</p>\n<h3>Как можно произвести обмен товара?</h3>\n<p>Товар для обмена отправляется Почтой России. Адрес для отправки товара Вам сообщит менеджер магазина по электронной почте.</p>\n<h3>Условия возврата товара:</h3>\n<ul class=\"gkBullet1\">\n<li>Упаковка товара не была повреждена</li>\n<li>Товар не был в употреблении, сохранены товарный вид и потребительские свойства, не подвергался стирке, химчистке, глажке и т.д.</li>\n<li>При возврате товара Клиент полностью оплачивает транспортные расходы, связанные с операцией возврата товара.</li>\n</ul>\n<h3>Процедура возврата товара:</h3>\n<p>Если приобретенный Вами товар не подошел по размеру или по другим характеристикам, и Вы хотели бы вернуть его, Вам необходимо написать письмо на электронный адрес <span id=\"cloak4265576d0649f7ee637db234927bfb48\"><a href=\"mailto:info@curry-moon.com\">info@curry-moon.com</a></span><script type=\"text/javascript\">\n				document.getElementById(\'cloak4265576d0649f7ee637db234927bfb48\').innerHTML = \'\';\n				var prefix = \'&#109;a\' + \'i&#108;\' + \'&#116;o\';\n				var path = \'hr\' + \'ef\' + \'=\';\n				var addy4265576d0649f7ee637db234927bfb48 = \'&#105;nf&#111;\' + \'&#64;\';\n				addy4265576d0649f7ee637db234927bfb48 = addy4265576d0649f7ee637db234927bfb48 + \'c&#117;rry-m&#111;&#111;n\' + \'&#46;\' + \'c&#111;m\';\n				var addy_text4265576d0649f7ee637db234927bfb48 = \'&#105;nf&#111;\' + \'&#64;\' + \'c&#117;rry-m&#111;&#111;n\' + \'&#46;\' + \'c&#111;m\';document.getElementById(\'cloak4265576d0649f7ee637db234927bfb48\').innerHTML += \'<a \' + path + \'\\\'\' + prefix + \':\' + addy4265576d0649f7ee637db234927bfb48 + \'\\\'>\'+addy_text4265576d0649f7ee637db234927bfb48+\'<\\/a>\';\n		</script> с темой письма \"возврат товара\", и указать способ, по которому вы производили оплату.</p>\n<p>Мы свяжемся с Вами и сообщим почтовый адрес, на который Вы сможете переслать возвращаемый товар.</p>\n<p>После того, как товар будет полученн, мы перечислим Вам денежные средства равные стоимости товара таким же способом, каким осуществлялась оплата товара:</p>\n<ul class=\"gkBullet1\">\n<li>При оплате банковской картой - переводом на счет банковской карты</li>\n<li>При онлайн-оплате Яндекс.Деньги – переводом на счет кошелька Яндекс.Деньги</li>\n<li>При безналичной оплате - переводом на банковский счет покупателя.</li>\n</ul>	</div>','','','','2017-11-29 17:30:33');
/*!40000 ALTER TABLE `static_pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_login` varchar(50) NOT NULL DEFAULT '',
  `user_password` varchar(100) NOT NULL,
  `user_sex` varchar(50) DEFAULT NULL,
  `user_name` varchar(250) DEFAULT NULL,
  `user_show_name` enum('true','false') NOT NULL DEFAULT 'false',
  `user_email` varchar(50) NOT NULL DEFAULT '',
  `user_show_email` enum('true','false') CHARACTER SET latin1 NOT NULL DEFAULT 'false',
  `user_phone` varchar(80) NOT NULL COMMENT 'телефон пользователя',
  `user_birthday` date DEFAULT NULL,
  `user_register_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_ip` int(10) unsigned NOT NULL DEFAULT '0',
  `user_url` varchar(250) DEFAULT NULL,
  `user_picture` int(11) NOT NULL DEFAULT '0',
  `user_description` text,
  `user_status` enum('active','deleted','banned') NOT NULL DEFAULT 'active',
  `user_last_login` datetime DEFAULT '0000-00-00 00:00:00',
  `user_activation` enum('done','waiting','fail1','fail2','failed','deleted') CHARACTER SET latin1 NOT NULL DEFAULT 'waiting',
  `user_is_fake` enum('true','false') CHARACTER SET latin1 NOT NULL DEFAULT 'false',
  `user_subscription_status` enum('active','canceled') CHARACTER SET latin1 NOT NULL DEFAULT 'active' COMMENT 'получает ли почтовую рассылку',
  `user_address` varchar(200) NOT NULL,
  `user_zip` varchar(30) NOT NULL,
  `user_country_id` int(11) NOT NULL,
  `user_city_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_name` (`user_name`),
  KEY `user_login` (`user_login`),
  KEY `users_fake` (`user_is_fake`),
  KEY `birthday` (`user_birthday`),
  KEY `email` (`user_email`),
  KEY `phone` (`user_phone`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (22,'anton.maslenn****@gmail.com','55da33ac3daf490610a47d690f05c649',NULL,'Маслеников Антон Николаевич','false','anton.maslennikov@gmail.com','false','',NULL,'2017-12-01 15:31:10',2130706433,NULL,0,NULL,'active','2017-12-01 18:44:00','done','false','active','','',176,12932);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users__meta`
--

DROP TABLE IF EXISTS `users__meta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users__meta` (
  `meta_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `meta_name` varchar(30) NOT NULL,
  `meta_value` varchar(10000) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`meta_id`),
  UNIQUE KEY `user_id` (`user_id`,`meta_name`),
  KEY `meta_id` (`meta_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users__meta`
--

LOCK TABLES `users__meta` WRITE;
/*!40000 ALTER TABLE `users__meta` DISABLE KEYS */;
/*!40000 ALTER TABLE `users__meta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `variables`
--

DROP TABLE IF EXISTS `variables`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `variables` (
  `variable_id` smallint(10) unsigned NOT NULL AUTO_INCREMENT,
  `variable_name` varchar(45) CHARACTER SET latin1 NOT NULL COMMENT 'Имя',
  `variable_value` text NOT NULL COMMENT 'Значение',
  `variable_description` varchar(255) DEFAULT NULL COMMENT 'Описание',
  `variable_category` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Категория',
  PRIMARY KEY (`variable_id`),
  UNIQUE KEY `variable_name` (`variable_name`),
  KEY `var` (`variable_name`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `variables`
--

LOCK TABLES `variables` WRITE;
/*!40000 ALTER TABLE `variables` DISABLE KEYS */;
INSERT INTO `variables` VALUES (1,'aaa','vvv',NULL,0),(2,'contactEmail','info@curry-moon.com',NULL,0),(3,'contactPhone1','+7 (985) 974-08-37',NULL,0),(4,'contactPhone2','+7 (916) 416-20-63',NULL,0),(5,'contactAddress','Россия, г. Москва, Языковский переулок, 5/6',NULL,0);
/*!40000 ALTER TABLE `variables` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-12-01 23:17:44
=======
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
>>>>>>> dd7b84b29c6d2c2773fd83cab80b7603c33829c7
