<?php

namespace App\Jobs;

use App\Mail\VerificarUsuario;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class Verificacion_Dos_Pasos implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $user, $verificationCode;
    /**
     * Create a new job instance.
     */
    public function __construct(User $user, $verificationCode)
    {
        $this->user=$user;
        $this->verificationCode=$verificationCode;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::channel('infos')->info('Informacion: Un usuario administrador inicio sesion ' . 'Usuario: '. $this->user);
        Mail::to($this->user->email)->send(new VerificarUsuario($this->user,$this->verificationCode));
    }
}
