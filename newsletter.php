<?php
/**
 * ============================================
 * NEWSLETTER HANDLER (newsletter.php)
 * ============================================
 * This file handles AJAX newsletter subscription.
 * 
 * It is NOT a page users visit — it's an API endpoint
 * that the footer form submits to via JavaScript (fetch).
 * 
 * What it does:
 *   1. Receives email from AJAX POST request
 *   2. Validates the email format
 *   3. Creates the newsletter table if it doesn't exist
 *   4. Inserts the email (ignoring duplicates)
 *   5. Returns JSON response (success or error)
 * 
 * IMPORTANT: This file returns JSON, not HTML!
 * It's called by the JavaScript in js/script.js
 * ============================================
 */

// ============================================
// STEP 1: Load database connection
// ============================================
require_once 'config/database.php';

// ============================================
// STEP 2: Only process POST requests
// ============================================
// If someone visits this page directly (GET), do nothing
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // ============================================
    // STEP 3: Get and validate email
    // ============================================
    // filter_var with FILTER_VALIDATE_EMAIL:
    //   - Returns the email if valid
    //   - Returns false if invalid
    $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
    
    // Check if email is valid (not false)
    if ($email) {
        
        try {
            // ============================================
            // STEP 4: Create table if it doesn't exist
            // ============================================
            // This is defensive programming — ensures the table
            // exists even if the SQL setup script wasn't run
            $pdo->exec(
                "CREATE TABLE IF NOT EXISTS newsletter (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    email VARCHAR(100) UNIQUE NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )"
            );
            
            // ============================================
            // STEP 5: Insert email (ignore duplicates)
            // ============================================
            // INSERT IGNORE = if email already exists, do nothing
            // (no error, just skip the insert)
            // This prevents duplicate subscribers
            $stmt = $pdo->prepare("INSERT IGNORE INTO newsletter (email) VALUES (?)");
            $stmt->execute([$email]);
            
            // ============================================
            // STEP 6: Return success response
            // ============================================
            // json_encode() converts PHP array to JSON string
            // The JavaScript in the footer will parse this
            echo json_encode([
                'success' => true,
                'message' => 'Subscribed successfully!'
            ]);
            
        } catch (PDOException $e) {
            // Database error — return error response
            echo json_encode([
                'success' => false,
                'message' => 'Database error'
            ]);
        }
        
    } else {
        // Email is invalid — return error response
        echo json_encode([
            'success' => false,
            'message' => 'Invalid email'
        ]);
    }
    
    // ============================================
    // STEP 7: Stop execution
    // ============================================
    // exit() prevents any additional output after the JSON
    // This is important for AJAX responses
    exit;
}

/**
 * ============================================
 * HOW THIS WORKS WITH THE FRONTEND:
 * ============================================
 * 
 * 1. User types email in footer and clicks submit
 * 
 * 2. JavaScript in js/script.js intercepts the form:
 *    fetch('newsletter.php', {
 *        method: 'POST',
 *        body: 'email=user@example.com'
 *    })
 * 
 * 3. This file processes the request and returns JSON:
 *    {"success": true, "message": "Subscribed successfully!"}
 * 
 * 4. JavaScript reads the response and shows a message
 *    to the user in the footer (green checkmark or red error)
 * 
 * 5. No page reload needed! (This is called AJAX)
 * ============================================
 */