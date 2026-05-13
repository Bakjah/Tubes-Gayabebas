<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /photobooth/
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
</IfModule>
