<?php /* Smarty version Smarty-3.1.7, created on 2012-02-18 15:35:51
         compiled from "templates/pictures.tpl" */ ?>
<?php /*%%SmartyHeaderCode:932842694f400ba7547388-46164020%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'db64a1c1f6eed0f8a728c506f14abb0d19f6acb3' => 
    array (
      0 => 'templates/pictures.tpl',
      1 => 1329596336,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '932842694f400ba7547388-46164020',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'aAlbums' => 0,
    'iPics' => 0,
    'aAlbum' => 0,
    'aPics' => 0,
    'sAlbum' => 0,
    'bI' => 0,
    'aPic' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_4f400ba75c772',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_4f400ba75c772')) {function content_4f400ba75c772($_smarty_tpl) {?><?php if (isset($_smarty_tpl->tpl_vars['aAlbums']->value)){?>
	
    <div>
    	
        <div class="section-title">Albums</div> <span class="gray italic size">(<?php echo count($_smarty_tpl->tpl_vars['aAlbums']->value);?>
 albums, <?php echo $_smarty_tpl->tpl_vars['iPics']->value;?>
 pictures)</span>
    	
    </div>
    
    <?php  $_smarty_tpl->tpl_vars['aAlbum'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['aAlbum']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['aAlbums']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['aAlbum']->key => $_smarty_tpl->tpl_vars['aAlbum']->value){
$_smarty_tpl->tpl_vars['aAlbum']->_loop = true;
?>
        
        <div class="album-container">
            
            <a <?php echo $_smarty_tpl->tpl_vars['aAlbum']->value['linkProps'];?>
>
                
                <div class="album">
                
                    <img src="<?php echo $_smarty_tpl->tpl_vars['aAlbum']->value['src'];?>
">
                    
                </div>
                
            </a>
            
            <div class="name"><a <?php echo $_smarty_tpl->tpl_vars['aAlbum']->value['linkProps'];?>
><?php echo $_smarty_tpl->tpl_vars['aAlbum']->value['name'];?>
</a></div>
            
        </div>
        
    <?php }
if (!$_smarty_tpl->tpl_vars['aAlbum']->_loop) {
?>
    	
        No albums.
        
	<?php } ?>

<?php }elseif(isset($_smarty_tpl->tpl_vars['aPics']->value)){?>
	
    <div class="section-title"><?php echo $_smarty_tpl->tpl_vars['sAlbum']->value;?>
</div> <span class="gray italic">(<?php echo count($_smarty_tpl->tpl_vars['aPics']->value);?>
 pictures)</span>
    
        <div<?php if (!$_smarty_tpl->tpl_vars['bI']->value){?> class="highslide-gallery"<?php }?> id="gallery">
        
    <?php  $_smarty_tpl->tpl_vars['aPic'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['aPic']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['aPics']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['aPic']->key => $_smarty_tpl->tpl_vars['aPic']->value){
$_smarty_tpl->tpl_vars['aPic']->_loop = true;
?>
        
            <div class="picture">
            
                <a href="<?php echo $_smarty_tpl->tpl_vars['aPic']->value['src'];?>
" <?php if ($_smarty_tpl->tpl_vars['bI']->value){?>rel="gallery"<?php }else{ ?>class="highslide" onclick='<?php if (isset($_smarty_tpl->tpl_vars['aPic']->value['caption'])){?>objOpts["captionText"] = "<?php echo $_smarty_tpl->tpl_vars['aPic']->value['caption'];?>
"; <?php }?>return hs.expand(this, objOpts)' <?php }?>title="<?php echo $_smarty_tpl->tpl_vars['aPic']->value['caption'];?>
">
                	
                	<img src="<?php echo $_smarty_tpl->tpl_vars['aPic']->value['thumb'];?>
" />
                    
                </a>
                 
            </div>
        
    <?php }
if (!$_smarty_tpl->tpl_vars['aPic']->_loop) {
?>
    	
        No pics.
        
    <?php } ?>
	
        </div>
        
    <div class="nav">
        
        <a class="back" href="index.php?pg=pictures"><?php echo @TEXT_BACK;?>
</a>
        
    </div>
    
<?php }?><?php }} ?>