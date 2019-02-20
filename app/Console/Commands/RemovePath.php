<?php

namespace App\Console\Commands;

use App\SearchPath;
use Illuminate\Console\Command;

class RemovePath extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ebooks:removepath {id_or_path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove an path from the database (by id or path)';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $path = $this->argument('id_or_path');
        $sp = false;
        if (is_numeric($path)) $sp = SearchPath::find($path);
        if(!$sp) $sp = SearchPath::where('path',$path)->first();
        if(!$sp) throw new \Exception("'$path' is not a valid path or id");
        $oldpath = $sp->path;
        $sp->delete();

        $this->info("Removed the path {$oldpath} from the database.");
    }
}
