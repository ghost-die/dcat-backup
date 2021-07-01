### Dcat Admin Extension

> 数据库备份

> 基于 https://github.com/spatie/laravel-backup 拓展

```bash
  composer require ghost/dcat-backup
  php artisan vendor:publish --provider="Spatie\Backup\BackupServiceProvider"
```

> 如需要通知请配置邮件 默认通知方式为邮件通知

```dotenv
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=null
MAIL_FROM_NAME="${APP_NAME}"
```



