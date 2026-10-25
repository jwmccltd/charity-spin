<?php
namespace Database\Seeders;

use App\Models\MusicGenre;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class MusicGenreSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('data/music_genres.csv');

        if (! is_readable($path)) {
            throw new RuntimeException(
                "Cannot read CSV file: {$path}"
            );
        }

        $handle = fopen($path, 'r');

        if ($handle === false) {
            throw new RuntimeException(
                "Cannot open CSV file: {$path}"
            );
        }

        try {
            $headers = fgetcsv($handle);

            if ($headers === false) {
                throw new RuntimeException('CSV file is empty.');
            }

            // Remove a UTF-8 BOM if present.
            $headers[0] = preg_replace(
                '/^\xEF\xBB\xBF/',
                '',
                $headers[0]
            );

            $headers = array_map(
                fn ($header) => strtolower(trim($header)),
                $headers
            );

            foreach (['name', 'parent'] as $required) {
                if (! in_array($required, $headers, true)) {
                    throw new RuntimeException(
                        "Missing CSV column: {$required}"
                    );
                }
            }

            $rows = [];

            while (($values = fgetcsv($handle)) !== false) {
                if ($values === [null] || $values === []) {
                    continue;
                }

                if (count($values) !== count($headers)) {
                    throw new RuntimeException(
                        'Invalid CSV column count.'
                    );
                }

                $row = array_combine($headers, $values);

                $name = trim($row['name'] ?? '');

                if ($name === '') {
                    throw new RuntimeException(
                        'A genre is missing its name.'
                    );
                }

                $rows[] = [
                    'name' => $name,
                    'parent' => trim($row['parent'] ?? ''),
                ];
            }

            DB::transaction(function () use ($rows) {
                $pending = $rows;
                $resolved = [];

                while (count($pending) > 0) {
                    $progress = false;

                    foreach ($pending as $index => $row) {
                        $parentId = null;
                        $parentName = $row['parent'];

                        if ($parentName !== '') {
                            $parentKey = mb_strtolower($parentName);

                            if (isset($resolved[$parentKey])) {
                                $parentId = $resolved[$parentKey];
                            } else {
                                $parent = MusicGenre::query()
                                    ->whereRaw(
                                        'LOWER(name) = ?',
                                        [$parentKey]
                                    )
                                    ->first();

                                if (! $parent) {
                                    continue;
                                }

                                $parentId = $parent->id;
                            }
                        }

                        $slug = Str::slug($row['name']);

                        $genre = MusicGenre::query()->updateOrCreate(
                            ['slug' => $slug],
                            [
                                'name' => $row['name'],
                                'parent_id' => $parentId,
                                'is_active' => true,
                            ]
                        );

                        $resolved[mb_strtolower($row['name'])] =
                            $genre->id;

                        unset($pending[$index]);
                        $progress = true;
                    }

                    if (! $progress) {
                        $unresolved = array_map(
                            fn ($row) => $row['name']
                                . ' (parent: '
                                . $row['parent'] . ')',
                            array_values($pending)
                        );

                        throw new RuntimeException(
                            'Unable to resolve genres: '
                            . implode(', ', $unresolved)
                        );
                    }
                }
            });
        } finally {
            fclose($handle);
        }
    }
}
