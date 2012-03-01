{$smarty.const.TEXT_NO_JS}

<div class="fb-comments" data-href="http://{$smarty.server.HTTP_HOST}{if $smarty.server.REQUEST_URI == '/'}/home{else}{$sCurrent}{/if}" data-num-posts="{$smarty.const.FB_COMMENTS_NUM}" data-width="{$smarty.const.FB_COMMENTS_WIDTH}"></div>