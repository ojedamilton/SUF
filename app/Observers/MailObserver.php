<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Mail\NotificacionRegistro;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\App;

class MailObserver
{
    /**
     * Handle the User "created" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function created(User $user)
    {
        // Instancia del $user recien creado
        Log::info("usuario creado: ".$user->name);

        $environment = App::environment();
        // Si APP_ENV <> local, se envia el correo
        if($environment == 'testing' || $environment == 'production')
        {
            $correo= new NotificacionRegistro($user->email,$user->getPwd());
            Mail::to($user->email)->send($correo); 
        }
    }

    /**
     * Handle the User "updated" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function updated(User $user)
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function deleted(User $user)
    {
        //
    }

    /**
     * Handle the User "restored" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function restored(User $user)
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        //
    }
}
