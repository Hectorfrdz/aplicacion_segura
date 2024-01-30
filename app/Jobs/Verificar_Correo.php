<?php

namespace App\Jobs;

use App\Mail\ActivarUsuario;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class Verificar_Correo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $user,$url;
    /**
     * Create a new job instance.
     */
    public function __construct(User $user, String $url)
    {
        $this->user=$user;
        $this->url=$url;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::channel('infos')->info('Informacion: Intento de registro, email ' . 'Usuario: '. $this->user . ' URL: ' . $this->url);
        Mail::to($this->user->email)->send(new ActivarUsuario($this->user,$this->url));
    }
}
