<?php
$pageTitle = $pageTitle ?? "John Rave| Portfolio";
$basePath = str_contains($_SERVER["SCRIPT_NAME"] ?? "", "/pages/") ? "../" : "";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Personal portfolio of John Rave Rodriguez Orpiano">
    <meta name="author" content="John Rave Rodriguez Orpiano">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <?php if ($basePath !== ""): ?>
        <base href="<?= $basePath ?>">
    <?php endif; ?>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>
    <?php include __DIR__ . "/navbar.php"; ?>