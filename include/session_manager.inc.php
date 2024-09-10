<?php

function redirectToLogin(string $message = ''): void
{
    header("Location: login.php?message=" . urlencode($message));
    exit();
}

function handleSessionExpiration(): void
{
    // Check if user session exists and if the session is invalid due to mismatch
    if (!isset($_COOKIE[session_name()])) {
        // Session or cookie expired or mismatch - clear session
        session_unset();
        session_destroy();
        setcookie(session_name(), '', time() - 3600); // Expire the session cookie
        // Redirect to login page
        redirectToLogin('Session has expired. Please log in again.');
    }
}

handleSessionExpiration();