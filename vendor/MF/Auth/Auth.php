<?php

namespace MF\Auth;

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
}