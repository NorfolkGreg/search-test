<?php

global $Wcms;


/*
 * Build a list of menu pages that are rendered as links.
 */
function getMenuLinks($items, $parentPath = '') {
    $links = [];

    foreach ($items as $item) {

        if (is_array($item)) {
            $item = (object)$item;
        }

        $slug = trim($item->slug ?? '', '/');

        if ($slug === '') {
            continue;
        }

        $currentPath = $parentPath === ''
            ? $slug
            : $parentPath . '/' . $slug;

        $subpages = [];

        if (!empty($item->subpages)) {
            $subpages = is_object($item->subpages)
                ? get_object_vars($item->subpages)
                : (array)$item->subpages;
        }

        $visibleSubpages = array_filter($subpages, function($subpage) {
            $subpage = is_array($subpage) ? (object)$subpage : $subpage;

            return !isset($subpage->visibility)
                || $subpage->visibility !== 'hide';
        });

        /*
         * No visible children means customClickMenu()
         * renders this item as a normal link.
         */
        if (empty($visibleSubpages)) {
            $links[] = $currentPath;
        }

        /*
         * Continue through ALL menu children, including hidden ones.
         */
        if (!empty($subpages)) {
            $links = array_merge(
                $links,
                getMenuLinks($subpages, $currentPath)
            );
        }
    }

    return $links;
}


/*
 * Walk the complete pages database and return every page path.
 */
function getAllPagePaths($pages, $parentPath = '') {
    $paths = [];

    foreach (get_object_vars($pages) as $key => $page) {

        $currentPath = $parentPath === ''
            ? $key
            : $parentPath . '/' . $key;

        $paths[] = $currentPath;

        $subpages = get_object_vars($page->subpages);

        if (!empty($subpages)) {
            $paths = array_merge(
                $paths,
                getAllPagePaths($page->subpages, $currentPath)
            );
        }
    }

    return $paths;
}


/*
 * Get the menu structure.
 */
$menuConfig = $Wcms->get('config', 'menuItems');

$menuItems = is_object($menuConfig)
    ? get_object_vars($menuConfig)
    : (array)$menuConfig;

$menuLinks = getMenuLinks($menuItems);


/*
 * Get every actual page in the database.
 */
$pages = $Wcms->get('pages');

$allPages = getAllPagePaths($pages);


/*
 * A page is searchable if:
 *   1. It exists in the pages database.
 *   2. It is a LINK in the menu.
 *   3. It is not search or 404.
 */
$searchablePages = [];

foreach ($allPages as $path) {

    if ($path === 'search' || $path === '404') {
        continue;
    }

    if (in_array($path, $menuLinks, true)) {
        $searchablePages[] = $path;
    }
}


/*
 * Display the result for testing.
 */
echo '<!-- Search Test: searchable pages = ' . count($searchablePages);
echo "\n" . implode("\n", $searchablePages);
echo "\n -->";
