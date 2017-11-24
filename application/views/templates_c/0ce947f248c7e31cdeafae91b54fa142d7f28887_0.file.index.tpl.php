<?php
/* Smarty version 3.1.31, created on 2017-11-24 18:10:11
  from "C:\OpenServer\domains\shop.loc\application\views\index.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5a183653da8105_73634922',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '0ce947f248c7e31cdeafae91b54fa142d7f28887' => 
    array (
      0 => 'C:\\OpenServer\\domains\\shop.loc\\application\\views\\index.tpl',
      1 => 1511535918,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5a183653da8105_73634922 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!DOCTYPE html>
<html xmlns:fb="http://www.facebook.com/2008/fbml" lang="<?php echo $_smarty_tpl->tpl_vars['PAGE']->value->lang;?>
">
<head>

	<title><?php if ($_smarty_tpl->tpl_vars['PAGE']->value->utitle) {
echo $_smarty_tpl->tpl_vars['PAGE']->value->utitle;
} else {
echo $_smarty_tpl->tpl_vars['PAGE']->value->title;
}
if ((($_smarty_tpl->tpl_vars['module']->value == 'catalog' || $_smarty_tpl->tpl_vars['module']->value == 'catalog.v2') && $_smarty_tpl->tpl_vars['good']->value) || $_smarty_tpl->tpl_vars['PAGE']->value->url == "/tag/" || ($_smarty_tpl->tpl_vars['user']->value && $_smarty_tpl->tpl_vars['module']->value == 'catalog' && !$_smarty_tpl->tpl_vars['filters']->value['category'] && !$_smarty_tpl->tpl_vars['good']->value)) {
} else { ?> - Maryjane | Мэри Джейн<?php }?></title>
	
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<meta content="<?php if ($_smarty_tpl->tpl_vars['PAGE']->value->udescription) {
echo $_smarty_tpl->tpl_vars['PAGE']->value->udescription;
} else {
echo $_smarty_tpl->tpl_vars['PAGE']->value->description;
}?>" name="description" />
	<meta content="<?php if ($_smarty_tpl->tpl_vars['PAGE']->value->ukeywords) {
echo $_smarty_tpl->tpl_vars['PAGE']->value->ukeywords;
} else {
echo $_smarty_tpl->tpl_vars['PAGE']->value->keywords;
}?>" name="keywords" />
	
</head>

<body>
	
    <?php if ($_smarty_tpl->tpl_vars['PAGE']->value->tpl) {?> 
        <?php $_smarty_tpl->_subTemplateRender($_smarty_tpl->tpl_vars['PAGE']->value->tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>

    <?php } else { ?>
        Не указан шаблон $content_tpl
    <?php }?>
	
</body>
</html><?php }
}
