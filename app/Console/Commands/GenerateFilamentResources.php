<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class GenerateFilamentResources extends Command
{
    protected $signature = 'generate:filament-resources {--generate : Generate Filament resources for all models}';

    protected $description = 'Generate Filament resources for all models';

    public function handle()
    {
        if ($this->option('generate')) {
            $modelsPath = app_path('Models');

            $models = File::files($modelsPath);

            foreach ($models as $model) {
                $modelName = pathinfo($model, PATHINFO_FILENAME);

                // Check if the resource file already exists
                $resourceFilePath = app_path("Filament/Resources/{$modelName}Resource.php");
                if (File::exists($resourceFilePath)) {
                    $this->warn("Filament resource already exists for model {$modelName}. Skipping...");
                    continue;
                }

                // Generate Filament resource for each model
                Artisan::call('make:filament-resource ' . $modelName . ' --generate');
            }

            $this->info('Filament resources generated for all models.');
        } else {
            $this->info('Use the --generate option to generate Filament resources for all models.');
        }
    }
}
