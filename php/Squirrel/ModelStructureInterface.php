<?php

namespace Squirrel;


interface ModelStructureInterface {

    public function __construct();

    public function __get($prop);

    public function __set($prop, $value);

}