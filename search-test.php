<?php

global $Wcms;

function findSearchablePages($pages, $path = '') {
    $searchable = [];

    foreach (get_object_vars($pages) as $key => $page) {
        $currentPath = $path === '' ? $key : $path . '/' . $key;

        $subpages = get_object_vars($page->subpages);

        // Visible children determine whether THIS page is a
        // menu button/container or a normal searchable page.
        $visibleSubpages = array_filter($subpages, function($subpage) {
            return !isset($subpage->visibility) || $subpage->visibility !== 'hide';
        });

        if (count($visibleSubpages) === 0) {
            $searchable[] = $currentPath;
        }

        // Always recurse into sub-pages, regardless of visibility.
        if (count($subpages) > 0) {
            $searchable = array_merge(
                $searchable,
                findSearchablePages($page->subpages, $currentPath)
            );
        }
    }

    return $searchable;
}

$pages = $Wcms->get('pages');

$searchablePages = findSearchablePages($pages);

$searchablePages = array_filter($searchablePages, function($path) {
    return $path !== 'search';
});

echo '<!-- Search Test: searchable pages = ' . count($searchablePages) . "\n";
echo implode("\n", $searchablePages);
echo ' -->';
