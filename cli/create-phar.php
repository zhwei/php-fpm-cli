<?php
$name = 'php-fpm-cli.phar';

// zhangwei@danke.com
// The php.ini setting phar.readonly must be set to 0
$pharFile = __DIR__ . '/../dist/' . $name;

// clean up
if (file_exists($pharFile)) {
    unlink($pharFile);
}
if (file_exists($pharFile . '.gz')) {
    unlink($pharFile . '.gz');
}

# clean dev packages
system('composer install --no-dev -d ' . realpath(__DIR__ . '/../'));


// create phar
$phar = new Phar($pharFile);

$dirs = [
    'cli',
    'vendor',
];
foreach ($dirs as $dir) {
    echo "adding {$dir} files... \n";

    $dirPath = realpath(__DIR__ . '/../' . $dir);
    $dirPathLen = strlen($dirPath);
    $rdi = new \RecursiveDirectoryIterator($dirPath, \RecursiveDirectoryIterator::FOLLOW_SYMLINKS);
    $di = new \RecursiveIteratorIterator($rdi, 0, \RecursiveIteratorIterator::CATCH_GET_CHILD);

    foreach ($di as $file) {
        // Skip hidden files.
        if (substr($file->getFilename(), 0, 1) === '.') {
            continue;
        }

        $path = $file->getPathname();
        if (strpos($path, '/tests/') !== false) {
            continue;
        }

        $phar->addFromString($dir . substr($path, $dirPathLen), php_strip_whitespace($path));
    }
}

// pointing main file which requires all classes
$phar->setDefaultStub('cli/index.php', 'cli/index.php');

echo $name . " successfully created\n";
