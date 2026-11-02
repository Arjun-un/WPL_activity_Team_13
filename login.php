<?php
/**
 * ============================================================================
 * Authentication View: Login & Registration (login.php)
 * Demonstrates:
 * - Client-side JS Regex validation (validation.js)
 * - Server-side PHP validation & sanitization
 * - MongoDB 'users' collection insertion & lookup
 * - Password hashing (password_hash & password_verify)
 * - Session creation, protected pages, and session destruction (logout)
 * ============================================================================
 */

require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/session.php';

// ----------------------------------------------------------------------------
// Handle Logout Action
// ----------------------------------------------------------------------------
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
    header("Location: login.php?msg=" . urlencode("You have been signed out successfully."));
    exit;
}

// Redirect if already logged in
if (is_logged_in() && !isset($_GET['action'])) {
    header("Location: orders.php");
    exit;
}

$active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'login';
$errors = [];
$success_msg = isset($_GET['msg']) ? $_GET['msg'] : '';

// ----------------------------------------------------------------------------
// Handle Registration Form (Server-Side Validation)
// ----------------------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_register'])) {
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $cliq_id  = strtoupper(trim($_POST['cliq_id'] ?? ''));

    // Server-Side Validation Rules
    if (!preg_match('/^[A-Za-z\s]{3,40}$/', $name)) {
        $errors[] = "Name must contain only alphabets and spaces (3 to 40 characters).";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    }
    if (strlen($password) < 8 || !preg_match('/[A-Z]/', $password) || !preg_match('/[0-9]/', $password)) {
        $errors[] = "Password must be at least 8 characters long and contain uppercase and numerical digits.";
    }
    if (!preg_match('/^CLIQ-[0-9]{4}-[A-Z]{2}$/', $cliq_id)) {
        $errors[] = "Tata CLiQ ID must follow the structured pattern: CLIQ-####-XX (e.g. CLIQ-1001-IN).";
    }

    // Check for duplicate email in MongoDB
    if (empty($errors)) {
        $existing_user = get_single_record('users', ['email' => $email]);
        if ($existing_user) {
            $errors[] = "An account with this email address already exists. Please sign in.";
        } else {
            // Insert new user into MongoDB
            $new_user = [
                'name'          => $name,
                'email'         => $email,
                'password_hash' => password_hash($password, PASSWORD_DEFAULT),
                'cliq_id'       => $cliq_id,
                'created_at'    => date('Y-m-d H:i:s')
            ];
            $user_id = insert_db_record('users', $new_user);
            if ($user_id) {
                // Auto-login newly registered user
                $_SESSION['user_id']    = $user_id;
                $_SESSION['user_name']  = $name;
                $_SESSION['user_email'] = $email;
                $_SESSION['cliq_id']    = $cliq_id;

                header("Location: orders.php?msg=" . urlencode("Account created successfully! Welcome to Tata CLiQ."));
                exit;
            } else {
                $errors[] = "Failed to create account. Please try again.";
            }
        }
    }
    $active_tab = 'register';
}

// ----------------------------------------------------------------------------
// Handle Login Form (Server-Side Validation)
// ----------------------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_login'])) {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $errors[] = "Please provide both email and password.";
    } else {
        $user = get_single_record('users', ['email' => $email]);
        if ($user && password_verify($password, $user['password_hash'])) {
            // Establish authenticated session
            $_SESSION['user_id']    = (string)$user['_id'];
            $_SESSION['user_name']  = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['cliq_id']    = $user['cliq_id'] ?? 'CLIQ-1001-IN';

            header("Location: orders.php?msg=" . urlencode("Welcome back, " . $user['name'] . "!"));
            exit;
        } else {
            $errors[] = "Invalid email address or password. Please try again.";
        }
    }
    $active_tab = 'login';
}

require_once __DIR__ . '/includes/header.php';
?>

<main class="main-wrapper">

    <div class="auth-box">
        <!-- Tab Selector -->
        <div style="display:flex; border-bottom:2px solid #eee; margin-bottom:24px;">
            <button 
                type="button" 
                onclick="window.location='login.php?tab=login'"
                style="flex:1; padding:10px; background:none; border:none; font-weight:700; cursor:pointer; font-size:1rem; color: <?= $active_tab === 'login' ? 'var(--cliq-primary); border-bottom:3px solid var(--cliq-primary);' : '#777'; ?>;">
                Sign In
            </button>
            <button 
                type="button" 
                onclick="window.location='login.php?tab=register'"
                style="flex:1; padding:10px; background:none; border:none; font-weight:700; cursor:pointer; font-size:1rem; color: <?= $active_tab === 'register' ? 'var(--cliq-primary); border-bottom:3px solid var(--cliq-primary);' : '#777'; ?>;">
                Register
            </button>
        </div>

        <!-- Success Message -->
        <?php if (!empty($success_msg)): ?>
            <div style="background:#e0f2fe; color:#0369a1; padding:10px 14px; border-radius:6px; font-size:0.85rem; margin-bottom:16px;">
                &check; <?= htmlspecialchars($success_msg); ?>
            </div>
        <?php endif; ?>

        <!-- Server-Side Validation Errors -->
        <?php if (!empty($errors)): ?>
            <div style="background:#fee2e2; color:#b91c1c; padding:10px 14px; border-radius:6px; font-size:0.85rem; margin-bottom:16px;">
                <ul style="padding-left:18px; margin:0;">
                    <?php foreach ($errors as $err): ?>
                        <li><?= htmlspecialchars($err); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- 1. SIGN IN FORM -->
        <?php if ($active_tab === 'login'): ?>
            <form action="login.php" method="POST" data-validate-form>
                <input type="hidden" name="action_login" value="1">

                <div class="form-group">
                    <label for="login_email">Registered Email Address:</label>
                    <input 
                        type="email" 
                        name="email" 
                        id="login_email" 
                        class="form-control" 
                        placeholder="e.g. arjun@tatacliq.com" 
                        data-validate="email"
                        data-help="Enter the email associated with your Tata CLiQ account"
                        required
                    >
                    <span class="error-feedback" id="login_email_error"></span>
                </div>

                <div class="form-group">
                    <label for="login_password">Account Password:</label>
                    <input 
                        type="password" 
                        name="password" 
                        id="login_password" 
                        class="form-control" 
                        placeholder="Enter your password" 
                        required
                    >
                </div>

                <button type="submit" class="btn-primary" style="width:100%; padding:12px; font-size:0.95rem;">
                    Sign In to Tata CLiQ
                </button>

                <div style="margin-top:16px; font-size:0.8rem; background:#f8fafc; padding:10px; border-radius:4px; border:1px solid #e2e8f0;">
                    <strong>Demo Credentials:</strong><br>
                    Email: <code>arjun@tatacliq.com</code><br>
                    Password: <code>Cliq@2026</code>
                </div>
            </form>

        <!-- 2. REGISTRATION FORM (Demonstrates all Regex validations) -->
        <?php else: ?>
            <form action="login.php?tab=register" method="POST" data-validate-form>
                <input type="hidden" name="action_register" value="1">

                <!-- Name Field -->
                <div class="form-group">
                    <label for="reg_name">Full Name (Alphabetic only):</label>
                    <input 
                        type="text" 
                        name="name" 
                        id="reg_name" 
                        class="form-control" 
                        placeholder="e.g. Rahul Sharma" 
                        data-validate="name"
                        data-help="Letters only, minimum 3 characters"
                        required
                    >
                    <span class="error-feedback" id="reg_name_error"></span>
                </div>

                <!-- Email Field -->
                <div class="form-group">
                    <label for="reg_email">Email Address:</label>
                    <input 
                        type="email" 
                        name="email" 
                        id="reg_email" 
                        class="form-control" 
                        placeholder="e.g. rahul@example.com" 
                        data-validate="email"
                        data-help="Must be a valid RFC email address"
                        required
                    >
                    <span class="error-feedback" id="reg_email_error"></span>
                </div>

                <!-- Structured Identifier (CLIQ ID) -->
                <div class="form-group">
                    <label for="reg_cliq_id">Tata CLiQ Member ID (Structured Pattern):</label>
                    <input 
                        type="text" 
                        name="cliq_id" 
                        id="reg_cliq_id" 
                        class="form-control" 
                        placeholder="CLIQ-####-XX (e.g. CLIQ-2024-KA)" 
                        data-validate="cliq_id"
                        data-help="Pattern must be CLIQ- followed by 4 digits and 2 capital letters"
                        required
                    >
                    <span class="error-feedback" id="reg_cliq_id_error"></span>
                </div>

                <!-- Password Field -->
                <div class="form-group">
                    <label for="reg_password">Strong Password:</label>
                    <input 
                        type="password" 
                        name="password" 
                        id="reg_password" 
                        class="form-control" 
                        placeholder="Min 8 chars, 1 Upper, 1 Digit, 1 Symbol" 
                        data-validate="password"
                        data-help="Must contain uppercase, lowercase, digit, and special symbol"
                        required
                    >
                    <span class="error-feedback" id="reg_password_error"></span>
                </div>

                <button type="submit" class="btn-primary" style="width:100%; padding:12px; font-size:0.95rem;">
                    Create Account
                </button>
            </form>
        <?php endif; ?>
    </div>

</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
