<?php

namespace App\Console\Commands;

use App\SearchPath;
use Illuminate\Console\Command;

class ReindexBooks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ebooks:reindex';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh all indexes';

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
        $indices = SearchPath::all();
        $this->info("Started reindex...");
        $bar = $this->output->createProgressBar(count($indices));
        foreach ($indices as $index) {
            $index->reindex($this);
            $bar->advance();
        }
        $bar->finish();
        $this->info("\nDone!");
    }
}
