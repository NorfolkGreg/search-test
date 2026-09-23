<?php

global $Wcms;

$menuItems = $Wcms->get('config', 'menuItems');

$items = is_object($menuItems) ? get_object_vars($menuItems) : (array)$menuItems;

$results = [];

foreach ($items as $index => $item) {
    $item = is_array($item) ? (object)$item : $item;

    $name = $item->name ?? '(no name)';
    $slug = $item->slug ?? '(no slug)';

    $subpages = [];
    if (!empty($item->subpages)) {
        $subpages = is_object($item->subpages)
            ? get_object_vars($item->subpages)
            : (array)$item->subpages;
    }

    $results[] = $index . ': name=' . $name
        . ', slug=' . $slug
        . ', children=' . count($subpages);
}

echo '<!-- Search Test: ' . htmlspecialchars(implode(' | ', $results), ENT_QUOTES, 'UTF-8') . ' -->';
