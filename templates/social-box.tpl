{if !$bI}

<script charset="utf-8" src="http://widgets.twimg.com/j/2/widget.js"></script>

<script>

new TWTR.Widget({
  version: 2,
  type: 'profile',
  rpp: 4,
  interval: 30000,
  width: 'auto',
  height: 100,
  theme: {
    shell: {
      background: '#ffd6c2',
      color: '#333333'
    },
    tweets: {
      background: '#ffffff',
      color: '#333333',
      links: '#f96d02'
    }
  },
  features: {
    scrollbar: true,
    loop: false,
    live: true,
    behavior: 'all'
  }
}).render().setUser('{str_replace("http://twitter.com/", "", $smarty.const.URL_TW)}').start();

</script>

{/if}

<a href="{$smarty.const.URL_FB}" target="_blank" title="Facebook"><img class="icon" src="img/icons/fb.png" /></a>
<a href="{$smarty.const.URL_TW}" target="_blank" title="Twitter"><img class="icon" src="img/icons/tw.png" /></a>
<a href="{$smarty.const.URL_MS}" target="_blank" title="MySpace"><img class="icon" src="img/icons/ms.png" /></a>
<a href="{$smarty.const.URL_RN}" target="_blank" title="ReverbNation"><img class="icon" src="img/icons/rn.png" /></a>
<a href="{$smarty.const.URL_YT}" target="_blank" title="YouTube"><img class="icon" src="img/icons/yt.png" /></a>

{if !$bI}
<div class="buttons center">
	
    <div class="fb-like" data-href="{$sCurrURL}" data-href="{$sCurrURL}" data-layout="{$smarty.const.FB_LIKE_LAYOUT}" data-send="{$smarty.const.FB_LIKE_SEND}" data-width="{$smarty.const.FB_LIKE_WIDTH}" data-show-faces="{$smarty.const.FB_LIKE_FACES}" data-font="{$smarty.const.FB_LIKE_FONT}"></div>
    
	<div class="g-plusone" data-href="{$sCurrURL}" data-size="{$smarty.const.GOOG_PLUS_SIZE}"></div>
    
	<a href="https://twitter.com/share" class="twitter-share-button" data-url="https://dev.twitter.com/pages/tweet_button" data-via="your_screen_name" data-lang="en" data-related="anywhereTheJavascriptAPI" data-count="vertical">Tweet</a>
    
	<script>
	!function(d, s, id) {
		var js,
		fjs = d.getElementsByTagName(s)[0];
		if (!d.getElementById(id)) {
			js = d.createElement(s);
			js.id = id;
			js.src = "//platform.twitter.com/widgets.js";
			fjs.parentNode.insertBefore(js, fjs);
		}
	} (document, "script", "twitter-wjs");
	</script>
    
</div>
{/if}