<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force the cache to use the file driver
        Cache::setDefaultDriver('file');
        
        // Custom database session handler - commenting out as we're now using file driver
        /* 
        Session::extend('custom_database', function ($app) {
            // Get the session table
            $table = config('session.table', 'sessions');
            
            // Create a custom closure for retrieving sessions
            $retrieveSession = function($sessionId) use ($table) {
                return DB::table($table)
                    ->where('session_id', $sessionId)
                    ->first();
            };
            
            // Create a custom closure for writing sessions
            $writeSession = function($sessionId, $data, $exists) use ($table) {
                $expires = time() + (config('session.lifetime') * 60);
                
                if ($exists) {
                    return DB::table($table)
                        ->where('session_id', $sessionId)
                        ->update([
                            'data' => $data,
                            'expires' => $expires
                        ]);
                }
                
                return DB::table($table)->insert([
                    'session_id' => $sessionId,
                    'data' => $data,
                    'expires' => $expires
                ]);
            };
            
            // Create a custom closure for destroying sessions
            $destroySession = function($sessionId) use ($table) {
                return DB::table($table)
                    ->where('session_id', $sessionId)
                    ->delete();
            };
            
            // Create a custom closure for garbage collection
            $gcSession = function($lifetime) use ($table) {
                $expiration = time();
                return DB::table($table)
                    ->where('expires', '<', $expiration)
                    ->delete();
            };
            
            // Return a custom handler
            return new CustomDatabaseSessionHandler(
                $retrieveSession,
                $writeSession,
                $destroySession,
                $gcSession,
                config('session.lifetime')
            );
        });
        */
    }
}

// Custom database session handler class - commenting out as we're using file driver
/*
class CustomDatabaseSessionHandler implements \SessionHandlerInterface
{
    protected $retrieveSession;
    protected $writeSession;
    protected $destroySession;
    protected $gcSession;
    protected $lifetime;
    
    public function __construct($retrieveSession, $writeSession, $destroySession, $gcSession, $lifetime)
    {
        $this->retrieveSession = $retrieveSession;
        $this->writeSession = $writeSession;
        $this->destroySession = $destroySession;
        $this->gcSession = $gcSession;
        $this->lifetime = $lifetime;
    }
    
    #[\ReturnTypeWillChange]
    public function open($savePath, $sessionName): bool
    {
        return true;
    }
    
    #[\ReturnTypeWillChange]
    public function close(): bool
    {
        return true;
    }
    
    #[\ReturnTypeWillChange]
    public function read($id): string|false
    {
        $session = call_user_func($this->retrieveSession, $id);
        if ($session) {
            return $session->data;
        }
        return '';
    }
    
    #[\ReturnTypeWillChange]
    public function write($id, $data): bool
    {
        $session = call_user_func($this->retrieveSession, $id);
        call_user_func($this->writeSession, $id, $data, (bool) $session);
        return true;
    }
    
    #[\ReturnTypeWillChange]
    public function destroy($id): bool
    {
        call_user_func($this->destroySession, $id);
        return true;
    }
    
    #[\ReturnTypeWillChange]
    public function gc($maxlifetime): int|false
    {
        call_user_func($this->gcSession, $maxlifetime);
        return true;
    }
}
*/
