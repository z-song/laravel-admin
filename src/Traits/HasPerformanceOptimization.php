<?php

namespace Encore\Admin\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Queue;
use Illuminate\Database\Eloquent\Builder;

/**
 * Laravel 11 Performance Optimization Trait
 * 
 * This trait provides Laravel 11 performance optimization features
 * including caching, queue management, and database optimizations.
 */
trait HasPerformanceOptimization
{
    /**
     * Cache configuration from admin settings
     */
    private function getCacheConfig(): array
    {
        return config('admin.performance.cache', [
            'enabled' => true,
            'driver' => 'redis',
            'prefix' => 'admin',
            'ttl' => 3600,
        ]);
    }

    /**
     * Get cached data with Laravel 11 optimizations
     *
     * @param string $key
     * @param callable $callback
     * @param int|null $ttl
     * @return mixed
     */
    protected function getCached(string $key, callable $callback, ?int $ttl = null): mixed
    {
        $config = $this->getCacheConfig();
        
        if (!$config['enabled']) {
            return $callback();
        }

        $cacheKey = $config['prefix'] . ':' . $key;
        $ttl = $ttl ?? $config['ttl'];

        return Cache::driver($config['driver'])->remember($cacheKey, $ttl, $callback);
    }

    /**
     * Cache admin menu data with Laravel 11 optimizations
     *
     * @return array
     */
    protected function getCachedMenu(): array
    {
        if (!config('admin.performance.cache.menu_cache', true)) {
            return $this->buildMenu();
        }

        return $this->getCached('menu:' . auth('admin')->id(), function () {
            return $this->buildMenu();
        }, 1800); // 30 minutes
    }

    /**
     * Cache permission data with Laravel 11 optimizations
     *
     * @param int $userId
     * @return array
     */
    protected function getCachedPermissions(int $userId): array
    {
        if (!config('admin.performance.cache.permission_cache', true)) {
            return $this->buildPermissions($userId);
        }

        return $this->getCached("permissions:{$userId}", function () use ($userId) {
            return $this->buildPermissions($userId);
        }, 3600); // 1 hour
    }

    /**
     * Cache role data with Laravel 11 optimizations
     *
     * @param int $userId
     * @return array
     */
    protected function getCachedRoles(int $userId): array
    {
        if (!config('admin.performance.cache.role_cache', true)) {
            return $this->buildRoles($userId);
        }

        return $this->getCached("roles:{$userId}", function () use ($userId) {
            return $this->buildRoles($userId);
        }, 3600); // 1 hour
    }

    /**
     * Clear specific cache keys
     *
     * @param string|array $keys
     * @return void
     */
    protected function clearCache(string|array $keys): void
    {
        $config = $this->getCacheConfig();
        $cache = Cache::driver($config['driver']);
        
        $keys = is_array($keys) ? $keys : [$keys];
        
        foreach ($keys as $key) {
            $cacheKey = $config['prefix'] . ':' . $key;
            $cache->forget($cacheKey);
        }
    }

    /**
     * Clear all admin-related cache
     *
     * @return void
     */
    protected function clearAllCache(): void
    {
        $config = $this->getCacheConfig();
        $cache = Cache::driver($config['driver']);
        
        // Clear all keys with admin prefix
        $pattern = $config['prefix'] . ':*';
        
        // For Redis driver
        if ($config['driver'] === 'redis') {
            $redis = $cache->getRedis();
            $keys = $redis->keys($pattern);
            
            if (!empty($keys)) {
                $redis->del($keys);
            }
        } else {
            // For other drivers, clear common cache keys
            $commonKeys = [
                'menu:*',
                'permissions:*',
                'roles:*',
                'settings',
                'config',
            ];
            
            foreach ($commonKeys as $pattern) {
                $this->clearCachePattern($pattern);
            }
        }
    }

    /**
     * Queue background operations if enabled
     *
     * @param string $job
     * @param array $data
     * @return void
     */
    protected function queueOperation(string $job, array $data = []): void
    {
        $queueConfig = config('admin.performance.queue', [
            'enabled' => false,
            'connection' => 'redis',
            'queue' => 'admin',
        ]);

        if (!$queueConfig['enabled']) {
            return;
        }

        Queue::connection($queueConfig['connection'])
            ->pushOn($queueConfig['queue'], $job, $data);
    }

    /**
     * Check if operation should be queued
     *
     * @param string $operation
     * @return bool
     */
    protected function shouldQueue(string $operation): bool
    {
        $operations = config('admin.performance.queue.operations', []);
        return $operations[$operation] ?? false;
    }

    /**
     * Queue export operation if configured
     *
     * @param string $model
     * @param array $data
     * @return void
     */
    protected function queueExport(string $model, array $data): void
    {
        if ($this->shouldQueue('export')) {
            $this->queueOperation('ExportAdminData', [
                'model' => $model,
                'data' => $data,
                'user_id' => auth('admin')->id(),
            ]);
        }
    }

    /**
     * Queue import operation if configured
     *
     * @param string $model
     * @param string $filePath
     * @return void
     */
    protected function queueImport(string $model, string $filePath): void
    {
        if ($this->shouldQueue('import')) {
            $this->queueOperation('ImportAdminData', [
                'model' => $model,
                'file_path' => $filePath,
                'user_id' => auth('admin')->id(),
            ]);
        }
    }

    /**
     * Queue bulk operations if configured
     *
     * @param string $operation
     * @param string $model
     * @param array $ids
     * @param array $data
     * @return void
     */
    protected function queueBulkOperation(string $operation, string $model, array $ids, array $data = []): void
    {
        if ($this->shouldQueue('bulk_operations')) {
            $this->queueOperation('BulkAdminOperation', [
                'operation' => $operation,
                'model' => $model,
                'ids' => $ids,
                'data' => $data,
                'user_id' => auth('admin')->id(),
            ]);
        }
    }

    /**
     * Apply Laravel 11 database optimizations to query builder
     *
     * @param Builder $query
     * @return Builder
     */
    protected function optimizeQuery(Builder $query): Builder
    {
        $dbConfig = config('admin.performance.database', [
            'eager_loading' => true,
            'chunk_size' => 1000,
            'query_cache' => true,
        ]);

        // Enable eager loading if configured
        if ($dbConfig['eager_loading']) {
            $query->with($this->getEagerLoadRelations());
        }

        // Set chunk size for large datasets
        if (method_exists($query, 'chunk') && $dbConfig['chunk_size']) {
            $query->chunk($dbConfig['chunk_size']);
        }

        return $query;
    }

    /**
     * Get relations that should be eager loaded
     *
     * @return array
     */
    protected function getEagerLoadRelations(): array
    {
        // Default relations that should be eager loaded
        return [
            'roles',
            'permissions',
            'parent',
            'children',
        ];
    }

    /**
     * Laravel 11 optimized pagination
     *
     * @param Builder $query
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    protected function paginateOptimized(Builder $query, int $perPage = 20): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        // Apply optimizations before pagination
        $query = $this->optimizeQuery($query);

        // Use Laravel 11 efficient pagination
        return $query->paginate($perPage);
    }

    /**
     * Laravel 11 memory-efficient chunking for large datasets
     *
     * @param Builder $query
     * @param callable $callback
     * @param int $chunkSize
     * @return void
     */
    protected function chunkOptimized(Builder $query, callable $callback, int $chunkSize = null): void
    {
        $chunkSize = $chunkSize ?? config('admin.performance.database.chunk_size', 1000);
        
        $query->chunkById($chunkSize, $callback);
    }

    /**
     * Create performance monitoring tags for Laravel 11
     *
     * @param string $operation
     * @return array
     */
    protected function getPerformanceTags(string $operation): array
    {
        return [
            'admin.operation' => $operation,
            'admin.user' => auth('admin')->id() ?? 'guest',
            'admin.model' => $this->getModelName(),
        ];
    }

    /**
     * Get model name for performance monitoring
     *
     * @return string
     */
    protected function getModelName(): string
    {
        return class_basename($this);
    }

    /**
     * Abstract methods that implementing classes should define
     */
    abstract protected function buildMenu(): array;
    abstract protected function buildPermissions(int $userId): array;
    abstract protected function buildRoles(int $userId): array;

    /**
     * Clear cache pattern (implementation depends on cache driver)
     *
     * @param string $pattern
     * @return void
     */
    private function clearCachePattern(string $pattern): void
    {
        // Implementation would depend on specific cache driver capabilities
        // This is a placeholder for pattern-based cache clearing
    }
}