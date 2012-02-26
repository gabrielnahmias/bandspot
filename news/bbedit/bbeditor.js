  function OnNewsSubmit(id)
  {
  
  }

function MakeUrlCode()
{
  var url = prompt('Enter the url to the web page','http://');
  var title = prompt('Enter a name for the web page');
  if (url==null || url=='http://'){alert('You must enter a valid url');return ''; exit;}
  if (title==''){alert('You must enter a title');return ''; exit;}
  return '[url='+url+']'+title+'[/url]';
}

function MakeVIMGCode()
{
  var url = prompt('Enter the url to the image','http://');
  var title = prompt('Enter a name for the image');
  if (url==null || url=='http://'){alert('You must enter a valid url');return ''; exit;}
  if (title==''){alert('You must enter a title');return ''; exit;}
  return '[vimg='+url+']'+title+'[/vimg]';
}

function InsertBBCode(tg, bbcode)
{
  var target = document.getElementById(tg);
  if (document.selection)
   {
    target.focus();
    sel = document.selection.createRange();
    sel.text = bbcode;
   }
   else if (target.selectionStart || target.selectionStart == 0)
   {
    var startPos = target.selectionStart;
    var endPos = target.selectionEnd;
    target.value = target.value.substring(0, startPos)+ bbcode+ target.value.substring(endPos, target.value.length);
   } else 
   {
    target.value += bbcode;
   }
   target.focus();
}

function InsertImage(tg,url)
{
 InsertBBCode(tg,'[img]'+url+'[/img]'); 
}
