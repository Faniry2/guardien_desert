<?php
// database/seeders/TrackSeeder.php

namespace Database\Seeders;

use App\Models\Playlist;
use App\Models\Track;
use Illuminate\Database\Seeder;

class TrackSeeder extends Seeder
{
    public function run(): void
    {
        // ── Pistes ────────────────────────────────────────────────
        $tracks = [
    [
        'title'            => 'Affirmations Positives du Matin',
        'slug'             => 'affirmations-positives-du-matin',
        'filename'         => 'affirmations_positives_du_matin.mp3',
        'category'         => 'meditation',
        'duration_seconds' => 480, // 08:00
        'duration_label'   => '08:00',
    ],
    [
        'title'            => 'Ancien Tambour Persan & Ney',
        'slug'             => 'ancien-tambour-persan-ney',
        'filename'         => 'ancien_tambour_persan_ney.mp3',
        'category'         => 'focus',
        'duration_seconds' => 160, // 02:40
        'duration_label'   => '02:40',
    ],
    [
        'title'            => 'Mélodies Calmes de Oud Instrumental Arabe',
        'slug'             => 'melodies-calmes-oud-instrumental-arabe',
        'filename'         => 'melodies_calmes_de_oud_instrumental_arabe.mp3',
        'category'         => 'sommeil',
        'duration_seconds' => 202, // 03:22
        'duration_label'   => '03:22',
    ],
    [
        'title'            => 'Musique Arabe Relaxante du Mirage — Méditation & Yoga',
        'slug'             => 'musique-arabe-relaxante-du-mirage',
        'filename'         => 'musique_arabe_relaxante_du_mirage_musique_de_meditation_et_yoga.mp3',
        'category'         => 'meditation',
        'duration_seconds' => 194, // 03:14
        'duration_label'   => '03:14',
    ],
    [
        'title'            => 'Musique de Oud Émotionnelle',
        'slug'             => 'musique-de-oud-emotionnelle',
        'filename'         => 'musique_de_oud_emotionnelle.mp3',
        'category'         => 'meditation',
        'duration_seconds' => 128, // 02:08
        'duration_label'   => '02:08',
    ],
    [
        'title'            => 'Musique Mystique Persane Ancienne — Rituels & Méditation',
        'slug'             => 'musique-mystique-persane-ancienne',
        'filename'         => 'musique_mystique_persane_ancienne_pour_rituels_l_ame_et_meditation.mp3',
        'category'         => 'module',
        'module'           => 'silence',
        'duration_seconds' => 323, // 05:23
        'duration_label'   => '05:23',
    ],
    [
        'title'            => 'Oud Désert — Musique Arabe Méditation dans le Désert',
        'slug'             => 'oud-desert-musique-arabe-meditation',
        'filename'         => 'oud_desert_musique_arabe_meditation_dans_le_desert.mp3',
        'category'         => 'module',
        'module'           => 'reset',
        'duration_seconds' => 81, // 01:21
        'duration_label'   => '01:21',
    ],
    [
        'title'            => 'Oud du Désert — Musique Arabe',
        'slug'             => 'oud-du-desert-musique-arabe',
        'filename'         => 'oud_du_desert_musique_arabe.mp3',
        'category'         => 'focus',
        'duration_seconds' => 165, // 02:45
        'duration_label'   => '02:45',
    ],
    [
        'title'            => 'Pulse Oriental — Musique Persane Rythmique',
        'slug'             => 'pulse-oriental-musique-persane-rythmique',
        'filename'         => 'pulse_oriental_musique_persane_rythmique.mp3',
        'category'         => 'focus',
        'duration_seconds' => 225, // 03:45
        'duration_label'   => '03:45',
    ],
    [
        'title'            => 'Salam — Musique de Méditation Arabe Relaxante & Apaisante',
        'slug'             => 'salam-musique-meditation-arabe',
        'filename'         => 'salam_musique_de_meditation_arabe_relaxante_profonde_apaisante.mp3',
        'category'         => 'module',
        'module'           => 'ancrage',
        'duration_seconds' => 182, // 03:02
        'duration_label'   => '03:02',
    ],
    [
        'title'            => 'Sama — Musique de Méditation Arabe Profonde & Émotionnelle',
        'slug'             => 'sama-musique-meditation-arabe-profonde',
        'filename'         => 'sama_musique_de_meditation_arabe_profonde_belle_et_emotionnelle.mp3',
        'category'         => 'module',
        'module'           => 'renaissance',
        'duration_seconds' => 203, // 03:23
        'duration_label'   => '03:23',
    ],
];
        foreach ($tracks as $i => $data) {
            Track::updateOrCreate(
                ['slug' => $data['slug']],
                array_merge($data, ['order' => $i, 'is_active' => true])
            );
        }

        // ── Playlist : Méditation & Présence ──────────────────────
        $meditation = Playlist::updateOrCreate(['slug' => 'meditation'], [
            'name'        => 'Méditation & Présence',
            'description' => 'Sons pour entrer dans le silence intérieur.',
            'type'        => 'ambiance',
            'category'    => 'meditation',
            'order'       => 1,
            'is_active'   => true,
        ]);
        $meditation->tracks()->sync(
            Track::whereIn('slug', [
                'affirmations-positives-du-matin',
                'musique-arabe-relaxante-du-mirage',
                'musique-de-oud-emotionnelle',
            ])->pluck('id')->toArray()
        );

        // ── Playlist : Sommeil du Nomade ──────────────────────────
        $sommeil = Playlist::updateOrCreate(['slug' => 'sommeil'], [
            'name'        => 'Sommeil du Nomade',
            'description' => 'Ambiances longues pour un repos profond.',
            'type'        => 'ambiance',
            'category'    => 'sommeil',
            'order'       => 2,
            'is_active'   => true,
        ]);
        $sommeil->tracks()->sync(
            Track::whereIn('slug', [
                'melodies-calmes-oud-instrumental-arabe',
                'musique-relaxante-ere-du-mirage',
            ])->pluck('id')->toArray()
        );

        // ── Playlist : Focus & Écriture ───────────────────────────
        $focus = Playlist::updateOrCreate(['slug' => 'focus'], [
            'name'        => 'Focus & Écriture',
            'description' => 'Pour accompagner le travail dans le carnet.',
            'type'        => 'ambiance',
            'category'    => 'focus',
            'order'       => 3,
            'is_active'   => true,
        ]);
        $focus->tracks()->sync(
            Track::whereIn('slug', [
                'ancien-tambour-persan-ney',
                'oud-du-desert-musique-arabe',
                'pulse-oriental-musique-persane-rythmique',
            ])->pluck('id')->toArray()
        );

        // ── Playlist Module : Reset ───────────────────────────────
        $moduleReset = Playlist::updateOrCreate(['slug' => 'module-reset'], [
            'name'        => 'Module Reset',
            'description' => 'Sons pour éteindre le bruit intérieur.',
            'type'        => 'module',
            'module'      => 'reset',
            'order'       => 1,
            'is_active'   => true,
        ]);
        $moduleReset->tracks()->sync(
            Track::whereIn('slug', [
                'oud-desert-musique-arabe-meditation',
            ])->pluck('id')->toArray()
        );

        // ── Playlist Module : Ancrage ─────────────────────────────
        $moduleAncrage = Playlist::updateOrCreate(['slug' => 'module-ancrage'], [
            'name'        => 'Module Ancrage',
            'description' => 'Sons pour retrouver ses racines.',
            'type'        => 'module',
            'module'      => 'ancrage',
            'order'       => 2,
            'is_active'   => true,
        ]);
        $moduleAncrage->tracks()->sync(
            Track::whereIn('slug', [
                'salam-musique-meditation-arabe',
            ])->pluck('id')->toArray()
        );

        // ── Playlist Module : Silence ─────────────────────────────
        $moduleSilence = Playlist::updateOrCreate(['slug' => 'module-silence'], [
            'name'        => 'Module Silence',
            'description' => 'Sons pour entendre l\'essentiel.',
            'type'        => 'module',
            'module'      => 'silence',
            'order'       => 3,
            'is_active'   => true,
        ]);
        $moduleSilence->tracks()->sync(
            Track::whereIn('slug', [
                'musique-mystique-persane-ancienne',
            ])->pluck('id')->toArray()
        );

        // ── Playlist Module : Renaissance ─────────────────────────
        $moduleRenaissance = Playlist::updateOrCreate(['slug' => 'module-renaissance'], [
            'name'        => 'Module Renaissance',
            'description' => 'Sons pour la métamorphose finale.',
            'type'        => 'module',
            'module'      => 'renaissance',
            'order'       => 4,
            'is_active'   => true,
        ]);
        $moduleRenaissance->tracks()->sync(
            Track::whereIn('slug', [
                'sama-musique-meditation-arabe-profonde',
            ])->pluck('id')->toArray()
        );
    }
}
