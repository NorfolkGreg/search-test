<?php

global $Wcms;

function findLeafPages($pages, $path = '') {
    $leaves = [];

    foreach (get_object_vars($pages) as $key => $page) {
        $currentPath = $path === '' ? $key : $path . '/' . $key;
        $subpages = get_object_vars($page->subpages);

        if (count($subpages) === 0) {
            $leaves[] = $currentPath;
        } else {
            $leaves = array_merge($leaves, findLeafPages($page->subpages, $currentPath));
        }
    }

    return $leaves;
}

$pages = $Wcms->get('pages');
$leafPages = findLeafPages($pages);

echo '<!-- Search Test: leaf pages found = ' . count($leafPages) . "\n";
echo implode("\n", $leafPages);
echo ' -->';
