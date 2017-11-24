<?php
/* Smarty version 3.1.31, created on 2017-11-24 18:07:56
  from "C:\OpenServer\domains\shop.loc\application\views\init_js_variables_from_top-line.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5a1835cc01c338_58586712',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'd1abf4175f6e34e7c9e39b5e25c3fedfcece7d6f' => 
    array (
      0 => 'C:\\OpenServer\\domains\\shop.loc\\application\\views\\init_js_variables_from_top-line.tpl',
      1 => 1511421408,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5a1835cc01c338_58586712 (Smarty_Internal_Template $_smarty_tpl) {
echo '<script'; ?>
 type="text/javascript">
	<?php if ($_smarty_tpl->tpl_vars['USER']->value->authorized) {?>
		var authorized = true;
	<?php } else { ?>
		var authorized = false;
	<?php }?>
	
	<?php if ($_smarty_tpl->tpl_vars['USER']->value->client->ismobiledevice == '1') {?>	
		var ismobiledevice = true;
	<?php } else { ?>
		var ismobiledevice = false;
	<?php }?>
	
	<?php if ($_smarty_tpl->tpl_vars['MobilePageVersion']->value) {?>
		var MobilePageVersion = true;
	<?php } else { ?>
		var MobilePageVersion = false;
	<?php }?>	

	<?php if ($_smarty_tpl->tpl_vars['USER']->value->user_id == 27278 || $_smarty_tpl->tpl_vars['USER']->value->user_id == 6199) {?>
		window.dev = true;
	<?php } else { ?>
		window.dev = false;
	<?php }?>
	
	window.currentuser = { user_id: <?php echo $_smarty_tpl->tpl_vars['USER']->value->user_id;?>
-0 };
	
	window.bonusBackPercent =<?php echo $_smarty_tpl->tpl_vars['bonusBackPercent']->value;?>
;//% от кол-ва заказов	
	
	window.module = '<?php echo $_smarty_tpl->tpl_vars['PAGE']->value->module;?>
';
	window.link={ link: '<?php echo $_smarty_tpl->tpl_vars['link']->value;?>
', page: '<?php echo $_smarty_tpl->tpl_vars['page']->value;?>
', prevlink: '<?php echo $_smarty_tpl->tpl_vars['HTTP_REFERER']->value;?>
'};
	window.CURRENT_CURRENCY = '<?php echo $_smarty_tpl->tpl_vars['L']->value['CURRENT_CURRENCY'];?>
';
	
	VK_APP_ID = <?php echo $_smarty_tpl->tpl_vars['VK_APP_ID']->value;?>
;
<?php echo '</script'; ?>
>
<!-- --><?php }
}
