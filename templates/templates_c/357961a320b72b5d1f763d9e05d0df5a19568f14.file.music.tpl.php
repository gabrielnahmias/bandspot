<?php /* Smarty version Smarty-3.1.7, created on 2012-02-04 06:51:04
         compiled from "templates\music.tpl" */ ?>
<?php /*%%SmartyHeaderCode:304944f2cd558ee9041-84082448%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '357961a320b72b5d1f763d9e05d0df5a19568f14' => 
    array (
      0 => 'templates\\music.tpl',
      1 => 1328241203,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '304944f2cd558ee9041-84082448',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'aAlbums' => 0,
    'aAlbum' => 0,
    'bI' => 0,
    'sIframe' => 0,
    'sTarget' => 0,
    'aTrack' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_4f2cd559355fb',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_4f2cd559355fb')) {function content_4f2cd559355fb($_smarty_tpl) {?><?php  $_smarty_tpl->tpl_vars['aAlbum'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['aAlbum']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['aAlbums']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['aAlbum']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['aAlbum']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['aAlbum']->key => $_smarty_tpl->tpl_vars['aAlbum']->value){
$_smarty_tpl->tpl_vars['aAlbum']->_loop = true;
 $_smarty_tpl->tpl_vars['aAlbum']->iteration++;
 $_smarty_tpl->tpl_vars['aAlbum']->last = $_smarty_tpl->tpl_vars['aAlbum']->iteration === $_smarty_tpl->tpl_vars['aAlbum']->total;
?>
    
    <div class="album-info">
        
        <div class="cover">
            
            <img height="100" src="<?php echo @DIR_COVERS;?>
/<?php echo $_smarty_tpl->tpl_vars['aAlbum']->key;?>
.jpg" width="100" />
            
            <div class="title">
                
                <?php if (isset($_smarty_tpl->tpl_vars['aAlbum']->value['ZIP'])){?><a href="download.php?f=<?php echo $_smarty_tpl->tpl_vars['aAlbum']->value['ZIP'];?>
" target="<?php if (!$_smarty_tpl->tpl_vars['bI']->value){?><?php echo $_smarty_tpl->tpl_vars['sIframe']->value;?>
<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['sTarget']->value;?>
<?php }?>" title='Click Here to Download the Entire "<?php echo $_smarty_tpl->tpl_vars['aAlbum']->key;?>
" Album in a ZIP Archive'><?php }?>
                    
                    <?php echo $_smarty_tpl->tpl_vars['aAlbum']->key;?>

                    
                <?php if (isset($_smarty_tpl->tpl_vars['aAlbum']->value['ZIP'])){?></a><?php }?>
                
            </div>
            
            <div class="year"><?php echo $_smarty_tpl->tpl_vars['aAlbum']->value['year'];?>
</div>
            
        </div>
        
        <div class="tracklist">
            
            <?php  $_smarty_tpl->tpl_vars['aTrack'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['aTrack']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['aAlbum']->value['tracks']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['aTrack']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['aTrack']->key => $_smarty_tpl->tpl_vars['aTrack']->value){
$_smarty_tpl->tpl_vars['aTrack']->_loop = true;
 $_smarty_tpl->tpl_vars['aTrack']->iteration++;
?>
                
                <div class="track">
                    
                    <div class="number"><?php echo $_smarty_tpl->tpl_vars['aTrack']->iteration;?>
</div>
                    
                        <?php if (!empty($_smarty_tpl->tpl_vars['aTrack']->value['MP3'])){?><a href="<?php if (!$_smarty_tpl->tpl_vars['bI']->value){?>download.php?f=<?php }?><?php echo $_smarty_tpl->tpl_vars['aTrack']->value['MP3'];?>
" target="<?php if (!$_smarty_tpl->tpl_vars['bI']->value){?><?php echo $_smarty_tpl->tpl_vars['sIframe']->value;?>
<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['sTarget']->value;?>
<?php }?>" title='Click Here to Download "<?php echo $_smarty_tpl->tpl_vars['aTrack']->key;?>
" in MP3 Format'><?php }?>
                        
                        <?php echo $_smarty_tpl->tpl_vars['aTrack']->key;?>

                        
                        <?php if (!empty($_smarty_tpl->tpl_vars['aTrack']->value['MP3'])){?> </a> <?php }?>
                        
                        <?php if (!empty($_smarty_tpl->tpl_vars['aTrack']->value['FLAC'])){?>(<a href="<?php if (!$_smarty_tpl->tpl_vars['bI']->value){?>download.php?f=<?php }?><?php echo $_smarty_tpl->tpl_vars['aTrack']->value['MP3'];?>
" target="<?php if (!$_smarty_tpl->tpl_vars['bI']->value){?><?php echo $_smarty_tpl->tpl_vars['sIframe']->value;?>
<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['sTarget']->value;?>
<?php }?>" title='Click Here to Download "<?php echo $_smarty_tpl->tpl_vars['aTrack']->key;?>
" in FLAC Format'>FLAC</a>) <?php }?>
                        
                        <?php if (!empty($_smarty_tpl->tpl_vars['aTrack']->value['M4A'])){?>(<a href="<?php if (!$_smarty_tpl->tpl_vars['bI']->value){?>download.php?f=<?php }?><?php echo $_smarty_tpl->tpl_vars['aTrack']->value['MP3'];?>
" target="<?php if (!$_smarty_tpl->tpl_vars['bI']->value){?><?php echo $_smarty_tpl->tpl_vars['sIframe']->value;?>
<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['sTarget']->value;?>
<?php }?>" title='Click Here to Download "<?php echo $_smarty_tpl->tpl_vars['aTrack']->key;?>
" in M4A Format'>M4A</a>)<?php }?>
                    
                </div>
                
            <?php } ?>
            
        </div>
        
    </div>
    
    <div class="clear"></div>
    
    <?php if ($_smarty_tpl->tpl_vars['aAlbum']->last&&!$_smarty_tpl->tpl_vars['bI']->value){?>
    <iframe name="<?php echo $_smarty_tpl->tpl_vars['sIframe']->value;?>
"></iframe>
    <?php }?>

<?php }
if (!$_smarty_tpl->tpl_vars['aAlbum']->_loop) {
?>
	
	<span class="bold"><?php echo @TEXT_NO_MUSIC;?>
<?php if (!$_smarty_tpl->tpl_vars['bI']->value){?><?php echo @TEXT_NO_MUSIC_ADD_DESK;?>
<?php }else{ ?><?php echo @TEXT_NO_MUSIC_ADD_IPHONE;?>
<?php }?>.</span>
    
<?php } ?><?php }} ?>