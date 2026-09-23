<?php

global $Wcms;

$pages = $Wcms->get('pages');
$games = $pages->games;
$table = $games->subpages->table;
$subpages = $table->subpages;

echo '<!-- Search Test: table subpages type=' . gettype($subpages) . '; properties=' . implode(', ', array_keys(get_object_vars($subpages))) . ' -->';
