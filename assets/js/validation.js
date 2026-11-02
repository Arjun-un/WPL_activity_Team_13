/**
 * ============================================================================
 * JavaScript Form Validation
 * Mandatory Syllabus Coverage:
 * - Validates Name, Email, Password, and Structured Identifier using Regex
 * - Displays clear inline error messages
 * ============================================================================
 */

// ============================================================================
// ON-SPOT MODIFICATION SPOT #4: REGULAR EXPRESSIONS & VALIDATION RULES
// Evaluator may ask to alter regex patterns, password rules, or ID formats here:
// ============================================================================
const VALIDATION_RULES = {
    // Name: Alphabet characters and spaces, 3 to 40 characters
    name: {
        regex: /^[A-Za-z\s]{3,40}$/,
        errorMsg: "Name must contain only letters and spaces (3-40 chars)."
    },
    // Standard RFC-compliant Email format
    email: {
        regex: /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/,
        errorMsg: "Please enter a valid email address (e.g., user@tatacliq.com)."
    },
    // Password: Min 8 chars, at least 1 uppercase, 1 lowercase, 1 digit, 1 special char
    password: {
        regex: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/,
        errorMsg: "Password must have >=8 chars, 1 uppercase, 1 lowercase, 1 number & 1 special symbol."
    },
    // Structured Identifier: Tata CLiQ Customer/Member ID format: CLIQ-####-XX
    // Example: CLIQ-1001-IN
    cliq_id: {
        regex: /^CLIQ-[0-9]{4}-[A-Z]{2}$/,
        errorMsg: "Identifier must match format: CLIQ-####-XX (e.g. CLIQ-1001-IN)."
    }
};

/**
 * Validates a single input field against its defined regex
 */
function validateField(inputElement) {
    const fieldType = inputElement.getAttribute('data-validate');
    if (!fieldType || !VALIDATION_RULES[fieldType]) return true;

    const rule = VALIDATION_RULES[fieldType];
    const value = inputElement.value.trim();
    const errorEl = document.getElementById(inputElement.id + '_error');

    if (!rule.regex.test(value)) {
        inputElement.classList.add('input-error');
        if (errorEl) {
            errorEl.textContent = rule.errorMsg;
            errorEl.classList.add('visible');
        }
        return false;
    } else {
        inputElement.classList.remove('input-error');
        if (errorEl) {
            errorEl.textContent = '';
            errorEl.classList.remove('visible');
        }
        return true;
    }
}

/**
 * Attaches real-time 'input' and 'blur' validation listeners to all forms
 */
document.addEventListener('DOMContentLoaded', function () {
    const validatedInputs = document.querySelectorAll('input[data-validate]');
    validatedInputs.forEach(input => {
        // Real-time feedback on input and blur
        input.addEventListener('input', () => validateField(input));
        input.addEventListener('blur', () => validateField(input));
    });

    // Form submit interception
    const forms = document.querySelectorAll('form[data-validate-form]');
    forms.forEach(form => {
        form.addEventListener('submit', function (e) {
            let formIsValid = true;
            const inputs = form.querySelectorAll('input[data-validate]');
            inputs.forEach(input => {
                if (!validateField(input)) {
                    formIsValid = false;
                }
            });

            if (!formIsValid) {
                e.preventDefault();
                // Alert banner or scroll to first invalid input
                const firstInvalid = form.querySelector('.input-error');
                if (firstInvalid) {
                    firstInvalid.focus();
                }
            }
        });
    });
});
