<?php

function ombublog_views_default_views() {

	$dir =  dirname(__FILE__) . '/views';
	$views = array();

	if ($handle = opendir($dir)) {
		while (false !== ($file = readdir($handle))) {
			if ($file !='.' && $file !='..'){
				$view = new stdClass();
				require $dir . '/' . $file;
				$views[$view->name] = $view;
			}
		}
		closedir($handle);
	}
	return $views;
}
