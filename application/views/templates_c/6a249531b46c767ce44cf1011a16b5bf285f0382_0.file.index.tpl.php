<?php
/* Smarty version 3.1.31, created on 2017-11-24 18:07:55
  from "C:\OpenServer\domains\shop.loc\application\views\main\2017\09\\index.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5a1835cbf1ff34_23375666',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '6a249531b46c767ce44cf1011a16b5bf285f0382' => 
    array (
      0 => 'C:\\OpenServer\\domains\\shop.loc\\application\\views\\main\\2017\\09\\\\index.tpl',
      1 => 1511421465,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:init_js_variables_from_top-line.tpl' => 1,
    'file:index.givegiftsHINT.tpl' => 1,
    'file:index.ypravlenie_zakazom.tpl' => 1,
    'file:index.freedelivery.tpl' => 1,
    'file:index.messages.tpl' => 1,
    'file:index.error.tpl' => 1,
    'file:top_line.mobile.tpl' => 1,
    'file:top_line.v4.tpl' => 1,
    'file:top_menu.v5.tpl' => 1,
    'file:index.admin_menu.tpl' => 1,
    'file:index.promolink.tpl' => 1,
    'file:index.counters.tpl' => 1,
    'file:phonecall-notifier.tpl' => 1,
  ),
),false)) {
function content_5a1835cbf1ff34_23375666 (Smarty_Internal_Template $_smarty_tpl) {
?>


	<?php if ($_COOKIE['MobilePageVersion'] != 2 && (($_smarty_tpl->tpl_vars['USER']->value->client->ismobiledevice == '1' && $_smarty_tpl->tpl_vars['USER']->value->client->istablet == 0) || $_COOKIE['MobilePageVersion'] == 1)) {?>
		<?php $_smarty_tpl->_assignInScope('MobilePageVersion', "1");
?>
	<?php }?>


<!DOCTYPE html>
<html xmlns:fb="http://www.facebook.com/2008/fbml" lang="<?php echo $_smarty_tpl->tpl_vars['PAGE']->value->lang;?>
">
<head>   

	<title><?php if ($_smarty_tpl->tpl_vars['PAGE']->value->utitle) {
echo $_smarty_tpl->tpl_vars['PAGE']->value->utitle;
} else {
echo $_smarty_tpl->tpl_vars['PAGE']->value->title;
}?> - Maryjane.ru</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	
	<?php if ($_smarty_tpl->tpl_vars['MobilePageVersion']->value) {?>
	<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, user-scalable=no, maximum-scale=1.0"/>
	<?php }?>
	
	<meta name="mailru" content="c82e9ec8a98f63c2" />
	<meta name='yandex-verification' content='654fbafa72e83003' />
	<meta name='yandex-verification' content='52db074e1fe65d18' />
	<meta name="verify-v1" content="rumBtJv9WGgVeIDRJSF7nDqeVEJsWO7JeAkaSQjZbC0=" />
	<meta name="google-site-verification" content="5xyTk5IPMhoKTgTCtjUejFk1FTSmO0yARy_Yk0b2H3w" />
	
	
	<meta property="og:title" content="<?php if ($_smarty_tpl->tpl_vars['PAGE']->value->ogPAGE_TITLE) {
echo $_smarty_tpl->tpl_vars['PAGE']->value->ogPAGE_TITLE;
} else {
echo $_smarty_tpl->tpl_vars['PAGE']->value->title;?>
 - Maryjane.ru<?php }?>"/>
	<meta property="og:url" content="http://www.maryjane.ru<?php if ($_smarty_tpl->tpl_vars['PAGE']->value->ogUrl) {
echo $_smarty_tpl->tpl_vars['PAGE']->value->ogUrl;
} else { ?>?utm_medium=social&utm_campaign=gigya&utm_source=island<?php }?>" />
	<meta property="og:image" content="<?php if ($_smarty_tpl->tpl_vars['PAGE']->value->ogImage) {
echo $_smarty_tpl->tpl_vars['PAGE']->value->ogImage;
} else { ?>http://www.maryjane.ru/images/mj_logo_shar-new.png<?php }?>" />
	<meta property="og:type" content="website" />
	<meta property="og:site_name" content="Maryjane Мэриджейн" />
	
	<meta property="og:description" content="<?php if ($_smarty_tpl->tpl_vars['PAGE']->value->ogPAGE_DESCRIPTION) {
echo $_smarty_tpl->tpl_vars['PAGE']->value->ogPAGE_DESCRIPTION;
} else {
if ($_smarty_tpl->tpl_vars['PAGE']->value->udescription) {
} else {
echo $_smarty_tpl->tpl_vars['PAGE']->value->description;
}
}?>" />
	
	<?php if ($_smarty_tpl->tpl_vars['PAGE']->value->noindex) {?>
		<meta name="robots" content="noindex">
	<?php }?>
	
	<?php if ($_smarty_tpl->tpl_vars['PAGE']->value->lang != 'en') {?>
	<link rel="alternate" hreflang="en" href="http://www.maryjane.ru/en/" />
	<?php }?>
	 
	<?php if ($_smarty_tpl->tpl_vars['PAGE']->value->lang != 'ru') {?>
	<link rel="alternate" hreflang="ru" href="http://www.maryjane.ru/" />
	<?php }?>
	
	<link href="https://plus.google.com/110170198345311125008" rel="publisher" />
	
	<link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon"/>
	
	<?php if ($_smarty_tpl->tpl_vars['MobilePageVersion']->value) {?>	
		<link rel="stylesheet" type="text/css" media="all" href="/min/g=css-mobile&v2"/>		
		<?php if ($_smarty_tpl->tpl_vars['active_order']->value && !$_smarty_tpl->tpl_vars['active_order']->value['hideExchangePanel']) {?>
			<link rel="stylesheet" href="/css/thickbox.css" type="text/css" media="all"/>
		<?php }?>			
	<?php } else { ?>
		<link rel="stylesheet" type="text/css" media="all" href="/min/?f=<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['PAGE']->value->css, 'path', false, NULL, 'cssforeach', array (
  'last' => true,
  'iteration' => true,
  'total' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['path']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_cssforeach']->value['iteration']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_cssforeach']->value['last'] = $_smarty_tpl->tpl_vars['__smarty_foreach_cssforeach']->value['iteration'] == $_smarty_tpl->tpl_vars['__smarty_foreach_cssforeach']->value['total'];
echo $_smarty_tpl->tpl_vars['path']->value;
if (!(isset($_smarty_tpl->tpl_vars['__smarty_foreach_cssforeach']->value['last']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_cssforeach']->value['last'] : null)) {?>,<?php }
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
?>
&v45" />
	<?php }?>
	
	<?php echo '<script'; ?>
 <?php if ($_smarty_tpl->tpl_vars['MobilePageVersion']->value) {?>async<?php }?> type='text/javascript' src="/min/?f=<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['PAGE']->value->js, 'path', false, NULL, 'jsforeach', array (
  'last' => true,
  'iteration' => true,
  'total' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['path']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_jsforeach']->value['iteration']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_jsforeach']->value['last'] = $_smarty_tpl->tpl_vars['__smarty_foreach_jsforeach']->value['iteration'] == $_smarty_tpl->tpl_vars['__smarty_foreach_jsforeach']->value['total'];
echo $_smarty_tpl->tpl_vars['path']->value;
if (!(isset($_smarty_tpl->tpl_vars['__smarty_foreach_jsforeach']->value['last']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_jsforeach']->value['last'] : null)) {?>,<?php }
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
?>
&v52"><?php echo '</script'; ?>
>
	
	<?php if (!$_smarty_tpl->tpl_vars['MobilePageVersion']->value) {?>
	<?php echo '<script'; ?>
 type="text/javascript" src="//vk.com/js/api/share.js?11" charset="windows-1251"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 type="text/javascript" src="//userapi.com/js/api/openapi.js?30"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 type="text/javascript">VK.init({ apiId: 2310743, onlyWidgets: true });<?php echo '</script'; ?>
>
	<?php }?>
	
	<?php if (!$_smarty_tpl->tpl_vars['MobilePageVersion']->value && $_smarty_tpl->tpl_vars['USER']->value->client->ismobiledevice == '1') {?>
		<style>#wrapmain70{ width: 980px;margin: 0 auto;padding: 0 30px; }</style>
	<?php }?>

	<?php $_smarty_tpl->_subTemplateRender("file:init_js_variables_from_top-line.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

	
	<?php if (!$_smarty_tpl->tpl_vars['USER']->value->meta->mjteam && $_smarty_tpl->tpl_vars['USER']->value->id != 190169 && $_smarty_tpl->tpl_vars['USER']->value->id != 96976 && $_smarty_tpl->tpl_vars['USER']->value->id != 10092 && !$_COOKIE['eatmj19022013']) {?>
	<!-- Google-analytics -->
	<?php echo '<script'; ?>
 type="text/javascript">
			
			(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){ (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');	
			ga('create', 'UA-2491544-1', 'auto');
			ga('require', 'displayfeatures');
				
		
		<?php if ($_smarty_tpl->tpl_vars['USER']->value->authorized) {?>ga('set', '&uid', <?php echo $_smarty_tpl->tpl_vars['USER']->value->id;?>
);<?php }?>	
		
		ga('send', 'pageview');
	<?php echo '</script'; ?>
>		
	<!-- /Google-analytics -->
	<?php }?>

</head>

<body class="<?php if ($_smarty_tpl->tpl_vars['USER']->value->client->ismobiledevice == '1') {?>ismobiledevice<?php }
if ($_smarty_tpl->tpl_vars['MobilePageVersion']->value) {?> MPV<?php }?>">

	
	
	<?php if (!$_smarty_tpl->tpl_vars['USER']->value->meta->mjteam) {?>
	
		<!-- Google Tag Manager -->
		<noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-5RFVBR"
		height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
		<?php echo '<script'; ?>
>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
		new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
		j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
		'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
		})(window,document,'script','dataLayer','GTM-5RFVBR');<?php echo '</script'; ?>
>
		<!-- End Google Tag Manager -->
	
	<?php }?>

<div id="wrapmain70">
	
	

	<?php if (!$_smarty_tpl->tpl_vars['MobilePageVersion']->value && !$_COOKIE['closegivegifts'] && !$_smarty_tpl->tpl_vars['USER']->value->authorized) {?>
		<?php $_smarty_tpl->_subTemplateRender("file:index.givegiftsHINT.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

	<?php }?>	
	
	<?php if (!$_smarty_tpl->tpl_vars['MobilePageVersion']->value) {?>
		<!--noindex-->
		<div class="need-hepl" style="position:fixed;top:217px;right:0"><a class="showFeedback" href="#"  title="Остался вопрос?" rel="nofollow"><img src="/images/buttons/needhelp_right.png" alt="Напишите нам"/></a></div>
		<!--/noindex-->
	<?php }?>
	
	<?php if (!$_smarty_tpl->tpl_vars['MobilePageVersion']->value) {?>
		<?php if ($_smarty_tpl->tpl_vars['active_order']->value && !$_smarty_tpl->tpl_vars['active_order']->value['hideExchangePanel']) {?>
			<?php $_smarty_tpl->_subTemplateRender("file:index.ypravlenie_zakazom.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

		<?php }?>
	<?php }?>
	
	
	<?php if ($_smarty_tpl->tpl_vars['MobilePageVersion']->value && $_smarty_tpl->tpl_vars['active_order']->value && !$_smarty_tpl->tpl_vars['active_order']->value['hideExchangePanel'] && $_COOKIE['closeYpravlenieZakazom'] != $_smarty_tpl->tpl_vars['active_order']->value['user_basket_id']) {?>
		<div class="ypravlenie_zakazom <?php echo $_smarty_tpl->tpl_vars['PAGE']->value->lang;?>
" <?php if ($_COOKIE['closeYpravlenieZakazom'] == $_smarty_tpl->tpl_vars['active_order']->value['user_basket_id']) {?>style="display:none"<?php }?>>
			<div class="y">
				<?php if ($_smarty_tpl->tpl_vars['exchengedBonuses']->value == 0) {?>
				<a rel="nofollow"  href="/orderhistory/<?php echo $_smarty_tpl->tpl_vars['active_order']->value['user_basket_id'];?>
/" class="n-zakaz" title="<?php echo $_smarty_tpl->tpl_vars['active_order']->value['status'];
if ($_smarty_tpl->tpl_vars['active_order']->value['user_basket_status'] != 'delivered' && $_smarty_tpl->tpl_vars['active_order']->value['printed'] >= $_smarty_tpl->tpl_vars['active_order']->value['goods']) {?>, <?php echo $_smarty_tpl->tpl_vars['L']->value['HEADER_management_made'];
}?>"><?php echo $_smarty_tpl->tpl_vars['L']->value['HEADER_management_order'];?>
 № <?php echo $_smarty_tpl->tpl_vars['active_order']->value['order'];?>
</a>
				<?php }?>
				
				<?php if ($_smarty_tpl->tpl_vars['active_order']->value['user_basket_status'] == 'delivered') {?>
					 <a rel="nofollow" href="#" class="border close-link" title="<?php echo $_smarty_tpl->tpl_vars['L']->value['HEADER_management_close'];?>
" data-order-id="<?php echo $_smarty_tpl->tpl_vars['active_order']->value['user_basket_id'];?>
" data-days="<?php echo $_smarty_tpl->tpl_vars['active_order']->value['exchangeIsPosible'];?>
">
						<span class="titl"><?php echo $_smarty_tpl->tpl_vars['L']->value['HEADER_management_close'];?>
</span>
						<span class="ico-o"></span>			
					</a>
				<?php }?>				
				
				<?php if ($_smarty_tpl->tpl_vars['active_order']->value['user_basket_status'] == 'ordered' && $_smarty_tpl->tpl_vars['active_order']->value['printed'] == 0) {?>
					<a rel="nofollow" href="/orderhistory/cancel/?height=210&width=260" class="border anull-link thickbox" title="<?php echo $_smarty_tpl->tpl_vars['L']->value['HEADER_management_cancel'];?>
">	
						<span class="ico-o"></span>	
						<span class="titl"><?php echo $_smarty_tpl->tpl_vars['L']->value['HEADER_management_cancel'];?>
</span>			
					</a>
				<?php }?>				
				<div style="clear:both"></div>
			</div>
		</div>
	<?php }?>
	
	<?php if (!$_smarty_tpl->tpl_vars['MobilePageVersion']->value) {?>
		<?php $_smarty_tpl->_subTemplateRender("file:index.freedelivery.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
	
	<?php }?>
	
	<?php $_smarty_tpl->_subTemplateRender("file:index.messages.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
	

	<?php $_smarty_tpl->_subTemplateRender("file:index.error.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

	
	<?php if (!$_smarty_tpl->tpl_vars['MobilePageVersion']->value) {?>
		
		<?php if ($_smarty_tpl->tpl_vars['USER']->value->meta->goodApproved && $_smarty_tpl->tpl_vars['USER']->value->id != 27278) {?>
		<div class="top-banner-line980">
			<a href="/voting/competition/winter2017/" rel="nofollow" title="Конкурс зимних принтов">
				<img src="/images/banners/winter2017.gif" alt='Конкурс зимних принтов' title='Конкурс зимних принтов' width="980" height="70"/>
			</a>
		</div>
		<?php } else { ?>
			<?php if (($_smarty_tpl->tpl_vars['datetime']->value['month'] == 11 && ($_smarty_tpl->tpl_vars['datetime']->value['day'] == 23 || $_smarty_tpl->tpl_vars['datetime']->value['day'] == 24 || $_smarty_tpl->tpl_vars['datetime']->value['day'] == 25)) || $_smarty_tpl->tpl_vars['USER']->value->id == 27278) {?>
			
				<div class="top-banner-line980">
					<a href="/catalog/" rel="nofollow" title="Чёрная пятница">
						<img src="/images/banners/blackfriday.jpg" alt='Чёрная пятница' />
					</a>
				</div>
			
			<?php }?>
		<?php }?>
		
		
		
		
		
		
		
		
		
		
		
		
		
	<?php }?>
	
	<div style="height:140px" class="topbar-remove">

		<?php if ($_smarty_tpl->tpl_vars['MobilePageVersion']->value) {?>
			<?php $_smarty_tpl->_subTemplateRender("file:top_line.mobile.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

		<?php } else { ?>
			<?php $_smarty_tpl->_subTemplateRender("file:top_line.v4.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
		
		<?php }?>
		
		<?php $_smarty_tpl->_subTemplateRender("file:top_menu.v5.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


		<?php if ($_smarty_tpl->tpl_vars['USER']->value->id == 27278 || $_smarty_tpl->tpl_vars['USER']->value->id == 81706 || ($_smarty_tpl->tpl_vars['USER']->value->id == 6199 && $_smarty_tpl->tpl_vars['USER']->value->client->ismobiledevice != '1')) {?>	
			<?php $_smarty_tpl->_subTemplateRender("file:index.admin_menu.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

		<?php }?>
		
		<?php if (!$_smarty_tpl->tpl_vars['MobilePageVersion']->value) {?>
			<a class="konkurs_flag" href="/voting/competition/main/" rel="nofollow" title="<?php echo $_smarty_tpl->tpl_vars['L']->value['MAIN_competition_win'];?>
 15000 р.*"><?php echo $_smarty_tpl->tpl_vars['L']->value['MAIN_competition_win'];?>
 15000 р.*</a>
		<?php }?>
	</div>
		
	<div class="main-wrapper topbar-remove">		
				
		<?php echo '<script'; ?>
>
			window.fbAsyncInit = function() {FB.init({appId: '192523004126352', status:true, cookie:true, xfbml: true });};
		        (function(d){
		           var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
		           js = d.createElement('script'); js.id = id; js.async = true;
		           js.src = "//connect.facebook.net/en_US/all.js";
		           d.getElementsByTagName('head')[0].appendChild(js);
		         }(document));	
		<?php echo '</script'; ?>
>			
		
		
		<?php if (!$_smarty_tpl->tpl_vars['MobilePageVersion']->value) {?>	
			<?php $_smarty_tpl->_subTemplateRender("file:index.promolink.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
	
		<?php }?>
		
		<h1><?php echo $_smarty_tpl->tpl_vars['PAGE']->value->title;?>
</h1>
		
		<?php if ($_smarty_tpl->tpl_vars['desktop_tpl']->value && $_smarty_tpl->tpl_vars['mobile_tpl']->value) {?> 
			<?php if ($_smarty_tpl->tpl_vars['MobilePageVersion']->value) {?>
				<?php $_smarty_tpl->_subTemplateRender($_smarty_tpl->tpl_vars['mobile_tpl']->value, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>

			<?php } else { ?>
				<?php $_smarty_tpl->_subTemplateRender($_smarty_tpl->tpl_vars['desktop_tpl']->value, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>

			<?php }?>			
		<?php } else { ?>
			Не указан шаблон $content_tpl - $desktop_tpl - $mobile_tpl
		<?php }?>
		
	</div>

</div>

	<?php $_smarty_tpl->_subTemplateRender("file:index.counters.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


	<?php $_smarty_tpl->_subTemplateRender("file:phonecall-notifier.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


</body>
</html><?php }
}
