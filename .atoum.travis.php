<?php

use \mageekguy\atoum;

require_once '.atoum.php';

$script->addDefaultReport();

$cloverWriter = new atoum\writers\file('clover.xml');
$cloverReport = new atoum\reports\asynchronous\clover();
$cloverReport->addWriter($cloverWriter);

$runner->addReport($cloverReport);
