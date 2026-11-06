<?php
/**
 * ============================================================================
 * Page 5: Mandatory Syllabus Interaction & Event Demonstration
 * (interaction_demo.php)
 * Specifically fulfills syllabus criteria:
 * - Client & Screen mouse cursor coordinates live tracking
 * - Contextual help box dynamic positioning
 * - Hover-to-front overlapping panels
 * - Keyboard events (e.g., '/' to focus search, Esc to dismiss)
 * ============================================================================
 */

require_once __DIR__ . '/config/session.php';
require_once __DIR__ . '/includes/header.php';
?>

<main class="main-wrapper">

    <div class="section-title">
        <h2>Syllabus Interaction & Event Demonstration</h2>
        <a href="index.php">&larr; Return to Homepage</a>
    </div>

    <!-- Syllabus Matrix Info Banner -->
    <div style="background:#fef3c7; border-left:4px solid #f59e0b; padding:12px 18px; border-radius:4px; margin-bottom:28px; font-size:0.85rem; color:#92400e;">
        <strong>Evaluator Guide:</strong> This dedicated view showcases all syllabus-mandated DOM events, mouse coordinate tracking, contextual help tooltips, and overlapping cascade panel interactions as specified in the rubric.
    </div>

    <!-- 1. MOUSE COORDINATES (CLIENT & SCREEN) -->
    <div style="background:#ffffff; border:1px solid var(--cliq-border); border-radius:8px; padding:24px; margin-bottom:30px;">
        <h3 style="font-size:1.2rem; margin-bottom:6px;">1. Real-Time Mouse Cursor Coordinates</h3>
        <p style="font-size:0.85rem; color:#666; margin-bottom:16px;">
            Demonstrates event handling capturing <code>e.clientX</code>, <code>e.clientY</code> (viewport relative) and <code>e.screenX</code>, <code>e.screenY</code> (monitor display relative).
        </p>

        <div id="demo-interactive-canvas" class="demo-canvas">
            <div style="font-weight:700; color:#475569; margin-bottom:10px;">
                &#10022; Move your mouse inside this active canvas area &#10022;
            </div>
            <div id="cursor-coords-display" class="coords-badge">
                Coordinates will appear here upon hovering...
            </div>
        </div>
    </div>

    <!-- 2. CONTEXTUAL FLOATING HELP BOX -->
    <div style="background:#ffffff; border:1px solid var(--cliq-border); border-radius:8px; padding:24px; margin-bottom:30px;">
        <h3 style="font-size:1.2rem; margin-bottom:6px;">2. Contextual Help Box & Keyboard Navigation</h3>
        <p style="font-size:0.85rem; color:#666; margin-bottom:16px;">
            Hover or focus over the test elements below to activate floating contextual assistance tooltips:
        </p>

        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap:20px;">
            <div class="form-group">
                <label>Tata CLiQ Privilege Code:</label>
                <input 
                    type="text" 
                    class="form-control" 
                    placeholder="Focus or hover here..."
                    data-help="Contextual Help: Enter your 10-digit Tata Neu privilege code for 5% NeuCoins."
                >
            </div>

            <div class="form-group">
                <label>PIN Code Delivery Estimator:</label>
                <input 
                    type="text" 
                    class="form-control" 
                    placeholder="e.g. 560001"
                    data-help="Contextual Help: Enter your 6-digit postal code to check express 24h delivery eligibility."
                >
            </div>

            <div style="display:flex; align-items:flex-end;">
                <button 
                    type="button" 
                    class="btn-primary" 
                    style="width:100%; padding:10px;"
                    data-help="Contextual Help: Click to verify all warehouse delivery slots across India."
                >
                    Test Button Hover
                </button>
            </div>
        </div>

        <div style="background:#f1f5f9; padding:12px; border-radius:6px; margin-top:14px; font-size:0.85rem; color:#334155;">
            &#9000; <strong>Keyboard Shortcut Demo:</strong> Press <kbd style="background:#fff; border:1px solid #ccc; padding:2px 6px; border-radius:3px;">/</kbd> anywhere on the webpage to instantly jump to and highlight the top search bar!
        </div>
    </div>

    <!-- 3. HOVER-TO-FRONT OVERLAPPING PANELS -->
    <div style="background:#ffffff; border:1px solid var(--cliq-border); border-radius:8px; padding:24px; margin-bottom:40px;">
        <h3 style="font-size:1.2rem; margin-bottom:6px;">3. Hover-to-Front Overlapping Panels</h3>
        <p style="font-size:0.85rem; color:#666; margin-bottom:16px;">
            Three overlapping promotional cards cascaded using relative positioning. Hovering over any card dynamically elevates its <code>z-index</code> to 20 and applies an elevated 3D transform.
        </p>

        <div class="overlapping-container">
            <!-- Card 1 -->
            <div class="overlap-card">
                <div style="font-size:1.8rem; margin-bottom:8px;">&#127873;</div>
                <h4 style="font-size:1.1rem; color:var(--cliq-primary); margin-bottom:6px;">NeuCoins Rewards</h4>
                <p style="font-size:0.82rem; color:#555; line-height:1.4;">
                    Earn 5% NeuCoins on every order across Tata CLiQ, Westside, BigBasket, and Taj Hotels.
                </p>
                <span style="font-size:0.75rem; color:#888; margin-top:12px; display:block;">(Panel 1 &bull; Hover to bring front)</span>
            </div>

            <!-- Card 2 -->
            <div class="overlap-card">
                <div style="font-size:1.8rem; margin-bottom:8px;">&#128142;</div>
                <h4 style="font-size:1.1rem; color:#047857; margin-bottom:6px;">CLiQ Luxury Shield</h4>
                <p style="font-size:0.82rem; color:#555; line-height:1.4;">
                    100% verified genuine luxury timepieces and jewelry shipped in tamper-evident security packaging.
                </p>
                <span style="font-size:0.75rem; color:#888; margin-top:12px; display:block;">(Panel 2 &bull; Hover to bring front)</span>
            </div>

            <!-- Card 3 -->
            <div class="overlap-card">
                <div style="font-size:1.8rem; margin-bottom:8px;">&#128666;</div>
                <h4 style="font-size:1.1rem; color:#1d4ed8; margin-bottom:6px;">Express 24H Dispatch</h4>
                <p style="font-size:0.82rem; color:#555; line-height:1.4;">
                    Enjoy priority same-day dispatch from nearest authorized brand hubs across metro cities.
                </p>
                <span style="font-size:0.75rem; color:#888; margin-top:12px; display:block;">(Panel 3 &bull; Hover to bring front)</span>
            </div>
        </div>
    </div>

</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

