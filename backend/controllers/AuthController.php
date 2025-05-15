<?php
require_once __DIR__ . './../models/User.php';
require_once __DIR__ . './../utils/helper.php';
require_once __DIR__ . './../utils/validators.php';

class AuthController
{
	/**
	 * Register a new user
	 */
	public static function register()
	{
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

			// Check if email already exists
			$existingUser = User::findByEmail($data['email']);
			if ($existingUser) {
				throw new Exception("Email already registered");
			}

			// Create user
			$user = User::create(
				$data['email'],
				$data['password'],
				$data['first_name'],
				$data['last_name'],
				$data['phone'] ?? null
			);

			// Start session
			self::startUserSession($user);

			// Return response without sensitive data
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
	public static function login()
	{
		try {
			$data = getJsonInput();

			// Validate input
			$required = ['email', 'password'];
			validateRequiredFields($data, $required);
			validateEmail($data['email']);

			// Authenticate user
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
	public static function logout()
	{
		try {
			// Destroy session
			session_unset();
			session_destroy();

			// Clear session cookie
			setcookie(session_name(), '', time() - 3600, '/');

			jsonResponse(['message' => 'Logout successful']);
		} catch (Exception $e) {
			jsonResponse(['error' => $e->getMessage()], 500);
		}
	}

	/**
	 * Get current user profile
	 */
	public static function profile()
	{
		try {
			authenticateUser();

			$user = User::findById($_SESSION['user_id']);
			jsonResponse(self::getUserResponse($user));
		} catch (Exception $e) {
			jsonResponse(['error' => $e->getMessage()], 401);
		}
	}

	/**
	 * Start a new session for the user
	 */
	private static function startUserSession($user)
	{
		// Regenerate session ID to prevent fixation
		session_regenerate_id(true);

		// Set session data
		$_SESSION['user_id'] = $user->id;
		$_SESSION['email'] = $user->email;
		$_SESSION['is_admin'] = $user->is_admin;
		$_SESSION['last_activity'] = time();
		$_SESSION['ip_address'] = $_SERVER['REMOTE_ADDR'];
		$_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
	}

	/**
	 * Get safe user data for responses
	 */
	private static function getUserResponse($user)
	{
		return [
			'id' => $user->id,
			'email' => $user->email,
			'first_name' => $user->first_name,
			'last_name' => $user->last_name,
			'phone' => $user->phone,
			'is_admin' => $user->is_admin,
			'created_at' => $user->created_at
		];
	}
}
