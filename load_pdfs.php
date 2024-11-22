<?php
$directory = 'dokumente/unpacked/';
$files = scandir($directory);
$pdfFiles = array_filter($files, function($file) {
    return pathinfo($file, PATHINFO_EXTENSION) === 'pdf';
});

echo json_encode(array_values($pdfFiles));
?>
