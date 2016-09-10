<?php

require_once '../vendor/autoload.php';

use Dgame\Ensurance\Exception\EnsuranceException;
use PHPUnit\Framework\TestCase;
use function Dgame\Constant\bind;
use function Dgame\Constant\get;
use function Dgame\Constant\let;

class ConstantTest extends TestCase
{
    public function testBasic()
    {
        let('msg')->be('Hello World');

        $this->assertEquals(get('msg'), 'Hello World');
        $this->assertEquals(get('msg'), 'Hello World');
    }

    public function testCollision()
    {
        $this->expectException(EnsuranceException::class);
        $this->expectExceptionMessage('Constant "created" already exists');

        let('created')->be(time());
        let('created')->be(time());
    }

    public function testNestedLifetime()
    {
        function foo(TestCase $tc)
        {
            let('created')->be(42);
            $tc->assertEquals('42', get('created'));
        }

        foo($this);
        foo($this);

        let('created')->be(23);
        $this->assertEquals(get('created'), '23');

        function bar(TestCase $tc)
        {
            let('result')->be(5 * 5);
            $tc->assertEquals(get('result'), '25');
        }

        bar($this);

        function quatz(TestCase $tc)
        {
            let('result')->be(6 * 6);

            $tc->assertEquals(get('result'), '36');

            foo($tc);
            bar($tc);

            $tc->assertEquals(get('result'), '36');
        }

        quatz($this);

        let('foo')->be('bar');

        $this->assertEquals(get('foo'), 'bar');
    }

    public function testValidScope()
    {
        let('zip_filename')->be('foo.zip');
        $this->assertEquals(get('zip_filename'), 'foo.zip');

        for ($i = 0; $i < 1; $i++) {
            let('img_filename')->be('bar.png');

            $this->assertEquals(get('zip_filename'), 'foo.zip');
            $this->assertEquals(get('img_filename'), 'bar.png');
        }

        $this->assertEquals(get('zip_filename'), 'foo.zip');
        $this->assertEquals(get('img_filename'), 'bar.png');
    }

    public function testInvalidScope()
    {
        $this->expectException(EnsuranceException::class);
        $this->expectExceptionMessage('Constant "img_filename" already exists');

        for ($i = 0; $i < 2; $i++) {
            let('img_filename')->be('bar.png');
        }
    }

    public function testBind()
    {
        bind(['a' => 42, 'b' => 23]);

        $this->assertEquals(42, get('a'));
        $this->assertEquals(23, get('b'));
    }
}