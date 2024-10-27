<?php
include '../layouts/header.php';
?>

<main id="content">
<div class="content">
<div class="blogbeitrag">
    <section>
        <!-- Notion Anfang -->
        <?php
        ini_set('display_errors', 1);
        error_reporting(E_ALL);

        $notionPageId = '12c405b924008088a65ccf1e9822e32f';
        $apiUrl = "https://notion-api.splitbee.io/v1/page/{$notionPageId}";

        function shouldSkipBlock($block) {
            // Überprüfe ob es sich um einen Seitentitel handelt
            if (isset($block['value']['type']) && $block['value']['type'] === 'page') {
                return true;
            }
            
            // Überprüfe ob es der "Name des Seite" Text ist
            if (isset($block['value']['properties']['title'])) {
                $text = '';
                foreach ($block['value']['properties']['title'] as $textPart) {
                    if (is_array($textPart)) {
                        $text .= $textPart[0];
                    } else {
                        $text .= $textPart;
                    }
                }
                
                // Verschiedene Varianten des Titels prüfen
                $skipTitles = [
                    'Name des Seite',
                    'Name der Seite',
                    'Unbenannt',
                    'Untitled'
                ];
                
                if (in_array(trim($text), $skipTitles)) {
                    return true;
                }
            }
            
            return false;
        }

        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $apiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
            
            $response = curl_exec($ch);
            
            if (curl_errno($ch)) {
                throw new Exception(curl_error($ch));
            }
            
            curl_close($ch);
            $notionData = json_decode($response, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('JSON Dekodierung fehlgeschlagen: ' . json_last_error_msg());
            }

            echo "<div class='notion-content'>";
            
            // Sortiere die Blöcke nach ihrer Position
            $sortedBlocks = [];
            foreach ($notionData as $blockId => $block) {
                if (isset($block['value']['order'])) {
                    $sortedBlocks[$block['value']['order']] = $block;
                } else {
                    $sortedBlocks[] = $block;
                }
            }
            ksort($sortedBlocks);

            $isFirstBlock = true;
            
            foreach ($sortedBlocks as $block) {
                // Überspringe den ersten Block (Seitentitel)
                if ($isFirstBlock) {
                    $isFirstBlock = false;
                    continue;
                }

                // Überprüfe ob der Block übersprungen werden soll
                if (shouldSkipBlock($block)) {
                    continue;
                }

                $value = $block['value'];
                
                // Code Blöcke
                if ($value['type'] === 'code') {
                    $code = '';
                    if (isset($value['properties']['title'])) {
                        foreach ($value['properties']['title'] as $codePart) {
                            if (is_array($codePart)) {
                                $code .= $codePart[0];
                            } else {
                                $code .= $codePart;
                            }
                        }
                    }
                    $language = isset($value['properties']['language'][0][0]) ? 
                               strtolower($value['properties']['language'][0][0]) : 
                               'plaintext';
                    
                    echo "<div class='notion-code-block'>";
                    echo "<div class='notion-code-language'>{$language}</div>";
                    echo "<pre><code class='language-{$language}'>" . htmlspecialchars($code) . "</code></pre>";
                    echo "</div>";
                }
                // Text Content
                elseif (isset($value['properties']['title'])) {
                    $text = '';
                    foreach ($value['properties']['title'] as $textPart) {
                        if (is_array($textPart)) {
                            $text .= $textPart[0];
                        } else {
                            $text .= $textPart;
                        }
                    }

                    switch ($value['type']) {
                        case 'header':
                            echo "<h1 class='notion-h1'>" . htmlspecialchars($text) . "</h1>";
                            break;
                        case 'sub_header':
                            echo "<h2 class='notion-h2'>" . htmlspecialchars($text) . "</h2>";
                            break;
                        case 'sub_sub_header':
                            echo "<h3 class='notion-h3'>" . htmlspecialchars($text) . "</h3>";
                            break;
                        case 'text':
                            if (!empty(trim($text))) {
                                echo "<p class='notion-text'>" . htmlspecialchars($text) . "</p>";
                            }
                            break;
                        case 'bulleted_list':
                            echo "<ul class='notion-list'><li>" . htmlspecialchars($text) . "</li></ul>";
                            break;
                        case 'numbered_list':
                            echo "<ol class='notion-list'><li>" . htmlspecialchars($text) . "</li></ol>";
                            break;
                        case 'quote':
                            echo "<blockquote class='notion-quote'>" . htmlspecialchars($text) . "</blockquote>";
                            break;
                    }
                }
                
                // Bilder
                if ($value['type'] === 'image') {
                    $imageUrl = isset($value['properties']['source'][0][0]) ? 
                              $value['properties']['source'][0][0] : 
                              (isset($value['format']['display_source']) ? 
                               $value['format']['display_source'] : '');
                    
                    if ($imageUrl) {
                        $caption = isset($value['properties']['caption'][0][0]) ? 
                                 htmlspecialchars($value['properties']['caption'][0][0]) : 
                                 '';
                        
                        echo "<figure class='notion-image-container'>";
                        echo "<img src='" . htmlspecialchars($imageUrl) . "' class='notion-image' alt='{$caption}'>";
                        if ($caption) {
                            echo "<figcaption class='notion-image-caption'>{$caption}</figcaption>";
                        }
                        echo "</figure>";
                    }
                }
            }
            
            echo "</div>";
            
        } catch (Exception $e) {
            echo "<div class='error-message'>";
            echo "<p>Fehler beim Laden der Notion-Inhalte: " . htmlspecialchars($e->getMessage()) . "</p>";
            echo "</div>";
        }
        ?>

<style>
        .notion-content {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Helvetica, sans-serif;
        }
        .notion-code-block {
            margin: 1.5em 0;
            background: #f6f8fa;
            border-radius: 6px;
            overflow: hidden;
        }
        .notion-code-language {
            background: #e1e4e8;
            padding: 0.5em 1em;
            font-size: 0.85em;
            color: #24292e;
            border-bottom: 1px solid #d1d5da;
        }
        .notion-code-block pre {
            margin: 0;
            padding: 1em;
            overflow-x: auto;
            background: #f6f8fa;
        }
        .notion-code-block code {
            font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, Courier, monospace;
            font-size: 0.9em;
            line-height: 1.5;
            color: #24292e;
        }
        /* Rest der Styles bleiben gleich */
        </style>
        <!-- Notion Ende -->
    </section>
</div>
</div>
</main>

<?php include '../layouts/footer.php'; ?>
