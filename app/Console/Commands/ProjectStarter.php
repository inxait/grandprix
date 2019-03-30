<?php

namespace App\Console\Commands;

use Artisan;
use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class ProjectStarter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'project:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to install Inxait project modules.';

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
        $modules = ['Frontstarter', 'Users', 'Locations', 'Metrics', 'Points'];

        $choice = $this->choice('Choose modules to install separated by commas', 
                                array_merge(['All'], $modules), null, null, true);
        
        if (in_array('Frontstarter', $choice)) {
            $this->addRepoToComposer('https://alejoinxait@bitbucket.org/alejoinxait/inxait-frontstarter.git');
            $this->execProcess('composer require inxait/frontstarter @dev');
            $this->execProcess('php artisan vendor:publish --provider="Inxait\Frontstarter\FrontServiceProvider"');
        }
    }

    private function addRepoToComposer($url)
    {
        //open file
        $file = file_get_contents(base_path('composer.json'));

        //check if repositories array exists
        $pos = strpos($file, '"repositories": [');

        if ($pos === false) {
           $this->addReposArrayToFile($file, $url);
        } else {
            //check if repo item already exists or add
            $pos = strpos($file, $url);

            if ($pos === false) {
                $file = explode('repositories": [', $file);
                $repoItem = "\n".'        {'."\n"
                       .'            "type": "vcs",'."\n"
                       .'            "url": "'.$url.'"'."\n"
                       .'        },';

                $file[1] = $repoItem.$file[1];
                $txt = implode('repositories": [', $file);
                $this->writeFile($txt);
            }            
        }
    }

    private function addReposArrayToFile($file, $url)
    {
        $reposTxt = '"repositories": ['."\n"
                   .'        {'."\n"
                   .'            "type": "vcs",'."\n"
                   .'            "url": "'.$url.'"'."\n"
                   .'        }'."\n"
                   .'    ],'."\n";
        
        $file = explode('"autoload"', $file);
        $file[0] .= $reposTxt;
        $txt = implode('    "autoload"', $file);
        $this->writeFile($txt);
    }

    private function writeFile($txt)
    {
        $newFile = fopen(base_path('composer.json'), 'w');
        fwrite($newFile, $txt);
        fclose($newFile);
    }

    private function execProcess($php)
    {
        $process = new Process($php);
        $process->start();

        foreach ($process as $type => $data) {
            echo "\n".$data;
        }
    }
}
