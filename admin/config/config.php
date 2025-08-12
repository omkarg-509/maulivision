<?php

class PageLayout
{
    public function header($title = "Admin Panel")
    {
        echo <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{$title}</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<header>
    <h1>{$title}</h1>
    <!-- Add navigation or logo here -->
</header>
HTML;
    }

    public function footer()
    {
        echo <<<HTML
<footer>
    <p>&copy; 2024 MauliVision. All rights reserved.</p>
</footer>
</body>
</html>
HTML;
    }
}

// Usage example:
