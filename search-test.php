<?php

global $Wcms;

function countLeafPages($pages) {
    $count = 0;

    foreach (get_object_vars($pages) as $page) {
        $subpages = get_object_vars($page->subpages);

        if (count($subpages) === 0) {
            $count++;
        } else {
            $count += countLeafPages($page->subpages);
        }
    }

    return $count;
}

$pages = $Wcms->get('pages');
$leafCount = countLeafPages($pages);

echo '<!-- Search Test: leaf pages found = ' . $leafCount . ' -->';
