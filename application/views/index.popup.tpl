<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
    <title>{if $PAGE->utitle}{$PAGE->utitle}{else}{$PAGE->title}{/if} | Maryjane.ru </title>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta content="Прикольные футболки, купить футболки, фото футболки, фото на футболке, печать футболок, О футболках, печать на футболках, шелкография, нанесение рисунка, рисунки, иллюстратор,дизайнер, дизайн футболок, прикольные футболки, футболки надпись, купить футболку, где купить футболку, интернет-магазин футболки,оформление футболки, прикольных маек, майки с необычными рисунками, необычные рисунки, прикольные надписи,маки с надписями,майки с прикольными надписями,изгототовление футболок,каталог футболок,склад футболок, заказ футболок, заказать футболку, заказ футболки, прикольные майки, подарок день рождения, необычные подарки,футболки с рисунком" name="keywords" />
    <meta name='yandex-verification' content='654fbafa72e83003' />
    <meta name='yandex-verification' content='52db074e1fe65d18' />
    <meta name="verify-v1" content="rumBtJv9WGgVeIDRJSF7nDqeVEJsWO7JeAkaSQjZbC0=" />
    <meta content="Maryjane.ru - эксклюзивные футболки, печать 1 футболки, футболки под заказ, прикольные майки, магазин футболок, надпись на футболках" name="description" />

    <link href="http://www.maryjane.ru/favicon.ico" rel="shortcut icon" type="image/x-icon"/>

    <style type="text/css" media="screen, projection">
        @import url('/css/css_v4.css');
        @import url('/css/css_v4.1.css');
        @import url('/css/thickbox.css');
        @import url('/css/tabs.css');
    </style>

	{if $PAGE->js|count > 0}
		<script type='text/javascript' src="http://www.maryjane.ru/min/?f={foreach $PAGE->js item="path" name="jsforeach"}{$path}{if !$smarty.foreach.jsforeach.last},{/if}{/foreach}"></script>
	{/if}
	
	{if $PAGE->css|count > 0}
		<link rel="stylesheet" href="http://www.maryjane.ru/min/?f={foreach $PAGE->css item="path" name="cssforeach"}{$path}{if !$smarty.foreach.cssforeach.last},{/if}{/foreach}" type="text/css" media="all"/>
	{/if}

    <meta name="robots" content="noindex">

{literal}
    <!--[if lte IE 6]>
    <link href="/css/mj-menu-ie.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="http://maryjane.ru/js/mj-menu.js"></script>
    <style type="text/css">.clearfix { height: 1% }</style>
    <![endif]-->
    <!--[if gte IE 7.0]>
    <style type="text/css">.clearfix {display: inline-block}</style>
    <![endif]-->
{/literal}
	
</head>
<body>
{if $PAGE->module=="faq" && $USER->country!='RU'}
	<div id="ytWidget" class="faqPopup">{*yandex перводчик*}</div>
	<script src="https://translate.yandex.net/website-widget/v1/widget.js?widgetId=ytWidget&pageLang=ru&widgetTheme=light&trnslKey=trnsl.1.1.20150713T073056Z.c4c1f11c5756ec50.e4f968cd9b96c79e3bb9bc6fad61f62f373e61fa&autoMode=false" type="text/javascript"></script>
{/if}

{if $content_tpl}
	{include file=$content_tpl}
{else}
	{include file=$PAGE->tpl}
{/if}

<!--[if lte IE 6]>
<script type='text/javascript' src="http://maryjane.ru/js/fixpng.js"></script>
<![endif]-->

</body>
</html>