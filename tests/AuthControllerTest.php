<?php
use PHPUnit\Framework\TestCase;

class AuthControllerTest extends TestCase
{
    private static $client;
    private static $baseUrl = 'http://localhost:8000';

	protected function setUp(): void {
		// Set the test request URI before each test
		$_SERVER['TEST_REQUEST_URI'] = '/api/register';
		$_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'] = 'POST';
		
		require_once __DIR__ . '/TestRequest.php';
		TestRequest::capture();
		
		// Clear cookies and session
		$cookieJar = self::$client->getConfig('cookies');
		$cookieJar->clear();
		session_unset();
	}

	public static function setUpBeforeClass(): void
    {
        self::$client = new GuzzleHttp\Client([
            'base_uri' => self::$baseUrl,
            'http_errors' => false,
            'cookies' => true,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ]
        ]);
        TestDatabase::reset();
    }

    protected function tearDown(): void
    {
        // Clear cookies between tests
        $cookieJar = self::$client->getConfig('cookies');
        $cookieJar->clear();
    }

    public function testSuccessfulRegistration()
    {
        $response = self::$client->post('/api/register', [
            'json' => [
                'email' => 'test@example.com',
                'password' => 'TestPass123!',
                'first_name' => 'Test',
                'last_name' => 'User'
			],
			// Force method override
			'headers' => [
				'X-HTTP-Method-Override' => 'POST'
			]
        ]);

        $this->assertEquals(201, $response->getStatusCode());
        $data = json_decode($response->getBody(), true);

        $this->assertArrayHasKey('user', $data);
        $this->assertEquals('test@example.com', $data['user']['email']);
        $this->assertFalse($data['user']['is_admin']);
    }

    public function testDuplicateRegistration()
    {
        // First registration (should succeed)
        self::$client->post('/api/register', [
            'json' => [
                'email' => 'duplicate@test.com',
                'password' => 'TestPass123!',
                'first_name' => 'Duplicate',
                'last_name' => 'User'
            ]
        ]);

        // Second registration with same email (should fail)
        $response = self::$client->post('/api/register', [
            'json' => [
                'email' => 'duplicate@test.com',
                'password' => 'TestPass123!',
                'first_name' => 'Duplicate',
                'last_name' => 'User'
            ]
        ]);

        $this->assertEquals(400, $response->getStatusCode());
        $data = json_decode($response->getBody(), true);
        $this->assertStringContainsString('already registered', $data['error']);
    }

    public function testSuccessfulLogin()
    {
        // First register a user
        self::$client->post('/api/register', [
            'json' => [
                'email' => 'login@test.com',
                'password' => 'LoginPass123!',
                'first_name' => 'Login',
                'last_name' => 'User'
            ]
        ]);

        // Test login
        $response = self::$client->post('/api/login', [
            'json' => [
                'email' => 'login@test.com',
                'password' => 'LoginPass123!'
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getBody(), true);

        $this->assertArrayHasKey('user', $data);
        $this->assertEquals('login@test.com', $data['user']['email']);

        // Verify automatic session handling by accessing profile
        $profileResponse = self::$client->get('/profile');
        $this->assertEquals(200, $profileResponse->getStatusCode());
    }

    public function testFailedLogin()
    {
        $response = self::$client->post('/api/login', [
            'json' => [
                'email' => 'nonexistent@test.com',
                'password' => 'WrongPass123!'
            ]
        ]);

        $this->assertEquals(401, $response->getStatusCode());
        $data = json_decode($response->getBody(), true);
        $this->assertStringContainsString('Invalid email or password', $data['error']);
    }

    public function testAdminLogin()
    {
        $response = self::$client->post('/api/login', [
            'json' => [
                'email' => 'admin@test.com',
                'password' => 'admin123'
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getBody(), true);
        $this->assertTrue($data['user']['is_admin']);
    }

    public function testProfileAccess()
    {
        // Register and login a user
        self::$client->post('/api/register', [
            'json' => [
                'email' => 'profile@test.com',
                'password' => 'ProfilePass123!',
                'first_name' => 'Profile',
                'last_name' => 'User'
            ]
        ]);

        self::$client->post('/api/login', [
            'json' => [
                'email' => 'profile@test.com',
                'password' => 'ProfilePass123!'
            ]
        ]);

        // Profile access with automatic session cookie
        $response = self::$client->get('/api/profile');
        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getBody(), true);
        $this->assertEquals('profile@test.com', $data['email']);
    }

    public function testUnauthorizedProfileAccess()
    {
        $response = self::$client->get('/api/profile');
        $this->assertEquals(401, $response->getStatusCode());
    }

    public function testLogout()
    {
        // Register and login a user
        self::$client->post('/api/register', [
            'json' => [
                'email' => 'logout@test.com',
                'password' => 'LogoutPass123!',
                'first_name' => 'Logout',
                'last_name' => 'User'
            ]
        ]);

        self::$client->post('/api/login', [
            'json' => [
                'email' => 'logout@test.com',
                'password' => 'LogoutPass123!'
            ]
        ]);

        // Test logout
        $logoutResponse = self::$client->post('/api/logout');
        $this->assertEquals(200, $logoutResponse->getStatusCode());

        // Verify session is invalidated
        $profileResponse = self::$client->get('/api/profile');
        $this->assertEquals(401, $profileResponse->getStatusCode());
    }
}
