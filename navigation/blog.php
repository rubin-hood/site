<?php
// Header einbinden
include '../layouts/header.php'; 

// Initialisierung der Variablen
$articlesDir = dirname(__DIR__) . '/articles/';
$articleName = isset($_GET['article']) ? $_GET['article'] : null;
?>

<main id="content">
<div class="content">
<div class="blogbeitrag">
    <section>
        <?php
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        require_once dirname(__DIR__) . '/blog/Parsedown.php';
        $parsedown = new Parsedown();

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

        // Style für Navigation
        echo '
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
            .article-navigation {
                display: flex;
                justify-content: space-between;
                margin: 40px auto;
                padding-top: 20px;
                max-width: 800px;
            }
            .nav-arrow {
                color: #009c6c;
                text-decoration: none;
                display: flex;
                align-items: center;
                gap: 5px;
            }
            .nav-arrow:hover {
                opacity: 0.8;
            }
        </style>';

        $markdownFile = $articleName ? $articlesDir . $articleName . '.md' : null;

        if ($articleName && file_exists($markdownFile)) {
            $rawContent = file_get_contents($markdownFile);
            list($frontmatter, $content) = getFrontMatter($rawContent);
            $html = $parsedown->text($content);
            
            // Hole alle Artikel für die Navigation
            $allFiles = glob($articlesDir . '*.md');
            $allArticles = [];
            
            foreach ($allFiles as $file) {
                if (is_file($file) && pathinfo($file, PATHINFO_EXTENSION) === 'md') {
                    $fileContent = file_get_contents($file);
                    list($fileFrontmatter, $fileContent) = getFrontMatter($fileContent);
                    preg_match('/^#\s*(.+)$/m', $fileContent, $matches);
                    $fileTitle = isset($matches[1]) ? trim($matches[1]) : basename($file, '.md');
                    
                    $allArticles[] = [
                        'title' => $fileTitle,
                        'file' => basename($file, '.md'),
                        'date' => isset($fileFrontmatter['date']) ? $fileFrontmatter['date'] : date('d.m.Y', filemtime($file))
                    ];
                }
            }

            // Sortiere nach Datum
            usort($allArticles, function($a, $b) {
                $dateA = DateTime::createFromFormat('d.m.Y', $a['date']) ?: new DateTime();
                $dateB = DateTime::createFromFormat('d.m.Y', $b['date']) ?: new DateTime();
                return $dateB <=> $dateA;
            });

            $currentIndex = array_search($articleName, array_column($allArticles, 'file'));
            $prevArticle = $currentIndex > 0 ? $allArticles[$currentIndex - 1] : null;
            $nextArticle = $currentIndex < count($allArticles) - 1 ? $allArticles[$currentIndex + 1] : null;

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

            // Artikel-Navigation mit begrenzter Breite
            echo '<div class="article-navigation">';
            if ($prevArticle) {
                echo '<a href="/navigation/blog.php?article=' . urlencode($prevArticle['file']) . '" class="nav-arrow">
                        <span style="font-size: 24px;">←</span>
                      </a>';
            } else {
                echo '<div></div>';
            }
            
            if ($nextArticle) {
                echo '<a href="/navigation/blog.php?article=' . urlencode($nextArticle['file']) . '" class="nav-arrow">
                        <span style="font-size: 24px;">→</span>
                      </a>';
            } else {
                echo '<div></div>';
            }
            echo '</div>';

        } else {
            // Artikelübersicht
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

            // Nach Datum sortieren
            usort($articles, function($a, $b) {
                $dateA = DateTime::createFromFormat('d.m.Y', $a['date']) ?: new DateTime();
                $dateB = DateTime::createFromFormat('d.m.Y', $b['date']) ?: new DateTime();
                return $dateB <=> $dateA;
            });

            // Pagination
            $itemsPerPage = 5; // Anzahl der Artikel pro Seite
            $currentPage = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
            $totalPages = ceil(count($articles) / $itemsPerPage);
            $offset = ($currentPage - 1) * $itemsPerPage;
            $articlesOnPage = array_slice($articles, $offset, $itemsPerPage);


            
            if (empty($articles)) {
                echo '<p>Keine Blog-Beiträge gefunden.</p>';
            } else {
                echo '<div class="articles-list" style="display: flex; flex-direction: column; gap: 20px;">';
                foreach ($articlesOnPage as $article) {
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

                if ($totalPages > 1) {
                    echo '<div style="display: flex; justify-content: center; gap: 10px; margin-top: 30px;">';
                    for ($i = 1; $i <= $totalPages; $i++) {
                        $activeStyle = $i === $currentPage ? 'background: #009c6c; color: white;' : 'color: #009c6c;';
                        echo '<a href="/navigation/blog.php?page=' . $i . '" style="padding: 5px 10px; text-decoration: none; border: 1px solid #009c6c; ' . $activeStyle . '">' . $i . '</a>';
                    }
                    echo '</div>';
                }
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