<?php

namespace Encore\Admin\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Encore\Admin\Events\AdminOperationEvent;
use Encore\Admin\Traits\BroadcastsOperations;

/**
 * Laravel 11 Queue Job for Admin Data Import
 * 
 * This job handles large data imports in the background
 * with validation and error reporting.
 */
class ImportAdminData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, BroadcastsOperations;

    public string $model;
    public string $filePath;
    public int $userId;
    public array $validationRules;
    public array $columnMapping;
    public bool $skipHeader;

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
    public int $timeout = 600; // 10 minutes

    /**
     * Create a new job instance.
     *
     * @param string $model
     * @param string $filePath
     * @param int $userId
     * @param array $validationRules
     * @param array $columnMapping
     * @param bool $skipHeader
     */
    public function __construct(
        string $model,
        string $filePath,
        int $userId,
        array $validationRules = [],
        array $columnMapping = [],
        bool $skipHeader = true
    ) {
        $this->model = $model;
        $this->filePath = $filePath;
        $this->userId = $userId;
        $this->validationRules = $validationRules;
        $this->columnMapping = $columnMapping;
        $this->skipHeader = $skipHeader;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        try {
            $result = $this->processImportFile();
            
            // Broadcast completion event
            $this->broadcastOperation(
                'imported',
                $this->model,
                $result,
                $this->userId
            );

        } catch (\Exception $e) {
            // Broadcast error event
            $this->broadcastOperation(
                'import_failed',
                $this->model,
                [
                    'error' => $e->getMessage(),
                    'file_path' => $this->filePath,
                ],
                $this->userId
            );

            throw $e;
        }
    }

    /**
     * Process the import file
     *
     * @return array Import results
     */
    private function processImportFile(): array
    {
        if (!Storage::exists($this->filePath)) {
            throw new \Exception("Import file not found: {$this->filePath}");
        }

        $extension = pathinfo($this->filePath, PATHINFO_EXTENSION);
        
        switch (strtolower($extension)) {
            case 'csv':
                return $this->processCsvFile();
            case 'json':
                return $this->processJsonFile();
            case 'xlsx':
            case 'xls':
                return $this->processExcelFile();
            default:
                throw new \Exception("Unsupported file format: {$extension}");
        }
    }

    /**
     * Process CSV file
     *
     * @return array
     */
    private function processCsvFile(): array
    {
        $content = Storage::get($this->filePath);
        $lines = explode("\n", $content);
        
        $headers = [];
        $data = [];
        $errors = [];
        $successCount = 0;
        $skipCount = 0;

        foreach ($lines as $index => $line) {
            $line = trim($line);
            if (empty($line)) continue;

            $row = str_getcsv($line);

            // Skip header row
            if ($index === 0 && $this->skipHeader) {
                $headers = $row;
                continue;
            }

            // Map columns if mapping is provided
            if (!empty($this->columnMapping) && !empty($headers)) {
                $mappedRow = [];
                foreach ($row as $colIndex => $value) {
                    $headerName = $headers[$colIndex] ?? "column_{$colIndex}";
                    $mappedKey = $this->columnMapping[$headerName] ?? $headerName;
                    $mappedRow[$mappedKey] = $value;
                }
                $row = $mappedRow;
            }

            // Validate row data
            $validation = $this->validateRowData($row, $index + 1);
            if (!$validation['valid']) {
                $errors[] = $validation['errors'];
                $skipCount++;
                continue;
            }

            // Import the row
            try {
                $this->importRowData($row);
                $successCount++;
            } catch (\Exception $e) {
                $errors[] = [
                    'row' => $index + 1,
                    'error' => $e->getMessage(),
                    'data' => $row,
                ];
                $skipCount++;
            }
        }

        return [
            'total_rows' => count($lines) - ($this->skipHeader ? 1 : 0),
            'success_count' => $successCount,
            'skip_count' => $skipCount,
            'error_count' => count($errors),
            'errors' => array_slice($errors, 0, 100), // Limit errors to first 100
            'file_path' => $this->filePath,
        ];
    }

    /**
     * Process JSON file
     *
     * @return array
     */
    private function processJsonFile(): array
    {
        $content = Storage::get($this->filePath);
        $jsonData = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Invalid JSON file: ' . json_last_error_msg());
        }

        $data = $jsonData['data'] ?? $jsonData;
        if (!is_array($data)) {
            throw new \Exception('JSON file must contain an array of data');
        }

        $errors = [];
        $successCount = 0;
        $skipCount = 0;

        foreach ($data as $index => $row) {
            // Validate row data
            $validation = $this->validateRowData($row, $index + 1);
            if (!$validation['valid']) {
                $errors[] = $validation['errors'];
                $skipCount++;
                continue;
            }

            // Import the row
            try {
                $this->importRowData($row);
                $successCount++;
            } catch (\Exception $e) {
                $errors[] = [
                    'row' => $index + 1,
                    'error' => $e->getMessage(),
                    'data' => $row,
                ];
                $skipCount++;
            }
        }

        return [
            'total_rows' => count($data),
            'success_count' => $successCount,
            'skip_count' => $skipCount,
            'error_count' => count($errors),
            'errors' => array_slice($errors, 0, 100),
            'file_path' => $this->filePath,
        ];
    }

    /**
     * Process Excel file (placeholder - would require PhpSpreadsheet)
     *
     * @return array
     */
    private function processExcelFile(): array
    {
        // This would require PhpSpreadsheet package
        throw new \Exception('Excel file import not yet implemented. Please use CSV format.');
    }

    /**
     * Validate row data using Laravel validation
     *
     * @param array $data
     * @param int $rowNumber
     * @return array
     */
    private function validateRowData(array $data, int $rowNumber): array
    {
        if (empty($this->validationRules)) {
            return ['valid' => true, 'errors' => []];
        }

        $validator = Validator::make($data, $this->validationRules);

        if ($validator->fails()) {
            return [
                'valid' => false,
                'errors' => [
                    'row' => $rowNumber,
                    'errors' => $validator->errors()->toArray(),
                    'data' => $data,
                ],
            ];
        }

        return ['valid' => true, 'errors' => []];
    }

    /**
     * Import a single row of data
     *
     * @param array $data
     * @return void
     */
    private function importRowData(array $data): void
    {
        $modelClass = $this->model;
        
        if (!class_exists($modelClass)) {
            throw new \Exception("Model class not found: {$modelClass}");
        }

        // Create or update the model
        $model = new $modelClass();
        $model->fill($data);
        $model->save();
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
            'import_failed',
            $this->model,
            [
                'error' => $exception->getMessage(),
                'file_path' => $this->filePath,
                'attempts' => $this->attempts(),
            ],
            $this->userId,
            'System'
        );

        // Log the failure
        \Log::error('Admin data import failed', [
            'model' => $this->model,
            'user_id' => $this->userId,
            'file_path' => $this->filePath,
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
            'import',
            "model:{$this->model}",
            "user:{$this->userId}",
        ];
    }
}