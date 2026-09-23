<?php

global $Wcms;

$pages = $Wcms->get('pages');
$games = $pages->games;
$subpages = $games->subpages;

echo '<!-- Search Test: subpages type=' . gettype($subpages) . '; properties=' . implode(', ', array_keys(get_object_vars($subpages))) . ' -->';
