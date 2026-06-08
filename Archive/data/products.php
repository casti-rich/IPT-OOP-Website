<?php
spl_autoload_register(function ($class) {
    $classFile = __DIR__ . '/../Classes/' . $class . '.inc.php';

    if (file_exists($classFile)) {
        require_once $classFile;
    }
});

$products = [];

$baseFenderRhodes = 'Assets/Products/Fender_Rhodes/';
$products['fender-rhodes-suitcase-73-key'] = new Keyboard(
    1,
    'Fender Rhodes Suitcase 73-Key',
    'The Fender Rhodes Suitcase is a classic electric piano that has been a staple in the music industry for decades.',
    458.00,
    3,
    true,
    [
        'img1' => $baseFenderRhodes . '01.jpg',
        'img2' => $baseFenderRhodes . '02.jpg',
        'img3' => $baseFenderRhodes . '03.jpg',
        'img4' => $baseFenderRhodes . '04.jpg',
        'img5' => $baseFenderRhodes . '05.jpg',
    ],
    73
);

$baseGibsonKita = 'Assets/Products/Gibson_Les-Paul-Jr._Kita-Model/';
$products['gibson-les-paul-jr-ikuyo-kita-model'] = new Product(
    2,
    'Gibson Les Paul Jr. Ikuyo Kita Model',
    'This accessible premium model features an Antique Pelham Blue finish and a modern medium C neck.',
    990.00,
    5,
    true,
    [
        'img1' => $baseGibsonKita . '01.jpg',
        'img2' => $baseGibsonKita . '02.jpg',
        'img3' => $baseGibsonKita . '03.jpg',
        'img4' => $baseGibsonKita . '04.jpg',
        'img5' => $baseGibsonKita . '05.jpg',
    ]
);

$basePlacehold = 'Assets/Products/Placehold/';
$products['rickenbacker-4003-bass'] = new Product(
    3,
    'Rickenbacker 4003 Bass',
    'Iconic bass with a punchy, articulate tone suited for modern and classic styles.',
    670.00,
    5,
    true,
    [
        'img1' => $basePlacehold . '01.jpg',
    ]
);

$products['fender-precision-plus-bass'] = new Product(
    4,
    'Fender Precision Plus Bass',
    'A versatile bass with a comfortable neck and classic Precision tone.',
    550.00,
    5,
    true,
    [
        'img1' => $basePlacehold . '02.jpg',
    ]
);

$products['1959-gibson-les-paul-standard'] = new Product(
    5,
    '1959 Gibson Les Paul Standard',
    'A legendary vintage Les Paul with a rich, resonant voice and timeless feel.',
    750000.00,
    1,
    true,
    [
        'img1' => $basePlacehold . '03.jpg',
    ]
);

$products['vox-continental-keyboard'] = new Keyboard(
    6,
    'Vox Continental Keyboard',
    'Retro-inspired keyboard with classic tones and modern playability.',
    15000.00,
    5,
    true,
    [
        'img1' => $basePlacehold . '04.jpg',
    ],
    61
);

$products['casio-ctk-7200-keyboard'] = new Keyboard(
    7,
    'Casio CTK-7200 Keyboard',
    'Feature-packed keyboard with hundreds of tones and rhythms for practice.',
    950.00,
    5,
    true,
    [
        'img1' => $basePlacehold . 'casio.jpg',
    ],
    61
);

$products['m-vave-ann-black-box'] = new Product(
    8,
    'M-Vave ANN Black Box',
    'Compact multi-effect pedal for versatile practice and performance tones.',
    50.00,
    5,
    true,
    [
        'img1' => $basePlacehold . '06.jpg',
    ]
);

$products['ibanez-ts9-overdrive-pedal'] = new Product(
    9,
    'Ibanez Ts9 Overdrive Pedal',
    'Classic overdrive pedal with a warm, mid-focused character.',
    100.00,
    5,
    true,
    [
        'img1' => $basePlacehold . '07.jpg',
    ]
);

$products['vintage-fender-stratocaster'] = new Product(
    10,
    'Vintage Fender Stratocaster',
    'Vintage Strat with sparkling highs and a smooth, expressive feel.',
    595000.00,
    5,
    true,
    [
        'img1' => $basePlacehold . '08.jpg',
    ]
);
