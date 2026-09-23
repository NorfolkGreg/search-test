<?php

global $Wcms;

function findSearchablePages($pages, $path = '') {
    $searchable = [];

    foreach (get_object_vars($pages) as $key => $page) {
        $currentPath = $path === '' ? $key : $path . '/' . $key;

        $subpages = get_object_vars($page->subpages);

        $visibleSubpages = array_filter($subpages, function($subpage) {
            return !isset($subpage->visibility) || $subpage->visibility !== 'hide';
        });

        if (count($visibleSubpages) === 0) {
            if ($currentPath !== 'search' && $currentPath !== '404') {
                $searchable[] = $currentPath;
            }
        }

        if (count($subpages) > 0) {
            $searchable = array_merge(
                $searchable,
                findSearchablePages($page->subpages, $currentPath)
            );
        }
    }

    return $searchable;
}

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
$searchablePages = findSearchablePages($pages);

$query = 'the';
$results = [];

foreach ($searchablePages as $path) {
    $page = getPageByPath($pages, $path);

    $title = $page->title ?? '';
    $content = $page->content ?? '';

    $text = $title . ' ' . strip_tags($content);
    $found = stripos($text, $query) !== false ? 'YES' : 'NO';

    $results[] = $path
        . ' | title=' . $title
        . ' | content=' . ($content !== '' ? 'YES' : 'NO')
        . ' | match=' . $found;
}

echo '<!-- Search Test: diagnostic' . "\n";
echo implode("\n", $results);
echo ' -->';
