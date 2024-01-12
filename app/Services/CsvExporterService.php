<?php


namespace App\Services;


use App\Models\Order;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CsvExporterService
{
    private array $columns = [];

    public function __construct()
    {
    }

    public function export(array $columns, $data, string $fileName): StreamedResponse
    {
        $this->columns = $columns;
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function () use ($data, $columns) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, $this->getHeaderColumns());
            $this->appendRow($data, $file);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * @return array
     */
    private function getHeaderColumns(): array
    {
        $headerColumns = [];
        foreach ($this->columns as $column) {
            if (!is_array($column)) {
                $headerColumns[] = $column;
            } else {
                $headerColumns[] = $column['as'];
            }
        }

        return $headerColumns;
    }

    private function appendRow($data, $file)
    {
        $row = [];
        foreach ($data as $item) {
            foreach ($this->columns as $key => $column) {
                $row[$column['as']] = $item[$key];
            }
            fputcsv($file, array_values($row));
        }
    }
}
