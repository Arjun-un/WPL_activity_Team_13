<?php
/**
 * Global Footer Component
 * Includes syllabus-required nested category lists, trust badges, and script imports.
 */
?>
    <!-- Site Footer -->
    <footer class="site-footer">
        <div class="footer-container">
            <!-- Brand Column -->
            <div class="footer-col">
                <div class="logo-badge" style="display:inline-block; margin-bottom:14px;">TATA <span>CLiQ</span></div>
                <p style="font-size:0.85rem; line-height:1.6; margin-bottom:14px;">
                    Tata CLiQ is the flagship digital commerce initiative of the Tata Group. Replicating authentic multi-category luxury shopping with guaranteed trust and verified brands.
                </p>
                <div style="font-size:0.8rem; color:#888;">
                    <strong>Academic Project Replica</strong> &bull; HTML5 &bull; CSS3 &bull; JS &bull; PHP &bull; MongoDB
                </div>
            </div>

            <!-- Mandatory Syllabus Nested Category Tree (Category 1) -->
            <div class="footer-col">
                <h4>Fashion & Apparel</h4>
                <ul class="nested-category-tree">
                    <li>
                        Men's Collection
                        <ul>
                            <li><a href="catalog.php?cat=Men+Fashion">Shirts & Formalwear</a></li>
                            <li><a href="catalog.php?cat=Men+Fashion">Denims & Trousers</a></li>
                            <li><a href="catalog.php?cat=Footwear">Sneakers & Casuals</a></li>
                        </ul>
                    </li>
                    <li>
                        Women's Collection
                        <ul>
                            <li><a href="catalog.php?cat=Women+Fashion">Kurtas & Ethnic Sets</a></li>
                            <li><a href="catalog.php?cat=Women+Fashion">Dresses & Tops</a></li>
                            <li><a href="catalog.php?cat=Luxury">Designer Wear</a></li>
                        </ul>
                    </li>
                </ul>
            </div>

            <!-- Mandatory Syllabus Nested Category Tree (Category 2) -->
            <div class="footer-col">
                <h4>Gadgets & Luxury</h4>
                <ul class="nested-category-tree">
                    <li>
                        Timepieces & Jewelry
                        <ul>
                            <li><a href="catalog.php?cat=Watches">Analog & Smartwatches</a></li>
                            <li><a href="catalog.php?cat=Luxury">Gold & Silver Jewelry</a></li>
                        </ul>
                    </li>
                    <li>
                        Electronics & Audio
                        <ul>
                            <li><a href="catalog.php?cat=Electronics">Noise Cancelling Audio</a></li>
                            <li><a href="catalog.php?cat=Electronics">Wireless Earbuds</a></li>
                        </ul>
                    </li>
                </ul>
            </div>

            <!-- Quick Links -->
            <div class="footer-col">
                <h4>Customer Central</h4>
                <ul class="nested-category-tree">
                    <li><a href="orders.php">Track Mock Orders</a></li>
                    <li><a href="feedback.php">File CRUD Feedback Board</a></li>
                    <li><a href="interaction_demo.php">DOM & Event Demo View</a></li>
                    <li><a href="seed.php" target="_blank">Re-seed MongoDB Data</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            &copy; <?= date('Y'); ?> Tata CLiQ Educational Replica. Designed for academic demonstration & evaluation.
        </div>
    </footer>

    <!-- Essential Scripts -->
    <script src="assets/js/validation.js"></script>
    <script src="assets/js/events.js"></script>
    <script src="assets/js/filter.js"></script>
</body>
</html>
