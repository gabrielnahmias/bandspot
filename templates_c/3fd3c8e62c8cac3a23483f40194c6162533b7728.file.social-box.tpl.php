<?php /* Smarty version Smarty-3.1.7, created on 2012-02-19 01:37:59
         compiled from ".\templates\social-box.tpl" */ ?>
<?php /*%%SmartyHeaderCode:58944f405277a68977-85560536%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3fd3c8e62c8cac3a23483f40194c6162533b7728' => 
    array (
      0 => '.\\templates\\social-box.tpl',
      1 => 1329608747,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '58944f405277a68977-85560536',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'bI' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_4f405277a8ce5',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_4f405277a8ce5')) {function content_4f405277a8ce5($_smarty_tpl) {?><a href="<?php echo @URL_FB;?>
" target="_blank" title="Facebook"><img class="icon" src="./img/icons/fb.png" /></a><a href="<?php echo @URL_MS;?>
" target="_blank" title="MySpace"><img class="icon" src="./img/icons/ms.png" /></a><a href="<?php echo @URL_RN;?>
" target="_blank" title="ReverbNation"><img class="icon" src="./img/icons/rn.png" /></a><a href="<?php echo @URL_YT;?>
" target="_blank" title="YouTube"><img class="icon" src="./img/icons/yt.png" /></a>

<?php if (!$_smarty_tpl->tpl_vars['bI']->value){?>
<div class="buttons center"><div class="fb-like" data-href="http://www.facebook.com/elemovements" data-layout="button_count" data-send="false" data-width="110" data-show-faces="false" data-font="arial"></div><g:plusone size="small"></g:plusone></div>
<?php }?><?php }} ?>