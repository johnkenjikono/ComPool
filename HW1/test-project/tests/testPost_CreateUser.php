<?php
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

class TestPost_CreateUser extends TestCase
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

    public function testPost_CreateUser()
    {
        $username = 'user_' . uniqid();

        $response = $this->client->request('POST', 'index.php/user/create', [
            'json' => [
                'username' => $username,
                'password' => 'newpass124'
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