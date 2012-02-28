<?php /* Smarty version Smarty-3.1.7, created on 2012-02-28 10:16:39
         compiled from ".\templates\comments-box.tpl" */ ?>
<?php /*%%SmartyHeaderCode:209354f405277b46a92-21595638%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '7b222afa87dd27e98382a42046f5273d517a49e3' => 
    array (
      0 => '.\\templates\\comments-box.tpl',
      1 => 1330313538,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '209354f405277b46a92-21595638',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_4f405277b58fe',
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_4f405277b58fe')) {function content_4f405277b58fe($_smarty_tpl) {?><?php echo @TEXT_NO_JS;?>


<div class="fb-comments" data-href="http://<?php echo $_SERVER['HTTP_HOST'];?>
<?php if ($_SERVER['REQUEST_URI']=='/'){?>/home<?php }else{ ?><?php echo strtok($_SERVER['REQUEST_URI'],"?");?>
<?php }?>" data-num-posts="<?php echo @FB_COMMENTS_NUM;?>
" data-width="<?php echo @FB_COMMENTS_WIDTH;?>
"></div><?php }} ?>