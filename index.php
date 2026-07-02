<?php
require_once __DIR__ . '/db.php';

$name = '';
$email = '';
$message = '';
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($name === '' || $email === '' || $message === '') {
        $error = 'Please fill in all fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        $sql = 'INSERT INTO contacts (name, email, message) VALUES (:name, :email, :message)';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':message' => $message,
        ]);
        $success = 'Your message has been saved successfully.';
        $name = $email = $message = '';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Contact Form</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f3f4f6; color: #111827; padding: 24px; }
        .container { max-width: 560px; margin: 0 auto; background: #fff; padding: 24px; border-radius: 12px; box-shadow: 0 12px 24px rgba(15,23,42,.08); }
        h1 { margin-top: 0; font-size: 1.75rem; }
        label { display: block; margin-bottom: 0.5rem; font-weight: 600; }
        input, textarea { width: 100%; padding: 0.75rem 0.85rem; margin-bottom: 1rem; border: 1px solid #d1d5db; border-radius: 0.75rem; font-size: 1rem; }
        textarea { min-height: 150px; resize: vertical; }
        button { display: inline-flex; align-items: center; justify-content: center; padding: 0.85rem 1.3rem; border: none; border-radius: 0.75rem; background: #2563eb; color: white; font-weight: 700; cursor: pointer; }
        button:hover { background: #1d4ed8; }
        .message { padding: 1rem; margin-bottom: 1rem; border-radius: 0.75rem; }
        .success { background: #d1fae5; color: #065f46; }
        .error { background: #fee2e2; color: #991b1b; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Contact Form</h1>
        <?php if ($success): ?>
            <div class="message success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="message error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required />

            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required />

            <label for="message">Message</label>
            <textarea id="message" name="message" required><?php echo htmlspecialchars($message); ?></textarea>

            <button type="submit">Send Message</button>
        </form>
    </div>
</body>
</html>

