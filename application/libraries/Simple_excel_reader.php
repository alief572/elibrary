<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Simple_excel_reader
 * Lightweight XLSX & CSV Reader and Writer with 0 external dependencies.
 * 100% Compatible with PHP 5.6, PHP 7.x, and PHP 8.x.
 */
class Simple_excel_reader
{
    /**
     * Parse XLSX or CSV file and return 2D array of rows
     */
    public function parse($filePath)
    {
        if (!file_exists($filePath)) {
            return false;
        }

        $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        if ($ext === 'csv' || $ext === 'txt') {
            return $this->parseCsv($filePath);
        }

        if ($ext === 'xlsx') {
            return $this->parseXlsx($filePath);
        }

        // Try XLSX first, then fallback to CSV
        $rows = $this->parseXlsx($filePath);
        if ($rows !== false && !empty($rows)) {
            return $rows;
        }

        return $this->parseCsv($filePath);
    }

    /**
     * Parse native XLSX using ZipArchive and SimpleXML
     */
    public function parseXlsx($filename)
    {
        if (!class_exists('ZipArchive')) {
            return false;
        }

        $zip = new ZipArchive();
        if ($zip->open($filename) !== TRUE) {
            return false;
        }

        // 1. Read shared strings
        $sharedStrings = array();
        $sharedStringsXml = $zip->getFromName('xl/sharedStrings.xml');
        if ($sharedStringsXml !== false) {
            $xml = @simplexml_load_string($sharedStringsXml);
            if ($xml) {
                foreach ($xml->si as $val) {
                    if (isset($val->t)) {
                        $sharedStrings[] = (string)$val->t;
                    } elseif (isset($val->r)) {
                        $str = '';
                        foreach ($val->r as $r) {
                            $str .= (string)$r->t;
                        }
                        $sharedStrings[] = $str;
                    } else {
                        $sharedStrings[] = '';
                    }
                }
            }
        }

        // 2. Read sheet1.xml (or first sheet)
        $sheetXml = $zip->getFromName('xl/worksheets/sheet1.xml');
        if ($sheetXml === false) {
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $entryName = $zip->getNameIndex($i);
                if (strpos($entryName, 'xl/worksheets/sheet') === 0) {
                    $sheetXml = $zip->getFromName($entryName);
                    break;
                }
            }
        }

        if ($sheetXml === false) {
            $zip->close();
            return false;
        }

        $rows = array();
        $xml = @simplexml_load_string($sheetXml);
        if ($xml && isset($xml->sheetData->row)) {
            foreach ($xml->sheetData->row as $row) {
                $rowCells = array();
                foreach ($row->c as $cell) {
                    $attr = $cell->attributes();
                    $cellRef = (string)$attr['r'];
                    $cellType = isset($attr['t']) ? (string)$attr['t'] : '';
                    $val = isset($cell->v) ? (string)$cell->v : '';

                    if ($cellType === 's' && isset($sharedStrings[(int)$val])) {
                        $val = $sharedStrings[(int)$val];
                    } elseif ($cellType === 'inlineStr' && isset($cell->is->t)) {
                        $val = (string)$cell->is->t;
                    }

                    // Extract column letters (A, B, C...)
                    if (preg_match('/^([A-Z]+)/', $cellRef, $matches)) {
                        $colLetters = $matches[1];
                        $colIndex = $this->colIndex($colLetters);
                        $rowCells[$colIndex] = trim($val);
                    }
                }
                if (!empty($rowCells)) {
                    ksort($rowCells);
                    $rows[] = $rowCells;
                }
            }
        }

        $zip->close();
        return $rows;
    }

    /**
     * Parse CSV with auto-detection of delimiter (comma, semicolon, tab)
     */
    public function parseCsv($filename)
    {
        $handle = @fopen($filename, 'r');
        if (!$handle) {
            return false;
        }

        $firstLine = fgets($handle);
        rewind($handle);

        $delimiter = ',';
        $semicolons = substr_count($firstLine, ';');
        $commas = substr_count($firstLine, ',');
        $tabs = substr_count($firstLine, "\t");

        if ($semicolons > $commas && $semicolons > $tabs) {
            $delimiter = ';';
        } elseif ($tabs > $commas && $tabs > $semicolons) {
            $delimiter = "\t";
        }

        $rows = array();
        while (($data = fgetcsv($handle, 4096, $delimiter)) !== FALSE) {
            $cleanRow = array();
            foreach ($data as $k => $v) {
                $cleanRow[$k] = trim($v);
            }
            if (!empty($cleanRow)) {
                $rows[] = $cleanRow;
            }
        }
        fclose($handle);
        return $rows;
    }

    /**
     * Convert Excel column letters (A, B... AA) to 0-based index
     */
    private function colIndex($letters)
    {
        $letters = strtoupper($letters);
        $len = strlen($letters);
        $num = 0;
        for ($i = 0; $i < $len; $i++) {
            $num = $num * 26 + (ord($letters[$i]) - ord('A') + 1);
        }
        return $num - 1;
    }

    /**
     * Convert 0-based column index to Excel column letter
     */
    public function getColLetter($colIdx)
    {
        $letter = '';
        $colIdx = (int)$colIdx + 1;
        while ($colIdx > 0) {
            $mod = ($colIdx - 1) % 26;
            $letter = chr(65 + $mod) . $letter;
            $colIdx = (int)(($colIdx - $mod) / 26);
        }
        return $letter;
    }

    /**
     * Robust Excel date parser (supports serial numbers, YYYY-MM-DD, DD/MM/YYYY, DD-MM-YYYY)
     */
    public function parseDate($value)
    {
        $value = trim((string)$value);
        if (empty($value)) {
            return null;
        }

        // If Excel numeric serial date (e.g. 45521 = 2024-08-17)
        if (is_numeric($value) && (float)$value > 20000 && (float)$value < 70000) {
            $unixTimestamp = ((float)$value - 25569) * 86400;
            return gmdate('Y-m-d', (int)$unixTimestamp);
        }

        // YYYY-MM-DD or YYYY/MM/DD
        if (preg_match('/^(\d{4})[-\/](\d{1,2})[-\/](\d{1,2})$/', $value, $m)) {
            return sprintf('%04d-%02d-%02d', (int)$m[1], (int)$m[2], (int)$m[3]);
        }

        // DD/MM/YYYY or DD-MM-YYYY
        if (preg_match('/^(\d{1,2})[-\/](\d{1,2})[-\/](\d{4})$/', $value, $m)) {
            return sprintf('%04d-%02d-%02d', (int)$m[3], (int)$m[2], (int)$m[1]);
        }

        // Fallback strtotime
        $time = strtotime($value);
        if ($time !== false && $time > 0) {
            return date('Y-m-d', $time);
        }

        return null;
    }

    /**
     * Create a styled XLSX file directly (Pure PHP, 0 dependencies)
     */
    public function createXlsx($filename, $headers, $rows, $sheetName = 'Template')
    {
        if (!class_exists('ZipArchive')) {
            return false;
        }

        $zip = new ZipArchive();
        if ($zip->open($filename, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
            return false;
        }

        // [Content_Types].xml
        $contentTypes = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">
    <Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>
    <Default Extension="xml" ContentType="application/xml"/>
    <Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>
    <Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>
    <Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>
</Types>';
        $zip->addFromString('[Content_Types].xml', $contentTypes);

        // _rels/.rels
        $rels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
    <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>
</Relationships>';
        $zip->addFromString('_rels/.rels', $rels);

        // xl/_rels/workbook.xml.rels
        $wbRels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
    <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>
    <Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>
</Relationships>';
        $zip->addFromString('xl/_rels/workbook.xml.rels', $wbRels);

        // xl/workbook.xml
        $workbook = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">
    <sheets>
        <sheet name="' . htmlspecialchars($sheetName) . '" sheetId="1" r:id="rId1"/>
    </sheets>
</workbook>';
        $zip->addFromString('xl/workbook.xml', $workbook);

        // xl/styles.xml (Header: Navy blue background #1E3A8A, Bold White Text, thin borders)
        $styles = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">
    <fonts count="2">
        <font><sz val="11"/><name val="Calibri"/></font>
        <font><b/><sz val="11"/><color rgb="FFFFFFFF"/><name val="Calibri"/></font>
    </fonts>
    <fills count="3">
        <fill><patternFill patternType="none"/></fill>
        <fill><patternFill patternType="gray125"/></fill>
        <fill><patternFill patternType="solid"><fgColor rgb="FF1E3A8A"/></patternFill></fill>
    </fills>
    <borders count="2">
        <border><left/><right/><top/><bottom/><diagonal/></border>
        <border>
            <left style="thin"><color rgb="FFD1D5DB"/></left>
            <right style="thin"><color rgb="FFD1D5DB"/></right>
            <top style="thin"><color rgb="FFD1D5DB"/></top>
            <bottom style="thin"><color rgb="FFD1D5DB"/></bottom>
        </border>
    </borders>
    <cellStyleXfs count="1">
        <xf numFmtId="0" fontId="0" fillId="0" borderId="0"/>
    </cellStyleXfs>
    <cellXfs count="3">
        <xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/>
        <xf numFmtId="0" fontId="1" fillId="2" borderId="1" xfId="0" applyFont="1" applyFill="1" applyBorder="1" applyAlignment="1">
            <alignment horizontal="center" vertical="center" wrapText="1"/>
        </xf>
        <xf numFmtId="0" fontId="0" fillId="0" borderId="1" xfId="0" applyBorder="1" applyAlignment="1">
            <alignment vertical="center"/>
        </xf>
    </cellXfs>
</styleSheet>';
        $zip->addFromString('xl/styles.xml', $styles);

        // xl/worksheets/sheet1.xml
        $sheetXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">
    <cols>
        <col min="1" max="1" width="8" customWidth="1"/>
        <col min="2" max="2" width="28" customWidth="1"/>
        <col min="3" max="3" width="40" customWidth="1"/>
        <col min="4" max="4" width="24" customWidth="1"/>
        <col min="5" max="5" width="42" customWidth="1"/>
    </cols>
    <sheetData>';

        $rowNum = 1;
        // Header row
        $sheetXml .= '<row r="1" ht="28" customHeight="1">';
        foreach ($headers as $colIdx => $headerText) {
            $colLetter = $this->getColLetter($colIdx);
            $sheetXml .= '<c r="' . $colLetter . '1" t="inlineStr" s="1"><is><t>' . htmlspecialchars($headerText) . '</t></is></c>';
        }
        $sheetXml .= '</row>';

        // Data rows
        foreach ($rows as $row) {
            $rowNum++;
            $sheetXml .= '<row r="' . $rowNum . '" ht="22" customHeight="1">';
            foreach ($row as $colIdx => $cellVal) {
                $colLetter = $this->getColLetter($colIdx);
                $sheetXml .= '<c r="' . $colLetter . $rowNum . '" t="inlineStr" s="2"><is><t>' . htmlspecialchars((string)$cellVal) . '</t></is></c>';
            }
            $sheetXml .= '</row>';
        }

        $sheetXml .= '</sheetData>
</worksheet>';
        $zip->addFromString('xl/worksheets/sheet1.xml', $sheetXml);

        $zip->close();
        return true;
    }

    /**
     * Directly send XLSX download headers and file content to browser
     */
    public function downloadXlsx($downloadFilename, $headers, $rows, $sheetName = 'Template')
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'xlsx_');
        $this->createXlsx($tempFile, $headers, $rows, $sheetName);

        if (file_exists($tempFile)) {
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="' . $downloadFilename . '"');
            header('Content-Length: ' . filesize($tempFile));
            header('Cache-Control: max-age=0');
            readfile($tempFile);
            @unlink($tempFile);
            exit;
        }
    }
}
