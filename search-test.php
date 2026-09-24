<?php

global $Wcms;

function cleanSearchTitle($title) {

    /*
     * Step 1:
     * Convert HTML entities such as &nbsp; and &#8209;
     * into their actual characters.
     */
    $title = html_entity_decode(
        $title,
        ENT_QUOTES | ENT_HTML5,
        'UTF-8'
    );

    /*
     * Step 2:
     * Remove the unwanted UTF-8 representation of a
     * non-breaking space when it appears as Â followed
     * by a space.
     */
    $title = str_replace(
        "\xC2\xA0",
        ' ',
        $title
    );

    return $title;
}

$testTitles = [
    'The Basic Concept behind&nbsp;Web&nbsp;Design',
    'Adding Images, Video&nbsp;&&nbsp;Audio to&nbsp;Your&nbsp;Site',
    'The Notepad2 editor for&nbsp;Windows',
    'Open Camera —&nbsp;an&nbsp;Android&nbsp;App',
    'A Walk around Winterton&#8209;on-Sea'
];

echo '<div style="background-color: #fff; padding: 20px;">';

echo '<h2>Search Test - Title Cleaning Test</h2>';

foreach ($testTitles as $title) {

    $cleanTitle = cleanSearchTitle($title);

    echo '<p>';

    echo '<strong>Original:</strong><br>';
    echo htmlspecialchars(
        $title,
        ENT_QUOTES,
        'UTF-8'
    );

    echo '<br><br>';

    echo '<strong>Cleaned:</strong><br>';
    echo htmlspecialchars(
        $cleanTitle,
        ENT_QUOTES,
        'UTF-8'
    );

    echo '</p>';

    echo '<hr>';
}

echo '</div>';
