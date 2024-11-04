<?php
// Header einbinden
include '../layouts/header.php'; 

// Initialisierung der Variablen
$articlesDir = dirname(__DIR__) . '/articles/';
$articleName = isset($_GET['article']) ? $_GET['article'] : null;
$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';
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

        // Funktion zum Extrahieren des ersten Bildes
        function getFirstImage($content) {
            if (preg_match('/!\[.*?\]\((.*?)\)/', $content, $matches)) {
                return $matches[1];
            }
            return null;
        }

        // Funktion zum Extrahieren des Textauszugs
        function getExcerpt($content, $length = 200) {
            // Entferne Überschriften
            $content = preg_replace('/^#.*$/m', '', $content);
            // Entferne Bilder
            $content = preg_replace('/!\[.*?\]\(.*?\)/', '', $content);
            // Entferne Markdown-Formatierung
            $content = preg_replace('/[*_`#]/', '', $content);
            // Bereinige mehrfache Leerzeichen und Zeilenumbrüche
            $content = preg_replace('/\s+/', ' ', trim($content));
            // Kürze den Text
            if (strlen($content) > $length) {
                $content = substr($content, 0, strrpos(substr($content, 0, $length + 10), ' ')) . '...';
            }
            return $content;
        }

        // Funktion zum Durchsuchen der Artikel
        function searchArticles($searchQuery, $articlesDir) {
            $results = [];
            $files = glob($articlesDir . '*.md');
            
            foreach ($files as $file) {
                if (is_file($file)) {
                    $content = file_get_contents($file);
                    list($frontmatter, $content) = getFrontMatter($content);
                    
                    preg_match('/^#\s*(.+)$/m', $content, $matches);
                    $title = isset($matches[1]) ? trim($matches[1]) : basename($file, '.md');
                    
                    if (stripos($title, $searchQuery) !== false || 
                        stripos($content, $searchQuery) !== false) {
                        
                        $firstImage = getFirstImage($content);
                        $excerpt = getExcerpt($content);
                        
                        $results[] = [
                            'title' => $title,
                            'file' => basename($file, '.md'),
                            'date' => isset($frontmatter['date']) ? $frontmatter['date'] : date('d.m.Y', filemtime($file)),
                            'image' => $firstImage,
                            'excerpt' => $excerpt
                        ];
                    }
                }
            }
            
            return $results;
        }

        // Style für Navigation und Artikel-Liste
        echo '
        <style>
            .blogbeitrag img {
                max-width: 100%;
                height: auto;
                display: block;
                margin: 20px auto;
            }
            .back-link-container {
                text-align: center;
                margin: 20px 0;
            }
            .back-link {
                display: inline-block;
                color: #009c6c;
                text-decoration: none !important;
                font-weight: 500;
            }
            .back-link:hover {
                color: #AA0600;
                text-decoration: none !important;
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
                text-decoration: none !important;
                display: flex;
                align-items: center;
                gap: 5px;
            }
            .nav-arrow:hover {
                color: #AA0600;
                opacity: 1;
            }
            .article-item {
                display: flex;
                gap: 20px;
                padding: 20px;
                border-bottom: 1px solid #eee;
                transition: background-color 0.2s;
            }
            .article-item:hover {
                background-color: #f9f9f9;
            }
            .article-image {
                width: 200px;
                height: 150px;
                object-fit: cover;
                border-radius: 4px;
                flex-shrink: 0;
            }
            .article-content {
                flex: 1;
                display: flex;
                flex-direction: column;
                gap: 10px;
            }
            .article-title {
                font-size: 1.4em;
                color: #2c3e50;
                text-decoration: none !important;
                font-weight: bold;
                display: block;
            }
            .article-title:hover {
                color: #AA0600;
            }
            .article-date {
                color: #666;
                font-size: 0.9em;
            }
            .article-excerpt {
                color: #555;
                line-height: 1.6;
                font-size: 1em;
            }
            .search-container {
                margin: 20px 0;
                text-align: center;
            }
            .search-container input {
                padding: 8px 15px;
                width: 300px;
                border: 1px solid #ddd;
                border-radius: 4px;
                margin-right: 10px;
            }
            .search-container button {
                padding: 8px 20px;
                background: #009c6c;
                color: white;
                border: none;
                border-radius: 4px;
                cursor: pointer;
            }
            .search-container button:hover {
                background: #AA0600;
            }
            /* Pagination Links Hover */
            [style*="color: #009c6c"]:hover {
                background: #AA0600 !important;
                border-color: #AA0600 !important;
                color: white !important;
            }
            @media (max-width: 768px) {
                .article-item {
                    flex-direction: column;
                }
                .article-image {
                    width: 100%;
                    height: 200px;
                }
                .search-container input {
                    width: 100%;
                    margin-bottom: 10px;
                }
            }
                
        </style>';

        // Suchformular
        echo '<div class="search-container">
            <form action="/navigation/blog.php" method="GET">
                <input type="text" 
                       name="search" 
                       value="' . htmlspecialchars($searchQuery) . '" 
                       placeholder="Suche in Artikeln...">
                <button type="submit">Suchen</button>
            </form>
        </div>';

        $markdownFile = $articleName ? $articlesDir . $articleName . '.md' : null;

        if ($articleName && file_exists($markdownFile)) {
            $rawContent = file_get_contents($markdownFile);
            list($frontmatter, $content) = getFrontMatter($rawContent);
            $html = $parsedown->text($content);
            
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

            usort($allArticles, function($a, $b) {
                $dateA = DateTime::createFromFormat('d.m.Y', $a['date']) ?: new DateTime();
                $dateB = DateTime::createFromFormat('d.m.Y', $b['date']) ?: new DateTime();
                return $dateB <=> $dateA;
            });

            $currentIndex = array_search($articleName, array_column($allArticles, 'file'));
            $prevArticle = $currentIndex > 0 ? $allArticles[$currentIndex - 1] : null;
            $nextArticle = $currentIndex < count($allArticles) - 1 ? $allArticles[$currentIndex + 1] : null;

            echo '<div class="back-link-container">';
            echo '<a href="/navigation/blog.php" class="back-link">← Zurück zur Übersicht</a>';
            echo '</div>';
            
            echo $html;
            
            echo '<div class="back-link-container">';
            echo '<a href="/navigation/blog.php" class="back-link">← Zurück zur Übersicht</a>';
            echo '</div>';

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
            // Artikelübersicht oder Suchergebnisse
            if (!empty($searchQuery)) {
                $articles = searchArticles($searchQuery, $articlesDir);
                if (empty($articles)) {
                    echo '<p>No articles found for "' . htmlspecialchars($searchQuery) . '"</p>';
                } else {
                    echo '<p>Search results for "' . htmlspecialchars($searchQuery) . '":</p>';
                }
            } else {
                $files = glob($articlesDir . '*.md');
                $articles = [];
                
                foreach ($files as $file) {
                    if (is_file($file) && pathinfo($file, PATHINFO_EXTENSION) === 'md') {
                        $rawContent = file_get_contents($file);
                        list($frontmatter, $content) = getFrontMatter($rawContent);
                        
                        preg_match('/^#\s*(.+)$/m', $content, $matches);
                        $title = isset($matches[1]) ? trim($matches[1]) : basename($file, '.md');
                        
                        $firstImage = getFirstImage($content);
                        $excerpt = getExcerpt($content);
                        
                        $articles[] = [
                            'title' => $title,
                            'file' => basename($file, '.md'),
                            'date' => isset($frontmatter['date']) ? $frontmatter['date'] : date('d.m.Y', filemtime($file)),
                            'image' => $firstImage,
                            'excerpt' => $excerpt
                        ];
                    }
                }
            }

            usort($articles, function($a, $b) {
                $dateA = DateTime::createFromFormat('d.m.Y', $a['date']) ?: new DateTime();
                $dateB = DateTime::createFromFormat('d.m.Y', $b['date']) ?: new DateTime();
                return $dateB <=> $dateA;
            });

            $itemsPerPage = 5;
            $currentPage = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
            $totalPages = ceil(count($articles) / $itemsPerPage);
            $offset = ($currentPage - 1) * $itemsPerPage;
            $articlesOnPage = array_slice($articles, $offset, $itemsPerPage);

            if (!empty($articles)) {
                echo '<div class="articles-list" style="display: flex; flex-direction: column; gap: 20px;">';
                foreach ($articlesOnPage as $article) {
                    ?>
                    <div class="article-item">
                        <?php if ($article['image']): ?>
                            <img src="<?php echo htmlspecialchars($article['image']); ?>" 
                                 alt="<?php echo htmlspecialchars($article['title']); ?>"
                                 class="article-image">
                        <?php endif; ?>
                        <div class="article-content">
                            <a href="/navigation/blog.php?article=<?php echo urlencode($article['file']); ?>" 
                               class="article-title">
                                <?php echo htmlspecialchars($article['title']); ?>
                            </a>
                            <div class="article-date">
                                <?php echo htmlspecialchars($article['date']); ?>
                            </div>
                            <div class="article-excerpt">
                                <?php echo htmlspecialchars($article['excerpt']); ?>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                echo '</div>';

                if ($totalPages > 1) {
                    echo '<div style="display: flex; justify-content: center; gap: 10px; margin-top: 30px;">';
                    for ($i = 1; $i <= $totalPages; $i++) {
                        $activeStyle = $i === $currentPage ? 'background: #009c6c; color: white;' : 'color: #009c6c;';
                        $pageUrl = '/navigation/blog.php?page=' . $i;
                        if (!empty($searchQuery)) {
                            $pageUrl .= '&search=' . urlencode($searchQuery);
                        }
                        echo '<a href="' . $pageUrl . '" style="padding: 5px 10px; text-decoration: none !important; border: 1px solid #009c6c; ' . $activeStyle . '">' . $i . '</a>';
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