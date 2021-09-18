<?php

/*****
 * Legacy
 *****/
Route::any("/{path?}", "LegacySiteController")->where("path", ".*");
