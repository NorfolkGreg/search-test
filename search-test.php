<?php

global $Wcms;

$pages = $Wcms->get('pages');
$games = $pages->games;
$table = $games->subpages->table;

echo '<!-- Search Test: table fields = ' . implode(', ', array_keys(get_object_vars($table))) . ' -->';
