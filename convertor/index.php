<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('memory_limit', '-1');

$input_dir = 'input/demo/';
$output_dir = 'output/';
$output_name='demo.png';
$output_format = 'png';
$output_cols = 10;

$nb_images = 0;
$images = scandir($input_dir);
if ($images) {
	$images = array_values(array_filter($images, function($item) {
		global $input_dir;
	    return ! is_dir($input_dir.$item);
	}));
	$nb_images = count($images);
}

if ($nb_images == 0) {
	die('Unable to load images');
}
if ($output_format != 'png') {
	die('Only png format is supported');
}
$output_lines = ceil($nb_images / $output_cols);

$size = getimagesize($input_dir.$images[0]);
if (! $size) {
	die('Unable to get images sizes');
}
$item_width = $size[0];
$item_height = $size[1];
$x = $y = $i = $j = 0;

// Create final image
$img = imagecreatetruecolor($item_width * $output_cols, $item_height * $output_lines);
imagesavealpha($img, true);
$bg = imagecolorallocatealpha($img, 0, 0, 0, 127);
imagefill($img, 0, 0, $bg);

// Copy images
foreach($images as $image) {
	$src = imagecreatefrompng($input_dir.$image);
	imagecopy($img, $src, $x, $y, 0, 0, $item_width, $item_height);
	imagedestroy($src);
	$j++;
	if ($j >=  $output_cols) {
		$j = 0;
		$i++;
	}
	$x = $j * $item_width;
	$y = $i * $item_height;
}

imagepng($img, $output_dir.$output_name);
imagedestroy($img);

echo '<a href="'.$output_dir.$output_name.'" target="_blank">Voir l\'image</a>';
?>