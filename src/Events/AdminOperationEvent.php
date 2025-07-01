<?php

namespace Encore\Admin\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Admin Operation Event for Laravel Reverb real-time broadcasting
 * 
 * This event is broadcasted when admin operations occur, enabling
 * real-time updates across all connected admin interfaces.
 */
class AdminOperationEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $operation;
    public string $model;
    public mixed $data;
    public int $userId;
    public string $userName;
    public string $timestamp;

    /**
     * Create a new event instance.
     *
     * @param string $operation The type of operation (create, update, delete, etc.)
     * @param string $model The model class that was affected
     * @param mixed $data The data related to the operation
     * @param int $userId The ID of the user who performed the operation
     * @param string $userName The name of the user who performed the operation
     */
    public function __construct(
        string $operation,
        string $model,
        mixed $data,
        int $userId,
        string $userName
    ) {
        $this->operation = $operation;
        $this->model = $model;
        $this->data = $data;
        $this->userId = $userId;
        $this->userName = $userName;
        $this->timestamp = now()->toISOString();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            // Private channel for specific user notifications
            new PrivateChannel('admin.operations.' . $this->userId),
            
            // General admin channel for all admin users
            new PrivateChannel('admin.notifications'),
            
            // Model-specific channel for grid updates
            new PrivateChannel('admin.grid.' . strtolower(class_basename($this->model))),
        ];
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'operation' => $this->operation,
            'model' => class_basename($this->model),
            'model_id' => $this->data['id'] ?? null,
            'user_id' => $this->userId,
            'user_name' => $this->userName,
            'timestamp' => $this->timestamp,
            'message' => $this->generateMessage(),
            'data' => $this->sanitizeData(),
        ];
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'admin.operation';
    }

    /**
     * Generate a human-readable message for the operation.
     *
     * @return string
     */
    private function generateMessage(): string
    {
        $modelName = class_basename($this->model);
        
        switch ($this->operation) {
            case 'created':
                return "{$this->userName} created a new {$modelName}";
            case 'updated':
                return "{$this->userName} updated {$modelName}";
            case 'deleted':
                return "{$this->userName} deleted {$modelName}";
            case 'restored':
                return "{$this->userName} restored {$modelName}";
            case 'exported':
                return "{$this->userName} exported {$modelName} data";
            case 'imported':
                return "{$this->userName} imported {$modelName} data";
            case 'bulk_updated':
                return "{$this->userName} performed bulk update on {$modelName}";
            case 'bulk_deleted':
                return "{$this->userName} performed bulk delete on {$modelName}";
            default:
                return "{$this->userName} performed {$this->operation} on {$modelName}";
        }
    }

    /**
     * Sanitize data for broadcasting (remove sensitive information).
     *
     * @return array<string, mixed>
     */
    private function sanitizeData(): array
    {
        if (!is_array($this->data)) {
            return [];
        }

        // Remove sensitive fields that shouldn't be broadcasted
        $sensitiveFields = [
            'password',
            'password_confirmation',
            'remember_token',
            'api_token',
            'secret',
            'private_key',
        ];

        $sanitized = $this->data;
        
        foreach ($sensitiveFields as $field) {
            unset($sanitized[$field]);
        }

        return $sanitized;
    }
}