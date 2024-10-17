<?php
$inputFile = 'otherFiles/OpenDocument.csv';
$outputFile = 'otherFiles/OpenDocument_utf8.csv';

$handle = fopen($inputFile, 'rb');
if ($handle === false) {
    throw new Exception("Ошибка при открытии файла: " . $inputFile);
}

$outputHandle = fopen($outputFile, 'wb');
if ($outputHandle === false) {
    throw new Exception("Ошибка при создании файла: " . $outputFile);
}

while (($line = fgets($handle)) !== false) {

    $utf8Line = mb_convert_encoding($line, 'UTF-8', 'Windows-1251');
    fwrite($outputHandle, $utf8Line);
}

fclose($handle);
fclose($outputHandle);

echo "Файл успешно конвертирован в UTF-8 и сохранен как " . $outputFile;