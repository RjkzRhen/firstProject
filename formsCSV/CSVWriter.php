<?php
namespace formsCSV; // РћРїСЂРµРґРµР»СЏРµС‚ РїСЂРѕСЃС‚СЂР°РЅСЃС‚РІРѕ РёРјРµРЅ РґР»СЏ РєР»Р°СЃСЃР° CSVWriter.

use Exception;

class CSVWriter { // РћР±СЉСЏРІР»РµРЅРёРµ РєР»Р°СЃСЃР° CSVWriter.
    private $filePath; // РџСЂРёРІР°С‚РЅРѕРµ СЃРІРѕР№СЃС‚РІРѕ РґР»СЏ С…СЂР°РЅРµРЅРёСЏ РїСѓС‚Рё Рє С„Р°Р№Р»Сѓ CSV.

    public function __construct($filePath) { // РљРѕРЅСЃС‚СЂСѓРєС‚РѕСЂ РєР»Р°СЃСЃР°, РїСЂРёРЅРёРјР°РµС‚ РїСѓС‚СЊ Рє С„Р°Р№Р»Сѓ РєР°Рє РїР°СЂР°РјРµС‚СЂ.
        $this->filePath = $filePath; // РџСЂРёСЃРІР°РёРІР°РЅРёРµ Р·РЅР°С‡РµРЅРёСЏ РїР°СЂР°РјРµС‚СЂР° filePath СЃРІРѕР№СЃС‚РІСѓ РєР»Р°СЃСЃР°.
    }

    public function addRecord(array $data): void { // РњРµС‚РѕРґ РґР»СЏ РґРѕР±Р°РІР»РµРЅРёСЏ Р·Р°РїРёСЃРё РІ CSV С„Р°Р№Р», РїСЂРёРЅРёРјР°РµС‚ РјР°СЃСЃРёРІ РґР°РЅРЅС‹С….Р­С‚РѕС‚ РјРµС‚РѕРґ РІС‹Р·С‹РІР°РµС‚СЃСЏ, РєРѕРіРґР° РІСЃРµ РїРѕР»СЏ С„РѕСЂРјС‹, РїРѕР»СѓС‡РµРЅРЅС‹Рµ РёР· Р·Р°РїСЂРѕСЃР° POST, РїСЂРѕРІРµСЂРµРЅС‹ Рё СЏРІР»СЏСЋС‚СЃСЏ РґРѕРїСѓСЃС‚РёРјС‹РјРё.
        $handle = fopen($this->filePath, 'a');  // РћС‚РєСЂС‹РІР°РµС‚ С„Р°Р№Р» РІ СЂРµР¶РёРјРµ РґРѕР±Р°РІР»РµРЅРёСЏ.
        if (!$handle) { // РџСЂРѕРІРµСЂСЏРµС‚ СѓСЃРїРµС€РЅРѕСЃС‚СЊ РѕС‚РєСЂС‹С‚РёСЏ С„Р°Р№Р»Р°.
            throw new Exception("Cannot open file: " . $this->filePath); // Р’С‹Р±СЂР°СЃС‹РІР°РµС‚ РёСЃРєР»СЋС‡РµРЅРёРµ, РµСЃР»Рё С„Р°Р№Р» РЅРµ СѓРґР°С‘С‚СЃСЏ РѕС‚РєСЂС‹С‚СЊ.
        }

        // РљРѕРЅРІРµСЂС‚Р°С†РёСЏ РјР°СЃСЃРёРІР° РІ СЃС‚СЂРѕРєСѓ, СЂР°Р·РґРµР»С‘РЅРЅСѓСЋ СЃРёРјРІРѕР»РѕРј ';'
        $csvLine = implode(';', $data);

        // Р—Р°РїРёСЃСЊ СЃС‚СЂРѕРєРё РІ С„Р°Р№Р» СЃ РїСЂРµРѕР±СЂР°Р·РѕРІР°РЅРёРµРј РєРѕРґРёСЂРѕРІРєРё РІ Windows-1251 РёР· UTF-8 Рё РґРѕР±Р°РІР»РµРЅРёРµРј СЃРёРјРІРѕР»Р° РЅРѕРІРѕР№ СЃС‚СЂРѕРєРё.
        fwrite($handle, mb_convert_encoding($csvLine . "\n", 'Windows-1251', 'UTF-8'));
        fclose($handle); // Р—Р°РєСЂС‹РІР°РµС‚ С„Р°Р№Р».
    }
}