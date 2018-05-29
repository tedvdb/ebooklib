# Installation
- Get the source and dependencies to specified folder:
```composer create-project tedvdb/ebooklib <install path>```
- Then in the root folder, copy .env.example to .env
- Create a mysql user and database for eBookLib and correct database settings in .env.
- Set the APP_URL variable in the .env file.
- Then, init the database:
```
php artisan key:generate
php artisan migrate
php artisan db:seed
```
The installation is now complete.
# First use
- Add path to ```search_paths``` table (no GUI yet)
- Start first indexing action:
```
php artisan ebooks:reindex
```

Now you can navigate in the browser to the url you've configured, and login with the default username 'admin@example.com' and the password 'secret'.

# OPDS
The library is now available in the url via OPDS1.1 protocol on ```<url>/opds/```.
OPDS support is experimental! Anly basic authentication is implemented.