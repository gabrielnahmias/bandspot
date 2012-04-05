{* TODO: I need to make sure that all these changes look good on the iPhone, too.  Use dad's! *}

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
    
        <div{if !$bI} class="highslide-gallery scroller"{/if}>
            
            <ul class="horiz-list scroller">
            
            {foreach $aPics as $aPic}
            	                
                <li class="picture">
                
                    <a href="{$aPic.src}" {if $bI}rel="gallery"{else}class="highslide" onclick='{if isset($aPic.caption)}objOpts["captionText"] = "{$aPic.caption}"; {/if}return hs.expand(this, objOpts)' {/if}title="{$aPic.caption}">
                        
                        <img src="{$aPic.thumb}" />
                        
                    </a>
                     
                </li>
                
            {foreachelse}
                
                No pics.
                
            {/foreach}
            
            </ul>
            
        </div>
        
    <div class="nav">
        
        <a class="back" href="pictures">{$smarty.const.TEXT_BACK}</a>
        
    </div>
    
{/if}