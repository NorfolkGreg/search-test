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
 * Find a page using its full path.
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
 * Get the searchable menu links.
 */
$menuConfig = $Wcms->get('config', 'menuItems');

$menuItems = is_object($menuConfig)
    ? get_object_vars($menuConfig)
    : (array)$menuConfig;

$menuLinks = getMenuLinks($menuItems);


/*
 * Search query.
 */
$query = 'wo';

$pages = $Wcms->get('pages');

$matches = [];


/*
 * Examine each searchable page.
 */
foreach ($menuLinks as $path) {

    if ($path === 'search' || $path === '404') {
        continue;
    }

    $page = getPageByPath($pages, $path);

    $title = $page->title ?? '';
    $content = $page->content ?? '';

    $plainContent = strip_tags($content);

    $titleMatch = stripos($title, $query) !== false;
    $contentMatch = stripos($plainContent, $query) !== false;

    if ($titleMatch || $contentMatch) {
        $matches[] = [
            'path' => $path,
            'title' => $title,
            'titleMatch' => $titleMatch ? 'YES' : 'NO',
            'contentMatch' => $contentMatch ? 'YES' : 'NO'
        ];
    }
}


/*
 * Display diagnostic result.
 */
echo '<div class="search-test-results" style="background-color: #fff">';

foreach ($matches as $match) {

    $url = '/' . $match['path'];

    $page = getPageByPath($pages, $match['path']);
    $content = $page->content ?? '';

    $plainContent = trim(strip_tags($content));

    $excerpt = substr($plainContent, 0, 100);

    echo '<p>';
    echo '<a href="' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '">'
        . htmlspecialchars($match['title'], ENT_QUOTES, 'UTF-8')
        . '</a><br>';
    echo htmlspecialchars($excerpt, ENT_QUOTES, 'UTF-8');
    echo '</p>';
}

echo '</div>';
