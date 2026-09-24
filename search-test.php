<?php

global $Wcms;

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

$menuConfig = $Wcms->get('config', 'menuItems');

$menuItems = is_object($menuConfig)
    ? get_object_vars($menuConfig)
    : (array)$menuConfig;

$menuLinks = getMenuLinks($menuItems);

$query = 'wo';

$pages = $Wcms->get('pages');

$matches = [];

foreach ($menuLinks as $path) {

    if ($path === 'search' || $path === '404') {
        continue;
    }

    $page = getPageByPath($pages, $path);

    $title = $page->title ?? '';
    $content = $page->content ?? '';

    $plainContent = html_entity_decode(
        strip_tags($content),
        ENT_QUOTES | ENT_HTML5,
        'UTF-8'
    );

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

echo '<div class="search-test-results" style="background-color: #fff">';

foreach ($matches as $match) {

    $url = '/' . $match['path'];

    $page = getPageByPath($pages, $match['path']);
    $content = $page->content ?? '';

    $plainContent = html_entity_decode(
        strip_tags($content),
        ENT_QUOTES | ENT_HTML5,
        'UTF-8'
    );

    $matchPosition = stripos($plainContent, $query);

    if ($matchPosition !== false) {
        $start = max(0, $matchPosition - 20);
        $excerpt = substr($plainContent, $start, 100);

        if ($start > 0) {
            $excerpt = '...' . $excerpt;
        }
    } else {
        $excerpt = substr($plainContent, 0, 100);
    }

    echo '<p>';
    echo '<a href="' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '">'
        . htmlspecialchars(
            html_entity_decode(
                $match['title'],
                ENT_QUOTES | ENT_HTML5,
                'UTF-8'
            ),
            ENT_QUOTES,
            'UTF-8'
        )
        . '</a><br>';
    echo htmlspecialchars($excerpt, ENT_QUOTES, 'UTF-8');
    echo '</p>';
}

echo '</div>';
