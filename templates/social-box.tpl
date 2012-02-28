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
    scrollbar: false,
    loop: false,
    live: true,
    behavior: 'all'
  }
}).render().setUser('Elemovements').start();
</script>
{/if}

<a href="{$smarty.const.URL_FB}" target="_blank" title="Facebook"><img class="icon" src="./img/icons/fb.png" /></a>
<a href="{$smarty.const.URL_TW}" target="_blank" title="Twitter"><img class="icon" src="./img/icons/tw.png" /></a>
<a href="{$smarty.const.URL_MS}" target="_blank" title="MySpace"><img class="icon" src="./img/icons/ms.png" /></a>
<a href="{$smarty.const.URL_RN}" target="_blank" title="ReverbNation"><img class="icon" src="./img/icons/rn.png" /></a>
<a href="{$smarty.const.URL_YT}" target="_blank" title="YouTube"><img class="icon" src="./img/icons/yt.png" /></a>

{if !$bI}
<div class="buttons center"><div class="fb-like" data-href="http://www.facebook.com/elemovements" data-layout="button_count" data-send="false" data-width="110" data-show-faces="false" data-font="arial"></div>
<g:plusone size="small"></g:plusone></div>
{/if}