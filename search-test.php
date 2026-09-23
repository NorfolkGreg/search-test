<?php

global $Wcms;

$menuItems = $Wcms->get('config', 'menuItems');

$items = is_object($menuItems) ? get_object_vars($menuItems) : (array)$menuItems;

$results = [];

foreach (['games', 'wondercms', 'sundry'] as $slug) {
    if (!isset($items[$slug])) {
        $results[] = $slug . ': not found';
        continue;
    }

    $item = is_array($items[$slug]) ? (object)$items[$slug] : $items[$slug];

    $subpages = [];
    if (!empty($item->subpages)) {
        $subpages = is_object($item->subpages)
            ? get_object_vars($item->subpages)
            : (array)$item->subpages;
    }

    $visibleSubpages = array_filter($subpages, function($sub) {
        $subObj = is_array($sub) ? (object)$sub : $sub;
        return !isset($subObj->visibility) || $subObj->visibility !== 'hide';
    });

    $results[] = $slug . ': children=' . count($subpages) . ', visible=' . count($visibleSubpages);
}

echo '<!-- Search Test: ' . implode(' | ', $results) . ' -->';
