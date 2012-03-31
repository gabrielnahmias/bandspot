{foreach $aShows as $aShow}
    
    <div class="date">
                                                        
        <div class="bold italic">{$aShow.date}</div>
        
        <span class="bold">{if !empty($aShow.url)}<a href="{$aShow.url}" target="_blank">{/if}{$aShow.venue}{if !empty($aShow.url)}</a>{/if}</span> in <span class="italic">{$aShow.city}</span> (<a href="{$aShow.mapURL}" target="_blank">map</a>){if !empty($aShow.price)}<span class="italic"> - {if stripos($aShow.price, "free") !== false}no cover{else}cover is <span class="bold green">${$aShow.price}</span>{/if}</span>{/if}
        
        {if !empty($aShow.description)}
            
            <div class="description italic">{$aShow.description}</div>
            
        {/if}
		
    </div>
    
{foreachelse}
	
    <span class="bold">{$smarty.const.TEXT_NO_DATES}</span>
    
{/foreach}
