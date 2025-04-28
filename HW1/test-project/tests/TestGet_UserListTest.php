<?php
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

class TestGet_UserListTest extends TestCase
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

    public function testGet_UserList()
    {
        $response = $this->client->request('GET', 'index.php/user/list');
        $this->assertEquals(200, $response->getStatusCode());
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->client = null;
    }
}
?>