<?php
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

class TestPost_LoginUser extends TestCase
{
    protected $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = new Client([
            'base_uri' => 'http://localhost/', 
            'http_errors' => false
        ]);
    }

    public function testPost_LoginUser()
    {
        $response = $this->client->request('POST', '/user/login', [ 
            'form_params' => [
                'username' => 'testuser123',
                'password' => 'securepassword'
            ]
        ]);
        $this->assertEquals(201, $response->getStatusCode());
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->client = null;
    }
}
?>