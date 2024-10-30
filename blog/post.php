<?php
// post.php
require_once 'Parsedown.php';

// Get article name from URL
$urlPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$articleName = basename($urlPath, '.php');

// Path to markdown file
$articlesDir = dirname(__DIR__) . '/articles/';
$markdownFile = $articlesDir . $articleName . '.md';

// Check if file exists
if (!file_exists($markdownFile)) {
    header("HTTP/1.0 404 Not Found");
    echo "Artikel nicht gefunden.";
    exit;
}

// Read and convert markdown
$markdown = file_get_contents($markdownFile);
$parsedown = new Parsedown();
$content = $parsedown->text($markdown);

// Get title
preg_match('/^#\s*(.+)$/m', $markdown, $matches);
$title = isset($matches[1]) ? trim($matches[1]) : $articleName;
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background: #f5f5f5;
        }
        .article-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        img {
            max-width: 100%;
            height: auto;
        }
        code {
            background: #f4f4f4;
            padding: 2px 5px;
            border-radius: 3px;
        }
        pre code {
            display: block;
            padding: 15px;
            overflow-x: auto;
        }
        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #3498db;
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="article-container">
        <a href="/navigation/blog.php" class="back-link">← Zurück zur Übersicht</a>
        <?php echo $content; ?>
    </div>
</body>
</html>