
# UAS II3160 - Mengembangkan Layanan Microservice (Tugas 2)
## Healthy Grocery Recommendation API
Microservice ini dikembangkan dalam rangka memenuhi UAS II3160 Teknologi Sistem Terintegrasi, lebih tepatnya Tugas 2 - Mengembangkan Layanan Microservices 
Nama Pembuat API: Favian Rafi Laftiyanto / 18223036
Nama Rekan Sekelompok: Ahmad Evander Ruizhi Xavier / 18223064

### A. Deskripsi
Healthy Grocery Recommendation API adalah sebuah microservice berbasis REST API yang menyediakan rekomendasi makanan sehat berdasarkan constraint nutrisi (misalnya berdasarkan kebutuhan kalori, protein, tipe diet, dll.)

Layanan ini dikembangkan menggunakan **Codeigniter 4** dan menerapkan **token-based authentication**. API di-deploy menggunakan **Docker** pada sebuah **Set Top Box (STB)** 1GB RAM, sedangkan untuk database menggunakan **PostgreSQL NeonDB** sehingga beban komputasi STB tetap ringan, stabil, dan mencegah terjadinya hang pada STB.

### B. Technology Stack
- Backend Framework: CodeIgniter 4
- Language: PHP 8.2
- Database: PostgreSQL (memanfaatkan NeonDB)
- Containerization: Docker
- Authentication: Bearer Token (Authorization Header)
- Deployment Target: Set Top Box (Armbian Linux)

### C. Arsitektur Sistem
```
Client (Web / Postman)
        |
        v
Healthy Grocery API (Docker - STB)
        |
        v
PostgreSQL NeonDB (Cloud)
```

### D. Cara Kerja Authentication & Authorization
Beberapa endpoint API ini dilindungi dengan **Bearer Token Authentication**
Berikut adalah format Header yang diperlukan untuk mendapatkan izin untuk memanfaatkan API ini:
```
Authorization: Bearer HEALTHY-FOOD-2025
```

Dengan header tersebut, API akan mengembalikan string JSON berisi makanan sehat. Apabila tidak menggunakan header, maka server akan mengembalikan:
```
{
  "status": "unauthorized",
  "message": "Invalid or missing token"
}
```

### E. URL API/Microservice dan API Endpoints
URL bersifat **statis** karena memanfaatkan tunneling **Cloudflared** ke domain cirro.my.id. Berikut adalah URL untuk Microservice ini beserta fungsinya:
- GET https://api.cirro.my.id/ping -> untuk mencoba apakah API aktif dan berjalan dengan baik  (API check)
- GET https://api.cirro.my.id/api/recommendation/food -> untuk mendapatkan data semua makanan yang ada pada database (GET all food data)
- POST https://api.cirro.my.id/api/recommendation/food -> untuk mendapatkan 5 makanan sehat yang paling cocok untuk direkomendasikan sesuai dengan input/constraint yang diberikan (GET food recommendation)

### F. Cara Deployment pada STB dengan Docker
1. Clone repositori ini pada STB dengan 
```
git clone https://github.com/19623238-Favian/API-Healthy-Grocery.git
```
2. Konfigurasi file .env dengan isi seperti berikut:
```
CI_ENVIRONMENT=production

DATABASE_URL=postgresql://neondb_owner:npg_wbD9TF4cunvX@ep-tiny-hill-a1nh548o-pooler.ap-southeast-1.aws.neon.tech/neondb?sslmode=require&channel_binding=require

database.default.hostname = ep-tiny-hill-a1nh548o-pooler.ap-southeast-1.aws.neon.tech
database.default.database = neondb
database.default.username = neondb_owner
database.default.password = npg_wbD9TF4cunvX
database.default.DBDriver = Postgre
database.default.port = 5432
database.default.sslmode = require

API_TOKEN=HEALTHY-FOOD-2025
```
3. Build & run Container
```
docker compose down -v
docker compose up -d --build
```
4. Tes API secara lokal atau dengan mengunjungi URL
```
curl http://localhost:5432/ping
```
atau mengunjungi url https://api.cirro.my.id/ping (asumsi tunneling sudah dilakukan)

### G. Struktur repositori
```
API-Healthy-Grocery/
├── app/                -> Berisi semua source code yang dibutuhkan API
├── docker/             -> Berisi keperluan untuk docker (init.sql dan Dockerfile)
├── public/             -> Default dari Codeigniter
├── tests/              -> Untuk testing (tidak digunakan)
├── writable/           -> Berisi logs API 
├── vendor/             # Tidak ada di repositori ini karena alasan keamanan
├── .gitignore          -> Berisi informasi data yang tidak dimasukkan ke repo
├── README.md           -> File deskripsi repositori
├── LICENSE
├── builds
├── composer.json
├── composer.lock
├── docker-compose.yml
├── phpunit.xml.dist
├── preload.php
└── .env                # Tidak ada di repositori ini karena alasan keamanan
```