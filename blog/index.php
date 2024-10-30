<?php
// /blog/index.php
require_once 'Parsedown.php';

// Get the requested article name from the URL
$requestUri = $_SERVER['REQUEST_URI'];
$articleName = basename($requestUri, '.php');

// Path to articles directory
$articlesDir = dirname(__DIR__) . '/articles/';

// If no specific article is requested, show the list
if ($articleName === 'blog' || $articleName === 'index') {
    // Get all markdown files
    $files = glob($articlesDir . '*.md');
    $articles = [];
    
    foreach ($files as $file) {
        $content = file_get_contents($file);
        $title = basename($file, '.md'); // Default title
        
        // Try to get title from markdown
        if (preg_match('/^#\s*(.+)$/m', $content, $matches)) {
            $title = trim($matches[1]);
        }
        
        $articles[] = [
            'title' => $title,
            'date' => filemtime($file),
            'url' => '/blog/' . basename($file, '.md') . '.php'
        ];
    }
    
    // Sort by date
    usort($articles, function($a, $b) {
        return $b['date'] - $a['date'];
    });
    
    // Show list of articles
    ?>
    <!DOCTYPE html>
    <html lang="de">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Blog Beiträge</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                line-height: 1.6;
                margin: 0;
                padding: 20px;
                background: #f5f5f5;
            }
            .blog-list {
                max-width: 800px;
                margin: 0 auto;
                background: white;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            }
            .blog-item {
                margin-bottom: 20px;
                padding: 15px;
                border-bottom: 1px solid #eee;
            }
            .blog-title {
                font-size: 1.2em;
                color: #2c3e50;
                text-decoration: none;
                font-weight: bold;
            }
            .blog-date {
                color: #666;
                font-size: 0.9em;
                margin-top: 5px;
            }
        </style>
    </head>
    <body>
        <div class="blog-list">
            <h1>Blog Beiträge</h1>
            <?php foreach ($articles as $article): ?>
                <div class="blog-item">
                    <a href="<?php echo htmlspecialchars($article['url']); ?>" class="blog-title">
                        <?php echo htmlspecialchars($article['title']); ?>
                    </a>
                    <div class="blog-date">
                        <?php echo date('d.m.Y H:i', $article['date']); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// If an article is requested, show it
$markdownFile = $articlesDir . $articleName . '.md';

if (!file_exists($markdownFile)) {
    header('HTTP/1.0 404 Not Found');
    echo 'Artikel nicht gefunden.';
    exit;
}

// Convert markdown to HTML
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
        pre {
            background: #f4f4f4;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
        }
        code {
            background: #f4f4f4;
            padding: 2px 5px;
            border-radius: 3px;
        }
        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #3498db;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="article-container">
        <a href="/blog/" class="back-link">← Zurück zur Übersicht</a>
        <?php echo $content; ?>
    </div>
</body>
</html>