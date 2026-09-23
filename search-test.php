<?php

global $Wcms;

$menuItems = $Wcms->get('config', 'menuItems');

$items = is_object($menuItems) ? get_object_vars($menuItems) : (array)$menuItems;

echo '<!-- Search Test: menu keys = ' . htmlspecialchars(implode(', ', array_keys($items)), ENT_QUOTES, 'UTF-8') . ' -->';
