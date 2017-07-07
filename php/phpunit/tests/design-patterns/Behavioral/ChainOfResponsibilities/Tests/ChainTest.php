<?php

namespace DesignPatterns\Behavioral\ChainOfResponsibilities\Tests;

use DesignPatterns\Behavioral\ChainOfResponsibilities\Handler;
use DesignPatterns\Behavioral\ChainOfResponsibilities\Responsible\HttpInMemoryCacheHandler;
use DesignPatterns\Behavioral\ChainOfResponsibilities\Responsible\SlowDatabaseHandler;
use PHPUnit\Framework\TestCase;

class ChainTest extends TestCase
{
    /**
     * @var Handler
     */
    private $chain;

    protected function setUp()
    {
        $this->chain = new HttpInMemoryCacheHandler(
            ['/foo/bar?index=1' => 'Hello In Memory!'],
            new SlowDatabaseHandler()
        );
    }

    public function testGlobalsAssignments()
    {
        global $myGlobalBool;   // defaults to null if not defined, so no need to first test for existence below
        global $myGlobalInt;
        global $myGlobalFloat;
        global $myGlobalString;
        global $myGlobalHash;
        global $myGlobalNull;
        global $myGlobalEmpty;

        //fwrite(STDERR, print_r( $GLOBALS, true ));    // debug output $GLOBALS array

        if (!isset($GLOBALS['myGlobalFromXml'])) {
            $GLOBALS['myGlobalFromXml'] = null; // ensure default assignment, since not declared as global above
        }

        // check that phpunit.xml has expected global defined
        $this->assertNotNull($GLOBALS['myGlobalFromXml'], "Expecting a global in phpunit.xml defined as: myGlobalFromXml");

        // check that command line has expected global defined (eg. --g myGlobalBool=true)
        $this->assertTrue($myGlobalBool, "Expecting a global to be defined: -g myGlobalBool=true");

        $this->assertEquals($myGlobalInt, 99, "Expecting a global to be defined: -g myGlobalInt=99");

        $this->assertEquals(round($myGlobalFloat, 1), round((float)3.3, 1), "Expecting a global to be defined: -g myGlobalFloat=3.3");

        $this->assertEquals($myGlobalString, "mysql:host=y;dbname=z", "Expecting a global to be defined: -g myGlobalString=\"mysql:host=y;dbname=z\"");

        $this->assertEquals($myGlobalHash["bar"], "baz", "Expecting a global to be defined: -g myGlobalHash='[\"bar\"=>\"baz\"]'");

        $this->assertNull($myGlobalNull, "Expecting a global to be defined: -g myGlobalNull=null");

        $this->assertEmpty($myGlobalEmpty, "Expecting a global to be defined: -g myGlobalNull=");
    }

    public function testCanRequestKeyInFastStorage()
    {
        $uri = $this->createMock('Psr\Http\Message\UriInterface');
        $uri->method('getPath')->willReturn('/foo/bar');
        $uri->method('getQuery')->willReturn('index=1');

        $request = $this->createMock('Psr\Http\Message\RequestInterface');
        $request->method('getMethod')
            ->willReturn('GET');
        $request->method('getUri')->willReturn($uri);

        $this->assertEquals('Hello In Memory!', $this->chain->handle($request));
    }

    public function testCanRequestKeyInSlowStorage()
    {
        $uri = $this->createMock('Psr\Http\Message\UriInterface');
        $uri->method('getPath')->willReturn('/foo/baz');
        $uri->method('getQuery')->willReturn('');

        $request = $this->createMock('Psr\Http\Message\RequestInterface');
        $request->method('getMethod')
            ->willReturn('GET');
        $request->method('getUri')->willReturn($uri);

        $this->assertEquals('Hello World!', $this->chain->handle($request));
    }
}
