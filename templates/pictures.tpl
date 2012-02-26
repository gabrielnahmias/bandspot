{if isset($aAlbums)}
	
    <div>
    	
        <div class="section-title">Albums</div> <span class="gray italic size">({count($aAlbums)} albums, {$iPics} pictures)</span>
    	
    </div>
    
    {foreach $aAlbums as $aAlbum}
        
        <div class="album-container">
            
            <a {$aAlbum.linkProps}>
                
                <div class="album">
                
                    <img src="{$aAlbum.src}">
                    
                </div>
                
            </a>
            
            <div class="name"><a {$aAlbum.linkProps}>{$aAlbum.name}</a></div>
            
        </div>
        
    {foreachelse}
    	
        No albums.
        
	{/foreach}

{elseif isset($aPics)}
	
    <div class="section-title">{$sAlbum}</div> <span class="gray italic">({count($aPics)} pictures)</span>
    
        <div{if !$bI} class="highslide-gallery"{/if} id="gallery">
        
    {foreach $aPics as $aPic}
        
            <div class="picture">
            
                <a href="{$aPic.src}" {if $bI}rel="gallery"{else}class="highslide" onclick='{if isset($aPic.caption)}objOpts["captionText"] = "{$aPic.caption}"; {/if}return hs.expand(this, objOpts)' {/if}title="{$aPic.caption}">
                	
                	<img src="{$aPic.thumb}" />
                    
                </a>
                 
            </div>
        
    {foreachelse}
    	
        No pics.
        
    {/foreach}
	
        </div>
        
    <div class="nav">
        
        <a class="back" href="index.php?pg=pictures">{$smarty.const.TEXT_BACK}</a>
        
    </div>
    
{/if}