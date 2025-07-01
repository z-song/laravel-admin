<?php

namespace Encore\Admin\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Admin Presence Event for Laravel Reverb user presence tracking
 * 
 * This event tracks when admin users come online/offline and
 * their activity within the admin interface.
 */
class AdminPresenceEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $userId;
    public string $userName;
    public string $userAvatar;
    public string $status; // 'online', 'offline', 'away', 'busy'
    public string $currentPage;
    public string $timestamp;
    public array $metadata;

    /**
     * Create a new event instance.
     *
     * @param int $userId
     * @param string $userName
     * @param string $userAvatar
     * @param string $status
     * @param string $currentPage
     * @param array $metadata Additional metadata about user activity
     */
    public function __construct(
        int $userId,
        string $userName,
        string $userAvatar,
        string $status,
        string $currentPage = '',
        array $metadata = []
    ) {
        $this->userId = $userId;
        $this->userName = $userName;
        $this->userAvatar = $userAvatar;
        $this->status = $status;
        $this->currentPage = $currentPage;
        $this->metadata = $metadata;
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
            // Presence channel to track who's online
            new PresenceChannel('admin.presence'),
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
            'user_id' => $this->userId,
            'user_name' => $this->userName,
            'user_avatar' => $this->userAvatar,
            'status' => $this->status,
            'current_page' => $this->currentPage,
            'timestamp' => $this->timestamp,
            'metadata' => $this->metadata,
        ];
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'admin.presence';
    }
}