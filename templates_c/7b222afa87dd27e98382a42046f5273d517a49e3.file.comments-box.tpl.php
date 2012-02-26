<?php /* Smarty version Smarty-3.1.7, created on 2012-02-19 01:37:59
         compiled from ".\templates\comments-box.tpl" */ ?>
<?php /*%%SmartyHeaderCode:209354f405277b46a92-21595638%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '7b222afa87dd27e98382a42046f5273d517a49e3' => 
    array (
      0 => '.\\templates\\comments-box.tpl',
      1 => 1329608748,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '209354f405277b46a92-21595638',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_4f405277b58fe',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_4f405277b58fe')) {function content_4f405277b58fe($_smarty_tpl) {?><?php echo @TEXT_NO_JS;?>


<div class="fb-comments" data-href="http://<?php echo $_SERVER['HTTP_HOST'];?>
<?php echo $_SERVER['PHP_SELF'];?>
" data-num-posts="5" data-width="619"></div><?php }} ?>