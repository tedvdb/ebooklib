<?php

namespace App;

use Choccybiccy\Mobi\Header\ExthHeader;
use Choccybiccy\Mobi\Reader;
use Illuminate\Support\Facades\Storage;

class MetaCollector
{

    public static function appendEpubMeta(&$fileinfo)
    {
        $zipper = new \Chumper\Zipper\Zipper;
        try {
            $zipper->make($fileinfo->path);
            $containerxml = $zipper->getFileContent('META-INF/container.xml');
            $xml = simplexml_load_string($containerxml);
            $rootfile = (string)$xml->rootfiles[0]->rootfile[0]["full-path"];
            $rootfilexml = $zipper->getFileContent($rootfile);
            $xml = simplexml_load_string($rootfilexml);
            $coverid = false;
            if($xml->metadata && $xml->metadata->meta) {
                foreach ($xml->metadata->meta as $meta) {
                    if ($meta->attributes()->name == "cover") {
                        $coverid = (string)$meta->attributes()->content;
                        break;
                    }
                }
            }
            if($coverid){
                $coverinfo = $xml->manifest->xpath('*[@id="'.$coverid.'"]');
                if(count($coverinfo)) {
                    $coverinfo = $coverinfo[0];
                    $fileinfo->coverimagecontent = $zipper->getFileContent(/*$basepath . '/' .*/ $coverinfo->attributes()->href);
                }
            }
            if($xml->metadata) {
                $meta = $xml->metadata->children('dc', TRUE);
                $fileinfo->title = (string)$meta->title;
                $fileinfo->creator = (string)$meta->creator;
                $fileinfo->lang = (string)$meta->language;
                $fileinfo->subject = (string)$meta->subject;
                $fileinfo->description = (string)$meta->description;
                $fileinfo->publisher = (string)$meta->publisher;
            }
        } catch (\Exception $e) {
            //TODO nette foutafhandeling
            //dd($e);
            //die("Error parsing epub:".$e->getMessage());
            return false;
        }
        return $fileinfo;

    }
    public static function appendMobiMeta(&$fileinfo)
    {
        try {
            $mobi = new Reader($fileinfo->path);

            $fileinfo->title = $mobi->getTitle();
            $fileinfo->creator = $mobi->getAuthor();
            $exth = $mobi->getExthHeader();
            try { $fileinfo->lang = $exth->getRecordByType(ExthHeader::TYPE_LANGUAGE); } catch (\Exception $e) {};
            try { $fileinfo->subject = $exth->getRecordByType(ExthHeader::TYPE_SUBJECT); } catch (\Exception $e) {};
            try { $fileinfo->description = $exth->getRecordByType(ExthHeader::TYPE_DESCRIPTION); } catch (\Exception $e) {};
            try { $fileinfo->publisher = $exth->getRecordByType(ExthHeader::TYPE_PUBLISHER); } catch (\Exception $e) {};
        } catch (\Exception $e) {
            //TODO nette foutafhandeling
            return false;
        }
        return $fileinfo;

    }
}
