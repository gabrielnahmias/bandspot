window.fbAsyncInit = function() {
  FB.init({
    appId      : oPHP.const.ID_FB_APP,
    status     : true, 
    cookie     : true,
    xfbml      : true,
    oauth      : true,
  });
};

(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=" + oPHP.const.ID_FB_APP;
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));