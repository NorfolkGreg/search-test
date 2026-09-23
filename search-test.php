<?php

global $Wcms;

function findSearchablePages($pages, $path = '') {
    $searchable = [];

    foreach (get_object_vars($pages) as $key => $page) {
        $currentPath = $path === '' ? $key : $path . '/' . $key;

        $subpages = get_object_vars($page->subpages);

        // Determine which children are visible in the menu.
        $visibleSubpages = array_filter($subpages, function($subpage) {
            return !isset($subpage->visibility) || $subpage->visibility !== 'hide';
        });

        if (count($visibleSubpages) === 0) {
            // No visible children: this is a searchable page.
            $searchable[] = $currentPath;
        } else {
            // Menu button/container: don't search its own content,
            // but continue searching its children.
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

// Search itself must not be searched.
$searchablePages = array_filter($searchablePages, function($path) {
    return $path !== 'search';
});

echo '<!-- Search Test: searchable pages = ' . count($searchablePages) . "\n";
echo implode("\n", $searchablePages);
echo ' -->';
