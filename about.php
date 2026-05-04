<?php
/**
 * ============================================
 * ABOUT PAGE (about.php)
 * ============================================
 * This page displays information about the company.
 * 
 * Sections:
 *   1. "Who are we?" - Company description with stats
 *   2. "Our Team" - Team members with roles and descriptions
 * 
 * Note: Team members are HARDCODED (not from database).
 * ============================================
 */

// ============================================
// STEP 1: Load database connection
// ============================================
// Even though this page doesn't fetch from database,
// we include it to have $pdo available if needed
// and to start the session
require_once 'config/database.php';

// ============================================
// STEP 2: Set page info and load header
// ============================================
$pageTitle = 'Atom | About Us - Digital Agency in Tunisia';
$currentPage = 'about.php';  // Tells navbar this is the active page
include 'includes/header.php';
?>

<!-- ============================================
     SECTION 1: WHO ARE WE?
     ============================================ -->
<section style="padding:140px 0 60px; background:#ffffff;">
    <!-- padding-top:140px accounts for the fixed navbar (70px) + extra space -->
    
    <div class="container">
        
        <!-- 2-column layout: text on left, logo/badge on right -->
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:50px; align-items:center;">
            
            <!-- ============================================
                 LEFT COLUMN: Text content
                 ============================================ -->
            <div>
                <!-- Main heading -->
                <h1 style="font-size:42px; margin-bottom:20px; color:#0b0c3d;">Who are we?</h1>
                
                <!-- Company description - paragraph 1 -->
                <p style="font-size:18px; line-height:1.8; margin-bottom:20px; color:#5a5d7a;">
                    Atom is a digital agency based in Tunisia, specializing in creating tailor-made 
                    digital solutions for hotels, restaurants, and entertainment venues.
                </p>
                
                <!-- Company description - paragraph 2 -->
                <p style="line-height:1.8; margin-bottom:20px; color:#5a5d7a;">
                    Our mission is to transform your digital presence into a true growth lever. 
                    We combine creativity, technical expertise, and strategy to deliver measurable results.
                </p>
                
                <!-- Key stats with checkmark icons -->
                <div style="margin-top:30px;">
                    <!-- Stat 1: Projects -->
                    <div style="margin-bottom:15px;">
                        <i class="fas fa-check-circle" style="color:#f29f5b; margin-right:10px;"></i>
                        <strong style="color:#0b0c3d;">+50 projects completed</strong>
                    </div>
                    
                    <!-- Stat 2: Sectors -->
                    <div style="margin-bottom:15px;">
                        <i class="fas fa-check-circle" style="color:#f29f5b; margin-right:10px;"></i>
                        <strong style="color:#0b0c3d;">+10 business sectors</strong>
                    </div>
                    
                    <!-- Stat 3: Support -->
                    <div style="margin-bottom:15px;">
                        <i class="fas fa-check-circle" style="color:#f29f5b; margin-right:10px;"></i>
                        <strong style="color:#0b0c3d;">24/7 support</strong>
                    </div>
                </div>
            </div>
            
            <!-- ============================================
                 RIGHT COLUMN: Decorative logo card
                 ============================================ -->
            <div style="background:linear-gradient(135deg, #0b0c3d, #1a2e1b); border-radius:30px; padding:60px; text-align:center; color:#ffffff; position:relative; overflow:hidden;">
                
                <!-- 
                    Decorative circle (top-right corner)
                    position:absolute places it relative to the parent
                    top:-30px; right:-30px makes it overflow the corners slightly
                -->
                <div style="position:absolute; top:-30px; right:-30px; width:120px; height:120px; background:rgba(242,159,91,0.2); border-radius:50%;"></div>
                
                <!-- 
                    Atom icon from Font Awesome
                    fa-atom shows an atomic symbol (matches the brand name)
                -->
                <i class="fas fa-atom" style="font-size:80px; margin-bottom:20px; color:#f29f5b;"></i>
                
                <!-- Brand name in large text -->
                <h2 style="color:#ffffff;">atom</h2>
                
                <!-- Tagline -->
                <p style="opacity:0.9;">The atom of your digital success</p>
            </div>
        </div>
    </div>
</section>

<!-- ============================================
     SECTION 2: OUR TEAM
     ============================================ -->
<section style="padding:60px 0; background:#f0f3ea;">
    <div class="container">
        
        <!-- Section heading -->
        <h2 style="text-align:center; margin-bottom:20px; font-size:36px; color:#0b0c3d;">Our Team</h2>
        <p style="text-align:center; color:#5a5d7a; margin-bottom:50px;">Meet the passionate experts behind Atom's success</p>
        
        <!-- 3-column grid for team members -->
        <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:30px;">
            
            <?php
            // ============================================
            // HARDCODED TEAM MEMBERS
            // ============================================
            // These are NOT from the database.
            // Each team member has: name, role, description, icon
            // The icons are Font Awesome classes
            // ============================================
            $team = [
                [
                    'name' => 'Mohamed Korbi',
                    'role' => 'Founder & CEO',
                    'desc' => '10+ years in digital marketing',
                    'icon' => 'fa-user-tie'      // Business person icon
                ],
                [
                    'name' => 'Sarah Ben Ali',
                    'role' => 'Creative Director',
                    'desc' => 'Award-winning designer',
                    'icon' => 'fa-paint-brush'    // Paint brush icon
                ],
                [
                    'name' => 'Karim Mansour',
                    'role' => 'Lead Developer',
                    'desc' => 'Full-stack expert',
                    'icon' => 'fa-laptop-code'    // Laptop with code icon
                ],
            ];
            
            // Loop through each team member and create a card
            foreach($team as $member): 
            ?>
                <!-- Individual team member card -->
                <div style="text-align:center; background:#ffffff; padding:40px 30px; border-radius:20px; box-shadow:0 5px 20px rgba(11,12,61,0.05); border-top:3px solid #f29f5b;">
                    
                    <!-- 
                        Circular avatar container
                        Uses a gradient background with centered icon
                        Acts as a profile picture placeholder
                    -->
                    <div style="width:130px; height:130px; margin:0 auto 20px; background:linear-gradient(135deg, #0b0c3d, #1a2e1b); border-radius:50%; display:flex; align-items:center; justify-content:center;">
                        <!-- 
                            Font Awesome icon (different for each team member)
                            fa-user-tie, fa-paint-brush, or fa-laptop-code
                        -->
                        <i class="fas <?php echo $member['icon']; ?>" style="font-size:60px; color:#f29f5b;"></i>
                    </div>
                    
                    <!-- Team member name -->
                    <h3 style="font-size:24px; color:#0b0c3d;"><?php echo $member['name']; ?></h3>
                    
                    <!-- Team member role (peach colored) -->
                    <p style="color:#f29f5b; font-weight:600;"><?php echo $member['role']; ?></p>
                    
                    <!-- Team member description -->
                    <p style="color:#5a5d7a; font-size:14px;"><?php echo $member['desc']; ?></p>
                </div>
            <?php endforeach; ?>
            
        </div>
    </div>
</section>

<!-- ============================================
     LOAD FOOTER
     ============================================ -->
<?php include 'includes/footer.php'; ?>