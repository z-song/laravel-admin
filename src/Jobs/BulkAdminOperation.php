<?php

namespace Encore\Admin\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Encore\Admin\Events\AdminOperationEvent;
use Encore\Admin\Traits\BroadcastsOperations;

/**
 * Laravel 11 Queue Job for Bulk Admin Operations
 * 
 * This job handles bulk operations (update, delete, etc.) in the background
 * to prevent timeouts and provide better user experience.
 */
class BulkAdminOperation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, BroadcastsOperations;

    public string $operation;
    public string $model;
    public array $ids;
    public array $data;
    public int $userId;

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
     * @param string $operation The bulk operation type (update, delete, etc.)
     * @param string $model The model class
     * @param array $ids Array of model IDs to operate on
     * @param array $data Additional data for the operation
     * @param int $userId The user performing the operation
     */
    public function __construct(
        string $operation,
        string $model,
        array $ids,
        array $data,
        int $userId
    ) {
        $this->operation = $operation;
        $this->model = $model;
        $this->ids = $ids;
        $this->data = $data;
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        try {
            $result = $this->performBulkOperation();
            
            // Broadcast completion event
            $this->broadcastOperation(
                "bulk_{$this->operation}",
                $this->model,
                $result,
                $this->userId
            );

        } catch (\Exception $e) {
            // Broadcast error event
            $this->broadcastOperation(
                "bulk_{$this->operation}_failed",
                $this->model,
                [
                    'error' => $e->getMessage(),
                    'operation' => $this->operation,
                    'ids_count' => count($this->ids),
                ],
                $this->userId
            );

            throw $e;
        }
    }

    /**
     * Perform the bulk operation based on the operation type
     *
     * @return array Operation results
     */
    private function performBulkOperation(): array
    {
        $modelClass = $this->model;
        
        if (!class_exists($modelClass)) {
            throw new \Exception("Model class not found: {$modelClass}");
        }

        $successCount = 0;
        $errorCount = 0;
        $errors = [];

        switch ($this->operation) {
            case 'update':
                return $this->performBulkUpdate($modelClass);
            
            case 'delete':
                return $this->performBulkDelete($modelClass);
            
            case 'soft_delete':
                return $this->performBulkSoftDelete($modelClass);
            
            case 'restore':
                return $this->performBulkRestore($modelClass);
            
            case 'force_delete':
                return $this->performBulkForceDelete($modelClass);
            
            default:
                throw new \Exception("Unsupported bulk operation: {$this->operation}");
        }
    }

    /**
     * Perform bulk update operation
     *
     * @param string $modelClass
     * @return array
     */
    private function performBulkUpdate(string $modelClass): array
    {
        $successCount = 0;
        $errorCount = 0;
        $errors = [];

        // Use chunk processing for large datasets
        $chunks = array_chunk($this->ids, 100);

        foreach ($chunks as $chunk) {
            try {
                $affected = $modelClass::whereIn('id', $chunk)->update($this->data);
                $successCount += $affected;
            } catch (\Exception $e) {
                $errorCount += count($chunk);
                $errors[] = [
                    'chunk' => $chunk,
                    'error' => $e->getMessage(),
                ];
            }
        }

        return [
            'operation' => 'bulk_update',
            'total_requested' => count($this->ids),
            'success_count' => $successCount,
            'error_count' => $errorCount,
            'errors' => $errors,
            'data' => $this->data,
        ];
    }

    /**
     * Perform bulk delete operation
     *
     * @param string $modelClass
     * @return array
     */
    private function performBulkDelete(string $modelClass): array
    {
        $successCount = 0;
        $errorCount = 0;
        $errors = [];

        // Use chunk processing for large datasets
        $chunks = array_chunk($this->ids, 100);

        foreach ($chunks as $chunk) {
            try {
                $affected = $modelClass::whereIn('id', $chunk)->delete();
                $successCount += $affected;
            } catch (\Exception $e) {
                $errorCount += count($chunk);
                $errors[] = [
                    'chunk' => $chunk,
                    'error' => $e->getMessage(),
                ];
            }
        }

        return [
            'operation' => 'bulk_delete',
            'total_requested' => count($this->ids),
            'success_count' => $successCount,
            'error_count' => $errorCount,
            'errors' => $errors,
        ];
    }

    /**
     * Perform bulk soft delete operation
     *
     * @param string $modelClass
     * @return array
     */
    private function performBulkSoftDelete(string $modelClass): array
    {
        // Check if model supports soft deletes
        $model = new $modelClass();
        if (!method_exists($model, 'bootSoftDeletes')) {
            throw new \Exception("Model {$modelClass} does not support soft deletes");
        }

        $successCount = 0;
        $errorCount = 0;
        $errors = [];

        $chunks = array_chunk($this->ids, 100);

        foreach ($chunks as $chunk) {
            try {
                $affected = $modelClass::whereIn('id', $chunk)->delete(); // This will be soft delete
                $successCount += $affected;
            } catch (\Exception $e) {
                $errorCount += count($chunk);
                $errors[] = [
                    'chunk' => $chunk,
                    'error' => $e->getMessage(),
                ];
            }
        }

        return [
            'operation' => 'bulk_soft_delete',
            'total_requested' => count($this->ids),
            'success_count' => $successCount,
            'error_count' => $errorCount,
            'errors' => $errors,
        ];
    }

    /**
     * Perform bulk restore operation
     *
     * @param string $modelClass
     * @return array
     */
    private function performBulkRestore(string $modelClass): array
    {
        // Check if model supports soft deletes
        $model = new $modelClass();
        if (!method_exists($model, 'bootSoftDeletes')) {
            throw new \Exception("Model {$modelClass} does not support soft deletes");
        }

        $successCount = 0;
        $errorCount = 0;
        $errors = [];

        $chunks = array_chunk($this->ids, 100);

        foreach ($chunks as $chunk) {
            try {
                $affected = $modelClass::withTrashed()->whereIn('id', $chunk)->restore();
                $successCount += $affected;
            } catch (\Exception $e) {
                $errorCount += count($chunk);
                $errors[] = [
                    'chunk' => $chunk,
                    'error' => $e->getMessage(),
                ];
            }
        }

        return [
            'operation' => 'bulk_restore',
            'total_requested' => count($this->ids),
            'success_count' => $successCount,
            'error_count' => $errorCount,
            'errors' => $errors,
        ];
    }

    /**
     * Perform bulk force delete operation
     *
     * @param string $modelClass
     * @return array
     */
    private function performBulkForceDelete(string $modelClass): array
    {
        $successCount = 0;
        $errorCount = 0;
        $errors = [];

        $chunks = array_chunk($this->ids, 100);

        foreach ($chunks as $chunk) {
            try {
                $models = $modelClass::withTrashed()->whereIn('id', $chunk)->get();
                foreach ($models as $model) {
                    $model->forceDelete();
                    $successCount++;
                }
            } catch (\Exception $e) {
                $errorCount += count($chunk);
                $errors[] = [
                    'chunk' => $chunk,
                    'error' => $e->getMessage(),
                ];
            }
        }

        return [
            'operation' => 'bulk_force_delete',
            'total_requested' => count($this->ids),
            'success_count' => $successCount,
            'error_count' => $errorCount,
            'errors' => $errors,
        ];
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
            "bulk_{$this->operation}_failed",
            $this->model,
            [
                'error' => $exception->getMessage(),
                'operation' => $this->operation,
                'ids_count' => count($this->ids),
                'attempts' => $this->attempts(),
            ],
            $this->userId,
            'System'
        );

        // Log the failure
        \Log::error('Admin bulk operation failed', [
            'operation' => $this->operation,
            'model' => $this->model,
            'user_id' => $this->userId,
            'ids_count' => count($this->ids),
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
            'bulk_operation',
            $this->operation,
            "model:{$this->model}",
            "user:{$this->userId}",
            "count:" . count($this->ids),
        ];
    }
}