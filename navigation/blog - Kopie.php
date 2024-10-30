<?php
// Header einbinden
include '../layouts/header.php'; 
?>

<main id="content">
<div class="content">
<div class="blogbeitrag">
    <section>
        <?php
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        require_once dirname(__DIR__) . '/blog/Parsedown.php';

        // Funktion zum Extrahieren des Frontmatters
        function getFrontMatter($content) {
            $pattern = '/^---\s*\n(.*?)\n---\s*\n/s';
            $frontmatter = [];
            
            if (preg_match($pattern, $content, $matches)) {
                $lines = explode("\n", trim($matches[1]));
                foreach ($lines as $line) {
                    if (strpos($line, ':') !== false) {
                        list($key, $value) = explode(':', $line, 2);
                        $frontmatter[trim($key)] = trim($value);
                    }
                }
                $content = preg_replace($pattern, '', $content);
            }
            
            return [$frontmatter, $content];
        }

        $articlesDir = dirname(__DIR__) . '/articles/';
        $parsedown = new Parsedown();
        $articleName = isset($_GET['article']) ? $_GET['article'] : null;
        $markdownFile = $articleName ? $articlesDir . $articleName . '.md' : null;

        // Style für den Zurück-Link
        $backLinkStyle = '
            <style>
            .back-link-container {
                text-align: center;
                margin: 20px 0;
            }
            .back-link {
                display: inline-block;
                color: #009c6c;
                text-decoration: none;
                font-weight: 500;
            }
            .back-link:hover {
                text-decoration: underline;
            }
            </style>
        ';

        if ($articleName && file_exists($markdownFile)) {
            $rawContent = file_get_contents($markdownFile);
            list($frontmatter, $content) = getFrontMatter($rawContent);
            $html = $parsedown->text($content);
            
            preg_match('/^#\s*(.+)$/m', $content, $matches);
            $title = isset($matches[1]) ? trim($matches[1]) : $articleName;
            
            // Ausgabe der Styles
            echo $backLinkStyle;
            
            // Oberer Zurück-Link
            echo '<div class="back-link-container">';
            echo '<a href="/navigation/blog.php" class="back-link">← Zurück zur Übersicht</a>';
            echo '</div>';
            
            // Artikel-Inhalt
            echo $html;
            
            // Unterer Zurück-Link
            echo '<div class="back-link-container">';
            echo '<a href="/navigation/blog.php" class="back-link">← Zurück zur Übersicht</a>';
            echo '</div>';

        } else {
            $files = glob($articlesDir . '*.md');
            $articles = [];

            foreach ($files as $file) {
                if (is_file($file) && pathinfo($file, PATHINFO_EXTENSION) === 'md') {
                    $rawContent = file_get_contents($file);
                    list($frontmatter, $content) = getFrontMatter($rawContent);
                    
                    preg_match('/^#\s*(.+)$/m', $content, $matches);
                    $title = isset($matches[1]) ? trim($matches[1]) : basename($file, '.md');
                    
                    $articles[] = [
                        'title' => $title,
                        'file' => basename($file, '.md'),
                        'date' => isset($frontmatter['date']) ? $frontmatter['date'] : date('d.m.Y', filemtime($file))
                    ];
                }
            }

            // Nach Datum sortieren (neueste zuerst)
            usort($articles, function($a, $b) {
                $dateA = DateTime::createFromFormat('d.m.Y', $a['date']) ?: new DateTime();
                $dateB = DateTime::createFromFormat('d.m.Y', $b['date']) ?: new DateTime();
                return $dateB <=> $dateA;
            });
            
            echo '<h1>Blog Beiträge</h1>';
            
            if (empty($articles)) {
                echo '<p>Keine Blog-Beiträge gefunden.</p>';
            } else {
                echo '<div class="articles-list" style="display: flex; flex-direction: column; gap: 20px;">';
                foreach ($articles as $article) {
                    ?>
                    <div class="article-item" style="padding: 15px; border-bottom: 1px solid #eee; transition: background-color 0.2s;">
                        <a href="/navigation/blog.php?article=<?php echo urlencode($article['file']); ?>" 
                           style="font-size: 1.2em; color: #2c3e50; text-decoration: none; font-weight: bold; display: block; margin-bottom: 5px;">
                            <?php echo htmlspecialchars($article['title']); ?>
                        </a>
                        <div style="color: #666; font-size: 0.9em;">
                            <?php echo htmlspecialchars($article['date']); ?>
                        </div>
                    </div>
                    <?php
                }
                echo '</div>';
            }
        }
        ?>
    </section>
</div>
</div>
</main>

<?php 
include '../layouts/footer.php'; 
?>