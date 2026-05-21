import os
import glob
import re

php_files = glob.glob('c:/xampp/htdocs/emad-stor/**/*.php', recursive=True)

count_info = 0
count_img = 0

for file in php_files:
    try:
        with open(file, 'r', encoding='utf-8') as f:
            content = f.read()
        
        new_content = content
        
        # Replace <a href="product.php?id=... class="info-btn" -> <button class="info-btn"
        # Since it's easier, we can just use regex.
        pattern_info = r'<a href="product\.php\?id=[^"]*"\s*class="info-btn"([^>]*)>ⓘ</a>'
        if re.search(pattern_info, new_content):
            new_content = re.sub(pattern_info, r'<button type="button" class="info-btn"\1>ⓘ</button>', new_content)
            count_info += 1
            
        # Also remove the link from the image
        pattern_img = r'<a href="product\.php\?id=[^"]*">\s*(<img[^>]*>)\s*</a>'
        if re.search(pattern_img, new_content):
            new_content = re.sub(pattern_img, r'\1', new_content)
            count_img += 1
            
        # In case the link is something else
        pattern_any = r'<a href="product\.php\?id=[^"]*"'
        if re.search(pattern_any, new_content):
             new_content = re.sub(pattern_any, r'<a href="#" onclick="return false;"', new_content)

        if content != new_content:
            with open(file, 'w', encoding='utf-8') as f:
                f.write(new_content)
            print(f'Updated {file}')
            
    except Exception as e:
        pass

print(f'Updated {count_info} info buttons and {count_img} image links.')
