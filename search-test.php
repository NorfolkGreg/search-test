<?php

global $Wcms;

$pages = $Wcms->get('pages');
$games = $pages->games;
$table = $games->subpages->table;
$castlekeep = $table->subpages->castlekeep;
$subpages = $castlekeep->subpages;

echo '<!-- Search Test: castlekeep subpages type=' . gettype($subpages) . '; properties=' . implode(', ', array_keys(get_object_vars($subpages))) . ' -->';
