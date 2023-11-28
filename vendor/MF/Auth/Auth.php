<?php

namespace MF\Auth;

use App\Models\Usuario;

class Auth 
{
    public static function isAutenticated()
    {
        session_start();

        if(isset($_SESSION['USER_ID']) && isset($_SESSION['USER_NAME'])) {
            return true;
        } 

        header('Location: /?auth=erro');
    }

    public static function getUserData()
    {
        return Usuario::getUsuarioById($_SESSION['USER_ID']);
    }
}