<?php /* Smarty version Smarty-3.1.7, created on 2012-02-18 15:20:38
         compiled from "./templates/social-box.tpl" */ ?>
<?php /*%%SmartyHeaderCode:7810379794f4008161fd189-59757342%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '29cac406eb0d67bccd274e6f4db916b59365cb8c' => 
    array (
      0 => './templates/social-box.tpl',
      1 => 1329596336,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '7810379794f4008161fd189-59757342',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'bI' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_4f400816216ea',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_4f400816216ea')) {function content_4f400816216ea($_smarty_tpl) {?><a href="<?php echo @URL_FB;?>
" target="_blank" title="Facebook"><img class="icon" src="./img/icons/fb.png" /></a><a href="<?php echo @URL_MS;?>
" target="_blank" title="MySpace"><img class="icon" src="./img/icons/ms.png" /></a><a href="<?php echo @URL_RN;?>
" target="_blank" title="ReverbNation"><img class="icon" src="./img/icons/rn.png" /></a><a href="<?php echo @URL_YT;?>
" target="_blank" title="YouTube"><img class="icon" src="./img/icons/yt.png" /></a>

<?php if (!$_smarty_tpl->tpl_vars['bI']->value){?>
<div class="buttons center"><div class="fb-like" data-href="http://www.facebook.com/elemovements" data-layout="button_count" data-send="false" data-width="110" data-show-faces="false" data-font="arial"></div><g:plusone size="small"></g:plusone></div>
<?php }?><?php }} ?>