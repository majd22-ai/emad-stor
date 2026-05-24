FROM php:8.2-apache

# تحديث النظام وتثبيت الإضافات اللازمة للاتصال بقاعدة البيانات
RUN apt-get update && apt-get install -y libpq-dev && docker-php-ext-install pdo pdo_pgsql

# تفعيل ميزة توجيه الروابط (mod_rewrite) في Apache
RUN a2enmod rewrite

# نسخ ملفات المشروع إلى المجلد الافتراضي لـ Apache
COPY . /var/www/html/

# تعديل صلاحيات الملفات لكي يتمكن Apache من قراءتها (مثل ملفات CSS والصور)
RUN chown -R www-data:www-data /var/www/html/ \
    && chmod -R 755 /var/www/html/
