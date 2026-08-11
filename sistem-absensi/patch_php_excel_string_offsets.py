from pathlib import Path
import re
root = Path('vendor/phpoffice/phpexcel/Classes')
pattern = re.compile(r'(\$[A-Za-z_][A-Za-z0-9_]*)\{(\$?[A-Za-z0-9_]+)\}')
files = list(root.rglob('*.php'))
updated = []
for path in files:
    text = path.read_text(encoding='utf-8')
    new = pattern.sub(r'\1[\2]', text)
    if new != text:
        path.write_text(new, encoding='utf-8')
        updated.append(str(path))
print(f'Updated {len(updated)} files')
for path in updated:
    print(path)
