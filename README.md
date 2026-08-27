# EventSphere - Desktop Version

Quick setup guide.

## Setup
1. composer install
2. copy .env.example .env
3. php artisan key:generate
4. php artisan migrate --seed
5. npm run dev
6. php artisan serve

## OTP Fix
Emails now send via queue (async) - fixes Gmail SMTP issues.

## Free Deployment
- PythonAnywhere: yourusername.pythonanywhere.com
- Render.com: Connect GitHub