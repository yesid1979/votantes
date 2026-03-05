<?php
$dir = dirname(__FILE__) . '/libs/PHPExcel';

$it = new RecursiveDirectoryIterator($dir);
foreach (new RecursiveIteratorIterator($it) as $file) {
    if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
        $content = file_get_contents($file);
        // Regex para capturar $variable{indice} y cambiarlo a $variable[indice]
        // Cubre variables simples, multidimensionales y propiedades de objeto
        $pattern = '/(\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*(?:->[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*|\[[^\]]+\])*)\{([^}]+)\}/';
        
        $newContent = preg_replace($pattern, '$1[$2]', $content);
        
        if ($newContent !== $content) {
            echo "Actualizando: " . $file . "\n";
            file_put_contents($file, $newContent);
        }
    }
}
echo "Proceso finalizado.\n";
