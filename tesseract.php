<?php
$tesseractPath = __DIR__ . '/tesseract';
if(!file_exists($tesseractPath)) {
  mkdir($tesseractPath, 0777);
}
foreach(glob(__DIR__ . '/raw/*.jpg') AS $jpgFile) {
  $p = pathinfo($jpgFile);
  $targetFile = $tesseractPath . '/' . $p['filename'];
  exec("/usr/bin/tesseract {$jpgFile} {$targetFile} -l chi_tra hocr");
}
