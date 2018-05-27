<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Laravel\Scout\Searchable;
use Spatie\ArrayToXml\ArrayToXml;

class Ebook extends Model
{
    use Searchable;

    protected $fillable = ['type', 'indexid', 'path', 'title','creator','lang','subject','description','publisher','mtime','size','coverimage'];

    public static function rootCatalog()
    {
        $catalog =  [
            '_attributes' => [
                'xmlns' => 'http://www.w3.org/2005/Atom'
            ],
                'id' => 'root/'.env('APP_NAME'),
                'title' => env('APP_NAME'),
                'updated' => date('c'),
                //'author' => env('APP_NAME'),
                'link' => [
                    [ '_attributes' => ['rel' => 'self', 'href' => "root.xml"]],
                    [ '_attributes' => [ 'rel' => 'self', 'href' => "root.xml"]]
                ],
                //'icon' => $this->feedIcon,
                'entry' => [],

        ];

        $categories = DB::table('ebooks')->select('subject')->distinct()->get();

        foreach ($categories as $category) {
            $category = $category->subject;
            if($category==null || $category=="") $category = 'empty';
            $category = str_replace('&','_',$category);
            $catalog['entry'][] = [
                'id' => 'category/'.env('APP_NAME').'/'.$category,
                'title' => $category,
                'link' => [ '_attributes'=>['rel' => 'subsection', 'href' => $category.".xml", 'type'=>'application/atom+xml;profile=opds-catalog;kind=acquisition']],
                    //$this->formatLink('subsection',($this->feedName === 'root' ? $category->link : "{$category->name}.xml"), 'acquisition'),
                'updated' => date('c'),
                'content' => [
                    '_attributes' => ['type' => 'text'],
                    '_value' => $category
                ]
            ];
        }

        /*$document = new \DOMDocument('1.0', 'UTF-8');
        $document->formatOutput = true;
        //$xml = new \SimpleXMLElement('<xml/>');
        array_walk_recursive($catalog, array ($document, 'createElement'));*/


        $xmla = new ArrayToXml($catalog,
            [
                'rootElementName'=>'feed',
                '_attributes'=>[
                    "xmlns" => "http://www.w3.org/2005/Atom",
                    "xmlns:dc"=>"http://purl.org/dc/terms/",
                    "xmlns:opds"=>"http://opds-spec.org/2010/catalog"
                ]
            ]
        );
        //$document = $xmla->toDom();
        //$document->formatOutput = true;
        return $xmla->toXml();
    }

}
