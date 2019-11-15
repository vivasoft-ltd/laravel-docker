<?php
namespace Vivasoft\LaravelDocker\Console\Commands;


use Illuminate\Console\Command;

class DockerInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vivasoft:dockerInstall';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Vivasoft Docker';

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
        $this->info('Installing Vivasoft Docker for current laravel project.');

        if ($this->isDockerComposeExists()) {

            $this->error('A docker-compose.yml file already exists in your root directory');

            if ( ! $this->confirm('Do you want to overwrite all the configuration?') )
                return;

            $this->info('Publishing Configuration');

            $this->call('vendor:publish', ['--provider' => 'Vivasoft\LaravelDocker\ServiceProvider']);
        }
    }

    private function isDockerComposeExists()
    {
        return file_exists( base_path('docker-compose.yml') );
    }
}