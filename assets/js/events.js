/**
 * ============================================================================
 * JavaScript Events & DOM Interactions
 * Mandatory Syllabus Coverage:
 * - Mouse cursor coordinates (clientX/Y & screenX/Y)
 * - Contextual help box positioned dynamically
 * - Hover-to-front overlapping panels
 * - Keyboard interactions (e.g. shortcut '/' to focus search)
 * ============================================================================
 */

// ============================================================================
// ON-SPOT MODIFICATION SPOT #5: MOUSE / KEYBOARD EVENT CONFIGURATIONS
// Evaluator may ask to change coordinate calculations, offsets, or keys
// ============================================================================

document.addEventListener('DOMContentLoaded', function () {

    // 1. MOUSE CURSOR COORDINATES DEMONSTRATION
    const coordsDisplay = document.getElementById('cursor-coords-display');
    const demoCanvas = document.getElementById('demo-interactive-canvas');

    if (demoCanvas && coordsDisplay) {
        demoCanvas.addEventListener('mousemove', function (e) {
            // Client coordinates (relative to browser viewport)
            const clientX = e.clientX;
            const clientY = e.clientY;

            // Screen coordinates (relative to physical computer screen)
            const screenX = e.screenX;
            const screenY = e.screenY;

            // Element-relative offset
            const rect = demoCanvas.getBoundingClientRect();
            const canvasX = Math.round(e.clientX - rect.left);
            const canvasY = Math.round(e.clientY - rect.top);

            coordsDisplay.innerHTML = `
                Viewport (Client): <strong>X: ${clientX}px, Y: ${clientY}px</strong> | 
                Monitor (Screen): <strong>X: ${screenX}px, Y: ${screenY}px</strong> | 
                Canvas Offset: <strong>X: ${canvasX}px, Y: ${canvasY}px</strong>
            `;
        });

        demoCanvas.addEventListener('mouseleave', function () {
            coordsDisplay.innerHTML = "Hover mouse inside the canvas box above to track real-time cursor coordinates.";
        });
    }

    // 2. CONTEXTUAL FLOATING HELP BOX
    let helpTooltip = document.getElementById('contextual-help-box');
    if (!helpTooltip) {
        helpTooltip = document.createElement('div');
        helpTooltip.id = 'contextual-help-box';
        helpTooltip.className = 'contextual-tooltip';
        document.body.appendChild(helpTooltip);
    }

    const helpElements = document.querySelectorAll('[data-help]');
    helpElements.forEach(element => {
        // Show tooltip on mouseenter or focus
        const showHelp = function (e) {
            const helpText = element.getAttribute('data-help');
            if (!helpText) return;

            helpTooltip.textContent = helpText;
            helpTooltip.style.display = 'block';

            // Calculate position near element or cursor
            const rect = element.getBoundingClientRect();
            const topPos = rect.bottom + window.scrollY + 6;
            const leftPos = rect.left + window.scrollX;

            helpTooltip.style.top = topPos + 'px';
            helpTooltip.style.left = leftPos + 'px';
        };

        const hideHelp = function () {
            helpTooltip.style.display = 'none';
        };

        element.addEventListener('mouseenter', showHelp);
        element.addEventListener('focus', showHelp);
        element.addEventListener('mouseleave', hideHelp);
        element.addEventListener('blur', hideHelp);
    });

    // 3. KEYBOARD EVENT: Press '/' anywhere to focus the search bar
    document.addEventListener('keydown', function (e) {
        const activeTag = document.activeElement ? document.activeElement.tagName.toLowerCase() : '';
        if (e.key === '/' && activeTag !== 'input' && activeTag !== 'textarea') {
            e.preventDefault();
            const searchInput = document.getElementById('global-search-input');
            if (searchInput) {
                searchInput.focus();
                searchInput.select();
            }
        }
        // Escape key hides tooltips and clear search
        if (e.key === 'Escape') {
            if (helpTooltip) helpTooltip.style.display = 'none';
        }
    });

    // 4. HOVER-TO-FRONT OVERLAPPING PANELS
    const overlappingPanels = document.querySelectorAll('.overlap-card');
    overlappingPanels.forEach(panel => {
        panel.addEventListener('mouseenter', function () {
            overlappingPanels.forEach(p => p.style.zIndex = '1');
            this.style.zIndex = '20';
        });
    });
});

