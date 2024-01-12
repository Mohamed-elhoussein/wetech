### Info & Description

```
Laravel Version : 8.54

```

### Requirements

```
PHP VERSION  ^7.3|^8.0

```

### Notes

```
please make sure that the document root of the domaine points to /public directory for security reason
/etc/apache2/sites-available
do not forget to set this settings in both, https and http
```

### 1. Clone the Package & install the packages and set the project folder name

```
<<<<<<< HEAD
git clone https://github.com/TakiDDine/drTech.git  project
=======
git init .
git remote add origin <repository-url>
git pull origin < branch >
>>>>>>> f69c3df03f3519d59a761613dc37e61cbdb8a0e8
```

```
composer install
npm install
```

### 2. Setup env file & testing env file

Run this commands from the Terminal:

```
cp .env.example .env
```

### 3. Generate app key

```
php artisan key:generate
```

### 4. Next make sure to create a new database and add your database credentials to your .env file:

```
DB_HOST=localhost
DB_DATABASE=homestead
DB_USERNAME=homestead
DB_PASSWORD=secret
```

### 5. Setup the database & add admin & some dummy data

Run this commands from the Terminal:

```
php artisan migrate --seed
```

### 6. Change permissions

IMPORTANT: Make sure you are inside the project folder

```
sudo chmod -R 775 storage
<<<<<<< HEAD
sudo chmod -R 777 storage/app/./
sudo chmod -R 775 public
sudo chmod -R 777 public/image/./
sudo chmod -R 777 public/images/./
sudo chmod -R 775 bootstrap/cache

=======
sudo chmod -R 775 public
sudo chmod -R 775 bootstrap/cache

sudo chown -R $USER:www-data storage
sudo chown -R $USER:www-data public

>>>>>>> f69c3df03f3519d59a761613dc37e61cbdb8a0e8
```

### 7. Publish telescope

```
php artisan telescope:publish
```

### 8. Link storage to public

```
php artisan storage:link
```

### 9. Run npm

```
npm run prod
```
# wetech
