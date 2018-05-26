<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;


class Ebook extends Model
{
    use Searchable;

    protected $fillable = ['type', 'indexid', 'path', 'title','creator','lang','subject','description','publisher','mtime','size','coverimage'];

}
