<?php

global $Wcms;

$pages = $Wcms->get('pages');

echo '<!-- Search Test: pages loaded = ' . count($pages) . ' -->';
