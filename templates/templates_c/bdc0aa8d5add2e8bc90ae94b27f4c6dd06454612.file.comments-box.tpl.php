<?php /* Smarty version Smarty-3.1.7, created on 2012-02-18 15:29:57
         compiled from "./templates/comments-box.tpl" */ ?>
<?php /*%%SmartyHeaderCode:543913064f400a455339d7-87164355%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'bdc0aa8d5add2e8bc90ae94b27f4c6dd06454612' => 
    array (
      0 => './templates/comments-box.tpl',
      1 => 1329596336,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '543913064f400a455339d7-87164355',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_4f400a45563cf',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_4f400a45563cf')) {function content_4f400a45563cf($_smarty_tpl) {?><?php echo @TEXT_NO_JS;?>


<div class="fb-comments" data-href="http://<?php echo $_SERVER['HTTP_HOST'];?>
<?php echo $_SERVER['PHP_SELF'];?>
" data-num-posts="5" data-width="619"></div><?php }} ?>