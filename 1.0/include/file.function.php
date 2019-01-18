<?
define ('UTF32_BIG_ENDIAN_BOM'   , chr(0x00) . chr(0x00) . chr(0xFE) . chr(0xFF));
define ('UTF32_LITTLE_ENDIAN_BOM', chr(0xFF) . chr(0xFE) . chr(0x00) . chr(0x00));
define ('UTF16_BIG_ENDIAN_BOM'   , chr(0xFE) . chr(0xFF));
define ('UTF16_LITTLE_ENDIAN_BOM', chr(0xFF) . chr(0xFE));
define ('UTF8_BOM'               , chr(0xEF) . chr(0xBB) . chr(0xBF));

function detect_utf_encoding($text) {
    $first2 = substr($text, 0, 2);
    $first3 = substr($text, 0, 3);
    $first4 = substr($text, 0, 3);
  
    if ($first3 == UTF8_BOM) return 'UTF-8';
    elseif ($first4 == UTF32_BIG_ENDIAN_BOM) return 'UTF-32BE';
    elseif ($first4 == UTF32_LITTLE_ENDIAN_BOM) return 'UTF-32LE';
    elseif ($first2 == UTF16_BIG_ENDIAN_BOM) return 'UTF-16BE';
    elseif ($first2 == UTF16_LITTLE_ENDIAN_BOM) return 'UTF-16LE';
}
function getFileEncoding($str){
    $encoding=mb_detect_encoding($str);
    if(empty($encoding)){
        $encoding=detect_utf_encoding($str);
    }
    return $encoding;
}
function get_code($codefile){
	if(file_exists($codefile)){
		$handle = fopen($codefile, "r");
		while (!feof($handle)) {
			$buffer = fgets($handle);
			
			$encode=getFileEncoding($buffer);
			if($encode!="UTF-8"){
				$code.=strtr(iconv("GB2312","UTF-8",$buffer),array('<'=>'&lt;'));
			}else{
				$code.=strtr($buffer,array('<'=>'&lt;'));
			}
		}
		fclose($handle);
	}else{
		return "404 文件没有找到";
	}
	return $code;
}
?>