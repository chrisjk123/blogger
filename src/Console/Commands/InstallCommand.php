<?php

namespace Chriscreates\Blog\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\DetectsApplicationNamespace;

class InstallCommand extends Command
{
    use DetectsApplicationNamespace;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blog:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install all of the necessary blog resources';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->callSilent('vendor:publish', ['--tag' => 'blog-factories']);
        $this->callSilent('vendor:publish', ['--tag' => 'blog-seeders']);
        $this->callSilent('vendor:publish', ['--tag' => 'blog-config']);
        $this->callSilent('migrate');

        // TODO: Do we need to publish a provider?

        $this->info('Installation complete.');
    }
}
