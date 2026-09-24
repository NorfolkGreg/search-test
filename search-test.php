<?php

global $Wcms;

function classifyMenuItems($items, $parentPath = '') {

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
            $subpage = is_array($subpage)
                ? (object)$subpage
                : $subpage;

            return !isset($subpage->visibility)
                || $subpage->visibility !== 'hide';
        });

        if (empty($visibleSubpages)) {
            $type = 'LINK';
        } else {
            $type = 'BUTTON';
        }

        echo '<p>';
        echo '<strong>'
            . htmlspecialchars($currentPath, ENT_QUOTES, 'UTF-8')
            . '</strong> &mdash; ';
        echo $type;
        echo '</p>';

        if (!empty($subpages)) {
            classifyMenuItems($subpages, $currentPath);
        }
    }
}

$menuConfig = $Wcms->get('config', 'menuItems');

$menuItems = is_object($menuConfig)
    ? get_object_vars($menuConfig)
    : (array)$menuConfig;

echo '<div style="background-color: #fff; padding: 20px;">';

echo '<h2>Search Test - Menu Classification</h2>';

echo '<p>';
echo 'LINK = page should be searchable.';
echo '<br>';
echo 'BUTTON = container only; page itself should not be searched.';
echo '</p>';

classifyMenuItems($menuItems);

echo '</div>';
