<?php

require_once 'vendor/autoload.php';

use function Constant\let;

print '<pre>';

function foo()
{
    let('created')->be(time());
    print __METHOD__ . ' -- created: ' . let('created') . PHP_EOL;
//    let('created')->be(time());
}

foo();
foo();

let('created')->be(time());
print 'Global -- created: ' . let('created') . PHP_EOL;

function bar()
{
    let('result')->be(5 * 5);
    print __METHOD__ . ' -- result ' . let('result') . PHP_EOL;
}

bar();

print 'Global -- created: ' . let('result') . PHP_EOL;

function quatz()
{
    let('result')->be(6 * 6);
    print __METHOD__ . ' -- result: ' . let('result') . PHP_EOL;

    foo();
    bar();

    print __METHOD__ . ' -- result: ' . let('result') . PHP_EOL;
}

quatz();

let('foo')->be('bar');

print 'Global -- foo: ' . let('foo') . PHP_EOL;

function test()
{
    let('zip_filename')->be('foo.zip');

    print __METHOD__ . ' -- zip_filename: ' . let('zip_filename') . PHP_EOL;

    for ($i = 0; $i < 1; $i++) {
        let('img_filename')->be('bar.png');

        print __METHOD__ . ' -- img_filename: ' . let('img_filename') . PHP_EOL;
    }

    print __METHOD__ . ' -- img_filename: ' . let('img_filename') . PHP_EOL;
    print __METHOD__ . ' -- zip_filename: ' . let('zip_filename') . PHP_EOL;
}

test();

let('fac')->be(function(int $in) {
    $out = 1;

    // Only if $in is >= 2
    for ($i = 2; $i <= $in; $i++) {
        $out *= $i;
    }

    return $out;
});

//print 'Global -- fac: ' . let('fac')(6) . PHP_EOL;