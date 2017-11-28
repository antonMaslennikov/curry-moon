<?php
/* Smarty version 3.1.31, created on 2017-11-27 22:51:05
  from "C:\OpenServer\domains\shop.loc\admin\application\views\index.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5a1c6ca92e7423_53382031',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '9216e1675628d71928d399e8bae3ddcf3d01a468' => 
    array (
      0 => 'C:\\OpenServer\\domains\\shop.loc\\admin\\application\\views\\index.tpl',
      1 => 1511812264,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header.tpl' => 1,
    'file:footer.tpl' => 1,
  ),
),false)) {
function content_5a1c6ca92e7423_53382031 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!DOCTYPE html>
<html prefix="og: http://ogp.me/ns#" lang="ru-ru"  class="frontpage" >
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# product: http://ogp.me/ns/product#">
    
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=2.0">
    
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    
    <title><?php echo $_smarty_tpl->tpl_vars['PAGE']->value->title;?>
</title>
    
    <link href="/public/css/normalize.css" rel="stylesheet" type="text/css" />
    
    <?php echo '<script'; ?>
 src="/public/js/jquery.min.js" type="text/javascript"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="/public/js/jquery-noconflict.js" type="text/javascript"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="/public/js/jquery-migrate.min.js" type="text/javascript"><?php echo '</script'; ?>
>
    
    <?php echo '<script'; ?>
 src="/public/js/bootstrap.min.js" type="text/javascript"><?php echo '</script'; ?>
>

<body>

    <?php $_smarty_tpl->_subTemplateRender("file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

    
    <?php if ($_smarty_tpl->tpl_vars['PAGE']->value->tpl) {?> 
        <?php $_smarty_tpl->_subTemplateRender($_smarty_tpl->tpl_vars['PAGE']->value->tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>

    <?php } else { ?>
        Не указан шаблон страницы
    <?php }?>
   
    <?php $_smarty_tpl->_subTemplateRender("file:footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
  	

</body>
</html><?php }
}
