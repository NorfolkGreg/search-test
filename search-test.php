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

        if (empty($visibleSubpages)) {
            $links[] = $currentPath;
        }

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
 * Find a page in the nested pages structure from its full path.
 */
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


/*
 * Get menu links.
 */
$menuConfig = $Wcms->get('config', 'menuItems');

$menuItems = is_object($menuConfig)
    ? get_object_vars($menuConfig)
    : (array)$menuConfig;

$menuLinks = getMenuLinks($menuItems);


/*
 * Search the legitimate searchable pages.
 */
$pages = $Wcms->get('pages');

$query = 'xxx';

$matches = [];

foreach ($menuLinks as $path) {

    if ($path === 'search' || $path === '404') {
        continue;
    }

    $page = getPageByPath($pages, $path);

    $title = $page->title ?? '';
    $content = $page->content ?? '';

    $text = $title . ' ' . strip_tags($content);

    if (stripos($text, $query) !== false) {
        $matches[] = $path;
    }
}


/*
 * Display diagnostic result.
 */
echo '<!-- Search Test: query="' . $query . '"; matches=' . count($matches);
echo "\n" . implode("\n", $matches);
echo "\n -->";
