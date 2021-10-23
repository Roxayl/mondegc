<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Routes vers l'application "legacy"
|--------------------------------------------------------------------------
|
| Cette route "par défaut" est utilisée lorsque l'application ne parvient
| pas à résoudre une route vers un controller de l'app Laravel. Cette route
| "attrape-tout" permet de faire gérer la requête par le controller dédié
| au site "legacy".
|
*/

Route::any("/{path?}", "LegacySiteController")->where("path", ".*");
