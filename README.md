# php-constant
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Dgame/php-constant/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Dgame/php-constant/?branch=master)

Dynamic constants at runtime with rust-like lifetime for PHP 7

##example

```php
<?php
use function Dgame\Constant\get;
use function Dgame\Constant\let;

print '<pre>';

function foo()
{
    let('created')->be(time());
    print __METHOD__ . ' -- created: ' . get('created') . PHP_EOL;
//    let('created')->be(time());
}

foo();
foo();

let('created')->be(time());
print 'Global -- created: ' . get('created') . PHP_EOL;

function bar()
{
    let('result')->be(5 * 5);
    print __METHOD__ . ' -- result ' . get('result') . PHP_EOL;
}

bar();

//print 'Global -- created: ' . get('result') . PHP_EOL;

function quatz()
{
    let('result')->be(6 * 6);
    print __METHOD__ . ' -- result: ' . get('result') . PHP_EOL;

    foo();
    bar();

    print __METHOD__ . ' -- result: ' . get('result') . PHP_EOL;
}

quatz();

let('foo')->be('bar');

print 'Global -- foo: ' . get('foo') . PHP_EOL;

function test()
{
    let('zip_filename')->be('foo.zip');

    print __METHOD__ . ' -- zip_filename: ' . get('zip_filename') . PHP_EOL;

    for ($i = 0; $i < 1; $i++) {
        let('img_filename')->be('bar.png');

        print __METHOD__ . ' -- img_filename: ' . get('img_filename') . PHP_EOL;
    }

    print __METHOD__ . ' -- img_filename: ' . get('img_filename') . PHP_EOL;
    print __METHOD__ . ' -- zip_filename: ' . get('zip_filename') . PHP_EOL;
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

print 'Global -- fac: ' . get('fac')(6) . PHP_EOL;
```
