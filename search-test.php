<?php

global $Wcms;

$pages = $Wcms->get('pages');

$tests = [];

$tests[] = 'games title=' . ($pages->games->title ?? '');
$tests[] = 'games/table title=' . ($pages->games->subpages->table->title ?? '');
$tests[] = 'games/table/castlekeep title=' . ($pages->games->subpages->table->subpages->castlekeep->title ?? '');
$tests[] = 'wondercms/background title=' . ($pages->wondercms->subpages->background->title ?? '');
$tests[] = 'sundry title=' . ($pages->sundry->title ?? '');
$tests[] = 'sundry/opencamera title=' . ($pages->sundry->subpages->opencamera->title ?? '');

echo '<!-- Search Test: ' . htmlspecialchars(implode(' | ', $tests), ENT_QUOTES, 'UTF-8') . ' -->';
