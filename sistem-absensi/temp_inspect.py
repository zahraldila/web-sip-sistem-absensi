from pathlib import Path
for path, ranges in [
    (Path('vendor/phpoffice/phpexcel/Classes/PHPExcel/Calculation.php'), [(2760, 2785), (6718, 6728)]),
    (Path('vendor/phpoffice/phpexcel/Classes/PHPExcel/Reader/Excel5.php'), [(2915, 2934)]),
    (Path('vendor/phpoffice/phpexcel/Classes/PHPExcel/Shared/String.php'), [(532, 538)])
]:
    print('===', path)
    lines = path.read_text(encoding='utf-8', errors='replace').splitlines()
    for start, end in ranges:
        for i in range(start-1, min(end, len(lines))):
            print(f'{i+1}: {lines[i]}')
        print('---')
