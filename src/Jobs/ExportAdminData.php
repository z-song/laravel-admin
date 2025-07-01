<?php

namespace Encore\Admin\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Encore\Admin\Events\AdminOperationEvent;
use Encore\Admin\Traits\BroadcastsOperations;

/**
 * Laravel 11 Queue Job for Admin Data Export
 * 
 * This job handles large data exports in the background
 * to prevent timeouts and improve user experience.
 */
class ExportAdminData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, BroadcastsOperations;

    public string $model;
    public array $data;
    public int $userId;
    public string $format;
    public array $columns;
    public array $filters;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public int $tries = 3;

    /**
     * The maximum number of seconds the job can run.
     *
     * @var int
     */
    public int $timeout = 300; // 5 minutes

    /**
     * Create a new job instance.
     *
     * @param string $model
     * @param array $data
     * @param int $userId
     * @param string $format
     * @param array $columns
     * @param array $filters
     */
    public function __construct(
        string $model,
        array $data,
        int $userId,
        string $format = 'csv',
        array $columns = [],
        array $filters = []
    ) {
        $this->model = $model;
        $this->data = $data;
        $this->userId = $userId;
        $this->format = $format;
        $this->columns = $columns;
        $this->filters = $filters;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        try {
            $filename = $this->generateExportFile();
            
            // Broadcast completion event
            $this->broadcastOperation(
                'exported',
                $this->model,
                [
                    'filename' => $filename,
                    'format' => $this->format,
                    'records_count' => count($this->data),
                    'file_size' => Storage::size($filename),
                ],
                $this->userId
            );

        } catch (\Exception $e) {
            // Broadcast error event
            $this->broadcastOperation(
                'export_failed',
                $this->model,
                [
                    'error' => $e->getMessage(),
                    'format' => $this->format,
                ],
                $this->userId
            );

            throw $e;
        }
    }

    /**
     * Generate export file based on format
     *
     * @return string Filename of the generated file
     */
    private function generateExportFile(): string
    {
        $timestamp = now()->format('Y-m-d_H-i-s');
        $modelName = strtolower(class_basename($this->model));
        $filename = "exports/{$modelName}_export_{$timestamp}.{$this->format}";

        switch ($this->format) {
            case 'csv':
                $this->generateCsvFile($filename);
                break;
            case 'xlsx':
                $this->generateExcelFile($filename);
                break;
            case 'json':
                $this->generateJsonFile($filename);
                break;
            default:
                throw new \InvalidArgumentException("Unsupported export format: {$this->format}");
        }

        return $filename;
    }

    /**
     * Generate CSV file
     *
     * @param string $filename
     * @return void
     */
    private function generateCsvFile(string $filename): void
    {
        $csv = '';
        
        // Add headers
        if (!empty($this->columns)) {
            $csv .= implode(',', $this->columns) . "\n";
        } elseif (!empty($this->data)) {
            $csv .= implode(',', array_keys($this->data[0])) . "\n";
        }

        // Add data rows
        foreach ($this->data as $row) {
            $csvRow = [];
            foreach ($row as $value) {
                // Escape CSV values
                $csvRow[] = '"' . str_replace('"', '""', $value) . '"';
            }
            $csv .= implode(',', $csvRow) . "\n";
        }

        Storage::put($filename, $csv);
    }

    /**
     * Generate Excel file (requires PhpSpreadsheet)
     *
     * @param string $filename
     * @return void
     */
    private function generateExcelFile(string $filename): void
    {
        // This would require PhpSpreadsheet package
        // For now, generate CSV and rename to xlsx as fallback
        $csvFilename = str_replace('.xlsx', '.csv', $filename);
        $this->generateCsvFile($csvFilename);
        
        // Move the CSV file to xlsx name (would need proper Excel generation in production)
        Storage::move($csvFilename, $filename);
    }

    /**
     * Generate JSON file
     *
     * @param string $filename
     * @return void
     */
    private function generateJsonFile(string $filename): void
    {
        $jsonData = [
            'model' => $this->model,
            'exported_at' => now()->toISOString(),
            'user_id' => $this->userId,
            'format' => $this->format,
            'columns' => $this->columns,
            'filters' => $this->filters,
            'data' => $this->data,
        ];

        Storage::put($filename, json_encode($jsonData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    /**
     * Handle a job failure.
     *
     * @param \Throwable $exception
     * @return void
     */
    public function failed(\Throwable $exception): void
    {
        // Broadcast failure event
        AdminOperationEvent::dispatch(
            'export_failed',
            $this->model,
            [
                'error' => $exception->getMessage(),
                'format' => $this->format,
                'attempts' => $this->attempts(),
            ],
            $this->userId,
            'System'
        );

        // Log the failure
        \Log::error('Admin data export failed', [
            'model' => $this->model,
            'user_id' => $this->userId,
            'format' => $this->format,
            'error' => $exception->getMessage(),
        ]);
    }

    /**
     * Get the tags that should be assigned to the job.
     *
     * @return array<int, string>
     */
    public function tags(): array
    {
        return [
            'admin',
            'export',
            "model:{$this->model}",
            "user:{$this->userId}",
            "format:{$this->format}",
        ];
    }
}