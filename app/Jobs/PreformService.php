<?php

namespace App\Jobs;

use App\Models\Service;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Events\ServiceFinished;

class PreformService implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //

        $user = $this->user;
        $service = $this->user->service;
        
        $user->service->update([
            'content_changed' => 'content changed'.rand(1, 100),
        ]);

        ServiceFinished::dispatch($user, $service);


    }
}
