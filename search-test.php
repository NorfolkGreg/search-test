<?php

global $Wcms;

$menuItems = $Wcms->get('config', 'menuItems');
$items = is_object($menuItems) ? get_object_vars($menuItems) : (array)$menuItems;

$results = [];

foreach ($items as $item) {
    $item = is_array($item) ? (object)$item : $item;

    $slug = $item->slug ?? '(no slug)';

    if (!in_array($slug, ['games', 'wondercms', 'sundry'])) {
        continue;
    }

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

    $results[] = $slug
        . ': children=' . count($subpages)
        . ', visible=' . count($visibleSubpages);
}

echo '<!-- Search Test: ' . htmlspecialchars(implode(' | ', $results), ENT_QUOTES, 'UTF-8') . ' -->';
