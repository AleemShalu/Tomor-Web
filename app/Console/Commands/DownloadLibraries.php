<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class DownloadLibraries extends Command
{
    protected $signature = 'download:libraries';
    protected $description = 'Download external libraries and save them to the public/libs directory';

    public function handle()
    {
        $libraries = [
            'lucide' => [
                'https://unpkg.com/lucide@latest' => 'lucide.js',
            ],
            'sweetalert2' => [
                'https://cdn.jsdelivr.net/npm/sweetalert2@11' => 'sweetalert2.js',
            ],
            'flowbite' => [
                'https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/flowbite.min.css' => 'flowbite.min.css',
                'https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/flowbite.min.js' => 'flowbite.min.js',
            ],
            'intl-tel-input' => [
                'https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/flowbite.min.css' => 'intlTelInput.css',
                'https://cdn.jsdelivr.net/npm/intl-tel-input@17.0.15/build/css/intlTelInput.min.css' => 'intlTelInput.min.css',
                'https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.15/js/intlTelInput.min.js' => 'intlTelInput.min.js',
                'https://cdn.jsdelivr.net/npm/intl-tel-input@17.0.15/build/js/utils.min.js' => 'utils.min.js',
            ],
            'ag-grid' => [
                'https://cdnjs.cloudflare.com/ajax/libs/ag-grid/31.0.0/ag-grid-community.min.js' => 'ag-grid-community.min.js',
                'https://cdnjs.cloudflare.com/ajax/libs/ag-grid/31.0.0/styles/ag-grid.css' => 'ag-grid.css',
                'https://cdnjs.cloudflare.com/ajax/libs/ag-grid/31.0.0/styles/ag-theme-alpine.css' => 'ag-theme-alpine.css',
                'https://cdnjs.cloudflare.com/ajax/libs/ag-grid/31.0.0/ag-grid-community.amd.js' => 'amd/ag-grid-community.amd.js',
                'https://cdnjs.cloudflare.com/ajax/libs/ag-grid/31.0.0/ag-grid-community.amd.min.js' => 'amd/ag-grid-community.amd.min.js',
                'https://cdnjs.cloudflare.com/ajax/libs/ag-grid/31.0.0/ag-grid-community.amd.min.noStyle.js' => 'amd/ag-grid-community.amd.min.noStyle.js',
                'https://cdnjs.cloudflare.com/ajax/libs/ag-grid/31.0.0/ag-grid-community.amd.min.noStyle.min.js' => 'amd/ag-grid-community.amd.min.noStyle.min.js',
                'https://cdnjs.cloudflare.com/ajax/libs/ag-grid/31.0.0/ag-grid-community.amd.noStyle.js' => 'amd/ag-grid-community.amd.noStyle.js',
                'https://cdnjs.cloudflare.com/ajax/libs/ag-grid/31.0.0/ag-grid-community.amd.noStyle.min.js' => 'amd/ag-grid-community.amd.noStyle.min.js',
                'https://cdnjs.cloudflare.com/ajax/libs/ag-grid/31.0.0/ag-grid-community.auto.complete.esm.js' => 'auto/ag-grid-community.auto.complete.esm.js',
                'https://cdnjs.cloudflare.com/ajax/libs/ag-grid/31.0.0/ag-grid-community.auto.complete.esm.min.js' => 'auto/ag-grid-community.auto.complete.esm.min.js',
                'https://cdnjs.cloudflare.com/ajax/libs/ag-grid/31.0.0/ag-grid-community.auto.esm.js' => 'auto/ag-grid-community.auto.esm.js',
                'https://cdnjs.cloudflare.com/ajax/libs/ag-grid/31.0.0/ag-grid-community.auto.esm.min.js' => 'auto/ag-grid-community.auto.esm.min.js',
                'https://cdnjs.cloudflare.com/ajax/libs/ag-grid/31.0.0/ag-grid-community.cjs.js' => 'cjs/ag-grid-community.cjs.js',
                'https://cdnjs.cloudflare.com/ajax/libs/ag-grid/31.0.0/ag-grid-community.cjs.min.js' => 'cjs/ag-grid-community.cjs.min.js',
                'https://cdnjs.cloudflare.com/ajax/libs/ag-grid/31.0.0/ag-grid-community.esm.js' => 'esm/ag-grid-community.esm.js',
                'https://cdnjs.cloudflare.com/ajax/libs/ag-grid/31.0.0/ag-grid-community.esm.min.js' => 'esm/ag-grid-community.esm.min.js',
                'https://cdnjs.cloudflare.com/ajax/libs/ag-grid/31.0.0/ag-grid-community.js' => 'ag-grid-community.js',
                'https://cdnjs.cloudflare.com/ajax/libs/ag-grid/31.0.0/ag-grid-community.min.noStyle.js' => 'ag-grid-community.min.noStyle.js',
                'https://cdnjs.cloudflare.com/ajax/libs/ag-grid/31.0.0/ag-grid-community.min.noStyle.min.js' => 'ag-grid-community.min.noStyle.min.js',
                'https://cdnjs.cloudflare.com/ajax/libs/ag-grid/31.0.0/ag-grid-community.noStyle.js' => 'ag-grid-community.noStyle.js',
                'https://cdnjs.cloudflare.com/ajax/libs/ag-grid/31.0.0/ag-grid-community.noStyle.min.js' => 'ag-grid-community.noStyle.min.js',
            ],
            'jquery' => [
                'https://code.jquery.com/jquery-3.6.0.min.js' => 'jquery.min.js',
            ],
            'font-awesome' => [
                'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js' => 'all.min.js',
            ],
            'flatpickr' => [
                'https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css' => 'flatpickr.min.css',
                'https://cdn.jsdelivr.net/npm/flatpickr' => 'flatpickr.js',
            ],
            'chart.js' => [
                'https://cdn.jsdelivr.net/npm/chart.js' => 'chart.js',
            ],
            'ckeditor' => [
                'https://cdn.ckeditor.com/4.17.0/standard/ckeditor.js' => 'ckeditor.js',
            ],
            'hijri-date' => [
                'https://rawgit.com/abdennour/hijri-date/master/cdn/hijri-date-latest.js' => 'hijri-date-latest.js',
                'https://cdn.jsdelivr.net/gh/abublihi/datepicker-hijri@v1.1/build/datepicker-hijri.js' => 'datepicker-hijri.js',
            ],
            'ag-grid-enterprise' => [
                'https://unpkg.com/ag-grid-enterprise@latest/dist/ag-grid-enterprise.min.js' => 'ag-grid-enterprise.min.js',
            ],
            'moment.js' => [
                'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js' => 'moment.min.js',
            ],
            'iban' => [
                'https://cdn.jsdelivr.net/npm/iban@0.0.14/iban.min.js' => 'iban.min.js',
            ],
            'datatables' => [
                'https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css' => 'jquery.dataTables.min.css',
                'https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js' => 'jquery.dataTables.min.js',
                'https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css' => 'buttons.dataTables.min.css',
                'https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js' => 'dataTables.buttons.min.js',
                'https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js' => 'buttons.html5.min.js',
            ],
        ];

        foreach ($libraries as $packageName => $packageUrls) {
            foreach ($packageUrls as $url => $filename) {
                $path = "public/libs/$packageName/$filename";
                if (!$this->fileExists($path)) {
                    try {
                        $this->downloadLibrary($url, $path);
                    } catch (\Exception $e) {
                        $this->error("Failed to download $url: " . $e->getMessage());
                    }
                } else {
                    $this->info("$path already exists. Skipping download.");
                }
            }
        }

        $this->info('Libraries download process completed.');
    }

    private function downloadLibrary($url, $path)
    {
        $contents = file_get_contents($url);
        if ($contents === false) {
            throw new \Exception("Failed to download $url");
        }

        File::ensureDirectoryExists(dirname(base_path($path)));
        File::put(base_path($path), $contents);
        $this->info("Downloaded $url to $path");
    }

    private function fileExists($path)
    {
        return File::exists(base_path($path));
    }
}
