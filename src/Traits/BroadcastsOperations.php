<?php

namespace Encore\Admin\Traits;

use Encore\Admin\Events\AdminOperationEvent;
use Encore\Admin\Facades\Admin;

/**
 * Trait for broadcasting admin operations using Laravel Reverb
 * 
 * This trait can be used by controllers and other classes to
 * automatically broadcast operations to connected admin users.
 */
trait BroadcastsOperations
{
    /**
     * Broadcast an admin operation if Reverb is enabled
     *
     * @param string $operation The operation type (create, update, delete, etc.)
     * @param string $model The model class
     * @param mixed $data The operation data
     * @param int|null $userId Override user ID (defaults to current admin user)
     * @param string|null $userName Override user name (defaults to current admin user)
     * @return void
     */
    protected function broadcastOperation(
        string $operation,
        string $model,
        mixed $data,
        ?int $userId = null,
        ?string $userName = null
    ): void {
        // Check if Reverb is enabled
        if (!config('admin.reverb.enabled', false)) {
            return;
        }

        $currentUser = Admin::user();
        
        // Use provided user info or default to current admin user
        $userId = $userId ?? ($currentUser ? $currentUser->id : 0);
        $userName = $userName ?? ($currentUser ? $currentUser->name : 'System');

        // Broadcast the operation event
        AdminOperationEvent::dispatch(
            $operation,
            $model,
            $data,
            $userId,
            $userName
        );
    }

    /**
     * Broadcast a create operation
     *
     * @param string $model
     * @param mixed $data
     * @return void
     */
    protected function broadcastCreated(string $model, mixed $data): void
    {
        $this->broadcastOperation('created', $model, $data);
    }

    /**
     * Broadcast an update operation
     *
     * @param string $model
     * @param mixed $data
     * @return void
     */
    protected function broadcastUpdated(string $model, mixed $data): void
    {
        $this->broadcastOperation('updated', $model, $data);
    }

    /**
     * Broadcast a delete operation
     *
     * @param string $model
     * @param mixed $data
     * @return void
     */
    protected function broadcastDeleted(string $model, mixed $data): void
    {
        $this->broadcastOperation('deleted', $model, $data);
    }

    /**
     * Broadcast a bulk operation
     *
     * @param string $operation
     * @param string $model
     * @param array $data
     * @return void
     */
    protected function broadcastBulkOperation(string $operation, string $model, array $data): void
    {
        $this->broadcastOperation("bulk_{$operation}", $model, $data);
    }

    /**
     * Broadcast an export operation
     *
     * @param string $model
     * @param mixed $data
     * @return void
     */
    protected function broadcastExported(string $model, mixed $data): void
    {
        $this->broadcastOperation('exported', $model, $data);
    }

    /**
     * Broadcast an import operation
     *
     * @param string $model
     * @param mixed $data
     * @return void
     */
    protected function broadcastImported(string $model, mixed $data): void
    {
        $this->broadcastOperation('imported', $model, $data);
    }

    /**
     * Check if specific real-time features are enabled
     *
     * @param string $feature Feature name (notifications, grid_updates, etc.)
     * @return bool
     */
    protected function isRealtimeFeatureEnabled(string $feature): bool
    {
        return config("admin.reverb.features.{$feature}", false);
    }

    /**
     * Get the appropriate broadcast channel for a model
     *
     * @param string $model
     * @return string
     */
    protected function getModelBroadcastChannel(string $model): string
    {
        $modelName = strtolower(class_basename($model));
        return str_replace('{model}', $modelName, config('admin.reverb.channels.grid_updates', 'admin.grid.{model}'));
    }
}