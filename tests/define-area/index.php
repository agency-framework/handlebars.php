<?php

require __DIR__ . '/../../vendor/autoload.php';
$core = \AgencyFramework\Handlebars\Core::init([
    'partialDir' => [__DIR__ . '/tmpl/']
]);

$yamlData = $core->getDefaultPartialData('index');

$data = [
    'links' => [
        [
            'partial' => 'partial/link',
            'title' => 'Test Link 1.',
            'url' => '#test-link-1'
        ],
        [
            'partial' => 'partial/link',
            'title' => 'Test Link 2.',
            'url' => '#test-link-2'
        ],
        [
            'partial' => 'partial/link',
            'title' => 'Test Link 3.',
            'url' => '#test-link-3'
        ]
    ]
];

echo $core->getEngine()->render('index', $data);

?>

<h2>Area Definitions Partial "index"</h2>

<table border="1">

    <tbody>

    <tr>

        <th>Name</th>

    </tr>

    <?php

    $definitions = $GLOBALS[$core::getInstance()->getGlobalExtraDefinitionsArea()];
    foreach ($GLOBALS[$core::getInstance()->getGlobalExtraDefinitionsArea()] as $field) {
            ?>
            <tr>
                <td><?php echo array_key_exists('name', $field) ? $field['name'] : ''; ?></td>
            </tr>
            <?php
    }

    ?>


    </tbody>

</table>
