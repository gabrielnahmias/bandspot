function ShowHint(id,hint)
{
 document.getElementById('hintbox'+id).value=hint;
}
function ShowHint2(hint)
{
 document.addnewsfrm.hintbox2.value=hint;
}
function fShowHint(hint)
{
 document.getElementById("hintbox").innerHTML=hint;
}

function CheckBlankFields1()
{
 if (document.setupform.displayname.value=='' || document.setupform.accountname.value=='' || document.setupform.password.value=='' || document.setupform.emailaddress.value=='') {return false;} else {return true;}
}