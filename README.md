
# UAS II3160 - Mengembangkan Layanan Microservice (Tugas 2)
<img width="400" height="400" alt="image" src="https://github.com/user-attachments/assets/037e90f0-c3fe-4f33-9c9f-2d184dfb051b" />

## Healthy Grocery Recommendation API
Microservice ini dikembangkan dalam rangka memenuhi UAS II3160 Teknologi Sistem Terintegrasi, lebih tepatnya Tugas 2 - Mengembangkan Layanan Microservices 

- Nama Pembuat API: Favian Rafi Laftiyanto / 18223036
- Nama Rekan Sekelompok: Ahmad Evander Ruizhi Xavier / 18223064

### A. Deskripsi
Healthy Grocery Recommendation API adalah sebuah microservice berbasis REST API yang menyediakan rekomendasi makanan sehat berdasarkan constraint nutrisi (misalnya berdasarkan kebutuhan kalori, protein, tipe diet, dll.)

Layanan ini dikembangkan menggunakan **Codeigniter 4** dan menerapkan **token-based authentication**. API di-deploy menggunakan **Docker** pada sebuah **Set Top Box (STB)** 1GB RAM, sedangkan untuk database menggunakan **PostgreSQL NeonDB** sehingga beban komputasi STB tetap ringan, stabil, dan mencegah terjadinya hang pada STB.

### B. Fitur Utama
- Autentikasi menggunakan Bearer Token (Authorization Header)
- Memanggil database berisi data makanan beserta nutrisinya
- Algoritma rekomendasi makanan sehat berdasarkan kadar nutrisi makanan seperti:
  - Maksimum kalori per sajian (kcal)
  - Karbohidrat (g)
  - Protein (g)
  - Lemak (g)
  - Sodium (mg)
  - Gula (g)
  - Fiber/Serat (g)
- Dapat diintegrasikan dengan layanan lain

### C. Technology Stack yang Digunakan
- Backend Framework: CodeIgniter 4
- Language: PHP 8.2
- Database: PostgreSQL (memanfaatkan NeonDB)
- Containerization: Docker
- Authentication: Bearer Token (Authorization Header)
- Deployment Target: Set Top Box (Armbian Linux)

### D. Arsitektur Sistem
```
Client (Web / Postman)
        |
        v
Healthy Grocery API (Docker - STB)
        |
        v
PostgreSQL NeonDB (Cloud)
```

### E. Cara Kerja Authentication & Authorization
Beberapa endpoint API ini dilindungi dengan **Bearer Token Authentication**
Berikut adalah format Header yang diperlukan untuk mendapatkan izin untuk memanfaatkan API ini:
```
Authorization: Bearer HEALTHY-FOOD-2025
```

Dengan header tersebut, API akan mengembalikan string JSON berisi makanan sehat. 
Berikut contoh memasang Headernya dengan Postman:
<img width="1447" height="431" alt="image" src="https://github.com/user-attachments/assets/d581330c-0cad-48b5-8953-72e5f4d74cf9" />

Apabila tidak menggunakan header, maka server akan mengembalikan:
```
{
  "status": "unauthorized",
  "message": "Invalid or missing token"
}
```

### F. URL API/Microservice dan API Endpoints
URL bersifat **statis** karena memanfaatkan tunneling **Cloudflared** ke domain cirro.my.id. Berikut adalah URL untuk Microservice ini beserta fungsinya:
- GET https://api.cirro.my.id/ping -> untuk mencoba apakah API aktif dan berjalan dengan baik  (API check)
- GET https://api.cirro.my.id/api/recommendation/food -> untuk mendapatkan data semua makanan yang ada pada database (GET all food data)
- POST https://api.cirro.my.id/api/recommendation/food -> untuk mendapatkan 15 makanan sehat yang paling cocok untuk direkomendasikan sesuai dengan input/constraint yang diberikan (GET food recommendation)

### G. Cara Menggunakan API
- Untuk endpoint POST /api/recommendation/food, API ini dapat memproses berbagai macam constraint yang berkaitan dengan nutrisi dan makanan. Berikut adalah bentuk set constraint dan daftar contraint apa saja yang dapat dimasukkan ke dalam API ini (beserta contoh valuenya):
```
{
    "meta": {
        "age": 45,
        "gender": "female",
        "bmi": 29.1,
        "bmi_category": "overweight",
        "bmr": 1484,
        "daily_calorie_needs": 2300
    },
    "constraints": {
        "max_calories_per_serving": 300,
        "macros": {
            "carbohydrates": {
                "max_g": 37
            },
            "protein": {
                "min_g": 18
            },
            "fat": {
                "max_g": 8
            }
        },
        "micros": {
            "sodium_mg_max": 300,
            "sugars_g_max": 5,
            "dietary_fiber_g_min": 6
        }
    },
    "diet_flags": {
        "low_sugar": true,
        "low_sodium": true,
        "heart_friendly": false,
        "high_fiber": true
    }
}
```

Dan berikut adalah contoh Output yang dihasilkan oleh API (untuk POST ke endpoint /api/recommendation/food):
```
{
    "status": "success",
    "count": 15,
    "data": [
        {
            "vitamin_c_mg": "0",
            "vitamin_b11_mg": "0.005",
            "sodium_mg": "0.9",
            "calcium_mg": "137.9",
            "carbohydrates_g": "6.1",
            "food": "cottage cheese low fat",
            "iron_mg": "0.3",
            "calories_kcal": "163",
            "sugars_g": "6.1",
            "fibers_g": "0",
            "fat_g": "2.3",
            "protein_g": "28",
            "food_normalized": "cottage cheese low fat",
            "serving_cal": "163",
            "serving_prot": "28",
            "health_score": "11.093596996848435"
        },
        
... (diskip, hanya contoh saja, aslinya API akan menghasilkan 15 makanan paling cocok dengan constraint yang diberikan)
        {
            "vitamin_c_mg": "15.3",
            "vitamin_b11_mg": "0.1",
            "sodium_mg": "0.7",
            "calcium_mg": "50.9",
            "carbohydrates_g": "43.1",
            "food": "spaghetti with meat sauce",
            "iron_mg": "3.5",
            "calories_kcal": "255",
            "sugars_g": "7.4",
            "fibers_g": "5.1",
            "fat_g": "2.9",
            "protein_g": "14.3",
            "food_normalized": "spaghetti with meat sauce",
            "serving_cal": "255",
            "serving_prot": "14.3",
            "health_score": "3.5476968162479334"
        }
    ]
}
```

- Untuk endpoint GET /api/recommendation/food, API akan menampilkan keseluruhan database (3454 makanan) dalam bentuk String JSON, seperti berikut (hanya cuplikan saja):
```
{"status":"success","total":3454,"data":[{"vitamin_c_mg":"0.082","vitamin_b11_mg":"0.086","sodium_mg":"0.018","calcium_mg":"2.8","carbohydrates_g":"0.073","food":"margarine with yoghurt","iron_mg":"0.027","calories_kcal":"88","sugars_g":"0","fibers_g":"0","fat_g":"9.8","protein_g":"0.058","food_normalized":"margarine with yoghurt"},{"vitamin_c_mg":"0.4","vitamin_b11_mg":"0.005","sodium_mg":"0.065","calcium_mg":"10.2","carbohydrates_g":"3.7","food":"sunflower seed butter","iron_mg":"0.7","calories_kcal":"99","sugars_g":"1.7","fibers_g":"0.9","fat_g":"8.8","protein_g":"2.8","food_normalized":"sunflower seed butter"},
...
``` 
- Untuk endpoint GET /ping, API hanya akan menampilkan String JSON berikut:
```
{"status":"ok"}
```

### H. Cara Deployment pada STB dengan Docker
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

### I. Struktur repositori
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
