<div class="solo">
    
    <img class="profile" src="" />
    
    <div class="biography"></div>
    
    <div class="nav">
        
        <a class="back">{$smarty.const.TEXT_BACK}</a>
        
    </div>
    
</div>

<div class="main">
    
    <div class="center">
        
        <h5>Click on anyone below to view their individual biography!</h5>
        
        <noscript><h6>Ooooh, you'll need JavaScript for that.  Scroll down to view those.</h6></noscript>
        
        {foreach $aPhotos as $sName => $sFile}
            <img class="band" src="{$sFile}" title="{$sName}" />
        {foreachelse}
            {* I could put some empty graphic here in case there's no band pictures. *}
        {/foreach}
        
    </div>
    
    <div class="quote">
        
        <div class="text">
            
            Elemovements is one of the few bands anywhere bold enough and skilled enough to incorporate multiple bass players in their ever-progressive musical arrangements...
            
            <br /><strong>- TheLocalVoice.net</strong> (<a href="http://www.thelocalvoice.net/LocalVoice-PDFs/TLV-137-web.pdf" target="_blank">read article</a>)
            
        </div>
        
    </div>
	
    {$smarty.const.TEXT_BIO|nl2br|replace:'	':{nbsp(5)}}
    
    <noscript>
    	
        <div class="section">
            
            <div class="center"><strong><u><h2>Members (from left to right):</h2></u></strong></div>
            
            {foreach $aBios as $sName => $sBio}
            
                <div class="section">
                	
                    <strong>{$sName}</strong> - {$sBio}
                	
                </div>
            
            {/foreach}
            
        </div>
        
    </noscript>
	
</div>