<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class StartLinkPreview extends Command
{

    protected $signature = 'serve:startLinkPreview';


    protected $description = 'Start both Laravel and Node.js servers';

    /**
     * @return void
     */
    public function handle()
    {
        $this->info('Starting Node.js server...');
        $nodeProcess = new Process(['node', './link-preview-server/index.js']);
        $nodeProcess->setTty(true);
        $nodeProcess->setTimeout(null);
        $nodeProcess->start();
        $nodeProcess->wait();
    }
}
