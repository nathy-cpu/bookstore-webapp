<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../utils/helper.php';
require_once __DIR__ . '/../utils/validators.php';
require_once __DIR__ . '/../utils/auth.php';

class AuthController
{
	/**
	 * Register a new user
	 */
	public static function register(): void {
		try {
			$data = getJsonInput();
			
			// Validate input
			$required = ['email', 'password', 'first_name', 'last_name'];
			validateRequiredFields($data, $required);

			// Additional validation
			validateEmail($data['email']);
			validatePassword($data['password']);
			validateName($data['first_name']);
			validateName($data['last_name']);

			if (isset($data['phone']) && !empty($data['phone'])) {
				validatePhone($data['phone']);
			}

			// Check if email exists
			if (User::findByEmail($data['email'])) {
				throw new Exception("Email already registered");
			}

			// Create user (now returns an object)
			$user = User::create(
				$data['email'],
				$data['password'],
				$data['first_name'],
				$data['last_name'],
				$data['phone'] ?? null
			);

			// Start session
			self::startUserSession($user);

			// Return response
			jsonResponse([
				'message' => 'Registration successful',
				'user' => self::getUserResponse($user)
			], 201);
		} catch (Exception $e) {
			jsonResponse(['error' => $e->getMessage()], 400);
		}
	}

	/**
	 * Login an existing user
	 */
	public static function login(): void {
		try {
			$data = getJsonInput();
			
			// Validate input
			$required = ['email', 'password'];
			validateRequiredFields($data, $required);
			validateEmail($data['email']);

			// Authenticate user (now returns an object)
			$user = User::authenticate($data['email'], $data['password']);

			// Start session
			self::startUserSession($user);

			// Return response
			jsonResponse([
				'message' => 'Login successful',
				'user' => self::getUserResponse($user)
			]);
		} catch (Exception $e) {
			jsonResponse(['error' => $e->getMessage()], 401);
		}
	}

	/**
	 * Logout the current user
	 */
	public static function logout(): void {
		try {
			// Check if session needs to be started
			if (session_status() === PHP_SESSION_NONE) {
				session_start([
					'cookie_httponly' => true,
					'cookie_secure' => true,
					'use_strict_mode' => true
				]);
			}

			// Clear session data
			$_SESSION = [];

			// Destroy the session
			if (session_id() !== '') {
				session_destroy();
			}

			// Expire the session cookie
			setcookie(session_name(), '', time() - 3600, '/');

			jsonResponse(['message' => 'Logout successful']);
		} catch (Exception $e) {
			jsonResponse(['error' => $e->getMessage()], 500);
		}
	}

	/**
	 * Get current user profile
	 */
	public static function profile(): void {
		try {
			// Debug: Log incoming session ID
			error_log("Incoming Session ID: " . ($_COOKIE[session_name()] ?? 'NULL'));
			
			// Start session if not active
			if (session_status() !== PHP_SESSION_ACTIVE) {
				session_start([
					'cookie_httponly' => true,
					'use_strict_mode' => true
				]);
			}
			
			// Debug: Log session data
			error_log("Session Data: " . print_r($_SESSION, true));
			
			if (empty($_SESSION['user_id'])) {
				throw new Exception("No active session");
			}
			
			$user = User::findById($_SESSION['user_id']);
			jsonResponse(self::getUserResponse($user));
			
		} catch (Exception $e) {
			error_log("Profile Error: " . $e->getMessage());
			jsonResponse(['error' => $e->getMessage()], 401);
		}
	}

	/**
	 * Start a new session for the user
	 * @param mixed $user
 	 */
	private static function startUserSession($user): void {
		// Ensure we have a valid user object
		if (!is_object($user)) {
			throw new InvalidArgumentException('User must be an object');
		}

		// Start session if not already started
		if (session_status() === PHP_SESSION_NONE) {
			session_start([
				'cookie_httponly' => true,
				'cookie_secure' => true,
				'use_strict_mode' => true
			]);
		}

		// Regenerate session ID
		session_regenerate_id(true);

		// Set session data
		$_SESSION = [
			'user_id' => $user->id,
			'email' => $user->email,
			'is_admin' => $user->is_admin ?? false,
			'last_activity' => time(),
			'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1',
			'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'CLI'
		];
	}
    /**
     * @param mixed $user
     */
	private static function getUserResponse($user): array {
		// Null check and type verification
		if (!$user || !is_object($user)) {
			return [
				'id' => null,
				'email' => null,
				'first_name' => null,
				'last_name' => null,
				'phone' => null,
				'is_admin' => null,
				'created_at' => null
			];
		}

		return [
			'id' => $user->id ?? null,
			'email' => $user->email ?? null,
			'first_name' => $user->first_name ?? null,
			'last_name' => $user->last_name ?? null,
			'phone' => $user->phone ?? null,
			'is_admin' => isset($user->is_admin) ? (bool)$user->is_admin : null,
			'created_at' => $user->created_at ?? null
		];
	}
}
