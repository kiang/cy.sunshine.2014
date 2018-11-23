<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

foreach(glob(__DIR__ . '/xlsx/*.xlsx') AS $xlsFile) {
  $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($xlsFile);
  $worksheet = $spreadsheet->getActiveSheet();
  // Get the highest row and column numbers referenced in the worksheet
  $highestRow = $worksheet->getHighestRow(); // e.g. 10

  $fh = fopen(__DIR__ . '/reports/' . $worksheet->getCellByColumnAndRow(1, 1)->getValue() . '.csv', 'w');
  for($i = 2; $i <= $highestRow; $i++) {
    $line = array();
    for($j = 1; $j <= 9; $j++) {
      $line[] = $worksheet->getCellByColumnAndRow($j, $i)->getValue();
    }
    if(!empty($line[0])) {
      fputcsv($fh, $line);
    }
  }
  fclose($fh);
}
