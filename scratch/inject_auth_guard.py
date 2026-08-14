import os
import glob

public_dir = '/var/www/hospital-management/frontend/public'
html_files = glob.glob(os.path.join(public_dir, '*.html'))

auth_script = '\n    <script src="assets/js/auth-guard.js"></script>\n'

count = 0
for filepath in html_files:
    with open(filepath, 'r') as f:
        content = f.read()
    
    if 'auth-guard.js' not in content and '</head>' in content:
        content = content.replace('</head>', auth_script + '</head>')
        with open(filepath, 'w') as f:
            f.write(content)
        count += 1

print(f"Injected auth-guard.js into {count} files.")
