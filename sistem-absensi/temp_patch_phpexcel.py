from pathlib import Path
import sys

replacements = {
    Path('vendor/phpoffice/phpexcel/Classes/PHPExcel/Shared/String.php'): [
        (
            'if( $bom_be ) { $val = ord($str[$i])   << 4; $val += ord($str{$i+1}); }',
            'if( $bom_be ) { $val = ord($str[$i])   << 4; $val += ord($str[$i+1]); }'
        )
    ],
    Path('vendor/phpoffice/phpexcel/Classes/PHPExcel/Reader/Excel5.php'): [
        (
            '\t\t\t\tfor ($j = 0; $j < $len; ++$j) {\n\t\t\t\t\t$retstr .= $recordData{$pos + $j} . chr(0);\n\t\t\t\t}',
            '\t\t\t\tfor ($j = 0; $j < $len; ++$j) {\n\t\t\t\t\t$retstr .= $recordData[$pos + $j] . chr(0);\n\t\t\t\t}'
        )
    ],
    Path('vendor/phpoffice/phpexcel/Classes/PHPExcel/Calculation.php'): [
        (
            'if ((isset(self::$_comparisonOperators[$opCharacter])) && (strlen($formula) > $index) && (isset(self::$_comparisonOperators[$formula{$index+1}]))) {',
            'if ((isset(self::$_comparisonOperators[$opCharacter])) && (strlen($formula) > $index) && (isset(self::$_comparisonOperators[$formula[$index+1]]))) {'
        ),
        (
            '$opCharacter .= $formula{++$index};',
            '$opCharacter .= $formula[++$index];'
        )
    ]
}

for path, pairs in replacements.items():
    text = path.read_text(encoding='utf-8')
    for old, new in pairs:
        if old not in text:
            print(f'Missing pattern in {path}: {old}')
            sys.exit(1)
        text = text.replace(old, new)
    path.write_text(text, encoding='utf-8')
    print(f'Patched {path}')
