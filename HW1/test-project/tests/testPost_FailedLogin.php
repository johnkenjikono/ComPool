<?php
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

class TestPost_FailedLogin extends TestCase
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

    public function testPost_FailedLogin()
    {
        $response = $this->client->request('POST', 'index.php/user/list', [
            'form_params' => [
                'username' => 'wronguser',
                'password' => 'wrongpass'
            ]
        ]);
        $this->assertEquals(401, $response->getStatusCode()); // Adjust if your API returns a different code
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->client = null;
    }
}
?>