<script language="javascript" type="text/javascript">

var aPreviews = [];
{foreach $aAlbums as $aAlbum}
{foreach $aAlbum.tracks as $aTrack}
{assign var="sVarName" value=stripslashes( preg_replace("/[-'\(\)]/i", "", $aTrack@key) )}
aPreviews["{$sVarName}"] = ( new buzz.sound('{$aTrack.preview}') ).bind("playing", function(e) {
	$("a[title*='{$sVarName}']").find(".control-button").removeClass('play').addClass('stop');
} ).bind("pause", function(e) {
	$("a[title*='{$sVarName}']").find(".control-button").removeClass('stop').addClass('play');
} ).bind("ended", function(e) {
	$("a[title*='{$sVarName}']").find(".control-button").removeClass('stop').addClass('play');
} ).bind("abort", function(e) {
	$("a[title*='{$sVarName}']").find(".control-button").removeClass('stop').addClass('play');
} );

{/foreach}
{/foreach}

</script>

{foreach $aAlbums as $aAlbum}
    
    <div class="album-info">
        
        <div class="cover">
            
            <img height="100" src="{$smarty.const.DIR_COVERS}/{$aAlbum@key}.jpg" width="100" />
            
            <div class="title">
                
                {if isset($aAlbum.ZIP)}<a href="download.php?f={$aAlbum.ZIP}" target="{if !$bI}{$sIframe}{else}{$sTarget}{/if}" title='Download the Entire "{$aAlbum@key}" Album in a ZIP Archive'>{/if}
                    
                    {$aAlbum@key}
                    
                {if isset($aAlbum.ZIP)}</a>{/if}
                
            </div>
            
            <div class="year">{$aAlbum.year}</div>
            
            {if $aAlbum.links}
            
            <div>
            	
                {foreach $aAlbum.links as $sLink}
                	
                    <a href="{$sLink}" target="_blank" title="Buy Album On {$sLink@key}">
                    
                    	<img class="fade-in icon" src="img/{if $sLink@key eq "iTunes"}itunes{elseif $sLink@key eq "CD Baby"}cdbaby{/if}.png" />
                        
                    </a>
                    
                {/foreach}
                
            </div>
            
            {/if}
            
        </div>
        
        <div class="right">
            
            {if !empty({$aAlbum.description|trim})}
            
            <div class="description">
            
                {$aAlbum.description}
            
            </div>
            
            {/if}
            
            <table class="tracklist">
                
                <tr>
                	
                    <th class="track">#</th>
                	
                    <th class="title" width="{if $aAlbum.hasDLs}77{else}100{/if}%">Title</th>
                    
                    {if array_key_exists_r("length", $aAlbum)}
                    
                    <th class="length">Length</th>
                	
                    {/if}
                    
                    {if array_key_exists_r("preview", $aAlbum.tracks)}
                    	
                        <th>Preview</th>
                        
                    {/if}
                    
                    {if $aAlbum.hasDLs}
                    
                    <th class="downloads" width="23%">Download</th>
                    
                    {/if}
                    
                </tr>
                
                {foreach $aAlbum.tracks as $aTrack}
                    
                    {assign var="sVarName" value=stripslashes( preg_replace("/[-'\(\)]/i", "", $aTrack@key) )}
                    
                    <tr class="track">
                        
                        <td>
                        
	                        <div class="number">{$aTrack@iteration}</div>
                            
                        </td>
                        
                        <td class="title">
                        	
                            {$aTrack@key}
                            
                        </td>
                        
                        {if isset($aTrack.length) or array_key_exists_r("length", $aAlbum)}
                        
                        <td>
                        	
                            {$aTrack.length}
                            
                        </td>
                        
                        {/if}
                        
                        {if array_key_exists_r("preview", $aAlbum.tracks)}
                            
                            <td>
                            	
                                {if isset($aTrack.preview)}
                                	
                                    <center>
                                    	
                                        <a onclick="if ( aPreviews['{$sVarName}'].isPaused() ) { buzz.all().stop(); aPreviews['{$sVarName}'].setTime(0).play(); } else aPreviews['{$sVarName}'].stop();" title='Preview "{$sVarName}"'>
                                            
                                            <div class="control-button play"></div>
                                            
                                        </a>
                                        
                                    </center>
                                    
                                {else}
                                	
                                    &nbsp;
                                    
                                {/if}
                                
                            </td>
                            
                    	{/if}
                    	
                        {if ($aTrack.MP3 or $aTrack.FLAC or $aTrack.M4A) or $aAlbum.hasDLs}
                        
                        <td class="downloads">
                        	
                            {if !empty($aTrack.MP3)}<a href="{if !$bI}download.php?f={/if}{$aTrack.MP3}" target="{if !$bI}{$sIframe}{else}{$sTarget}{/if}" title="Download in MP3 Format"><img src="img/formats/mp3.png" /></a> {/if}
                            
                            {if !empty($aTrack.M4A)}<a href="{if !$bI}download.php?f={/if}{$aTrack.MP3}" target="{if !$bI}{$sIframe}{else}{$sTarget}{/if}" title="Download in M4A Format"><img src="img/formats/m4a.png" /></a>{/if}
                            
                            {if !empty($aTrack.FLAC)}<a href="{if !$bI}download.php?f={/if}{$aTrack.MP3}" target="{if !$bI}{$sIframe}{else}{$sTarget}{/if}" title="Download in FLAC Format"><img src="img/formats/flac.png" /></a> {/if}
                            
                            {if !empty($aTrack.OGG)}<a href="{if !$bI}download.php?f={/if}{$aTrack.OGG}" target="{if !$bI}{$sIframe}{else}{$sTarget}{/if}" title="Download in Ogg Vorbis Format"><img src="img/formats/ogg.png" /></a>{/if}
                            
                            {if !empty($aTrack.WAV)}<a href="{if !$bI}download.php?f={/if}{$aTrack.WAV}" target="{if !$bI}{$sIframe}{else}{$sTarget}{/if}" title="Download in WAV Format"><img src="img/formats/wav.png" /></a>{/if}
                            
                        </td>
                        
                        {/if}
                        
                    </tr>
                    
                {/foreach}
                
            </table>
            
        </div>
        
    </div>
    
    <div class="clear"></div>
    
    {if $aAlbum@last && !$bI}
    <iframe name="{$sIframe}"></iframe>
    {/if}

{foreachelse}
	
	<span class="bold">{$smarty.const.TEXT_NO_MUSIC}{if !$bI}{$smarty.const.TEXT_NO_MUSIC_ADD_DESK}{else}{$smarty.const.TEXT_NO_MUSIC_ADD_IPHONE}{/if}.</span>
    
{/foreach}