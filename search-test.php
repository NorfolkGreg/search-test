<?php

global $Wcms;

$menuConfig = $Wcms->get('config', 'menuItems');
$menuItems = is_object($menuConfig) ? get_object_vars($menuConfig) : (array)$menuConfig;

$results = [];

foreach ($menuItems as $item) {

    if (is_array($item)) {
        $item = (object)$item;
    }

    $slug = trim($item->slug ?? '', '/');
    $name = $item->name ?? $item->title ?? $slug;

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

    $results[] = $slug . ' = ' . $type;
}

echo '<!-- Search Test: menu classification';
echo "\n" . implode("\n", $results);
echo "\n -->";
