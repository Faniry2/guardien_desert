<?php
// database/seeders/TrackSeeder.php
// Exemple de données pour tester

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
            // Ambiances
            ['title' => 'Vent du Sahara',        'slug' => 'vent-du-sahara',        'filename' => 'vent-du-sahara.mp3',        'category' => 'meditation', 'duration_seconds' => 720,  'duration_label' => '12:00'],
            ['title' => 'Sable et Silence',      'slug' => 'sable-et-silence',      'filename' => 'sable-et-silence.mp3',      'category' => 'sommeil',    'duration_seconds' => 1800, 'duration_label' => '30:00'],
            ['title' => 'Feu de Bivouac',        'slug' => 'feu-de-bivouac',        'filename' => 'feu-de-bivouac.mp3',        'category' => 'focus',      'duration_seconds' => 900,  'duration_label' => '15:00'],
            ['title' => 'Pluie sur les Dunes',   'slug' => 'pluie-sur-les-dunes',   'filename' => 'pluie-sur-les-dunes.mp3',   'category' => 'sommeil',    'duration_seconds' => 2400, 'duration_label' => '40:00'],
            ['title' => 'Aurore du Tassili',     'slug' => 'aurore-du-tassili',     'filename' => 'aurore-du-tassili.mp3',     'category' => 'meditation', 'duration_seconds' => 600,  'duration_label' => '10:00'],

            // Modules
            ['title' => 'Méditation du Reset',   'slug' => 'meditation-reset',      'filename' => 'meditation-reset.mp3',      'category' => 'module',     'module' => 'reset',        'duration_seconds' => 1200, 'duration_label' => '20:00'],
            ['title' => 'Souffle du Reboot',     'slug' => 'souffle-reboot',        'filename' => 'souffle-reboot.mp3',        'category' => 'module',     'module' => 'reboot',       'duration_seconds' => 900,  'duration_label' => '15:00'],
            ['title' => 'Clarté du Désert',      'slug' => 'clarte-desert',         'filename' => 'clarte-desert.mp3',         'category' => 'module',     'module' => 'clarte',       'duration_seconds' => 720,  'duration_label' => '12:00'],
            ['title' => 'Ancrage Profond',       'slug' => 'ancrage-profond',       'filename' => 'ancrage-profond.mp3',       'category' => 'module',     'module' => 'ancrage',      'duration_seconds' => 1500, 'duration_label' => '25:00'],
            ['title' => 'Le Grand Silence',      'slug' => 'grand-silence',         'filename' => 'grand-silence.mp3',         'category' => 'module',     'module' => 'silence',      'duration_seconds' => 1800, 'duration_label' => '30:00'],
        ];

        foreach ($tracks as $i => $data) {
            Track::updateOrCreate(['slug' => $data['slug']], array_merge($data, ['order' => $i, 'is_active' => true]));
        }

        // ── Playlists Ambiance ─────────────────────────────────────
        $meditation = Playlist::updateOrCreate(['slug' => 'meditation'], [
            'name'        => 'Méditation & Présence',
            'description' => 'Sons pour entrer dans le silence intérieur.',
            'type'        => 'ambiance',
            'category'    => 'meditation',
            'order'       => 1,
            'is_active'   => true,
        ]);
        $meditation->tracks()->sync(
            Track::whereIn('slug', ['vent-du-sahara', 'aurore-du-tassili'])->pluck('id')->toArray()
        );

        $sommeil = Playlist::updateOrCreate(['slug' => 'sommeil'], [
            'name'        => 'Sommeil du Nomade',
            'description' => 'Ambiances longues pour un repos profond.',
            'type'        => 'ambiance',
            'category'    => 'sommeil',
            'order'       => 2,
            'is_active'   => true,
        ]);
        $sommeil->tracks()->sync(
            Track::whereIn('slug', ['sable-et-silence', 'pluie-sur-les-dunes'])->pluck('id')->toArray()
        );

        $focus = Playlist::updateOrCreate(['slug' => 'focus'], [
            'name'        => 'Focus & Écriture',
            'description' => 'Pour accompagner le travail dans le carnet.',
            'type'        => 'ambiance',
            'category'    => 'focus',
            'order'       => 3,
            'is_active'   => true,
        ]);
        $focus->tracks()->sync(
            Track::whereIn('slug', ['feu-de-bivouac'])->pluck('id')->toArray()
        );

        // ── Playlists Module ──────────────────────────────────────
        $moduleReset = Playlist::updateOrCreate(['slug' => 'module-reset'], [
            'name'        => 'Module Reset',
            'description' => 'Sons pour éteindre le bruit intérieur.',
            'type'        => 'module',
            'module'      => 'reset',
            'order'       => 1,
            'is_active'   => true,
        ]);
        $moduleReset->tracks()->sync(
            Track::whereIn('slug', ['meditation-reset', 'grand-silence'])->pluck('id')->toArray()
        );

        $moduleReboot = Playlist::updateOrCreate(['slug' => 'module-reboot'], [
            'name'        => 'Module Reboot',
            'description' => 'Sons pour repartir de zéro.',
            'type'        => 'module',
            'module'      => 'reboot',
            'order'       => 2,
            'is_active'   => true,
        ]);
        $moduleReboot->tracks()->sync(
            Track::whereIn('slug', ['souffle-reboot', 'ancrage-profond'])->pluck('id')->toArray()
        );
    }
}