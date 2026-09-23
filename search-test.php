<?php

global $Wcms;

$menuItems = $Wcms->get('config', 'menuItems');

$results = [];

foreach (get_object_vars($menuItems) as $key => $item) {
    if (in_array($item->slug, ['games', 'wondercms', 'sundry'])) {
        $results[] = $item->slug . ': ' . implode(', ', array_keys(get_object_vars($item)));
    }
}

echo '<!-- Search Test: ' . implode(' | ', $results) . ' -->';
