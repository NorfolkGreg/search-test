<?php

global $Wcms;

$pages = $Wcms->get('pages');

echo '<!-- Search Test: properties = ' . implode(', ', get_object_vars($pages) ? array_keys(get_object_vars($pages)) : []) . ' -->';
