<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


function get_files($dir) {
	$files = array();

	if(!is_dir($dir)) {
		return $files;
	}

	$handle = opendir($dir);
	if($handle) {
		while(false !== ($file = readdir($handle))) {
			if ($file != '.' && $file != '..') {
				$filename = $dir . "/"  . $file;
				if(is_file($filename) && preg_match('/\.php$/', $filename)) {
					$filename = preg_replace('/.php$/', '',  basename($filename));
					$files[] = $filename;
				}else {
					$files = array_merge($files, get_files($filename));
				}
			}
		}   //  end while
		closedir($handle);
	}
	return $files;
}   //  end function

function merge($origin, $get){	//{{{
	return array_unique(array_merge($origin, $get));
}	//}}}

$libs_file = APPPATH . "libraries";
$models_file = APPPATH . "models";
$autoload['libraries'] = merge($autoload['libraries'], get_files($libs_file));
$autoload['model'] = merge($autoload['model'], get_files($models_file));
