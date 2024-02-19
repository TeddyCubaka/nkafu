<?php

/**
 * This is a example of module urls array.
 * Use this for all your module urls files
 * the represent the path in the uri and the value the class view to call
 */
$urlpatterns = [
    'auth' => 'AuthView',
    'auth/token/refresh' => 'RefreshTokenView',
    'auth/token/verify' => 'VerifyTokenView'
];
