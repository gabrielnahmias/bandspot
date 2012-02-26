<?php



function GenerateCatlist($page,$listitem,$listbody){
	$catlist=GetCatList();
	$links='';
	foreach ($catlist as $i=>$cat){
		$link=$page."?xnews-catlist={CATID}";
		$tmp=str_replace("{LINK}",$link,$listitem);
		$tmp=str_replace("{CATNAME}",$cat['name'],$tmp);
		$tmp=str_replace("{CATID}",$cat['id'],$tmp);
		$links.=$tmp;
	}
	return str_replace('{LINKS}',$links,$listbody);
}

function GenerateHeadlinks($page,$shownum,$listitem,$listbody,$catlist='',$authorlist=''){
	$newsfiles=GetArchList("newest");
	$links='';

	$num=0;
	if (count($newsfiles)>0) 
		foreach ($newsfiles as $i=>$line){
			$news=GetNews($line,$catlist,$authorlist,0,'newest',"");
			foreach ($news as $j=>$article) {
				$link=$page."?xnewsaction=fullnews&amp;newsarch={NEWSARCH}&amp;newsid={NEWSID}";
				$temp=str_replace('{LINK}',$link,$listitem);
				$temp=str_replace('{NEWSARCH}',$article['archive'],$temp);
				$temp=str_replace('{NEWSID}',$article['id'],$temp);
				$temp=str_replace('{HEADLINE}',$article['title'],$temp);
				$links.=$temp;
				$num++;
				if ($num==$shownum)
        			break;
		
			}
			if ($num==$shownum)
				break;
      
		}
  

	return str_replace('{LINKS}',$links,$listbody);
}


?>
