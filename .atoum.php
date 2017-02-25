<?php

$runner->addTestsFromDirectory(__DIR__.'/Tests');

if (getenv('TRAVIS') !== false) {
    $script->addDefaultReport();

    $cloverWriter = new atoum\writers\file(__DIR__.'/clover.xml');
    $cloverReport = new atoum\reports\asynchronous\clover();
    $cloverReport->addWriter($cloverWriter);

    $runner->addReport($cloverReport);
}
