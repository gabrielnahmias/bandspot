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
}).render().setUser('{$smarty.const.TWTR_DOMAIN}').start();

</script>

{/if}

{* might wanna use a constant for this and str_replace it with the URLs *}

<a href="{$smarty.const.URL_FB}" target="_blank" title="Facebook"><img class="icon" src="img/icons/fb.png" /></a>
<a href="{$smarty.const.URL_TW}" target="_blank" title="Twitter"><img class="icon" src="img/icons/tw.png" /></a>
<a href="{$smarty.const.URL_MS}" target="_blank" title="MySpace"><img class="icon" src="img/icons/ms.png" /></a>
<a href="{$smarty.const.URL_RN}" target="_blank" title="ReverbNation"><img class="icon" src="img/icons/rn.png" /></a>
<a href="{$smarty.const.URL_YT}" target="_blank" title="YouTube"><img class="icon" src="img/icons/yt.png" /></a>