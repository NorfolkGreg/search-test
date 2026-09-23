<?php

global $Wcms;

$pages = $Wcms->get('pages');
$games = $pages->games;

echo '<!-- Search Test: games fields = ' . implode(', ', array_keys(get_object_vars($games))) . ' -->';
