<?php

global $Wcms;

function getPageByPath($pages, $path) {
    $parts = explode('/', $path);
    $current = $pages;

    foreach ($parts as $index => $part) {

        if ($index === 0) {
            $current = $current->{$part};
        } else {
            $current = $current->subpages->{$part};
        }
    }

    return $current;
}

$pages = $Wcms->get('pages');

$testPaths = [
    'wondercms/web-design',
    'wondercms/themes/gregcustom/media',
    'wondercms/editors/external/windows/notepad2',
    'sundry/opencamera',
    'sundry/wintertonwalk'
];

echo '<div style="background-color: #fff; padding: 20px;">';

echo '<h2>Search Test - Title Diagnostic</h2>';

foreach ($testPaths as $path) {

    $page = getPageByPath($pages, $path);

    $title = $page->title ?? '';

    echo '<h3>' . htmlspecialchars($path, ENT_QUOTES, 'UTF-8') . '</h3>';

    echo '<p><strong>Title as PHP sees it:</strong><br>';

    echo htmlspecialchars(
        $title,
        ENT_QUOTES,
        'UTF-8'
    );

    echo '</p>';

    echo '<p><strong>Hexadecimal bytes:</strong><br>';

    echo '<code>';

    echo htmlspecialchars(
        bin2hex($title),
        ENT_QUOTES,
        'UTF-8'
    );

    echo '</code></p>';

    echo '<hr>';
}

echo '</div>';
