<?php
// Script to fix PHP 5.3 compatibility in the whole project
$dir = __DIR__;

function fixFiles($directory) {
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));
    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'php') {
            $path = $file->getRealPath();
            $content = file_get_contents($path);
            $original = $content;

            // Simple search for [] array declarations that are NOT variable access
            // This is a bit tricky with regex, so we do common patterns
            
            // 1. empty arrays: = [] 
            $content = preg_replace('/=\s*\[\s*\]/', '= array()', $content);
            
            // 2. return []
            $content = preg_replace('/return\s*\[\s*\]/', 'return array()', $content);

            // 3. json_encode([...])
            // We look for json_encode followed by [ and a balanced or simple content
            // Warning: this could be complex, but usually we have simple associative arrays
            $content = preg_replace_callback('/json_encode\(\s*\[(.*?)\]\s*\)/s', function($matches) {
                return 'json_encode(array(' . $matches[1] . '))';
            }, $content);

            // 4. Associative arrays in assignments
            // Matches something like $var = ['key' => 'val', ...];
            // We use a simplified pattern for common cases
            $content = preg_replace_callback('/=\s*\[([^\]]*=>[^\]]*)\]/', function($matches) {
                return '= array(' . $matches[1] . ')';
            }, $content);

            if ($content !== $original) {
                file_put_contents($path, $content);
                echo "Fixed: $path\n";
            }
        }
    }
}

// Focus on controllers, models and views
fixFiles($dir . '/controllers');
fixFiles($dir . '/models');
fixFiles($dir . '/views');
fixFiles($dir . '/formatos');

echo "Done.\n";
