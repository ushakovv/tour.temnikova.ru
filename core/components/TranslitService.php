<?php

namespace core\components;

class TranslitService {

	public static function cyrillicToLatin($text, $toLowCase = TRUE, $spaceReplacement = "-", $maxLength = null)
	{
		$matrix=array(
			"й"=>"i","ц"=>"c","у"=>"u","к"=>"k","е"=>"e","н"=>"n",
			"г"=>"g","ш"=>"sh","щ"=>"shch","з"=>"z","х"=>"h","ъ"=>"",
			"ф"=>"f","ы"=>"y","в"=>"v","а"=>"a","п"=>"p","р"=>"r",
			"о"=>"o","л"=>"l","д"=>"d","ж"=>"zh","э"=>"e","ё"=>"e",
			"я"=>"ya","ч"=>"ch","с"=>"s","м"=>"m","и"=>"i","т"=>"t",
			"ь"=>"","б"=>"b","ю"=>"yu",
			"Й"=>"I","Ц"=>"C","У"=>"U","К"=>"K","Е"=>"E","Н"=>"N",
			"Г"=>"G","Ш"=>"SH","Щ"=>"SHCH","З"=>"Z","Х"=>"X","Ъ"=>"",
			"Ф"=>"F","Ы"=>"Y","В"=>"V","А"=>"A","П"=>"P","Р"=>"R",
			"О"=>"O","Л"=>"L","Д"=>"D","Ж"=>"ZH","Э"=>"E","Ё"=>"E",
			"Я"=>"YA","Ч"=>"CH","С"=>"S","М"=>"M","И"=>"I","Т"=>"T",
			"Ь"=>"","Б"=>"B","Ю"=>"YU",
			"«"=>"","»"=>""," "=>"-", "\""=>"", "–"=>"-", "\,"=>"", "\("=>"", "\)"=>"",
			"\?"=>"", "\!"=>"", "\:"=>"", '#' => '', '№' => '',' - '=>'-', '/'=>'-', '  '=>'-',
		);
		$matrix[' '] = $spaceReplacement;

		if($maxLength){
			// Enforce the maximum component length
			$text = implode(array_slice(explode('<br>',wordwrap(trim(strip_tags(html_entity_decode($text))),$maxLength,'<br>',false)),0,1));
		} else {
			$text = trim(strip_tags(html_entity_decode($text)));
		}

		foreach($matrix as $from=>$to)
			$text=mb_eregi_replace($from,$to,$text);

		// Optionally convert to lower case.
		if ($toLowCase)
		{
			$text = strtolower($text);
		}

		return $text;
	}

} 