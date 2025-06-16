<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BaseExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithCustomStartCell, WithEvents
{
    protected $model;
    protected $columns;
    protected $selects = [];

    // Define color variables
    protected $headerColor = '#d9ead3'; // Light green color for headers
    protected $textColor = '#000000';   // Black color for text

    // Define font variables
    protected $fontPath = 'public/fonts/Tajawal-Light.ttf';
    protected $fontName = 'Tajawal';

    public function __construct(string $model, array $selects = [], array $columns = [])
    {
        $this->model = $model;
        $this->selects = $selects;
        $this->columns = $columns;
    }

    public function collection(): Collection
    {
        $query = $this->model::query();

        // Apply selects if provided
        if (!empty($this->selects)) {
            foreach ($this->selects as $key => $value) {
                $query->where($key, $value);
            }
        }

        return $query->get();
    }

    public function map($item): array
    {
        $result = [];
        foreach ($this->columns as $column) {
            if (strpos($column, '.') !== false) {
                // Handle nested attributes
                list($relation, $attribute) = explode('.', $column);
                if (method_exists($item, $relation)) {
                    $relationItems = $item->$relation;
                    if ($relationItems instanceof \Illuminate\Database\Eloquent\Collection) {
                        $values = $relationItems->pluck($attribute)->implode(', ');
                        $result[$column] = $values;
                    } else {
                        $result[$column] = $relationItems->$attribute ?? null;
                    }
                } else {
                    $result[$column] = null;
                }
            } elseif (method_exists($item, $column)) {
                // Handle method calls
                $value = $item->$column();
                $result[$column] = is_bool($value) ? ($value ? 'Yes' : 'No') : $value;
            } else {
                // Handle properties
                $result[$column] = $item->$column ?? null;
            }
        }

        return $result;
    }

    public function headings(): array
    {
        // Retrieve columns dynamically if not provided
        if (empty($this->columns)) {
            $this->columns = $this->getModelColumns();
        }

        // Ensure there's at least one item to avoid issues
        $modelName = strtolower(class_basename($this->model)); // Use $this->model instead of $firstItem

        return array_map(function ($column) use ($modelName) {
            // Construct translation key based on the model name and column
            return __('models.' . $modelName . '.' . $column);
        }, $this->columns);
    }

    protected function getModelColumns(): array
    {
        // Assuming the model has a method `columnsExport` that returns column names
        if (method_exists($this->model, 'columnsExport')) {
            return (new $this->model)->columnsExport();
        }

        // Fallback to empty array if the method does not exist
        return [];
    }

    public function styles(Worksheet $sheet)
    {
        // Determine the last column letter (e.g., 'Z')
        $lastColumn = $sheet->getHighestColumn(); // e.g., 'Z'

        // Define the ranges dynamically
        $rangeHeader = "A8:" . $lastColumn . "8";  // Header row from A8 to the last column
        $rangeMetadata = "A1:" . $lastColumn . "7"; // Metadata rows from A1 to the last column in rows 1-7

        return [
            $rangeMetadata => [
                'font' => [
                    'color' => ['argb' => $this->textColor],
                    'name' => $this->fontName, // Specify the custom font name
                    'size' => 12, // Adjust size as needed
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => $this->headerColor],
                ],
            ],
            $rangeHeader => [
                'font' => [
                    'bold' => true,
                    'color' => ['argb' => $this->textColor],
                    'name' => $this->fontName, // Specify the custom font name
                    'size' => 12, // Adjust size as needed
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => $this->headerColor],
                ],
                'borders' => [
                    'outline' => [
                        'borderStyle' => Border::BORDER_NONE, // Remove borders
                    ],
                ],
            ],
        ];
    }

    public function startCell(): string
    {
        return 'A8';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Add company logo with rounded corners
                $drawing = new Drawing();
                $drawing->setName('Logo')
                    ->setDescription('Company Logo with Rounded Corners')
                    ->setPath(public_path('images/tomor-logo-05.png'))
                    ->setHeight(90)
                    ->setCoordinates('A1')
                    ->setWorksheet($sheet);

                // Merge cells for the logo
                $sheet->mergeCells('A1:C5');

                // Add and style metadata
                $user = Auth::user()->name ?? 'NULL';
                $sheet->setCellValue('A6', "Created by: " . $user)
                    ->setCellValue('A7', 'Exported at: ' . convertDateToRiyadhTimezone(\Carbon\Carbon::now()));

                $sheet->getStyle('A6:A7')->applyFromArray([
                    'font' => [
                        'italic' => true,
                        'color' => ['argb' => $this->textColor],
                        'name' => $this->fontName, // Specify the custom font name
                        'size' => 12, // Adjust size as needed
                    ],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
                ]);

                // Calculate and set column widths based on header lengths
                $headings = $this->headings();
                foreach (range('A', $sheet->getHighestColumn()) as $columnIndex => $columnID) {
                    if (isset($headings[$columnIndex])) {
                        $columnWidth = strlen($headings[$columnIndex]) + 10; // Add padding for better readability
                        $sheet->getColumnDimension($columnID)->setWidth($columnWidth);
                    }
                }

                // Make the header row sticky
                $sheet->freezePane('A9');
            },
        ];
    }

}
