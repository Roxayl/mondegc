<?php

require(DEF_LEGACYROOTPATH . 'php/logout.php');

header("Status: 301 Moved Permanently", false, 301);
header('Location: ' . route('infrastructure-judge.index'));

exit;
