<?php

/*****
 * Legacy
 *****/
Route::any("/{path?}", "LegacySiteController@index")->where("path", ".*");
