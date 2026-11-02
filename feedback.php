<?php
/**
 * ============================================================================
 * Secondary Module: Customer Feedback Board (feedback.php)
 * Mandatory Syllabus Coverage:
 * - PHP Text-File CRUD Operations (Separate from MongoDB)
 * - Create: Append feedback to flat text file
 * - Read: Parse text records and display in a structured table
 * - Update: Modify existing review text / rating
 * - Delete: Remove feedback entry and re-save file
 * ============================================================================
 */

require_once __DIR__ . '/config/session.php';

// ============================================================================
// ON-SPOT MODIFICATION SPOT #9: FILE PATH & RECORD DELIMITER
// Evaluator may ask to change file storage path, format, or delimiter
// ============================================================================
$FILE_PATH = __DIR__ . '/data/feedback.txt';
$DELIMITER = '|';

// Ensure data directory and file exist
if (!file_exists(dirname($FILE_PATH))) {
    mkdir(dirname($FILE_PATH), 0777, true);
}
if (!file_exists($FILE_PATH)) {
    touch($FILE_PATH);
}

/**
 * Helper: Read all records from the text file
 */
function read_all_feedbacks($file, $delimiter) {
    $records = [];
    if (!file_exists($file)) return $records;

    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $parts = explode($delimiter, trim($line));
        if (count($parts) >= 6) {
            $records[] = [
                'id'        => $parts[0],
                'name'      => $parts[1],
                'email'     => $parts[2],
                'rating'    => (int)$parts[3],
                'comment'   => $parts[4],
                'timestamp' => $parts[5]
            ];
        }
    }
    return $records;
}

/**
 * Helper: Write all records back to the text file
 */
function write_all_feedbacks($file, $records, $delimiter) {
    $lines = [];
    foreach ($records as $r) {
        $lines[] = implode($delimiter, [
            $r['id'],
            str_replace($delimiter, '-', $r['name']),
            str_replace($delimiter, '-', $r['email']),
            $r['rating'],
            str_replace([$delimiter, "\r", "\n"], ' ', $r['comment']),
            $r['timestamp']
        ]);
    }
    file_put_contents($file, implode("\n", $lines) . (empty($lines) ? '' : "\n"), LOCK_EX);
}

$action = isset($_GET['action']) ? $_GET['action'] : '';
$edit_id = isset($_GET['edit_id']) ? $_GET['edit_id'] : '';
$status_msg = '';

// ----------------------------------------------------------------------------
// 1. CREATE: Append new feedback
// ----------------------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_add_feedback'])) {
    $name    = trim($_POST['fb_name'] ?? '');
    $email   = trim($_POST['fb_email'] ?? '');
    $rating  = (int)($_POST['fb_rating'] ?? 5);
    $comment = trim($_POST['fb_comment'] ?? '');

    if (!empty($name) && !empty($comment) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $existing = read_all_feedbacks($FILE_PATH, $DELIMITER);
        $next_id = empty($existing) ? 1 : (max(array_column($existing, 'id')) + 1);

        $new_entry = [
            'id'        => $next_id,
            'name'      => $name,
            'email'     => $email,
            'rating'    => $rating,
            'comment'   => $comment,
            'timestamp' => date('Y-m-d H:i:s')
        ];

        // Append using standard PHP file operations
        $fp = fopen($FILE_PATH, 'a');
        if ($fp && flock($fp, LOCK_EX)) {
            $line = implode($DELIMITER, $new_entry) . "\n";
            fwrite($fp, $line);
            flock($fp, LOCK_UN);
            fclose($fp);
            header("Location: feedback.php?msg=" . urlencode("Thank you! Feedback recorded to text log."));
            exit;
        }
    } else {
        $status_msg = "Please fill in all feedback fields with valid data.";
    }
}

// ----------------------------------------------------------------------------
// 2. UPDATE: Edit existing feedback
// ----------------------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_update_feedback'])) {
    $target_id = trim($_POST['update_id'] ?? '');
    $rating    = (int)($_POST['fb_rating'] ?? 5);
    $comment   = trim($_POST['fb_comment'] ?? '');

    if (!empty($target_id) && !empty($comment)) {
        $feedbacks = read_all_feedbacks($FILE_PATH, $DELIMITER);
        $found = false;
        foreach ($feedbacks as &$fb) {
            if ($fb['id'] == $target_id) {
                $fb['rating']    = $rating;
                $fb['comment']   = $comment;
                $fb['timestamp'] = date('Y-m-d H:i:s') . ' (Edited)';
                $found = true;
                break;
            }
        }
        if ($found) {
            write_all_feedbacks($FILE_PATH, $feedbacks, $DELIMITER);
            header("Location: feedback.php?msg=" . urlencode("Feedback #$target_id updated successfully in text file."));
            exit;
        }
    }
}

// ----------------------------------------------------------------------------
// 3. DELETE: Remove feedback entry
// ----------------------------------------------------------------------------
if ($action === 'delete' && !empty($_GET['id'])) {
    $del_id = trim($_GET['id']);
    $feedbacks = read_all_feedbacks($FILE_PATH, $DELIMITER);
    $filtered = array_filter($feedbacks, function ($item) use ($del_id) {
        return $item['id'] != $del_id;
    });

    write_all_feedbacks($FILE_PATH, array_values($filtered), $DELIMITER);
    header("Location: feedback.php?msg=" . urlencode("Feedback #$del_id deleted from text file."));
    exit;
}

// Read current records
$feedbacks = read_all_feedbacks($FILE_PATH, $DELIMITER);

// If editing, find target item
$editing_record = null;
if (!empty($edit_id)) {
    foreach ($feedbacks as $fb) {
        if ($fb['id'] == $edit_id) {
            $editing_record = $fb;
            break;
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<main class="main-wrapper">

    <div class="section-title">
        <h2>Customer Feedback & Reviews Board (PHP File Handling CRUD)</h2>
        <a href="index.php">&larr; Return to Home</a>
    </div>

    <!-- Syllabus Explanation Notice -->
    <div style="background:#eff6ff; border-left:4px solid #3b82f6; padding:12px 18px; border-radius:4px; margin-bottom:24px; font-size:0.85rem; color:#1e40af;">
        <strong>Syllabus Requirement Fulfilled:</strong> This secondary module performs full <strong>Create, Read, Update, and Delete</strong> operations directly on a flat text file (<code>data/feedback.txt</code>), completely isolated from the primary MongoDB database.
    </div>

    <?php if (isset($_GET['msg'])): ?>
        <div style="background:#ecfdf5; color:#065f46; padding:10px 16px; border-radius:6px; margin-bottom:20px; font-size:0.9rem;">
            &check; <?= htmlspecialchars($_GET['msg']); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($status_msg)): ?>
        <div style="background:#fee2e2; color:#b91c1c; padding:10px 16px; border-radius:6px; margin-bottom:20px; font-size:0.9rem;">
            &cross; <?= htmlspecialchars($status_msg); ?>
        </div>
    <?php endif; ?>

    <div style="display:grid; grid-template-columns: 1fr 2fr; gap:28px; margin-bottom:40px;">
        
        <!-- Left: Form to Add or Update (Create / Update) -->
        <div style="background:#fff; border:1px solid var(--cliq-border); border-radius:8px; padding:24px; height:fit-content;">
            <h3 style="font-size:1.15rem; margin-bottom:16px;">
                <?= $editing_record ? 'Edit Feedback #' . $editing_record['id'] : 'Submit New Feedback'; ?>
            </h3>

            <form action="feedback.php" method="POST">
                <?php if ($editing_record): ?>
                    <input type="hidden" name="update_id" value="<?= $editing_record['id']; ?>">
                    <input type="hidden" name="btn_update_feedback" value="1">

                    <div class="form-group">
                        <label>Customer Name:</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($editing_record['name']); ?>" disabled style="background:#f1f5f9;">
                    </div>
                <?php else: ?>
                    <input type="hidden" name="btn_add_feedback" value="1">

                    <div class="form-group">
                        <label for="fb_name">Customer Name:</label>
                        <input type="text" name="fb_name" id="fb_name" class="form-control" placeholder="Your name" value="<?= is_logged_in() ? htmlspecialchars($current_user['name']) : ''; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="fb_email">Email Address:</label>
                        <input type="email" name="fb_email" id="fb_email" class="form-control" placeholder="user@example.com" value="<?= is_logged_in() ? htmlspecialchars($current_user['email']) : ''; ?>" required>
                    </div>
                <?php endif; ?>

                <div class="form-group">
                    <label for="fb_rating">Experience Rating:</label>
                    <select name="fb_rating" id="fb_rating" class="form-control">
                        <option value="5" <?= ($editing_record && $editing_record['rating'] == 5) ? 'selected' : ''; ?>>&#9733;&#9733;&#9733;&#9733;&#9733; (5/5) Exceptional</option>
                        <option value="4" <?= ($editing_record && $editing_record['rating'] == 4) ? 'selected' : ''; ?>>&#9733;&#9733;&#9733;&#9733;&#9734; (4/5) Very Good</option>
                        <option value="3" <?= ($editing_record && $editing_record['rating'] == 3) ? 'selected' : ''; ?>>&#9733;&#9733;&#9733;&#9734;&#9734; (3/5) Average</option>
                        <option value="2" <?= ($editing_record && $editing_record['rating'] == 2) ? 'selected' : ''; ?>>&#9733;&#9733;&#9734;&#9734;&#9734; (2/5) Below Expectations</option>
                        <option value="1" <?= ($editing_record && $editing_record['rating'] == 1) ? 'selected' : ''; ?>>&#9733;&#9734;&#9734;&#9734;&#9734; (1/5) Poor</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="fb_comment">Review / Feedback Remarks:</label>
                    <textarea name="fb_comment" id="fb_comment" rows="4" class="form-control" placeholder="Share your shopping experience with Tata CLiQ..." required><?= $editing_record ? htmlspecialchars($editing_record['comment']) : ''; ?></textarea>
                </div>

                <button type="submit" class="btn-primary" style="width:100%;">
                    <?= $editing_record ? 'Save Changes to File' : 'Append to feedback.txt'; ?>
                </button>

                <?php if ($editing_record): ?>
                    <a href="feedback.php" class="btn-secondary" style="width:100%; text-align:center; margin-top:8px; display:block;">Cancel Edit</a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Right: Structured Table of File Records (Read / Delete) -->
        <div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:40px;">#ID</th>
                        <th>User & Contact</th>
                        <th>Rating</th>
                        <th>Feedback Comment</th>
                        <th>Timestamp</th>
                        <th style="width:120px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($feedbacks)): ?>
                        <?php foreach ($feedbacks as $item): ?>
                            <tr>
                                <td><strong><?= $item['id']; ?></strong></td>
                                <td>
                                    <strong><?= htmlspecialchars($item['name']); ?></strong><br>
                                    <span style="font-size:0.75rem; color:#666;"><?= htmlspecialchars($item['email']); ?></span>
                                </td>
                                <td style="color:#f59e0b; white-space:nowrap;">
                                    <?= str_repeat('&#9733;', $item['rating']) . str_repeat('&#9734;', 5 - $item['rating']); ?>
                                </td>
                                <td><?= htmlspecialchars($item['comment']); ?></td>
                                <td style="font-size:0.75rem; color:#777; white-space:nowrap;"><?= htmlspecialchars($item['timestamp']); ?></td>
                                <td>
                                    <a href="feedback.php?edit_id=<?= $item['id']; ?>" style="color:#2563eb; font-weight:600; font-size:0.8rem; margin-right:8px;">Edit</a>
                                    <a href="feedback.php?action=delete&id=<?= $item['id']; ?>" style="color:#dc2626; font-weight:600; font-size:0.8rem;" onclick="return confirm('Delete this record from feedback.txt?');">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align:center; color:#888; padding:30px;">
                                No feedback recorded in <code>data/feedback.txt</code> yet. Add the first one using the form!
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
