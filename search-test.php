<?php

global $Wcms;

$pages = $Wcms->get('pages');

echo '<!-- Search Test: top-level keys = ' . implode(', ', array_keys($pages)) . ' -->';
