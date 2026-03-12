# Experl Logistics TMS

> cPanel Git Version Control ile kurulum

## cPanel'de Kurulum Adımları

### 1. Git Version Control
- cPanel → Git Version Control → Create
- Clone URL: `https://github.com/KULLANICIADINIZ/experl-logistics.git`
- Repository Path: `/home/ergin/public_html`
- Repository Name: `experl-logistics`

### 2. .env Dosyası Oluştur
cPanel Dosya Yöneticisi'nde `.env.example`'ı kopyalayıp `.env` yapın ve doldurun.

### 3. Deploy
Git Version Control → Manage → Deploy HEAD Commit

## Gereksinimler
- PHP 8.2+
- MySQL 8.0+
- Composer (cPanel'de otomatik çalışır)
