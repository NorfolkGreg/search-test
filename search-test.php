<?php

global $Wcms;

$pages = $Wcms->get('pages');

echo '<!-- Search Test: type=' . gettype($pages) . '; count=' . count($pages) . '; keys=' . implode(', ', array_keys($pages)) . ' -->';
