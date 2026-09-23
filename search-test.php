<?php

global $Wcms;

function classifyMenuItems($items, $parentPath = '') {
    $results = [];

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

        $type = empty($visibleSubpages) ? 'LINK' : 'BUTTON';

        $results[] = $currentPath . ' = ' . $type;

        if (!empty($subpages)) {
            $results = array_merge(
                $results,
                classifyMenuItems($subpages, $currentPath)
            );
        }
    }

    return $results;
}

$menuConfig = $Wcms->get('config', 'menuItems');

$menuItems = is_object($menuConfig)
    ? get_object_vars($menuConfig)
    : (array)$menuConfig;

$results = classifyMenuItems($menuItems);

echo '<!-- Search Test: recursive menu classification';
echo "\n" . implode("\n", $results);
echo "\n -->";
