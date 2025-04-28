<?php
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

class TestPost_LoginUserTest extends TestCase
{
    protected $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = new Client([
            'base_uri' => 'http://localhost/ComPool/HW1/', // Corrected base_uri
            'http_errors' => false
        ]);
    }

    public function testPost_LoginUser()
    {
        $response = $this->client->request('POST', 'login.php', [
            'form_params' => [
                'username' => 'testuser123',
                'password' => 'securepassword'
            ]
        ]);

        // Expecting 200 OK instead of 201
        $this->assertEquals(200, $response->getStatusCode()); // Updated status code
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->client = null;
    }
}
?>