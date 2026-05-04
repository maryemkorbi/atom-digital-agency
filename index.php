<?php
/**
 * ============================================
 * HOMEPAGE (index.php)
 * ============================================
 * This is the main landing page of the Atom Digital Agency website.
 * 
 * Sections displayed (in order):
 *   1. HERO - Big banner with animated particles and CTA
 *   2. CLIENTS - Logos of trusted brands
 *   3. SERVICES - Cards showing services from database
 *   4. STATS - Animated counters from database
 *   5. PORTFOLIO PREVIEW - Latest 6 projects
 *   6. TESTIMONIALS - Client reviews carousel
 *   7. CTA - Call to action "Ready to boost?"
 * 
 * Data sources:
 *   - Database: services, portfolio, stats tables
 *   - Hardcoded: clients, testimonials
 * ============================================
 */

// ============================================
// STEP 1: Load required files
// ============================================
require_once 'config/database.php';        // Database connection ($pdo variable)
require_once 'config/stats_helper.php';     // Functions: getStats(), updateStats()

// ============================================
// STEP 2: Auto-update stats from real data
// ============================================
// This counts actual projects/categories/clients in the database
// and updates the stats table before we fetch it.
// Runs EVERY time the homepage loads.
updateStats();

// ============================================
// STEP 3: Fetch data from database
// ============================================
try {
    // --- Fetch ALL services ---
    // Used in the "Our Services" section
    $stmtServices = $pdo->query("SELECT * FROM services");
    $services = $stmtServices->fetchAll();  // Get all rows as array
    
    // --- Fetch LATEST 6 portfolio projects ---
    // ORDER BY id DESC = newest first
    // LIMIT 6 = only get 6 projects (for the preview section)
    $stmtPortfolio = $pdo->query("SELECT * FROM portfolio ORDER BY id DESC LIMIT 6");
    $portfolio = $stmtPortfolio->fetchAll();
    
    // --- Fetch stats ---
    // Uses the helper function that returns stats from the database
    $stats = getStats();
    
} catch (PDOException $e) {
    // If database fails, set everything to empty arrays
    // This prevents the page from crashing with an error
    // The sections will simply show no content instead
    $services = [];
    $portfolio = [];
    $stats = [];
}

// ============================================
// STEP 4: Set page info and load header
// ============================================
$pageTitle = 'Atom | Digital Agency - Web Development, Branding, Social Media';
$currentPage = 'index.php';  // Tells the navbar this is the active page
include 'includes/header.php';  // Loads navigation bar, CSS, etc.
?>

<!-- ============================================
     SECTION 1: HERO (Big Banner)
     ============================================ -->
<section class="hero">
    <!-- Animated floating particles (decorative dots) -->
    <div class="particles">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>
    
    <div class="container">
        <!-- 
            Main headline
            animate-fadeUp = fades in and slides up on page load
            <span> = the peach-colored part of the text
        -->
        <h1 class="animate-fadeUp">The atom of your <span>digital success</span></h1>
        
        <!-- 
            Subtitle/description
            delay-1 = appears slightly after the headline (staggered animation)
        -->
        <p class="animate-fadeUp delay-1">From creative strategy to technical execution, Atom mobilizes the essence of digital to transform your potential into market leadership.</p>
        
        <!-- 
            Call-to-action button
            delay-2 = appears last in the animation sequence
            cta-glow = pulsing glow effect to draw attention
            href="contact.php" = links to contact page
        -->
        <a href="contact.php" class="btn-primary cta-glow animate-fadeUp delay-2">Get a free quote</a>
    </div>
</section>

<!-- ============================================
     SECTION 2: CLIENTS (Trusted Brands)
     ============================================ -->
<section style="padding:60px 0; background:#ffffff;">
    <div class="container">
        <!-- Section title -->
        <h2 style="text-align:center; margin-bottom:20px; font-size:32px; color:#0b0c3d;">They Chose Excellence</h2>
        <p style="text-align:center; color:#5a5d7a; margin-bottom:40px;">Trusted by leading brands across Tunisia</p>
        
        <!-- 
            6-column grid for client logos
            Each client is displayed in a card with hover effects
        -->
        <div style="display:grid; grid-template-columns:repeat(6,1fr); gap:20px;">
            
            <?php
            // ============================================
            // HARDCODED CLIENT LIST
            // ============================================
            // These are NOT from the database.
            // The client logos are stored in uploads/clients/ folder.
            // Each client has: name (alt text) and image path
            // ============================================
            $clients = [
                ['name' => 'Hilton',        'image' => 'uploads/clients/hilton.png'],
                ['name' => 'Royal Thalassa', 'image' => 'uploads/clients/royal-thalassa.png'],
                ['name' => 'LEMDINA',        'image' => 'uploads/clients/lemdina.png'],
                ['name' => 'MONARQUE',       'image' => 'uploads/clients/monarque.png'],
                ['name' => 'LE GOLFE',       'image' => 'uploads/clients/legolfe.png'],
                ['name' => 'AQUA',           'image' => 'uploads/clients/aqua.png'],
            ];
            
            // Loop through each client and create a card
            foreach($clients as $client): 
            ?>
                <!-- Client card -->
                <div style="text-align:center; padding:25px; background:#f0f3ea; border-radius:15px; cursor:pointer; transition:all 0.3s; border:2px solid transparent; display:flex; align-items:center; justify-content:center; min-height:100px;"
                     onmouseover="this.style.borderColor='#f29f5b'; this.style.transform='translateY(-5px)';"
                     onmouseout="this.style.borderColor='transparent'; this.style.transform='translateY(0)';">
                    
                    <!-- 
                        Client logo image
                        onerror = if image fails to load, show client name as text instead
                        This is a graceful fallback for missing images
                    -->
                    <img src="<?php echo $client['image']; ?>" 
                         alt="<?php echo $client['name']; ?>" 
                         style="max-width:120px; max-height:60px; object-fit:contain;"
                         onerror="this.parentElement.innerHTML='<h4 style=color:#0b0c3d;margin:0>' + '<?php echo $client['name']; ?>' + '</h4>'">
                </div>
            <?php endforeach; ?>
            
        </div>
    </div>
</section>

<!-- ============================================
     SECTION 3: SERVICES
     ============================================ -->
<section class="services">
    <div class="container">
        <h2>Our Services</h2>
        
        <!-- 
            4-column grid for service cards
            Data comes FROM DATABASE (dynamic, not hardcoded)
        -->
        <div class="services-grid">
            
            <?php foreach($services as $service): ?>
                <!-- Individual service card -->
                <div class="service-card">
                    <!-- 
                        Icon from Font Awesome
                        htmlspecialchars() prevents XSS attacks
                        Example: $service['icon'] = 'fas fa-code'
                    -->
                    <i class="<?php echo htmlspecialchars($service['icon']); ?>"></i>
                    
                    <!-- Service name -->
                    <h3><?php echo htmlspecialchars($service['name']); ?></h3>
                    
                    <!-- Service description -->
                    <p><?php echo htmlspecialchars($service['description']); ?></p>
                    
                    <!-- Service price -->
                    <div class="service-price"><?php echo htmlspecialchars($service['price']); ?></div>
                </div>
            <?php endforeach; ?>
            
        </div>
    </div>
</section>

<!-- ============================================
     SECTION 4: LIVE STATS (Animated Counters)
     ============================================ -->
<section class="stats">
    <div class="container">
        
        <!-- 4-column grid (only showing 4 stats, not 6) -->
        <div class="stats-grid" style="grid-template-columns: repeat(4, 1fr);">
            
            <?php
            // ============================================
            // EXTRACT SPECIFIC STATS FROM ARRAY
            // ============================================
            // The getStats() function returns all stats.
            // We loop through them and extract the ones we need.
            // ============================================
            $projectsDone = 0;      // Will hold "Projects Done" number
            $businessSectors = 0;   // Will hold "Business Sectors" number
            $yearsExp = 0;          // Will hold "Years Experience" number
            
            foreach($stats as $stat) {
                // Match each stat by its unique key
                if ($stat['stat_key'] == 'projects_completed') {
                    $projectsDone = $stat['stat_value'];
                }
                if ($stat['stat_key'] == 'business_sectors') {
                    $businessSectors = $stat['stat_value'];
                }
                if ($stat['stat_key'] == 'years_experience') {
                    $yearsExp = $stat['stat_value'];
                }
            }
            ?>
            
            <!-- Stat 1: Projects Done (with animated counter) -->
            <div class="stat-card">
                <i class="fas fa-briefcase"></i>
                <h3>
                    <!-- 
                        data-target = the final number to count to
                        class="counter" = JavaScript targets this for animation
                        Starts at 0 and counts up to $projectsDone
                    -->
                    <span class="counter" data-target="<?php echo $projectsDone; ?>">0</span>+
                </h3>
                <p>Projects Done</p>
            </div>
            
            <!-- Stat 2: Business Sectors -->
            <div class="stat-card">
                <i class="fas fa-globe"></i>
                <h3>
                    <span class="counter" data-target="<?php echo $businessSectors; ?>">0</span>
                </h3>
                <p>Business Sectors</p>
            </div>
            
            <!-- Stat 3: Support (static, not animated) -->
            <div class="stat-card">
                <i class="fas fa-headset"></i>
                <h3>24/7</h3>  <!-- This one is just text, not a counter -->
                <p>Support</p>
            </div>
            
            <!-- Stat 4: Years Experience -->
            <div class="stat-card">
                <i class="fas fa-calendar-check"></i>
                <h3>
                    <span class="counter" data-target="<?php echo $yearsExp; ?>">0</span>+
                </h3>
                <p>Years Experience</p>
            </div>
            
        </div>
    </div>
</section>

<!-- ============================================
     SECTION 5: PORTFOLIO PREVIEW
     ============================================ -->
<section class="portfolio-preview">
    <div class="container">
        <h2>Our Latest Work</h2>
        
        <!-- 
            Masonry-style grid (auto-filling columns)
            Shows the 6 most recent projects from database
        -->
        <div class="portfolio-masonry">
            
            <?php foreach($portfolio as $project): ?>
                <!-- Each project card -->
                <div class="masonry-item">
                    <div class="portfolio-card">
                        
                        <!-- Image container -->
                        <div class="portfolio-image">
                            
                            <?php if (!empty($project['image_url'])): ?>
                                <!-- If project has an image, display it -->
                                <img src="<?php echo htmlspecialchars($project['image_url']); ?>" 
                                     alt="<?php echo htmlspecialchars($project['title']); ?>" 
                                     loading="lazy">  <!-- lazy = load image only when scrolled into view -->
                            <?php else: ?>
                                <!-- If no image, show a placeholder icon -->
                                <i class="fas fa-image"></i>
                            <?php endif; ?>
                            
                            <!-- 
                                Overlay that slides up on hover
                                Shows project title and category
                            -->
                            <div class="portfolio-overlay">
                                <h3><?php echo htmlspecialchars($project['title']); ?></h3>
                                <p><?php echo htmlspecialchars($project['category']); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            
        </div>
        
        <!-- "Show more" button linking to full portfolio page -->
        <div class="text-center">
            <a href="portfolio.php" class="btn-outline slide-underline">Show more +</a>
        </div>
    </div>
</section>

<!-- ============================================
     SECTION 6: TESTIMONIALS CAROUSEL
     ============================================ -->
<section style="background: #f0f3ea; padding: 80px 0;">
    <div class="container">
        <h2>What Our Clients Say</h2>
        
        <!-- Carousel container -->
        <div style="position: relative; max-width: 700px; margin: 0 auto;">
            
            <!-- Slides container (hides overflow so only one slide shows at a time) -->
            <div id="testimonialSlides" style="position: relative; overflow: hidden; border-radius: 20px;">
                
                <!-- ============================================
                     SLIDE 1 (Visible by default)
                     ============================================ -->
                <div class="testimonial-slide" style="background:#fff; padding:40px 30px; border-radius:20px; text-align:center; border-top:4px solid #f29f5b;">
                    <!-- Client photo (from external API with fallback) -->
                    <div style="width:100px;height:100px;margin:0 auto 20px;border-radius:50%;overflow:hidden;border:4px solid #f29f5b;">
                        <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Ahmed Kouki" 
                             style="width:100%;height:100%;object-fit:cover;" 
                             onerror="this.src='https://via.placeholder.com/100/0b0c3d/f29f5b?text=AK'">
                    </div>
                    <!-- Star rating -->
                    <div style="color:#f29f5b;margin-bottom:15px;font-size:18px;">★★★★★</div>
                    <!-- Testimonial text -->
                    <p style="color:#5a5d7a;font-style:italic;margin-bottom:20px;line-height:1.7;">"Atom captured the very essence of our establishment. Our online bookings increased by 150% within 3 months!"</p>
                    <!-- Client name and title -->
                    <h4 style="color:#0b0c3d;">Ahmed Kouki</h4>
                    <p style="color:#888;font-size:14px;">Owner, L'Espoir Luxury Hotel</p>
                </div>
                
                <!-- ============================================
                     SLIDE 2 (Hidden by default, display:none)
                     ============================================ -->
                <div class="testimonial-slide" style="display:none; background:#fff; padding:40px 30px; border-radius:20px; text-align:center; border-top:4px solid #f29f5b;">
                    <div style="width:100px;height:100px;margin:0 auto 20px;border-radius:50%;overflow:hidden;border:4px solid #f29f5b;">
                        <img src="https://randomuser.me/api/portraits/men/45.jpg" alt="Ali Cherki" 
                             style="width:100%;height:100%;object-fit:cover;" 
                             onerror="this.src='https://via.placeholder.com/100/0b0c3d/f29f5b?text=AC'">
                    </div>
                    <div style="color:#f29f5b;margin-bottom:15px;font-size:18px;">★★★★★</div>
                    <p style="color:#5a5d7a;font-style:italic;margin-bottom:20px;line-height:1.7;">"Professional team, amazing results. Our website traffic increased by 200%. The digital menu they created is a game changer!"</p>
                    <h4 style="color:#0b0c3d;">Ali Cherki</h4>
                    <p style="color:#888;font-size:14px;">Manager, La Table Gourmande</p>
                </div>
                
                <!-- ============================================
                     SLIDE 3
                     ============================================ -->
                <div class="testimonial-slide" style="display:none; background:#fff; padding:40px 30px; border-radius:20px; text-align:center; border-top:4px solid #f29f5b;">
                    <div style="width:100px;height:100px;margin:0 auto 20px;border-radius:50%;overflow:hidden;border:4px solid #f29f5b;">
                        <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Leila Trabelsi" 
                             style="width:100%;height:100%;object-fit:cover;" 
                             onerror="this.src='https://via.placeholder.com/100/0b0c3d/f29f5b?text=LT'">
                    </div>
                    <div style="color:#f29f5b;margin-bottom:15px;font-size:18px;">★★★★★</div>
                    <p style="color:#5a5d7a;font-style:italic;margin-bottom:20px;line-height:1.7;">"The social media strategy Atom developed revolutionized our online presence. Our Instagram following grew 5x!"</p>
                    <h4 style="color:#0b0c3d;">Leila Trabelsi</h4>
                    <p style="color:#888;font-size:14px;">Marketing Director, Dar El Jeld</p>
                </div>
                
                <!-- ============================================
                     SLIDE 4
                     ============================================ -->
                <div class="testimonial-slide" style="display:none; background:#fff; padding:40px 30px; border-radius:20px; text-align:center; border-top:4px solid #f29f5b;">
                    <div style="width:100px;height:100px;margin:0 auto 20px;border-radius:50%;overflow:hidden;border:4px solid #f29f5b;">
                        <img src="https://randomuser.me/api/portraits/men/52.jpg" alt="Mehdi Ben Salah" 
                             style="width:100%;height:100%;object-fit:cover;" 
                             onerror="this.src='https://via.placeholder.com/100/0b0c3d/f29f5b?text=MB'">
                    </div>
                    <div style="color:#f29f5b;margin-bottom:15px;font-size:18px;">★★★★★</div>
                    <p style="color:#5a5d7a;font-style:italic;margin-bottom:20px;line-height:1.7;">"Atom built our e-commerce website from scratch. The results were immediate — 300+ orders in the first month!"</p>
                    <h4 style="color:#0b0c3d;">Mehdi Ben Salah</h4>
                    <p style="color:#888;font-size:14px;">CEO, Monarque Club</p>
                </div>
                
                <!-- ============================================
                     SLIDE 5
                     ============================================ -->
                <div class="testimonial-slide" style="display:none; background:#fff; padding:40px 30px; border-radius:20px; text-align:center; border-top:4px solid #f29f5b;">
                    <div style="width:100px;height:100px;margin:0 auto 20px;border-radius:50%;overflow:hidden;border:4px solid #f29f5b;">
                        <img src="https://randomuser.me/api/portraits/women/28.jpg" alt="Sonia Miled" 
                             style="width:100%;height:100%;object-fit:cover;" 
                             onerror="this.src='https://via.placeholder.com/100/0b0c3d/f29f5b?text=SM'">
                    </div>
                    <div style="color:#f29f5b;margin-bottom:15px;font-size:18px;">★★★★★</div>
                    <p style="color:#5a5d7a;font-style:italic;margin-bottom:20px;line-height:1.7;">"The branding work Atom did for our spa was exceptional. Our clients constantly compliment our new visual identity."</p>
                    <h4 style="color:#0b0c3d;">Sonia Miled</h4>
                    <p style="color:#888;font-size:14px;">Founder, Royal Thalassa Spa</p>
                </div>
            </div>
            
            <!-- ============================================
                 LEFT ARROW BUTTON
                 ============================================ -->
            <button onclick="changeSlide(-1)" 
                    style="position:absolute;top:50%;left:-15px;transform:translateY(-50%);width:44px;height:44px;background:#fff;border:2px solid #f29f5b;border-radius:50%;cursor:pointer;font-size:16px;color:#f29f5b;z-index:2;box-shadow:0 4px 12px rgba(0,0,0,0.1);" 
                    onmouseover="this.style.background='#f29f5b';this.style.color='#fff';" 
                    onmouseout="this.style.background='#fff';this.style.color='#f29f5b';">
                ‹
            </button>
            
            <!-- ============================================
                 RIGHT ARROW BUTTON
                 ============================================ -->
            <button onclick="changeSlide(1)" 
                    style="position:absolute;top:50%;right:-15px;transform:translateY(-50%);width:44px;height:44px;background:#fff;border:2px solid #f29f5b;border-radius:50%;cursor:pointer;font-size:16px;color:#f29f5b;z-index:2;box-shadow:0 4px 12px rgba(0,0,0,0.1);" 
                    onmouseover="this.style.background='#f29f5b';this.style.color='#fff';" 
                    onmouseout="this.style.background='#fff';this.style.color='#f29f5b';">
                ›
            </button>
            
            <!-- ============================================
                 NAVIGATION DOTS (generated by JavaScript)
                 ============================================ -->
            <div id="testimonialDots" style="display:flex;justify-content:center;gap:10px;margin-top:25px;"></div>
        </div>
    </div>
</section>

<!-- ============================================
     SECTION 7: CTA (Call to Action)
     ============================================ -->
<section class="cta">
    <div class="container">
        <h2>Ready to boost your digital presence?</h2>
        <a href="contact.php" class="btn-primary">Request your free quote</a>
    </div>
</section>

<!-- ============================================
     TESTIMONIAL CAROUSEL JAVASCRIPT
     ============================================ -->
<script>
/**
 * This is an IIFE (Immediately Invoked Function Expression).
 * It runs as soon as the page loads.
 * All variables inside are private (don't conflict with other scripts).
 */
(function() {
    // --- Get all testimonial slides ---
    // querySelectorAll returns a NodeList (like an array of elements)
    var slides = document.querySelectorAll('.testimonial-slide');
    
    // --- Get the dots container ---
    var dotsContainer = document.getElementById('testimonialDots');
    
    // --- Track which slide is currently visible ---
    // Start at 0 = first slide
    var current = 0;
    
    // ============================================
    // CREATE NAVIGATION DOTS
    // ============================================
    // For each slide, create a clickable dot
    slides.forEach(function(_, i) {  // _ = slide element (unused), i = index
        
        // Create a <span> element for the dot
        var dot = document.createElement('span');
        
        // Style the dot
        // If it's the first dot (i===0), color it peach (active)
        // Otherwise, color it gray (inactive)
        dot.style.cssText = 'width:12px;height:12px;border-radius:50%;background:' + 
                            (i === 0 ? '#f29f5b' : '#d0d2c8') + 
                            ';cursor:pointer;display:inline-block;transition:all 0.3s;';
        
        // When a dot is clicked, jump to that slide
        dot.onclick = function() { 
            goTo(i); 
        };
        
        // Add the dot to the container
        dotsContainer.appendChild(dot);
    });
    
    // --- Get all dots (after they've been created) ---
    var dots = dotsContainer.querySelectorAll('span');
    
    // ============================================
    // CHANGE SLIDE FUNCTION (exposed globally)
    // ============================================
    // This is called by the arrow buttons (onclick="changeSlide(-1)" etc.)
    // dir = direction: -1 for left, +1 for right
    window.changeSlide = function(dir) {
        // Hide the current slide
        slides[current].style.display = 'none';
        
        // Calculate new slide index
        // The modulo (%) wraps around:
        //   If current=4 and dir=1 → (4+1)%5 = 0 → goes to first slide
        //   If current=0 and dir=-1 → (0-1+5)%5 = 4 → goes to last slide
        current = (current + dir + slides.length) % slides.length;
        
        // Show the new slide
        slides[current].style.display = 'block';
        
        // Update all dots: active dot = peach, others = gray
        dots.forEach(function(d, i) {
            d.style.background = i === current ? '#f29f5b' : '#d0d2c8';
            d.style.transform = i === current ? 'scale(1.3)' : 'scale(1)';
        });
    };
    
    // ============================================
    // GO TO SPECIFIC SLIDE FUNCTION (internal)
    // ============================================
    // Called when a dot is clicked
    function goTo(i) {
        // Hide current slide
        slides[current].style.display = 'none';
        
        // Set new current
        current = i;
        
        // Show new slide
        slides[current].style.display = 'block';
        
        // Update dots
        dots.forEach(function(d, j) {
            d.style.background = j === current ? '#f29f5b' : '#d0d2c8';
            d.style.transform = j === current ? 'scale(1.3)' : 'scale(1)';
        });
    }
    
    // ============================================
    // AUTO-ROTATE EVERY 5 SECONDS
    // ============================================
    // setInterval runs the function repeatedly
    // 5000 = 5 seconds in milliseconds
    setInterval(function() { 
        changeSlide(1);  // Move to next slide
    }, 5000);
    
})();
</script>

<!-- ============================================
     LOAD FOOTER
     ============================================ -->
<?php include 'includes/footer.php'; ?>