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

function pageContainsQuery($page, $query) {
    $title = $page->title ?? '';
    $content = $page->content ?? '';

    $text = $title . ' ' . strip_tags($content);

    return stripos($text, $query) !== false;
}

$pages = $Wcms->get('pages');

$searchablePages = findSearchablePages($pages);

$query = 'the';
$matches = [];

foreach ($searchablePages as $path) {
    $parts = explode('/', $path);

    $page = $pages;

    foreach ($parts as $part) {
        $page = $page->{$part};
    }

    if (pageContainsQuery($page, $query)) {
        $matches[] = $path;
    }
}

echo '<!-- Search Test: query="' . $query . '", matches=' . count($matches) . "\n";
echo implode("\n", $matches);
echo ' -->';
