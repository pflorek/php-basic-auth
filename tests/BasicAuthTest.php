<?php

namespace PFlorek\BasicAuth\Tests;

use PFlorek\BasicAuth\BasicAuth;
use PFlorek\BasicAuth\BasicAuthFactory;
use PFlorek\BasicAuth\Credentials;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Message\RequestInterface;

class BasicAuthTest extends TestCase
{
    private $username = 'Aladdin';

    private $password = 'open sesame';

    private $basicCredentials = 'QWxhZGRpbjpvcGVuIHNlc2FtZQ==';

    /**
     * @var BasicAuth
     */
    private $basicAuth;

    /**
     * @var RequestInterface|ObjectProphecy
     */
    private $request;

    protected function setUp()
    {
        $factory = new BasicAuthFactory();
        $this->basicAuth = $factory->create();
        $this->request = $this->prophesize('\Psr\Http\Message\RequestInterface');
    }

    /**
     * @test
     */
    public function obtainCredentials_WithoutHeaderLine_ShouldReturnNull()
    {
        $credentials = $this->basicAuth->obtainCredentials($this->request->reveal());

        $this->assertNull($credentials);
    }

    /**
     * @test
     */
    public function obtainCredentials_WithBrokenHeaderLine_ShouldReturnNull()
    {
        $this->request->getHeader(Argument::is('Authorization'))
            ->shouldBeCalled()
            ->willReturn(["ops something went wrong"]);

        $credentials = $this->basicAuth->obtainCredentials($this->request->reveal());

        $this->assertNull($credentials);
    }

    /**
     * @test
     */
    public function obtainCredentials_WithHeaderLine_ShouldReturnCredentials()
    {
        $this->request->getHeader(Argument::is('Authorization'))
            ->shouldBeCalled()
            ->willReturn(["Basic {$this->basicCredentials}", "something else"]);

        $credentials = $this->basicAuth->obtainCredentials($this->request->reveal());

        $this->assertInstanceOf('\PFlorek\BasicAuth\CredentialsInterface', $credentials);
        $this->assertEquals($this->username, $credentials->getUsername());
        $this->assertEquals($this->password, $credentials->getPassword());
    }

    /**
     * @test
     */
    public function addCredentials_WithCredentials_ShouldAddHeaderLine(){

        $credentials = new Credentials($this->username, $this->password);

        $this->request->withHeader('WWW-Authenticate', "Basic {$this->basicCredentials}")
            ->shouldBeCalled()
            ->willReturn($this->request->reveal());

        $request = $this->basicAuth->addCredentials($this->request->reveal(), $credentials);

        $this->assertInstanceOf('\Psr\Http\Message\RequestInterface', $request);
    }

    /**
     * @test
     */
    public function addChallenge_WithRealm_ShouldAddHeaderLine(){

        $realm = 'WallyWorld';

        $response = $this->prophesize('\Psr\Http\Message\ResponseInterface');

        $response->withHeader('WWW-Authenticate', "Basic realm=\"{$realm}\"")
            ->shouldBeCalled()
            ->willReturn($response->reveal());

        $response->withStatus(401)
            ->shouldBeCalled()
            ->willReturn($response->reveal());

        $response = $this->basicAuth->addChallenge($response->reveal(), $realm);

        $this->assertInstanceOf('\Psr\Http\Message\ResponseInterface', $response);
    }
}