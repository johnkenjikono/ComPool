<?php
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

class TestPost_FailedLoginTest extends TestCase
{
    protected $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = new Client([
            'base_uri' => 'http://localhost/ComPool/HW1/',
            'http_errors' => false
        ]);
    }

    public function testPost_FailedLogin()
    {
        // Attempting to login with incorrect credentials
        $response = $this->client->request('POST', 'login.php', [
            'form_params' => [
                'userid' => 'wronguser', // Use 'userid' to match the field in the form
                'password' => 'wrongpass' // Incorrect password
            ]
        ]);

        // Expecting 401 Unauthorized as the login fails
        $this->assertEquals(401, $response->getStatusCode()); 
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->client = null;
    }
}
?>