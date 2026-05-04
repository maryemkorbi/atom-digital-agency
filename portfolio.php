<?php
/**
 * ============================================
 * PORTFOLIO PAGE (portfolio.php)
 * ============================================
 * This page displays ALL completed projects.
 * Unlike the homepage (which shows only 6), this
 * shows EVERYTHING from the portfolio table.
 * 
 * Data comes FROM DATABASE (portfolio table).
 * Ordered by completion_date (most recent first).
 * 
 * Layout: 3-column grid with hover animations
 * ============================================
 */

// ============================================
// STEP 1: Load database connection
// ============================================
require_once 'config/database.php';

// ============================================
// STEP 2: Fetch all portfolio projects
// ============================================
try {
    // ORDER BY completion_date DESC = newest projects first
    // (Different from homepage which uses "ORDER BY id DESC LIMIT 6")
    $stmt = $pdo->query("SELECT * FROM portfolio ORDER BY completion_date DESC");
    
    // Get ALL rows (no LIMIT here)
    $portfolio = $stmt->fetchAll();
    
} catch (PDOException $e) {
    // If database fails, use empty array
    $portfolio = [];
}

// ============================================
// STEP 3: Set page info and load header
// ============================================
$pageTitle = 'Atom | Portfolio - Our Work & Case Studies';
$currentPage = 'portfolio.php';  // Highlights "Portfolio" in navbar
include 'includes/header.php';
?>

<!-- ============================================
     PORTFOLIO SECTION
     ============================================ -->
<section style="padding:140px 0 60px; background:#ffffff;">
    <!-- padding-top:140px = space for fixed navbar + breathing room -->
    
    <div class="container">
        
        <!-- Page heading -->
        <h1 style="text-align:center; margin-bottom:20px; color:#0b0c3d;">Our Work</h1>
        
        <!-- Subtitle (centered, max-width:600px for readability) -->
        <p style="text-align:center; max-width:600px; margin:0 auto 50px; color:#5a5d7a;">
            Discover some of our most recent projects
        </p>
        
        <!-- 
            3-column grid for project cards
            Each card shows:
            - Image (or placeholder icon)
            - Project title
            - Category (peach colored)
            - Description
            - Client name
        -->
        <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:30px;">
            
            <?php foreach($portfolio as $project): ?>
                <!-- ============================================
                     INDIVIDUAL PROJECT CARD
                     ============================================ -->
                <div style="background:#ffffff; border-radius:15px; overflow:hidden; box-shadow:0 4px 15px rgba(11,12,61,0.08); transition:all 0.3s; border:1px solid #e0e2d8;"
                     onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='0 15px 35px rgba(11,12,61,0.12)';"
                     onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(11,12,61,0.08)';">
                    
                    <!-- ============================================
                         IMAGE CONTAINER
                         ============================================ -->
                    <div style="background:linear-gradient(135deg, #0b0c3d, #1a2e1b); height:200px; display:flex; align-items:center; justify-content:center;">
                        
                        <?php if (!empty($project['image_url'])): ?>
                            <!-- 
                                If project has an image, show it
                                object-fit:cover = fills the container without stretching
                                (image may be cropped to fit)
                            -->
                            <img src="<?php echo htmlspecialchars($project['image_url']); ?>" 
                                 alt="<?php echo htmlspecialchars($project['title']); ?>" 
                                 style="width:100%; height:100%; object-fit:cover;">
                        <?php else: ?>
                            <!-- 
                                If no image, show a placeholder icon
                                opacity:0.5 = semi-transparent
                            -->
                            <i class="fas fa-image" style="font-size:48px; color:#ffffff; opacity:0.5;"></i>
                        <?php endif; ?>
                        
                    </div>
                    
                    <!-- ============================================
                         CARD CONTENT (Text below image)
                         ============================================ -->
                    <div style="padding:20px;">
                        
                        <!-- Project title -->
                        <h3 style="color:#0b0c3d;">
                            <?php echo htmlspecialchars($project['title']); ?>
                        </h3>
                        
                        <!-- 
                            Project category
                            Peach color, bold, small margin
                        -->
                        <p style="color:#f29f5b; margin:5px 0; font-weight:600;">
                            <?php echo htmlspecialchars($project['category']); ?>
                        </p>
                        
                        <!-- Project description -->
                        <p style="color:#5a5d7a; font-size:14px;">
                            <?php echo htmlspecialchars($project['description']); ?>
                        </p>
                        
                        <!-- 
                            Client name (with user icon)
                            Only shown if client_name is not empty
                        -->
                        <p style="margin-top:15px; font-size:14px; color:#888;">
                            <i class="fas fa-user" style="margin-right:5px;"></i> 
                            Client: <?php echo htmlspecialchars($project['client_name']); ?>
                        </p>
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