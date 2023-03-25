<?php
// Function to download the MP3 file
function downloadMP3($url, $fileName) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
    $data = curl_exec($ch);
    curl_close($ch);
    file_put_contents($fileName, $data);
}

// URL to fetch
$url = 'https://tinyenlau.nl';

echo "Fetching URL: $url" . PHP_EOL;

// Send GET request and fetch the HTML content
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$html = curl_exec($ch);
curl_close($ch);

// Check if the HTML content is fetched
if (empty($html)) {
    echo "Failed to fetch the URL. Please check the URL or your internet connection." . PHP_EOL;
    exit;
}

// Use regex to find audio elements with src attribute
$pattern = '/<audio[^>]+src="([^"]+\.mp3)"[^>]*>/';
preg_match_all($pattern, $html, $matches);

if (count($matches[1]) > 0) {
    echo "Found " . count($matches[1]) . " audio element(s)." . PHP_EOL;
} else {
    echo "No audio elements found." . PHP_EOL;
}

$i = 1;
foreach ($matches[1] as $src) {
    $fileName = 'audio_' . $i . '.mp3';
    $fileUrl = $url . '/' . $src;
    downloadMP3($fileUrl, $fileName);
    echo "Downloaded: " . $fileName . PHP_EOL;
    $i++;
}
