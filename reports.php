<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$pageFh = fopen(__DIR__ . '/index.html', 'w');

fputs($pageFh, '<!DOCTYPE html>');
fputs($pageFh, '<html lang="zh-tw">');
fputs($pageFh, '<head>');
fputs($pageFh, '<meta charset="utf-8">');
fputs($pageFh, '<title>103年擬參選人政治獻金專戶摘要表(營利事業)</title>');
fputs($pageFh, '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">');
fputs($pageFh, '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">');
fputs($pageFh, '</head>');
fputs($pageFh, '<body>');
fputs($pageFh, '<h1>103年擬參選人政治獻金專戶摘要表(營利事業)</h1>');
fputs($pageFh, '<table class="table table-bordered table-responsive">');
fputs($pageFh, '<thead><tr><td>帳戶名稱</td><td>資料筆數</td><td>金額小計</td><td>報表檔案</td><td>掃描圖檔</td></tr></thead>');
fputs($pageFh, '<tbody>');
foreach(glob(__DIR__ . '/xlsx/*.xlsx') AS $xlsFile) {
  $rawXlsPath = pathinfo($xlsFile);
  $rawImageUrl = 'https://kiang.github.io/cy.sunshine.2014/raw/' . $rawXlsPath['filename'] . '.jpg';
  $xlsxUrl = 'https://kiang.github.io/cy.sunshine.2014/xlsx/' . $rawXlsPath['filename'] . '.xlsx';
  $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($xlsFile);
  $worksheet = $spreadsheet->getActiveSheet();
  // Get the highest row and column numbers referenced in the worksheet
  $highestRow = $worksheet->getHighestRow(); // e.g. 10
  $accountName = $worksheet->getCellByColumnAndRow(1, 1)->getValue();
  $reportFile = '/reports/' . $accountName . '.csv';
  $csvUrl = 'https://kiang.github.io/cy.sunshine.2014' . $reportFile;

  $fh = fopen(__DIR__ . $reportFile, 'w');
  $lineCount = -1;
  $lineSum = 0;
  for($i = 2; $i <= $highestRow; $i++) {
    $line = array();
    for($j = 1; $j <= 9; $j++) {
      $line[] = $worksheet->getCellByColumnAndRow($j, $i)->getValue();
    }
    if(!empty($line[0])) {
      $lineSum += $line[5];
      ++$lineCount;
      fputcsv($fh, $line);
    }
  }
  fclose($fh);
  fputs($pageFh, "<tr>");
  fputs($pageFh, "<td>{$accountName}</td>");
  fputs($pageFh, "<td>{$lineCount}</td>");
  fputs($pageFh, "<td>{$lineSum}</td>");
  fputs($pageFh, "<td><a href=\"{$xlsxUrl}\" target=\"_blank\">xlsx</a>, <a href=\"{$csvUrl}\" target=\"_blank\">csv</a></td>");
  fputs($pageFh, "<td><a href=\"{$rawImageUrl}\" target=\"_blank\">{$rawXlsPath['filename']}</a></td>");
  fputs($pageFh, "</tr>");
}
fputs($pageFh, '</tbody>');
fputs($pageFh, '</table>');
fputs($pageFh, '</body></html>');
