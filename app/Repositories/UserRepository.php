<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function getUsersByEmpresa($idEmpresa, $buscar = null)
    {
        $query = User::whereHas('empresas', function ($query) use ($idEmpresa) {
            $query->where('idEmpresa', $idEmpresa);
        });

        if (!empty($buscar)) {
            $query->where(function ($query) use ($buscar) {
                $query->where('name', 'like', '%' . $buscar . '%')
                    ->orWhere('apellido', 'like', '%' . $buscar . '%')
                    ->orWhere('email', 'like', '%' . $buscar . '%');
            });
        }

        return $query->orderBy('name', 'asc')
            ->paginate(10);
    }
}
