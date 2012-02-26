<?php /* Smarty version Smarty-3.1.7, created on 2012-02-01 21:07:24
         compiled from ".\templates\comments-box.tpl" */ ?>
<?php /*%%SmartyHeaderCode:146164f29a98c3216a5-95891071%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '7b222afa87dd27e98382a42046f5273d517a49e3' => 
    array (
      0 => '.\\templates\\comments-box.tpl',
      1 => 1328129315,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '146164f29a98c3216a5-95891071',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_4f29a98c33a87',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_4f29a98c33a87')) {function content_4f29a98c33a87($_smarty_tpl) {?><?php echo @TEXT_NO_JS;?>


<div class="fb-comments" data-href="http://<?php echo $_SERVER['HTTP_HOST'];?>
<?php echo $_SERVER['PHP_SELF'];?>
" data-num-posts="5" data-width="619"></div><?php }} ?>