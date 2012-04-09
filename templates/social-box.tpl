{if !$bI}

<div id="twitter_box">
    
    <span class="title">Tweets</span>
    
    <a class="right" href="{$smarty.const.URL_TW}" target="_blank">@{$smarty.const.TWTR_DOMAIN}</a>
    
    <div id="tweets">
        
    </div>
    
    <div class="overlay">
    
        <img src="img/load2.gif" />
    	
    </div>
    
    <div id="follow">
    	
        {$smarty.const.CODE_FOLLOW}
        
    </div>
    
</div>

{/if}

{* might wanna use a constant for this and str_replace it with the URLs *}

<a href="{$smarty.const.URL_FB}" target="_blank" title="Facebook"><img class="fade-in icon" src="img/icons/fb.png" /></a>
<a href="{$smarty.const.URL_TW}" target="_blank" title="Twitter"><img class="fade-in icon" src="img/icons/tw.png" /></a>
<a href="{$smarty.const.URL_MS}" target="_blank" title="MySpace"><img class="fade-in icon" src="img/icons/ms.png" /></a>
<a href="{$smarty.const.URL_RN}" target="_blank" title="ReverbNation"><img class="fade-in icon" src="img/icons/rn.png" /></a>
<a href="{$smarty.const.URL_YT}" target="_blank" title="YouTube"><img class="fade-in icon" src="img/icons/yt.png" /></a>