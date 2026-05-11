<?php
$page_title = 'Request Admin Access';
require_once 'includes/header.php';

$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';
    
    if (!$username || !$email || !$password) {
        $error = 'All fields are required.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        require_once 'includes/auth.php';
        if (request_account($username, $email, $password)) {
            $success = true;
        } else {
            $error = 'That username is already taken or has a pending request.';
        }
    }
}
?>
<section class="bg-navy py-10">
    <div class="container mx-auto px-4">
        <h1 class="text-2xl font-bold text-white">Request <span class="text-gold">Admin Access</span></h1>
        <p class="text-gray-400 text-sm mt-1">For McKee Realty team members only</p>
    </div>
</section>

<section class="py-12">
    <div class="container mx-auto px-4 max-w-lg">
        <?php if ($success): ?>
        <div class="bg-green-50 border border-green-200 text-green-700 p-6 text-sm">
            <h2 class="font-semibold mb-2">Request Submitted!</h2>
            <p>Your admin account request has been sent to Billy McKee for approval. You'll receive access once approved.</p>
            <a href="/" class="text-green-800 underline mt-3 inline-block">Back to Home</a>
        </div>
        <?php else: ?>
        <div class="bg-white border border-gray-200 p-8">
            <h2 class="text-lg font-semibold text-navy mb-4">Request an Account</h2>
            <p class="text-sm text-gray-500 mb-6">Fill out this form to request admin access. An administrator will review and approve your account.</p>
            
            <?php if ($error): ?>
            <div class="bg-red-50 text-red-600 p-3 text-sm mb-4 border border-red-200"><?= $error ?></div>
            <?php endif; ?>
            
            <form method="POST" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Desired Username *</label>
                    <input type="text" name="username" required class="border border-gray-300 w-full px-3 py-2.5 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Your Email *</label>
                    <input type="email" name="email" required class="border border-gray-300 w-full px-3 py-2.5 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password *</label>
                    <input type="password" name="password" required minlength="6" class="border border-gray-300 w-full px-3 py-2.5 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password *</label>
                    <input type="password" name="confirm" required minlength="6" class="border border-gray-300 w-full px-3 py-2.5 text-sm">
                </div>
                <button type="submit" class="btn-primary w-full text-sm">Submit Request</button>
            </form>
            <p class="text-center text-xs text-gray-400 mt-4">
                <a href="/admin/login.php" class="hover:text-navy transition">Already have an account? Sign in</a>
            </p>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>