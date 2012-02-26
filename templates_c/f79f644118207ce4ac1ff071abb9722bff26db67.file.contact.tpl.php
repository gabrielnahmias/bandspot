<?php /* Smarty version Smarty-3.1.7, created on 2012-02-18 16:13:05
         compiled from "templates/contact.tpl" */ ?>
<?php /*%%SmartyHeaderCode:21147610764f4014617f3db1-92187442%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f79f644118207ce4ac1ff071abb9722bff26db67' => 
    array (
      0 => 'templates/contact.tpl',
      1 => 1329596336,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '21147610764f4014617f3db1-92187442',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_4f4014618222d',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_4f4014618222d')) {function content_4f4014618222d($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_replace')) include '/home/tsoft/public_html/el/inc/smarty/plugins/modifier.replace.php';
?><?php ob_start();?><?php echo nbsp(5);?>
<?php $_tmp1=ob_get_clean();?><?php echo smarty_modifier_replace(nl2br(@TEXT_CONTACT),'	',$_tmp1);?>
<?php }} ?>