<?php
/**
 * ============================================
 * CONTACT PAGE (contact.php)
 * ============================================
 * This page displays a contact form and processes submissions.
 * 
 * Features:
 *   - Form with name, email, phone, message fields
 *   - Server-side validation (required fields, email format)
 *   - Saves messages to database (contacts table)
 *   - Sends automatic "Thank you" email reply
 *   - Shows success/error messages to the user
 *   - Displays company contact info and Google Maps
 * 
 * Flow:
 *   1. User visits page (GET request) → Shows empty form
 *   2. User fills form and clicks Send (POST request)
 *   3. PHP validates input
 *   4. Saves to database
 *   5. Sends auto-reply email
 *   6. Shows success message
 * ============================================
 */

// ============================================
// STEP 1: Load required files
// ============================================
require_once 'config/database.php';      // Database connection ($pdo) and session start
require_once 'config/email_helper.php';   // Function: sendAutoReply($email, $name)

// ============================================
// STEP 2: Initialize variables
// ============================================
$success = false;  // Becomes true when form is submitted successfully
$error = '';       // Stores error messages to show to user

// ============================================
// STEP 3: Check if form was submitted
// ============================================
// $_SERVER['REQUEST_METHOD'] tells us HOW the page was accessed
// 'POST' means the user clicked the submit button
// 'GET' means they just typed the URL or clicked a link
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // ============================================
    // STEP 4: Get and clean form data
    // ============================================
    // $_POST contains all submitted form data
    // ?? ''  = if the field doesn't exist, use empty string (safety)
    // trim() = removes spaces from beginning and end
    
    $name    = trim($_POST['name'] ?? '');       // Sender's full name
    $email   = trim($_POST['email'] ?? '');       // Sender's email address
    $phone   = trim($_POST['phone'] ?? '');       // Sender's phone (optional)
    $message = trim($_POST['message'] ?? '');     // The actual message content
    
    // ============================================
    // STEP 5: Validate required fields
    // ============================================
    // In PHP, an empty string is "falsy" (evaluates to false)
    // && means ALL conditions must be true
    // Phone is NOT checked because it's optional
    
    if ($name && $email && $message) {
        
        // ============================================
        // STEP 6: Validate email format
        // ============================================
        // filter_var() is PHP's built-in validation function
        // FILTER_VALIDATE_EMAIL checks if email is properly formatted
        // Returns the email if valid, false if invalid
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Email is NOT valid
            $error = "Please enter a valid email address.";
            
        } else {
            // Email IS valid — proceed with saving
            
            // ============================================
            // STEP 7: Save message to database
            // ============================================
            try {
                // Prepare SQL statement with PLACEHOLDERS (?)
                // This PREVENTS SQL INJECTION because the data
                // is sent separately from the SQL code
                $stmt = $pdo->prepare(
                    "INSERT INTO contacts (name, email, phone, message) 
                     VALUES (?, ?, ?, ?)"
                );
                
                // Execute with actual data
                // The data is bound to the ? placeholders IN ORDER:
                //   First ?  → $name
                //   Second ? → $email
                //   Third ?  → $phone
                //   Fourth ? → $message
                $stmt->execute([$name, $email, $phone, $message]);
                
                // ============================================
                // STEP 8: Send auto-reply email
                // ============================================
                // The @ symbol suppresses any errors
                // If email fails (no internet, server config),
                // the page won't crash — message is already saved
                // sendAutoReply() is defined in email_helper.php
                @sendAutoReply($email, $name);
                
                // Mark as successful (shows green message to user)
                $success = true;
                
            } catch (PDOException $e) {
                // Database error (server down, table missing, etc.)
                // Show user-friendly message (not the technical error)
                $error = "Database error. Please try again.";
            }
        }
        
    } else {
        // Required fields are missing
        $error = "Please fill in all required fields.";
    }
}

// ============================================
// STEP 9: Set page info and load header
// ============================================
$pageTitle = 'Atom | Contact - Get a Free Quote';
$currentPage = 'contact.php';  // Highlights "Contact" in navbar
include 'includes/header.php';
?>

<!-- ============================================
     CONTACT SECTION
     ============================================ -->
<section style="padding:140px 0 60px; background:#ffffff;">
    <!-- padding-top:140px = space for fixed navbar + breathing room -->
    
    <div class="container">
        
        <!-- Page heading -->
        <h1 style="text-align:center; margin-bottom:20px; color:#0b0c3d;">Contact Us</h1>
        
        <!-- Subtitle -->
        <p style="text-align:center; max-width:600px; margin:0 auto 50px; color:#5a5d7a;">
            Let's talk about your project. Fill out the form and we'll get back to you within 24 hours.
        </p>
        
        <!-- 
            2-column layout:
            Left  = Company info (dark card)
            Right = Contact form
        -->
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:50px;">
            
            <!-- ============================================
                 LEFT COLUMN: Company Information
                 ============================================ -->
            <div style="background:#0b0c3d; padding:40px; border-radius:20px; color:#ffffff; position:relative; overflow:hidden;">
                
                <!-- 
                    Decorative circle (bottom-right corner)
                    Adds visual interest to the card
                -->
                <div style="position:absolute; bottom:-20px; right:-20px; width:100px; height:100px; background:rgba(242,159,91,0.15); border-radius:50%;"></div>
                
                <!-- Section title -->
                <h3 style="color:#f29f5b; margin-bottom:25px; font-size:22px;">Information</h3>
                
                <!-- 
                    Phone number
                    fa-phone = phone icon from Font Awesome
                    tel: link would be added for mobile click-to-call
                -->
                <p style="margin-bottom:15px;">
                    <i class="fas fa-phone" style="color:#f29f5b; margin-right:10px;"></i> 
                    +216 22 431 379
                </p>
                
                <!-- 
                    Email address
                    fa-envelope = envelope icon
                -->
                <p style="margin-bottom:15px;">
                    <i class="fas fa-envelope" style="color:#f29f5b; margin-right:10px;"></i> 
                    atomedigital@gmail.com
                </p>
                
                <!-- 
                    Physical address
                    fa-map-marker-alt = location pin icon
                -->
                <p style="margin-bottom:15px;">
                    <i class="fas fa-map-marker-alt" style="color:#f29f5b; margin-right:10px;"></i> 
                    1st Floor El Hamd Residence, Sahline 5012
                </p>
                
                <!-- 
                    Business hours
                    Separated by a subtle border line
                -->
                <div style="margin-top:30px; padding-top:25px; border-top:1px solid rgba(255,255,255,0.1);">
                    <h4 style="color:#f29f5b; margin-bottom:10px;">Hours</h4>
                    <p style="opacity:0.8;">Mon - Fri: 9am - 6pm</p>
                    <p style="opacity:0.8;">Saturday: 10am - 2pm</p>
                </div>
            </div>
            
            <!-- ============================================
                 RIGHT COLUMN: Contact Form
                 ============================================ -->
            <div>
                
                <!-- ============================================
                     SUCCESS MESSAGE
                     ============================================ -->
                <?php if ($success): ?>
                    <div style="background:#d4edda; color:#155724; padding:15px; border-radius:10px; margin-bottom:20px; border-left:4px solid #28a745;">
                        ✅ Message sent! We'll contact you soon.
                    </div>
                <?php endif; ?>
                
                <!-- ============================================
                     ERROR MESSAGE
                     ============================================ -->
                <?php if ($error): ?>
                    <div style="background:#fee2e2; color:#dc2626; padding:15px; border-radius:10px; margin-bottom:20px; border-left:4px solid #dc2626;">
                        ❌ <?php echo htmlspecialchars($error); ?>
                        <!-- htmlspecialchars() converts special characters to HTML entities
                             Example: <script> becomes &lt;script&gt;
                             This prevents Cross-Site Scripting (XSS) attacks -->
                    </div>
                <?php endif; ?>
                
                <!-- ============================================
                     CONTACT FORM
                     ============================================ -->
                <!-- 
                    method="POST" = data sent in request body (not visible in URL)
                    No action attribute = submits to the SAME page (contact.php)
                -->
                <form method="POST">
                    
                    <!-- 
                        NAME FIELD
                        required = browser won't submit if empty
                        onfocus = when user clicks inside → peach border
                        onblur = when user clicks away → gray border
                    -->
                    <input type="text" name="name" placeholder="Your name *" required 
                           style="width:100%; padding:14px 16px; margin-bottom:20px; border:2px solid #e0e2d8; border-radius:12px; font-size:15px; outline:none;"
                           onfocus="this.style.borderColor='#f29f5b'" 
                           onblur="this.style.borderColor='#e0e2d8'">
                    
                    <!-- 
                        EMAIL FIELD
                        type="email" = browser validates email format
                    -->
                    <input type="email" name="email" placeholder="Your email *" required 
                           style="width:100%; padding:14px 16px; margin-bottom:20px; border:2px solid #e0e2d8; border-radius:12px; font-size:15px; outline:none;"
                           onfocus="this.style.borderColor='#f29f5b'" 
                           onblur="this.style.borderColor='#e0e2d8'">
                    
                    <!-- 
                        PHONE FIELD (Optional - no asterisk, no "required")
                        type="tel" = brings up number keyboard on mobile
                    -->
                    <input type="tel" name="phone" placeholder="Your phone" 
                           style="width:100%; padding:14px 16px; margin-bottom:20px; border:2px solid #e0e2d8; border-radius:12px; font-size:15px; outline:none;"
                           onfocus="this.style.borderColor='#f29f5b'" 
                           onblur="this.style.borderColor='#e0e2d8'">
                    
                    <!-- 
                        MESSAGE FIELD (Textarea)
                        rows="5" = visible height of 5 lines
                        required = must be filled
                        resize:vertical = user can only resize up/down, not sideways
                    -->
                    <textarea name="message" rows="5" placeholder="Your message *" required 
                              style="width:100%; padding:14px 16px; margin-bottom:20px; border:2px solid #e0e2d8; border-radius:12px; font-size:15px; outline:none; resize:vertical; font-family:inherit;"
                              onfocus="this.style.borderColor='#f29f5b'" 
                              onblur="this.style.borderColor='#e0e2d8'"></textarea>
                    
                    <!-- 
                        SUBMIT BUTTON
                        type="submit" = submits the form
                        Full width, rounded corners, peach color with glow shadow
                    -->
                    <button type="submit" 
                            style="width:100%; padding:16px; background:#f29f5b; color:#0b0c3d; border:none; border-radius:50px; font-weight:700; font-size:16px; cursor:pointer; transition:all 0.3s; box-shadow:0 4px 15px rgba(242,159,91,0.3);">
                        Send message
                    </button>
                </form>
            </div>
        </div>
        
        <!-- ============================================
             GOOGLE MAPS EMBED
             ============================================ -->
        <div style="margin-top:40px; border-radius:20px; overflow:hidden; box-shadow:0 10px 30px rgba(11,12,61,0.1);">
            <!-- 
                iframe embeds Google Maps showing Sahline, Monastir
                width="100%" = responsive (fills container)
                height="300" = fixed height in pixels
                allowfullscreen = allows fullscreen mode
                loading="lazy" = only loads when scrolled into view (performance)
            -->
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d25902.444248607285!2d10.6998!3d35.7567!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x12fd8b8c5c5c5c5c%3A0x5c5c5c5c5c5c5c5c!2sSahline%2C%20Monastir!5e0!3m2!1sen!2stn!4v1700000000000!5m2!1sen!2stn" 
                    width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div>
    </div>
</section>

<!-- ============================================
     LOAD FOOTER
     ============================================ -->
<?php include 'includes/footer.php'; ?>