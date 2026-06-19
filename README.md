# 🍜 StreetFoodies

**Platform penemuan kuliner kaki lima terbaik di Indonesia.**

Dibangun untuk UAS Web Programming — memetakan hidden gem street food, mendukung digitalisasi UMKM kuliner.

---

## Tech Stack

| Layer    | Teknologi                        |
|----------|----------------------------------|
| Backend  | Laravel 12 + PHP 8.2             |
| Database | MySQL 8                          |
| Frontend | Tailwind CSS (CDN) + Alpine.js   |
| Maps     | Leaflet.js + OpenStreetMap       |
| Image    | PHP GD (8 filter)                |
| Charts   | Chart.js                         |
| Email    | Laravel Mail + Gmail SMTP        |
| API News | GNews API                        |

## Fitur

- 🔐 Multi-role auth (Admin, Vendor, Buyer)
- 🗺️ Peta interaktif lapak kuliner
- 📸 Kamera + 8 filter gambar (PHP GD)
- ⭐ Review & rating bintang
- 🔍 Smart search + filter (kategori, rating, radius, harga)
- 📰 Artikel kuliner (GNews API + demo mode)
- 📊 Admin dashboard + Chart.js
- ✉️ Email otomatis (welcome, approve/reject)

## Quick Start

```bash
git clone https://github.com/***/anggatugasv2.git
cd anggatugasv2
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

## Demo Accounts

| Role   | Email                     | Password   |
|--------|---------------------------|------------|
| Admin  | admin@streetfoodies.com   | admin123   |
| Vendor | vendor@streetfoodies.com  | vendor123  |
| Buyer  | buyer@streetfoodies.com   | buyer123   |

## License

MIT — built for educational purposes.
