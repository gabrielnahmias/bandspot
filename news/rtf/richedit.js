 

function insertRawHTML(html,id)
   {
        var getWin = document.getElementById("iView" + id).contentWindow;
        getWin.focus();
        if (document.all) {
                var oRng = getWin.document.selection.createRange();
                oRng.pasteHTML(html);
                oRng.collapse(false);
                oRng.select();
        } else {
                getWin.document.execCommand('insertHTML', false, html);
        }
}



function get_text_selection(id)
{
    var txt = '';
    var win = document.getElementById("iView" + id).contentWindow; 
      if (win.getSelection)
    {
        txt = win.getSelection();
     }
    else if (win.document.getSelection)
    {
        txt = win.document.getSelection();
    }
	else if (win.document.selection)
	{
		txt = win.document.selection.createRange().text;
	}
    return txt;
}


function IsSelection(id)
{
    var sel = 0;
	var win = document.getElementById("iView" + id).contentWindow;
	var txt="";
    txt=get_text_selection(id);
    if (txt!="") sel=1;
	return sel;
}

if(typeof HTMLElement!="undefined" && !HTMLElement.prototype.insertAdjacentElement){
  HTMLElement.prototype.insertAdjacentElement = function
  (where,parsedNode)
	{
	  switch (where){
		case 'beforeBegin':
			this.parentNode.insertBefore(parsedNode,this)
			break;
		case 'afterBegin':
			this.insertBefore(parsedNode,this.firstChild);
			break;
		case 'beforeEnd':
			this.appendChild(parsedNode);
			break;
		case 'afterEnd':
			if (this.nextSibling) 
      this.parentNode.insertBefore(parsedNode,this.nextSibling);
			else this.parentNode.appendChild(parsedNode);
			break;
		}
	}

	HTMLElement.prototype.insertAdjacentHTML = function
  (where,htmlStr)
	{
		var r = this.ownerDocument.createRange();
		r.setStartBefore(this);
		var parsedHTML = r.createContextualFragment(htmlStr);
		this.insertAdjacentElement(where,parsedHTML)
	}


	HTMLElement.prototype.insertAdjacentText = function
  (where,txtStr)
	{
		var parsedText = document.createTextNode(txtStr)
		this.insertAdjacentElement(where,parsedText)
	}
};

function getOffsetTop(elm) {

  var mOffsetTop = elm.offsetTop;
  var mOffsetParent = elm.offsetParent;

  while(mOffsetParent){
    mOffsetTop += mOffsetParent.offsetTop;
    mOffsetParent = mOffsetParent.offsetParent;
  }
 
  return mOffsetTop;
}

function getOffsetLeft(elm) {

  var mOffsetLeft = elm.offsetLeft;
  var mOffsetParent = elm.offsetParent;

  while(mOffsetParent){
    mOffsetLeft += mOffsetParent.offsetLeft;
    mOffsetParent = mOffsetParent.offsetParent;
  }
 
  return mOffsetLeft;
}
  
  function SetDesignMode(id)
  {
	
	document.getElementById("tabHTMLview"+id).className='SpanClass2';
	document.getElementById("tabSourceview"+id).fontWeight='normal';
	document.getElementById("tabSourceview"+id).className='SpanClass4';
	document.getElementById("tabHTMLview"+id).fontWeight='bold';
	document.getElementById("tabHTMLview"+id).style.background='url(inc/img/toolbar-tab.jpg)';  
	document.getElementById("tabSourceview"+id).style.background='url()';  
    ShowDesign(id);	
  }
  
  function SetCodeMode(id)
  {
	document.getElementById("tabHTMLview"+id).className="SpanClass4";
	document.getElementById("tabHTMLview"+id).fontWeight='normal';  
	document.getElementById("tabSourceview"+id).fontWeight='bold';
	document.getElementById("tabSourceview"+id).className='SpanClass2';
	document.getElementById("tabSourceview"+id).style.background='url(inc/img/toolbar-tab.jpg)';  
	document.getElementById("tabHTMLview"+id).style.background='url()';  
	ShowSource(id);
  }  
  
  function MakeEnv(id)
  {
    var iframe = '<iframe frameborder="0" id="iView' + id + '" style="width: 99%; height:205px"></iframe>\n';
    document.getElementById("textbox"+id).style.display = "none"; 
 	document.getElementById("textbox"+id).insertAdjacentHTML("afterEnd", iframe);
	
	var doc = document.getElementById("iView" + id).contentWindow.document;
    doc.open();
    doc.write(document.getElementById("textbox"+id).value);
    doc.close();
	doc.body.contentEditable = true;
    doc.designMode = "on";
  
  }
  
  function UpdateTextBox(id)
  {
    var src = ""; 
    var getDocument = document.getElementById("iView" + id).contentWindow.document;
	var browserName = navigator.appName;
	
    src = getDocument.body.innerHTML;
    document.getElementById("textbox"+id).value=src;
  }
  
  function OnNewsSubmit(id)
  {
  
  }
  

  
  function setHTMLBold(id)
  {
	document.getElementById("iView"+id).contentWindow.document.execCommand('bold', false, null);
    document.getElementById("iView"+id).contentWindow.focus();  
  }

  function setHTMLItalic(id)
  {
	document.getElementById("iView"+id).contentWindow.document.execCommand('italic', false, null);
    document.getElementById("iView"+id).contentWindow.focus();  
  }

  function setHTMLUnderline(id)
  {
	document.getElementById("iView"+id).contentWindow.document.execCommand('underline', false, null);
    document.getElementById("iView"+id).contentWindow.focus();  
  }
  
  function setHTMLStrikethrough(id)
  {
	document.getElementById("iView"+id).contentWindow.document.execCommand('strikethrough', false, null);
    document.getElementById("iView"+id).contentWindow.focus();  
  }  
  
  function doLeft(id)
  {
    document.getElementById("iView"+id).contentWindow.document.execCommand('justifyleft', false, null);
  }

  function doCenter(id)
  {
    document.getElementById("iView"+id).contentWindow.document.execCommand('justifycenter', false, null);
  }

  function doRight(id)
  {
    document.getElementById("iView"+id).contentWindow.document.execCommand('justifyright', false, null);
  }
 

  function doOrdList(id)
  {
    document.getElementById("iView"+id).contentWindow.document.execCommand('insertorderedlist', false, null);
  }

  function doBulList(id)
  {
    document.getElementById("iView"+id).contentWindow.document.execCommand('insertunorderedlist', false, null);
  }

  function doTextLink(id)
  {
   document.getElementById("iView"+id).contentWindow.document.execCommand('createlink', false,get_text_selection(id));
  }

  function doLink(opener,id,linkname,linkurl,openmode)
  {
	var hlink="";
	hlink='<a href="'+linkurl+'" target="'+openmode+'">'+linkname+'</a>&nbsp;';
	opener.insertRawHTML(hlink,id);
	opener.document.getElementById("iView" + id).contentWindow.focus(); 
  }
  
  function doImage(id,url,text,border,alignment)
  {
    if (border=="") border="0";
	var html='<img src="'+url+'" alt="'+text+'" border="'+border+'"';
	if (alignment!="") html=html+' alignment="'+alignment+'">'; else html=html+'>';
    opener.insertRawHTML(html,id);
	opener.document.getElementById("iView" + id).contentWindow.focus(); 
  }
  
  function doTable(id,table)
  {
    opener.insertRawHTML(table,id);
	opener.document.getElementById("iView" + id).contentWindow.focus(); 
  }
  
  function InsertImage(id,url)
  {
    if (id=="shortstory") id="1";
    if (id=="fullstory") id="2";
	var html='<img src="'+url+'" border="0">';
	insertRawHTML(html,id);
	document.getElementById("iView" + id).contentWindow.focus(); 

  }
  
  function doRule()
  {
    document.getElementById("iView").contentWindow.document.execCommand('inserthorizontalrule', false, null);
  }
  
  
  
  function doHead(hType)
  {
    if(hType != '')
    {
      document.getElementById("iView").contentWindow.document.execCommand('formatblock', false, hType);  
      doFont(selFont.options[selFont.selectedIndex].value);
    }
  }
  
  function ShowSource(id)
  {
    var getDocument = document.getElementById("iView" + id).contentWindow.document;
	var browserName = navigator.appName;
	
    if (browserName == "Microsoft Internet Explorer")
	{
     var iHTML = getDocument.body.innerHTML;
     getDocument.body.innerText = iHTML;
	}
    else 
	{
     var html = document.createTextNode(getDocument.body.innerHTML);
     getDocument.body.innerHTML = "";
     getDocument.body.appendChild(html);
    }
	getDocument.body.style.fontSize = "11px";
	getDocument.body.style.fontFamily = "Verdana";

 
   }	
  
  function ShowDesign(id)
  {
    var getDocument = document.getElementById("iView" + id).contentWindow.document;
    var browserName = navigator.appName;
	if (browserName == "Microsoft Internet Explorer") 
	{
     var iText = getDocument.body.innerText;
     getDocument.body.innerHTML = iText;
	}
    else
    {
     var html = getDocument.body.ownerDocument.createRange();
     html.selectNodeContents(getDocument.body);
     getDocument.body.innerHTML = html.toString();
	}
    getDocument.body.style.fontSize = "";
	getDocument.body.style.fontFamily = "";

  }
  
  


  
  function InsertLiveEmoticon(emoticon,id,sender)
  {
    var getDocument = document.getElementById("iView" + id).contentWindow.document;
    var browserName = navigator.appName;
	emoticon='<img src="'+sender.src+'" name="emoticon" id="'+emoticon+'">';
	insertRawHTML(emoticon,id);
	document.getElementById("iView" + id).contentWindow.focus(); 
  }
  
 function ShowColorPalette(id,caller,mode)
 {
	document.getElementById("colorpalette"+id).style.left = getOffsetLeft(document.getElementById(caller));
	if (mode!='') 
	{
	 document.getElementById("colorpalette"+id).style.left=250;
     	
	}
	document.getElementById("colorpalette"+id).style.top = getOffsetTop(document.getElementById(caller))+document.getElementById(caller).height;
    document.getElementById("colorpalette"+id).style.visibility="visible"; 
	document.getElementById("colorpalette"+id).contentWindow.callerid=id;
	document.getElementById("colorpalette"+id).contentWindow.mode=mode;
 }

 function HideColorPalette(id)
 {
    document.getElementById("colorpalette"+id).style.visibility="hidden"; 
 }
 function SelectOption(id,obj)
 {
    var tname=obj+id;
	var seln = document.getElementById(tname).selectedIndex;
	var selected = document.getElementById(tname).options[seln].value;
    document.getElementById('iView'+id).contentWindow.document.execCommand(obj, false, selected);
	document.getElementById("iView"+id).contentWindow.focus();
 }
  

