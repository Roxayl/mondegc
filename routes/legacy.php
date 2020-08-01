<?php

/*****
 * Legacy
 *****/
Route::any("/{path?}", "Legacy\LegacySiteController@index")->where("path", ".*");