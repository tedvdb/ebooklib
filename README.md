- copy .env.example to .env
- correct DB settings
```
php artisan key:generate
php artisan migrate
php artisan db:seed
```
- Add path to ```search_paths``` table
```
php artisan ebooks:reindex
```