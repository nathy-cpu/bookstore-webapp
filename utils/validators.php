<?php
function validateEmail($email): void
{
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		throw new Exception("Invalid email format");
	}
}

function validatePassword($password): void
{
	if (strlen($password) < 8) {
		throw new Exception("Password must be at least 8 characters");
	}
}

function validateName($name): void
{
	if (!preg_match('/^[a-zA-Z \-\']{2,50}$/', $name)) {
		throw new Exception("Invalid name format");
	}
}

function validatePhone($phone): void
{
	if (!preg_match('/^[\d\s\+\(\)\-]{10,20}$/', $phone)) {
		throw new Exception("Invalid phone number format");
	}
}
