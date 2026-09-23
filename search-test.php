<?php

global $Wcms;

$pages = $Wcms->get('pages');

echo '<!-- Search Test: type = ' . gettype($pages) . '; value = ' . htmlspecialchars(print_r($pages, true), ENT_QUOTES, 'UTF-8') . ' -->';
