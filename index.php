<?php

use Elminson\BigJson\FileGenerator as BigJsonFileGenerator;
use Elminson\BigJson\FileReader as BigJsonFileReader;

require_once 'vendor/autoload.php';

$fileName = 'big_file.json';
$totalItems = 10000;

// Start measuring memory usage and CPU time
$memoryStart = memory_get_usage();
$cpuStart = microtime(true);

$generator = new BigJsonFileGenerator($fileName, $totalItems);

// Generate the JSON file
foreach ($generator as $item) {
	fwrite($generator->fileHandle, $item . "\n");
}

// Reading the generated JSON file using yield
$reader = new BigJsonFileReader($fileName);

// Using the where method to filter items
// $filteredItems = $reader->where('id', '=', 1000);
$filteredItems = $reader->where('contact.email', '=', 'danieljones@example.com');

foreach ($filteredItems as $item) {
	echo "Filtered Item: {$item['name']}\n";
}

// Using the first method
$firstItem = $reader->first();
if ($firstItem) {
	echo "First Item: {$firstItem['id']}\n";
} else {
	echo "No items found.\n";
}


// Stop measuring memory usage and CPU time
$memoryEnd = memory_get_usage();
$cpuEnd = microtime(true);

// Calculate memory and CPU usage
$memoryUsage = $memoryEnd - $memoryStart;
$cpuUsage = round(($cpuEnd - $cpuStart), 2);

echo "Total Items: " . number_format($totalItems) . PHP_EOL;
echo "Memory Used: " . formatBytes($memoryUsage) . PHP_EOL;
echo "CPU Time Used: ". $cpuUsage." seconds" . PHP_EOL;

function formatBytes($bytes, $precision = 2)
{

	$units = ['B', 'KB', 'MB', 'GB', 'TB'];

	$bytes = max($bytes, 0);
	$pow = floor(($bytes ? log($bytes) : 0) / log(1024));
	$pow = min($pow, count($units) - 1);

	return number_format(round($bytes, $precision)) . ' ' . $units[$pow];
}