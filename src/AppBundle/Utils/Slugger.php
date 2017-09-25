<?php 

namespace AppBundle\Utils;

class Slugger
{
	public function slugify($title)
	{
		$search = explode(",","ç,æ,œ,á,é,í,ó,ú,à,è,ì,ò,ù,ä,ë,ï,ö,ü,ÿ,â,ê,î,ô,û,å,e,i,ø,u");
		$replace = explode(",","c,ae,oe,a,e,i,o,u,a,e,i,o,u,a,e,i,o,u,y,a,e,i,o,u,a,e,i,o,u");


	    return str_replace(' ', '-', mb_strtolower(str_replace($search, $replace, $title), 'UTF-8') ) ;
	}
}