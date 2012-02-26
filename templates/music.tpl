{foreach $aAlbums as $aAlbum}
    
    <div class="album-info">
        
        <div class="cover">
            
            <img height="100" src="{$smarty.const.DIR_COVERS}/{$aAlbum@key}.jpg" width="100" />
            
            <div class="title">
                
                {if isset($aAlbum.ZIP)}<a href="download.php?f={$aAlbum.ZIP}" target="{if !$bI}{$sIframe}{else}{$sTarget}{/if}" title='Click Here to Download the Entire "{$aAlbum@key}" Album in a ZIP Archive'>{/if}
                    
                    {$aAlbum@key}
                    
                {if isset($aAlbum.ZIP)}</a>{/if}
                
            </div>
            
            <div class="year">{$aAlbum.year}</div>
            
        </div>
        
        <div class="tracklist">
            
            {foreach $aAlbum.tracks as $aTrack}
                
                <div class="track">
                    
                    <div class="number">{$aTrack@iteration}</div>
                    
                        {if !empty($aTrack.MP3)}<a href="{if !$bI}download.php?f={/if}{$aTrack.MP3}" target="{if !$bI}{$sIframe}{else}{$sTarget}{/if}" title='Click Here to Download "{$aTrack@key}" in MP3 Format'>{/if}
                        
                        {$aTrack@key}
                        
                        {if !empty($aTrack.MP3)} </a> {/if}
                        
                        {if !empty($aTrack.FLAC)}(<a href="{if !$bI}download.php?f={/if}{$aTrack.MP3}" target="{if !$bI}{$sIframe}{else}{$sTarget}{/if}" title='Click Here to Download "{$aTrack@key}" in FLAC Format'>FLAC</a>) {/if}
                        
                        {if !empty($aTrack.M4A)}(<a href="{if !$bI}download.php?f={/if}{$aTrack.MP3}" target="{if !$bI}{$sIframe}{else}{$sTarget}{/if}" title='Click Here to Download "{$aTrack@key}" in M4A Format'>M4A</a>){/if}
                    
                </div>
                
            {/foreach}
            
        </div>
        
    </div>
    
    <div class="clear"></div>
    
    {if $aAlbum@last && !$bI}
    <iframe name="{$sIframe}"></iframe>
    {/if}

{foreachelse}
	
	<span class="bold">{$smarty.const.TEXT_NO_MUSIC}{if !$bI}{$smarty.const.TEXT_NO_MUSIC_ADD_DESK}{else}{$smarty.const.TEXT_NO_MUSIC_ADD_IPHONE}{/if}.</span>
    
{/foreach}