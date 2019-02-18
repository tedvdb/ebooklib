<?php

namespace App;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class SearchPath extends Model
{
    public $timestamps = false;

    private $command;

    public function reindex(Command $command = null)
    {
        $this->command = $command;

        $files = File::allFiles($this->path);
        $starttime = Carbon::now();
        $count = 0;

        foreach ($files as $file) {
            //echo "Indexing ".$file->getPathname()."\n";
            $fileinfo = new \stdClass();
            $fileinfo->indexid = $this->id;
            $fileinfo->type = $file->getExtension();
            $fileinfo->path = $file->getPathname();
            $fileinfo->mtime = $file->getMtime();
            $fileinfo->size = $file->getSize();

            //check if file exists in index, if same mtime don't update
            if ($existing = Ebook::where('mtime', $fileinfo->mtime)->where('path', $fileinfo->path)->first()) {
                $existing->touch();
                continue;
            }

            switch ($fileinfo->type) {
                case 'epub':
                    MetaCollector::appendEpubMeta($fileinfo);
                    break;
                case 'mobi':
                    MetaCollector::appendMobiMeta($fileinfo);
                    break;
                default: // ignore other extensions
                    $fileinfo = false;
            }

            if ($fileinfo) {
                if (!property_exists($fileinfo, "creator")) {
                    $fileinfo->creator = "?";
                    $tmp = $file->getBasename('.' . $file->getExtension());
                    $tmp = explode(' - ', $tmp, 2);
                    if (count($tmp) == 2) {
                        $fileinfo->creator = $tmp[0];
                    }
                }
                if (!property_exists($fileinfo, "title")) {
                    $fileinfo->title = "?";
                    $tmp = $file->getBasename('.' . $file->getExtension());
                    $tmp = explode(' - ', $tmp, 2);
                    if (count($tmp) == 2) {
                        $fileinfo->title = $tmp[1];
                    }
                }

                $book = Ebook::updateOrCreate(
                    ["path" => $fileinfo->path],
                    (array)$fileinfo
                );
                $book->touch();

                if (property_exists($fileinfo, 'coverimagecontent')) {
                    $book->saveCoverImage($fileinfo->coverimagecontent);
                }
            }
            $count++;

            if ($this->command && $count % 50 == 0 && $command && time() != $starttime->timestamp) {
                $speed = number_format($count / (time() - $starttime->timestamp), 1);
                $this->printLn("Found {$count} ebooks ({$speed} books/sec)");
            }
        }

        $totaltime = time() - $starttime->timestamp;
        $speed = number_format($count / $totaltime, 1);
        $this->printLn("Found a total of {$count} ebooks in {$totaltime} seconds, wich results in a speed of {$speed} books/sec");
        Ebook::where("indexid", $this->id)->where('updated_at', '<', $starttime)->delete();
    }

    function printLn($line)
    {
        if ($this->command) $this->command->info($line);
    }
}
