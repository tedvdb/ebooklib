<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Laravel\Scout\Searchable;
use Spatie\ArrayToXml\ArrayToXml;

class Ebook extends Model
{
    const CAT_UNKNOWN = 'unknown';
    use Searchable;

    protected $fillable = ['type', 'indexid', 'path', 'title','creator','lang','subject','description','publisher','mtime','size','cover','coverthumb'];

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
                    [ '_attributes' => ['rel' => 'self', 'href' => route('opdsroot')]],
                    [ '_attributes' => ['rel' => 'start', 'href' => route('opdsroot')]]
                ],
                //'icon' => $this->feedIcon,
                'entry' => [],

        ];

        $categories = DB::table('ebooks')->select('subject')->distinct()->get();

        $unknownaddes = false;
        foreach ($categories as $category) {
            $category = $category->subject;
            if($category==null || $category=="") {
                $category = self::CAT_UNKNOWN;
                if($unknownaddes) continue;
                $unknownaddes = true;
            }

            $catalog['entry'][] = [
                'id' => 'category/'.env('APP_NAME').'/'.str_slug($category),
                'title' => $category,
                'link' => [
                    '_attributes'=> [
                        'rel' => 'subsection',
                        'href' =>  route('category', ['category' => str_slug($category)]),
                        'type'=>'application/atom+xml;profile=opds-catalog;kind=acquisition'
                    ]
                ],
                'updated' => date('c'),
                'content' => [
                    '_attributes' => ['type' => 'text'],
                    '_value' => $category
                ]
            ];
        }

        $xmla = new ArrayToXml($catalog, getRootElement() );
        return $xmla->toXml();
    }

    public static function catalog($categoryslug) {
        $category = self::getCategoryFromSlug($categoryslug);
        if($category=='') {
            $books = Ebook::where('subject','')->orWhere('subject',null)->get();
        } else {
            $books = Ebook::where('subject',$category)->get();
        }

        $catalog = [
            'id' => 'category/'.env('APP_NAME').'/'.str_slug($category).'/',
            'link' => [
                        [ '_attributes' => [
                            'rel' => 'self',
                            'href' => route('category', ['category' => str_slug($category)]),
                            'type' => 'application/atom+xml;profile=opds-catalog;kind=acquisition']
                        ],
                        [ '_attributes' => ['rel' => 'up', 'href' =>  route('opdsroot')]],
                        [ '_attributes' => ['rel' => 'start', 'href' => route('opdsroot')]]
                    ],
                    'title' => $category,
                    'updated' => date('c'),
        ];
        $catalog['entry'] = [];

        foreach($books as $book) {
            $entry = [
                'id' => 'category/'.env('APP_NAME').'/'.str_slug($category).'/'.$book->id,
                'title' => $book->title,
                'updated' => $book->updated_at->toIso8601String(),
                'summary' => $book->description,
                'author' => [ 'name'=>$book->creator ],
            ];
            /*if (! empty($entry->image)) {
                $catalog['feed']['content']['entry'][$i]['content']['link'][] = $this->formatLink('http://opds-spec.org/image', $entry->image, null, $this->getImageType($entry->image));
            }
            if (! empty($entry->thumbnail)) {
                $catalog['feed']['content']['entry'][$i]['content']['link'][] = $this->formatLink('http://opds-spec.org/image/thumbnail', $entry->thumbnail, null, $this->getImageType($entry->thumbnail));
            }*/
            $entry['link'] = [
                '_attributes' => [
                    'rel' => 'http://opds-spec.org/acquisition',
                    'href' => route('download', ['id' => $book->id]),
                    'type' => $book->getMime()
                ]
            ];

            /*if (! empty($entry->issued)) {
                $catalog['feed']['content']['entry'][$i]['content']['dc:issued'] = $entry->issued;
            }*/
            if ($book->lang) {
                $entry['dc:language'] = $book->lang;
            }

            $catalog['entry'][] = $entry;
        }

        $xmla = new ArrayToXml($catalog, self::getRootElement());
        return $xmla->toXml();


    }

    private static function getCategoryFromSlug($slug) {
        if($slug==self::CAT_UNKNOWN) return '';
        $categories = DB::table('ebooks')->select('subject')->distinct()->get();
        foreach($categories as $category) {
            if($slug == str_slug($category->subject)) {
                return $category->subject;
            }
        }
        die("Unkown slug.");
    }

    public function getMime() {
        //pdf epub mobi,
        $types = [
            'epub' => 'application/epub+zip',
            'mobi' => 'application/x-mobipocket-ebook',
            'pdf' => 'application/pdf'
        ];
        return $types[$this->type];
    }

    private static function getRootElement() {
        return [
            'rootElementName'=>'feed',
            '_attributes'=>[
                "xmlns" => "http://www.w3.org/2005/Atom",
                "xmlns:dc"=>"http://purl.org/dc/terms/",
                "xmlns:opds"=>"http://opds-spec.org/2010/catalog"
            ]
        ];
    }

}
