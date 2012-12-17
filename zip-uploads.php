<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// pwd and dir
$pwd = isset($_GET['pwd']) && $_GET['pwd'] == '20130101' ? true : false;
$dir = isset($_GET['dir']) && $_GET['dir'] == date('m') ? true : false;

// Get the directory to zip
$directory = rtrim(dirname(__FILE__), '/ ') .'/wp-content/uploads';
$directory .= $dir ? ( '/' . date('Y') . '/' . date('m') ) : '';

if ($pwd && file_exists($directory)) {
	// get a tmp name for the .zip
	$tmp_zip = rtrim(dirname(__FILE__), '/ ') .'/wp-content/upgrade/folder'. time() .'.zip';

	// ZipIt
	$zip = Zip($directory, $tmp_zip);

	// calc the length of the zip. it is needed for the progress bar of the browser
	$filesize = @filesize($tmp_zip);
	if ($filesize > 0) {
		// we deliver a zip file
		header('Content-Type: archive/zip');
		header('Content-Disposition: attachment; filename=wp-content-uploads.'. time() . '.zip');
		header("Content-Length: {$filesize}");

		// deliver the zip file
		$fp = fopen($tmp_zip,'r');
		echo fpassthru($fp);

		// clean up the tmp zip file
		unlink($tmp_zip);
	}

}
else {
	print $directory . "\n";
}


function Zip($source, $destination) {
	if (!extension_loaded('zip') || !file_exists($source)) {
		return false;
	}

	$zip = new ZipArchive();
	if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
		return false;
	}

	$source = str_replace('\\', '/', realpath($source));
	if (is_dir($source) === true) {
		$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);
		foreach ($files as $file)
		{
			$file = str_replace('\\', '/', $file);

			// Ignore "." and ".." folders
			if( in_array(substr($file, strrpos($file, '/')+1), array('.', '..')) ) continue;

			$file = realpath($file);
			if (is_dir($file) === true) {
				$zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
			}
			elseif (is_file($file) === true) {
				$zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
			}
		}
	}
	elseif (is_file($source) === true) {
		$zip->addFromString(basename($source), file_get_contents($source));
	}

	return $zip->close();
}
