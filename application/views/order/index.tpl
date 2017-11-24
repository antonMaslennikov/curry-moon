<!DOCTYPE html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
<title>{$PAGE_TITLE} {$PAGE_TITLE_ADDITIONAL} - Maryjane.ru</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<meta content="{$PAGE_DESCRIPTION}" name="description" />
<meta content="{$PAGE_META}" name="keywords" />

<meta property="og:title" content="{$PAGE_TITLE} {$PAGE_TITLE_ADDITIONAL} - Maryjane.ru"/>
<meta property="og:type" content="Maryjane.ru - магазин дизайнерских футболок №1" />
<meta property="og:site_name" content="Maryjane.ru - магазин дизайнерских футболок №1" />
<meta property="og:description" content="{$PAGE_DESCRIPTION}" />
<meta property="fb:app_id" content="192523004126352" />

<meta name='yandex-verification' content='654fbafa72e83003' />
<meta name='yandex-verification' content='52db074e1fe65d18' />
<meta name="verify-v1" content="rumBtJv9WGgVeIDRJSF7nDqeVEJsWO7JeAkaSQjZbC0=" />

{if $noindex}
<meta name="robots" content="noindex">
{/if}

<link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon"/>

<link rel="stylesheet" href="/css/css_v4.css" type="text/css" media="all"/>
<link rel="stylesheet" href="/css/css_v4.1.css" type="text/css" media="all"/>
<link rel="stylesheet" href="/css/thickbox.css" type="text/css" media="screen"/>
<link rel="stylesheet" href="/css/tabs.css" type="text/css" media="print, projection, screen"/>

<script type='text/javascript' src='/js/jquery.js'></script>
<script type='text/javascript' src='/js/jquery-ui.js'></script>
<script type='text/javascript' src='/js/main.js'></script>

{literal}

<!--[if lte IE 6]>
<link rel="stylesheet" type="text/css" href="/css/mj-menu-ie.css" />
<link rel="stylesheet" type="text/css" href="/css/mj-menu-ie6.css" media="all">
<script type="text/javascript" src="/js/mj-menu.js"></script>
<style type="text/css">
.clearfix {	height: 1%}
</style>
<script type="text/javascript" src="/js/fixpng.js"></script>
<![endif]-->
<!--[if gte IE 7.0]>
<style type="text/css">
.clearfix {	display: inline-block}
</style>
<![endif]-->
{/literal}

</head>
<body>
	
	{if $USER->authorized}
	<script type="text/javascript">var authorized = true;</script>
	{else}
	<script type="text/javascript">var authorized = false;</script>
	<script type='text/javascript' src='/js/2012/login_form_with_fb.js'></script>
	<link rel="stylesheet" href="/css/registration_page.css" type="text/css" media="screen"/>
	{/if}
	
	{if $top_line}
		{include file="$top_line"}
	{else}
		{include file="top_line.tpl"}
	{/if}
<div id="wrapper">
	
		{* <a href="http://www.maryjane.ru/catalog/"  title="Новогодняя распродажа" id="sale-2012-banner"><img src="http://www.maryjane.ru/images/ny2012/sale_ot_590.gif"  width="980" alt="Новогодняя распродажа" /></a> *}
	
		<div id="containerwrap">
	
		<!-- начало MAINBODY -->
		<div id="mainbody"class="clearfix">
			
			<!-- CONTENT -->
			<div id="content"class="clearfix">
				<div id="current-content" class="clearfix">		
				{if $content_tpl} 
					{include file=$content_tpl}
				{else}
					Не указан шаблон $content_tpl
				{/if}
				</div>
			</div>
			<!-- /CONTENT -->
			
		</div>
		<!-- /MAINBODY -->

		</div>
	
	</div>

	<div class="basket-footer">
	<div class="footer-text">
	&copy;2003-2013 Футболки Maryjane.ru &mdash; магазин футболок #1. <br/>
	Самые свежие, эксклюзивные работы на заказ.
	</div>
	</div>	

<!-- /CONTENTWRAP -->

<!-- /WRAPPER -->

<!-- Google-analytics -->
{literal}
<script type="text/javascript">

	var _gaq = _gaq || [];
	_gaq.push(['_setAccount', 'UA-2491544-1']);
	_gaq.push(['_setDomainName', '.maryjane.ru']);
	_gaq.push(['_setAllowHash', false]);
	_gaq.push(['_trackPageview']);

	{/literal}
	
	{if $GA_T_I}
	
		_gaq.push(['_addTrans',
		    '{$order.user_basket_id}',    	// order ID - required
		    'maryjane.ru',       	// affiliation or store name
	    	'{$totalPrice}',     // total - required
		    '0',           		// tax
			'{$deliveryCost}',   // shipping
		    '{$basketCity}',     // city
		    '{$basketKray}',     // state or province
		    '{$basketCountry}'   // country
		]);
	
		{foreach from=$GA_T_I item="i"}  
		_gaq.push(['_addItem',
		    '{$order.user_basket_id}',           							// order ID - required
		    '{$i.good_stock_id}',       		    // SKU/code
		    '{$i.good_name}',      				// product name
		    '{$i.style_name}',  					// category or variation
		    '{$i.user_basket_good_total_price}',  // unit price - required
		    '{$i.user_basket_good_quantity}'      // quantity - required
		]);
		{/foreach}
		
		_gaq.push(['_trackTrans']); //submits transaction to the Analytics servers
	
	{/if}
	
	
	{literal}

	(function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	})();

</script>
{/literal}

<!-- Yandex.Metrika counter -->
{literal}
	<div style="display:none;"><script type="text/javascript">
	(function(w, c) {
	    (w[c] = w[c] || []).push(function() {
	        try {
	            w.yaCounter265828 = new Ya.Metrika({id:265828, enableAll: true, webvisor:true});
	        }
	        catch(e) { }
	    });
	})(window, "yandex_metrika_callbacks");
	</script></div>
	<script src="//mc.yandex.ru/metrika/watch.js" type="text/javascript" defer="defer"></script>
	<noscript><div><img src="//mc.yandex.ru/watch/265828" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
{/literal}
<!-- /Yandex.Metrika counter -->

<!--LiveInternet counter-->
{literal}
<script type="text/javascript"><!--
document.write("<a rel=\"nofollow\" href='http://www.liveinternet.ru/click' "+
"target=_blank><img src='//counter.yadro.ru/hit?t45.6;r"+
escape(document.referrer)+((typeof(screen)=="undefined")?"":
";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?
screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
";h"+escape(document.title.substring(0,80))+";"+Math.random()+
"' alt='' title='LiveInternet' "+
"border='0' width='1' height='1'><\/a>")
//--></script><!--/LiveInternet-->
{/literal}


</body>
</html>