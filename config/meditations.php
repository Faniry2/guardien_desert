<?php
// config/meditations.php
// Ajoute ici tes méditations — audio ou vidéo (YouTube, Vimeo, fichier local)

return [
    // [
    //     'slug'        => 'detente',
    //     'title'       => 'Medieval',
    //     'duration'    => '12 min',
    //     'type'        => 'audio',                          // 'audio' ou 'video'
    //     'src'         => '/espace/detente/audio/respiration-consciente', // route stream local
    //     'description' => 'Une guidance douce pour revenir au souffle et apaiser le mental.',
    // ],
    [
        'slug'        => 'meditation-montain',
        'title'       => 'Méditation du moine',
        'duration'    => '20 min',
        'type'        => 'video',
        'src'         => 'https://www.youtube.com/watch?v=0Ni00XDSd6E',     // URL YouTube
        'embed'       => 'https://www.youtube.com/embed/0Ni00XDSd6E?si=uDP7wbIhsYX_xori',       // URL embed YouTube
        'description' => 'Au sommet d’une montagne silencieuse, un moine est assis en tailleur sur un rocher lisse, immobile comme s’il faisait partie du paysage lui-même. Autour de lui, le vent glisse doucement entre les herbes et fait frémir les pins, tandis que les nuages passent lentement au-dessus des sommets. Le monde semble suspendu.',
    ],
     [
        'slug'        => 'meditation-nature',
        'title'       => 'Méditation guidé',
        'duration'    => '20 min',
        'type'        => 'video',
        'src'         => 'https://www.youtube.com/watch?v=UzMMNFxPXAY',     // URL YouTube
        'embed'       => 'https://www.youtube.com/embed/UzMMNFxPXAY?si=1GSdfO9p5T6hI0J7',       // URL embed YouTube
        'description' => 'Imagine une voix d’homme, douce et posée, qui t’accompagne lentement. Elle ne force rien, elle invite simplement à se laisser porter.',
    ],
     [
        'slug'        => 'detente',
        'title'       => 'Medieval',
        'duration'    => '1 heure',
        'type'        => 'video',
        'src'         => 'https://www.youtube.com/watch?v=xxYJONmXE8w&t=1323s',     // URL YouTube
        'embed'       => 'https://www.youtube.com/embed/xxYJONmXE8w?si=yDXq01pj9zUY0W4i',       // URL embed YouTube
        'description' => 'Si tu cherches un son médiéval apaisant pour te détendre, imagine une ambiance douce, presque hors du temps, comme si tu étais dans un vieux monastère ou un château tranquille.',
    ],
    // Ajoute autant de méditations que tu veux...
    // Pour une vidéo Vimeo :
    // [
    //     'slug'    => 'pleine-conscience',
    //     'title'   => 'Pleine Conscience',
    //     'duration'=> '15 min',
    //     'type'    => 'video',
    //     'src'     => 'https://vimeo.com/XXXXXXX',
    //     'embed'   => 'https://player.vimeo.com/video/XXXXXXX',
    // ],
];