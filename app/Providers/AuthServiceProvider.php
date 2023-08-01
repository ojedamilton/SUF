<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\UserGrupo;
use Illuminate\Support\Facades\DB;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        /* define a admin user role */
        // Con el metodo pluck traemos solo ese valor del campo de la collection
        // con toArray lo transformamos
        Gate::define('isAdmin', function($user) {
                    $userGrupo=UserGrupo::where('idUsuario',$user->id)
                        ->pluck('idGrupo')
                        ->toArray();
                    $resultado=in_array(1,$userGrupo);
                    return $resultado;
                });
        /* define a buyer user role */
        Gate::define('isBuyer', function($user) {
        $userGrupo=UserGrupo::where('idUsuario',$user->id)
            ->pluck('idGrupo')
            ->toArray();
        $resultado=in_array(2,$userGrupo);
            return $resultado;
        });
        /* define a seller user role */
        Gate::define('isSeller', function($user) {
        $userGrupo=UserGrupo::where('idUsuario',$user->id)
            ->pluck('idGrupo')
            ->toArray();
        $resultado=in_array(3,$userGrupo);
        return $resultado;
        });

        // Definimos Gate Para Las Acciones De Los Usuarios
        // La implementamos con el metodo @can el el sidebar.blade.php
        // Admin
        Gate::define('viewAdmin', function($user) {
            $grupos = $user->grupos()->pluck('id');
            $acciones = DB::table('grupoacciones as ga')
                            ->join('acciones as ac', 'ga.idAccion', '=', 'ac.id')
                            ->whereIn('idGrupo', $grupos)
                            ->pluck('ac.nombreAccion')
                            ->toArray();

            $resultado=in_array('viewAdmin',$acciones);
            return $resultado;
        }); 
        Gate::define('createAdmin', function($user) {
            $grupos = $user->grupos()->pluck('id');
            $acciones = DB::table('grupoacciones as ga')
                            ->join('acciones as ac', 'ga.idAccion', '=', 'ac.id')
                            ->whereIn('idGrupo', $grupos)
                            ->pluck('ac.nombreAccion')
                            ->toArray();

            $resultado=in_array('createAdmin',$acciones);
            return $resultado;
        }); 
        Gate::define('editAdmin', function($user) {
            $grupos = $user->grupos()->pluck('id');
            $acciones = DB::table('grupoacciones as ga')
                            ->join('acciones as ac', 'ga.idAccion', '=', 'ac.id')
                            ->whereIn('idGrupo', $grupos)
                            ->pluck('ac.nombreAccion')
                            ->toArray();

            $resultado=in_array('editAdmin',$acciones);
            return $resultado;
        });
        Gate::define('deleteAdmin', function($user) {
            $grupos = $user->grupos()->pluck('id');
            $acciones = DB::table('grupoacciones as ga')
                            ->join('acciones as ac', 'ga.idAccion', '=', 'ac.id')
                            ->whereIn('idGrupo', $grupos)
                            ->pluck('ac.nombreAccion')
                            ->toArray();

             $resultado=in_array('deleteAdmin',$acciones);
             return $resultado;
        });
        Gate::define('reportAdmin', function($user) {
            $grupos = $user->grupos()->pluck('id');
            $acciones = DB::table('grupoacciones as ga')
                            ->join('acciones as ac', 'ga.idAccion', '=', 'ac.id')
                            ->whereIn('idGrupo', $grupos)
                            ->pluck('ac.nombreAccion')
                            ->toArray();

             $resultado=in_array('reportAdmin',$acciones);
             return $resultado;
        });
        // Vendedor (reemplazar edit por create)
        Gate::define('viewVendedor', function($user) {
            $grupos = $user->grupos()->pluck('id');
            $acciones = DB::table('grupoacciones as ga')
                            ->join('acciones as ac', 'ga.idAccion', '=', 'ac.id')
                            ->whereIn('idGrupo', $grupos)
                            ->pluck('ac.nombreAccion')
                            ->toArray();

             $resultado=in_array('viewVendedor',$acciones);
             return $resultado;
        });
        Gate::define('createVendedor', function($user) {
            $grupos = $user->grupos()->pluck('id');
            $acciones = DB::table('grupoacciones as ga')
                            ->join('acciones as ac', 'ga.idAccion', '=', 'ac.id')
                            ->whereIn('idGrupo', $grupos)
                            ->pluck('ac.nombreAccion')
                            ->toArray();

            $resultado=in_array('createVendedor',$acciones);
            return $resultado;
        }); 
        Gate::define('editVendedor', function($user) {
            $grupos = $user->grupos()->pluck('id');
            $acciones = DB::table('grupoacciones as ga')
                            ->join('acciones as ac', 'ga.idAccion', '=', 'ac.id')
                            ->whereIn('idGrupo', $grupos)
                            ->pluck('ac.nombreAccion')
                            ->toArray();

             $resultado=in_array('editVendedor',$acciones);
             return $resultado;
        });
        Gate::define('deleteVendedor', function($user) {
            $grupos = $user->grupos()->pluck('id');
            $acciones = DB::table('grupoacciones as ga')
                            ->join('acciones as ac', 'ga.idAccion', '=', 'ac.id')
                            ->whereIn('idGrupo', $grupos)
                            ->pluck('ac.nombreAccion')
                            ->toArray();

             $resultado=in_array('deleteVendedor',$acciones);
             return $resultado;
        });
        Gate::define('reportVendedor', function($user) {
            $grupos = $user->grupos()->pluck('id');
            $acciones = DB::table('grupoacciones as ga')
                            ->join('acciones as ac', 'ga.idAccion', '=', 'ac.id')
                            ->whereIn('idGrupo', $grupos)
                            ->pluck('ac.nombreAccion')
                            ->toArray();

             $resultado=in_array('reportVendedor',$acciones);
             return $resultado;
        });
        // Comprador (reemplazar edit por create)
        Gate::define('viewComprador', function($user) {
            $grupos = $user->grupos()->pluck('id');
            $acciones = DB::table('grupoacciones as ga')
                            ->join('acciones as ac', 'ga.idAccion', '=', 'ac.id')
                            ->whereIn('idGrupo', $grupos)
                            ->pluck('ac.nombreAccion')
                            ->toArray();

             $resultado=in_array('viewComprador',$acciones);
             return $resultado;
        });
        Gate::define('createComprador', function($user) {
            $grupos = $user->grupos()->pluck('id');
            $acciones = DB::table('grupoacciones as ga')
                            ->join('acciones as ac', 'ga.idAccion', '=', 'ac.id')
                            ->whereIn('idGrupo', $grupos)
                            ->pluck('ac.nombreAccion')
                            ->toArray();

            $resultado=in_array('createComprador',$acciones);
            return $resultado;
        });  
        Gate::define('editComprador', function($user) {
            $grupos = $user->grupos()->pluck('id');
            $acciones = DB::table('grupoacciones as ga')
                            ->join('acciones as ac', 'ga.idAccion', '=', 'ac.id')
                            ->whereIn('idGrupo', $grupos)
                            ->pluck('ac.nombreAccion')
                            ->toArray();

             $resultado=in_array('editComprador',$acciones);
             return $resultado;
        });
        Gate::define('deleteComprador', function($user) {
            $grupos = $user->grupos()->pluck('id');
            $acciones = DB::table('grupoacciones as ga')
                            ->join('acciones as ac', 'ga.idAccion', '=', 'ac.id')
                            ->whereIn('idGrupo', $grupos)
                            ->pluck('ac.nombreAccion')
                            ->toArray();

             $resultado=in_array('deleteComprador',$acciones);
             return $resultado;
        });
        Gate::define('reportComprador', function($user) {
            $grupos = $user->grupos()->pluck('id');
            $acciones = DB::table('grupoacciones as ga')
                            ->join('acciones as ac', 'ga.idAccion', '=', 'ac.id')
                            ->whereIn('idGrupo', $grupos)
                            ->pluck('ac.nombreAccion')
                            ->toArray();

             $resultado=in_array('reportComprador',$acciones);
             return $resultado;
        });  
    }
}
