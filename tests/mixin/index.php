<?php

require __DIR__ . '/../../vendor/autoload.php';
$core = \AgencyFramework\Handlebars\Core::init([
    'partialDir' => [__DIR__ . '/tmpl/'],
    'relativeToRoot' => '../../'
]);

$data = [
    'headline-1' => 'Example 1.',
    'headline-2' => 'Example 2.',
    'headline-3' => 'Example 3.',
    'headline-list-array' => 'Array List',
    'headline-list-object' => 'Object List',
    'example-list-array' => ['list' => [
        'Example List Item 1.',
        'Example List Item 2.',
        'Example List Item 3.'
    ]],
    'example-list-object' => ['list' => [
        'list-item-1' => 'Example List Item 1.',
        'list-item-2' => 'Example List Item 2.',
        'list-item-3' => 'Example List Item 3.'
    ]]
];

echo $core->render('index', $data);

?>
