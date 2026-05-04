<?php
/**
 * ============================================
 * SERVICES PAGE (services.php)
 * ============================================
 * This page displays all services offered by the agency.
 * 
 * Data comes FROM DATABASE (services table).
 * Each service shows: icon, name, description, price.
 * 
 * Layout: 2-column grid
 * Hover effects: cards lift up with shadow
 * ============================================
 */

// ============================================
// STEP 1: Load database connection
// ============================================
require_once 'config/database.php';

// ============================================
// STEP 2: Fetch all services from database
// ============================================
try {
    // ORDER BY id = show them in the order they were created
    $stmt = $pdo->query("SELECT * FROM services ORDER BY id");
    
    // fetchAll() gets ALL rows as an array of associative arrays
    // Example: [['name'=>'Web Creation', 'description'=>'...', 'icon'=>'fas fa-code'], [...]]
    $services = $stmt->fetchAll();
    
} catch (PDOException $e) {
    // If database fails, set empty array
    // Page will show "no services" instead of crashing
    $services = [];
}

// ============================================
// STEP 3: Set page info and load header
// ============================================
$pageTitle = 'Atom | Our Services - Web Development, Branding, Social Media';
$currentPage = 'services.php';  // Highlights "Services" in navbar
include 'includes/header.php';
?>

<!-- ============================================
     SERVICES SECTION
     ============================================ -->
<section style="padding:140px 0 60px; background:#ffffff;">
    <!-- padding-top:140px = space for fixed navbar (70px) + breathing room -->
    
    <div class="container">
        
        <!-- Page heading -->
        <h1 style="text-align:center; margin-bottom:20px; color:#0b0c3d;">Our Services</h1>
        
        <!-- Subtitle (max-width:600px keeps it narrow and readable) -->
        <p style="text-align:center; max-width:600px; margin:0 auto 50px; color:#5a5d7a;">
            Complete digital solutions for your business
        </p>
        
        <!-- 
            2-column grid for service cards
            Each card has:
            - Left orange border (border-left:4px solid)
            - Hover effect (lifts up with shadow)
        -->
        <div style="display:grid; grid-template-columns:repeat(2,1fr); gap:30px;">
            
            <?php foreach($services as $service): ?>
                <!-- ============================================
                     INDIVIDUAL SERVICE CARD
                     ============================================ -->
                <div style="background:#f0f3ea; padding:40px; border-radius:20px; border-left:4px solid #f29f5b; transition:all 0.3s;"
                     onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 15px 35px rgba(11,12,61,0.1)';"
                     onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                    
                    <!-- 
                        Service icon (Font Awesome)
                        htmlspecialchars() = prevents XSS attacks
                        Example output: <i class="fas fa-code"></i>
                    -->
                    <i class="<?php echo htmlspecialchars($service['icon']); ?>" 
                       style="font-size:48px; color:#f29f5b; margin-bottom:20px;"></i>
                    
                    <!-- Service name -->
                    <h2 style="color:#0b0c3d;">
                        <?php echo htmlspecialchars($service['name']); ?>
                    </h2>
                    
                    <!-- Service description -->
                    <p style="margin:15px 0; color:#5a5d7a;">
                        <?php echo htmlspecialchars($service['description']); ?>
                    </p>
                    
                    <!-- 
                        Service price
                        Dark green color (#1a2e1b), bold, larger text
                    -->
                    <div style="font-weight:700; color:#1a2e1b; font-size:18px;">
                        <?php echo htmlspecialchars($service['price']); ?>
                    </div>
                </div>
            <?php endforeach; ?>
            
        </div>
    </div>
</section>

<!-- ============================================
     LOAD FOOTER
     ============================================ -->
<?php include 'includes/footer.php'; ?>