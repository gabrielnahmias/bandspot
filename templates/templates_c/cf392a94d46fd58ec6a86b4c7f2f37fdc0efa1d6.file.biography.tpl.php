<?php /* Smarty version Smarty-3.1.7, created on 2012-02-01 21:09:41
         compiled from "templates\biography.tpl" */ ?>
<?php /*%%SmartyHeaderCode:56944f29aa15c23ad3-62298554%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'cf392a94d46fd58ec6a86b4c7f2f37fdc0efa1d6' => 
    array (
      0 => 'templates\\biography.tpl',
      1 => 1327991620,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '56944f29aa15c23ad3-62298554',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'aPhotos' => 0,
    'sFile' => 0,
    'sName' => 0,
    'aBios' => 0,
    'sBio' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_4f29aa15ca057',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_4f29aa15ca057')) {function content_4f29aa15ca057($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_replace')) include 'C:\\Server\\www\\sites\\el\\inc\\smarty\\plugins\\modifier.replace.php';
?><div class="solo">
    
    <img class="profile" src="" />
    
    <div class="biography"></div>
    
    <div class="nav">
        
        <a class="back"><?php echo @TEXT_BACK;?>
</a>
        
    </div>
    
</div>

<div class="main">
    
    <div class="center">
        
        <h5>Click on anyone below to view their individual biography!</h5>
        
        <noscript><h6>Ooooh, you'll need JavaScript for that.  Scroll down to view those.</h6></noscript>
        
        <?php  $_smarty_tpl->tpl_vars['sFile'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['sFile']->_loop = false;
 $_smarty_tpl->tpl_vars['sName'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['aPhotos']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['sFile']->key => $_smarty_tpl->tpl_vars['sFile']->value){
$_smarty_tpl->tpl_vars['sFile']->_loop = true;
 $_smarty_tpl->tpl_vars['sName']->value = $_smarty_tpl->tpl_vars['sFile']->key;
?>
            <img class="band" src="<?php echo $_smarty_tpl->tpl_vars['sFile']->value;?>
" title="<?php echo $_smarty_tpl->tpl_vars['sName']->value;?>
" />
        <?php }
if (!$_smarty_tpl->tpl_vars['sFile']->_loop) {
?>
            
        <?php } ?>
        
    </div>
    
    <div class="quote">
        
        <div class="text">
            
            Elemovements is one of the few bands anywhere bold enough and skilled enough to incorporate multiple bass players in their ever-progressive musical arrangements...
            
            <br /><strong>- TheLocalVoice.net</strong> (<a href="http://www.thelocalvoice.net/LocalVoice-PDFs/TLV-137-web.pdf" target="_blank">read article</a>)
            
        </div>
        
    </div>
	
    <?php ob_start();?><?php echo nbsp(5);?>
<?php $_tmp1=ob_get_clean();?><?php echo smarty_modifier_replace(nl2br(@TEXT_BIO),'	',$_tmp1);?>

    
    <noscript>
    	
        <div class="section">
            
            <div class="center"><strong><u><h2>Members (from left to right):</h2></u></strong></div>
            
            <?php  $_smarty_tpl->tpl_vars['sBio'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['sBio']->_loop = false;
 $_smarty_tpl->tpl_vars['sName'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['aBios']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['sBio']->key => $_smarty_tpl->tpl_vars['sBio']->value){
$_smarty_tpl->tpl_vars['sBio']->_loop = true;
 $_smarty_tpl->tpl_vars['sName']->value = $_smarty_tpl->tpl_vars['sBio']->key;
?>
            
                <div class="section">
                	
                    <strong><?php echo $_smarty_tpl->tpl_vars['sName']->value;?>
</strong> - <?php echo $_smarty_tpl->tpl_vars['sBio']->value;?>

                	
                </div>
            
            <?php } ?>
            
        </div>
        
    </noscript>
	
</div><?php }} ?>