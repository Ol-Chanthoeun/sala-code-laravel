<?php

namespace App\Services\Reports;

final class SimpleXlsxWriter
{
    public function create(array $headings, iterable $rows): string
    {
        $columns = '<cols>';
        foreach ($headings as $index => $heading) {
            $width = min(34, max(12, mb_strlen((string) $heading) + 5));
            $columns .= '<col min="'.($index + 1).'" max="'.($index + 1).'" width="'.$width.'" customWidth="1"/>';
        }
        $columns .= '</cols>';
        $sheet = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"><sheetViews><sheetView workbookViewId="0"><pane ySplit="1" topLeftCell="A2" activePane="bottomLeft" state="frozen"/></sheetView></sheetViews>'.$columns.'<sheetData>';
        $sheet .= $this->row($headings, 1, 1);
        $index = 2;
        foreach ($rows as $row) $sheet .= $this->row($row, $index++);
        $sheet .= '</sheetData></worksheet>';

        $files = [
            '[Content_Types].xml' => '<?xml version="1.0" encoding="UTF-8"?><Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types"><Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/><Default Extension="xml" ContentType="application/xml"/><Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/><Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/><Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/></Types>',
            '_rels/.rels' => '<?xml version="1.0" encoding="UTF-8"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/></Relationships>',
            'xl/workbook.xml' => '<?xml version="1.0" encoding="UTF-8"?><workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"><sheets><sheet name="Report" sheetId="1" r:id="rId1"/></sheets></workbook>',
            'xl/_rels/workbook.xml.rels' => '<?xml version="1.0" encoding="UTF-8"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/><Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/></Relationships>',
            'xl/styles.xml' => '<?xml version="1.0" encoding="UTF-8"?><styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"><fonts count="2"><font><sz val="11"/><name val="Calibri"/></font><font><b/><color rgb="FFFFFFFF"/><sz val="11"/><name val="Calibri"/></font></fonts><fills count="3"><fill><patternFill patternType="none"/></fill><fill><patternFill patternType="gray125"/></fill><fill><patternFill patternType="solid"><fgColor rgb="FF4F46E5"/></patternFill></fill><borders count="1"><border/></borders><cellStyleXfs count="1"><xf/></cellStyleXfs><cellXfs count="2"><xf fontId="0" fillId="0" borderId="0" xfId="0"/><xf fontId="1" fillId="2" borderId="0" xfId="0" applyFont="1" applyFill="1"/></cellXfs></styleSheet>',
            'xl/worksheets/sheet1.xml' => $sheet,
        ];
        return $this->zip($files);
    }

    private function row(iterable $values, int $number, int $style=0): string
    {
        $xml='<row r="'.$number.'">'; $column=1;
        foreach($values as $value){$ref=$this->column($column++).$number;$safe=htmlspecialchars($this->clean((string)($value??'')),ENT_XML1|ENT_QUOTES,'UTF-8');$xml.='<c r="'.$ref.'" t="inlineStr"'.($style?' s="1"':'').'><is><t xml:space="preserve">'.$safe.'</t></is></c>';}
        return $xml.'</row>';
    }

    private function column(int $n): string { $s=''; while($n){$n--; $s=chr(65+$n%26).$s;$n=intdiv($n,26);} return $s; }
    private function clean(string $v): string { return preg_replace('/[^\P{C}\t\n\r]/u','',$v) ?? ''; }

    private function zip(array $files): string
    {
        $data='';$central='';$offset=0;$count=0;$time=$this->dosTime();
        foreach($files as $name=>$content){$compressed=gzdeflate($content,6);$crc=crc32($content);$nameLength=strlen($name);$local=pack('VvvvvvVVVvv',0x04034b50,20,0x0800,8,$time[0],$time[1],$crc,strlen($compressed),strlen($content),$nameLength,0).$name.$compressed;$data.=$local;$central.=pack('VvvvvvvVVVvvvvvVV',0x02014b50,20,20,0x0800,8,$time[0],$time[1],$crc,strlen($compressed),strlen($content),$nameLength,0,0,0,0,0,$offset).$name;$offset+=strlen($local);$count++;}
        return $data.$central.pack('VvvvvVVv',0x06054b50,0,0,$count,$count,strlen($central),strlen($data),0);
    }

    private function dosTime(): array { $d=getdate(); return [(($d['hours']<<11)|($d['minutes']<<5)|intdiv($d['seconds'],2)),((max(1980,$d['year'])-1980)<<9)|($d['mon']<<5)|$d['mday']]; }
}
