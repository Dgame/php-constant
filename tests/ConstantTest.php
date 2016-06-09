<?php

require_once '../vendor/autoload.php';

use function Dgame\Constant\let;
use \PHPUnit\Framework\TestCase;

class ConstantTest extends TestCase
{
    public function testBasic()
    {
        let('msg')->be('Hello World');

        $this->assertEquals(let('msg'), 'Hello World');
        $this->assertEquals(let('_'), '');
    }

    public function testCollision()
    {
        $this->expectException(\Constant\FormatException::class);
        $this->expectExceptionMessage('Value of "created" is already set');

        let('created')->be(time());
        let('created')->be(time());
    }

    public function testNestedLifetime()
    {
        function foo(TestCase $tc)
        {
            let('created')->be(42);
            $tc->assertEquals('42', let('created'));
        }

        foo($this);
        foo($this);

        let('created')->be(23);
        $this->assertEquals(let('created'), '23');

        function bar(TestCase $tc)
        {
            let('result')->be(5 * 5);
            $tc->assertEquals(let('result'), '25');
        }

        bar($this);

        $this->assertEquals(let('result'), '');

        function quatz(TestCase $tc)
        {
            let('result')->be(6 * 6);

            $tc->assertEquals(let('result'), '36');

            foo($tc);
            bar($tc);

            $tc->assertEquals(let('result'), '36');
        }

        quatz($this);

        let('foo')->be('bar');

        $this->assertEquals(let('foo'), 'bar');
    }

    public function testValidScope()
    {
        let('zip_filename')->be('foo.zip');
        $this->assertEquals(let('zip_filename'), 'foo.zip');

        for ($i = 0; $i < 1; $i++) {
            let('img_filename')->be('bar.png');

            $this->assertEquals(let('zip_filename'), 'foo.zip');
            $this->assertEquals(let('img_filename'), 'bar.png');
        }

        $this->assertEquals(let('zip_filename'), 'foo.zip');
        $this->assertEquals(let('img_filename'), 'bar.png');
    }

    public function testInvalidScope()
    {
        $this->expectException(\Constant\FormatException::class);
        $this->expectExceptionMessage('Value of "img_filename" is already set');

        for ($i = 0; $i < 2; $i++) {
            let('img_filename')->be('bar.png');
        }
    }
}