<?php /* Smarty version Smarty-3.1.7, created on 2012-02-01 21:12:28
         compiled from "templates\contact.tpl" */ ?>
<?php /*%%SmartyHeaderCode:203374f29aabc75ab04-05449265%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4958bade2459ebfc78f25522f1930824514df229' => 
    array (
      0 => 'templates\\contact.tpl',
      1 => 1327992627,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '203374f29aabc75ab04-05449265',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_4f29aabc787c9',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_4f29aabc787c9')) {function content_4f29aabc787c9($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_replace')) include 'C:\\Server\\www\\sites\\el\\inc\\smarty\\plugins\\modifier.replace.php';
?><?php ob_start();?><?php echo nbsp(5);?>
<?php $_tmp1=ob_get_clean();?><?php echo smarty_modifier_replace(nl2br(@TEXT_CONTACT),'	',$_tmp1);?>
<?php }} ?>