<?php

global $Wcms;

function findSearchablePages($pages, $path = '') {
    $searchable = [];

    foreach (get_object_vars($pages) as $key => $page) {

        // Permanent exclusions
        if ($key === 'search' || $key === '404') {
            continue;
        }

        $currentPath = $path === '' ? $key : $path . '/' . $key;

        $subpages = get_object_vars($page->subpages);

        // Determine which children are visible in the menu.
        $visibleSubpages = array_filter($subpages, function($subpage) {
            return !isset($subpage->visibility)
                || $subpage->visibility !== 'hide';
        });

        // If this page has no visible children, the menu renders
        // it as a normal link, so its own content is searchable.
        if (count($visibleSubpages) === 0) {
            $searchable[] = $currentPath;
        }

        // Always continue down into its children.
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

echo '<!-- Search Test: searchable pages = ' . count($searchablePages) . "\n";
echo implode("\n", $searchablePages);
echo ' -->';
