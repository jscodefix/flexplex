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
        //fwrite(STDERR, print_r( $GLOBALS, true ));    // debug output $GLOBALS array

        if (!isset($GLOBALS['myGlobalFromXml'])) {
            $GLOBALS['myGlobalFromXml'] = null; // ensure default assignment
        }
            
        // check that phpunit.xml has expected global defined
        $this->assertNotNull($GLOBALS['myGlobalFromXml'], "Expecting a global in phpunit.xml defined as: myGlobalFromXml");

        if (!isset($GLOBALS['myGlobalFromCommand'])) {
            $GLOBALS['myGlobalFromCommand'] = null; // ensure default assignment
        }
            
        // check that command line has expected global defined (eg. --global myGlobalFromCommand="foo")
        $this->assertNotNull($GLOBALS['myGlobalFromCommand'], "Expecting a global defined on command line as: --global myGlobalFromCommand=\"anything\"");
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
