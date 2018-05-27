<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class SearchPath extends Model
{
    public $timestamps = false;

    public function reindex() {

        $files = File::allFiles($this->path);
        $starttime = Carbon::now();

        foreach ($files as $file)
        {
            //echo "Indexing ".$file->getPathname()."\n";
            $fileinfo = new \stdClass();
            $fileinfo->indexid = $this->id;
            $fileinfo->type = $file->getExtension();
            $fileinfo->path = $file->getPathname();
            $fileinfo->mtime = $file->getMtime();
            $fileinfo->size = $file->getSize();

            //check if file exists in index, if same mtime don't update
            if($existing = Ebook::where('mtime',$fileinfo->mtime)->where('path',$fileinfo->path)->first()) {
                $existing->touch();
                continue;
            }

            switch($fileinfo->type) {
                case 'epub':
                    MetaCollector::appendEpubMeta($fileinfo);
                    break;
                default: // ignore other extensions
                    $fileinfo = false;
            }

            if($fileinfo) {
                if(!property_exists($fileinfo,"creator")) {
                    $fileinfo->creator = "?";
                    $tmp = $file->getFileName();
                    $tmp = explode(' - ',$tmp, 2);
                    if(count($tmp)==2) {
                        $fileinfo->creator = $tmp[0];
                    }
                }
                if(!property_exists($fileinfo,"title")) {
                    $fileinfo->title = "?";
                    $tmp = $file->getFileName();
                    $tmp = explode(' - ',$tmp, 2);
                    if(count($tmp)==2) {
                        $fileinfo->title = $tmp[1];
                    }
                }

                $book = Ebook::updateOrCreate(
                    ["path" => $fileinfo->path],
                    (array)$fileinfo
                );
                $book->touch();

                if(property_exists($fileinfo,'coverimagecontent')) {
                    $book->saveCoverImage($fileinfo->coverimagecontent);
                }
            }
        }

        Ebook::where("indexid",$this->id)->where('updated_at', '<',$starttime)->delete();
    }
}
