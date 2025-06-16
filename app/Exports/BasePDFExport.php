<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Mpdf\Mpdf;

class BasePDFExport
{
    protected $model;
    protected $columns;
    protected $selects = [];
    protected $reportName;
    protected $orientation = "P"; // Default orientation

    public function __construct(string $model, array $selects = [], array $columns = [], string $reportName, string $orientation = "P")
    {
        $this->model = $model;
        $this->selects = $selects;
        $this->columns = $columns;
        $this->reportName = $reportName;
        $this->orientation = $orientation; // Set orientation
    }

    public function generate(): string
    {
        $mpdf = new Mpdf([
            'mode' => 'UTF-8',
            'display_mode' => 'fullpage',
            'orientation' => $this->orientation,
            'fontDir' => base_path('public/fonts/'),
            'fontdata' => [
                'arabicfont' => [
                    'R' => 'Tajawal-Regular.ttf',
                    'B' => 'Tajawal-Bold.ttf',
                    'I' => 'Tajawal-Bold.ttf',
                    'useOTL' => 0xFF,
                    'useKashida' => 75,
                ],
            ],
            'default_font' => 'arabicfont',
            'margin_left' => 0,
            'margin_right' => 0,
            'margin_top' => 1,
            'margin_bottom' => 5,
            'margin_header' => 0,
            'margin_footer' => 0,
            'setAutoTopMargin' => 'pad',
            'setAutoBottomMargin' => 'pad',
//            'tempDir' => '/tmp/mpdf',
            'tempDir' => sys_get_temp_dir(),
        ]);

        $reportName = $this->reportName;
        $headerHtml = view('export.components.header', compact('reportName'))->render();
        $footerHtml = view('export.components.footer')->render();

        $mpdf->SetHTMLHeader($headerHtml);
        $mpdf->SetHTMLFooter($footerHtml);

        $data = $this->getData();
        $html = $this->generateHtml($data);
        $mpdf->WriteHTML($html);

        return $mpdf->Output('', 'S'); // Return PDF as a string
    }

    protected function getData(): Collection
    {
        $query = $this->model::query();

        if (!empty($this->selects)) {
            foreach ($this->selects as $key => $value) {
                $query->where($key, $value);
            }
        }

        return $query->get();
    }

    protected function generateHtml(Collection $data): string
    {
        $user = Auth::user()->name ?? 'NULL';

        // Get columns if not provided
        if (empty($this->columns)) {
            $this->columns = Schema::getColumnListing((new $this->model)->getTable());
        }

        // Retrieve translated column headers
        $headers = $this->getColumnHeaders();

        return view('export.pdf_template', [
            'data' => $data,
            'columns' => $this->columns,
            'headers' => $headers,
            'user' => $user,
        ])->render();
    }

    protected function getColumnHeaders(): array
    {
        $modelName = strtolower(class_basename($this->model));

        return array_map(function ($column) use ($modelName) {
            return __('models.' . $modelName . '.' . $column);
        }, $this->columns);
    }

    public function map($item): array
    {
        $result = [];

        foreach ($this->columns as $column) {
            if (strpos($column, '.') !== false) {
                // Handle nested attributes or relationships
                [$relation, $attribute] = explode('.', $column);

                // Ensure relationship is loaded
                if (!method_exists($item, $relation)) {
                    continue; // Skip if relation method doesn't exist
                }

                // Lazy load the relationship if not loaded
                if (!isset($item->$relation)) {
                    $item->load($relation);
                }

                // Check if it's a relation
                if ($item->$relation instanceof \Illuminate\Database\Eloquent\Relations\Relation) {
                    $relatedItem = $item->$relation()->first();
                    $result[$column] = $relatedItem ? $relatedItem->$attribute : null;
                } else {
                    $result[$column] = null;
                }
            } elseif (method_exists($item, $column)) {
                // Handle method calls on the item
                $value = $item->$column();
                $result[$column] = is_bool($value) ? ($value ? 'Yes' : 'No') : $value;
            } elseif ($item instanceof \Illuminate\Database\Eloquent\Model && $item->hasGetMutator($column)) {
                // Handle model attribute with getter mutator
                $result[$column] = $item->$column;
            } elseif (isset($item->$column)) {
                // Handle direct property access
                $result[$column] = $item->$column;
            } elseif (method_exists($item, Str::camel($column))) {
                // Check for camel-cased method
                $result[$column] = $item->{Str::camel($column)}();
            } else {
                // Default to null if attribute/method not found
                $result[$column] = null;
            }
        }

        return $result;
    }

}
