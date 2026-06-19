<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class ArticleController extends Controller
{
    /**
     * Show culinary articles from NewsAPI (or demo data).
     */
    public function index()
    {
        $articles = $this->getArticles();

        return view('articles.index', compact('articles'));
    }

    /**
     * Fetch articles with caching.
     */
    private function getArticles(): array
    {
        $apiKey = env('GNEWS_API_KEY') ?: env('NEWSAPI_KEY');

        // ── Try GNews API if key is configured ────────────
        if ($apiKey) {
            $articles = Cache::remember('culinary_articles', 3600, function () use ($apiKey) {
                try {
                    $response = Http::timeout(10)->get('https://gnews.io/api/v4/search', [
                        'token'  => $apiKey,
                        'q'      => 'street food OR kuliner OR makanan',
                        'lang'   => 'id',
                        'max'    => 10,
                    ]);

                    if ($response->successful()) {
                        $data = $response->json();

                        return collect($data['articles'] ?? [])
                            ->map(fn ($article) => [
                                'title'       => $article['title'] ?? '',
                                'description' => $article['description'] ?? '',
                                'url'         => $article['url'] ?? '#',
                                'image'       => $article['image'] ?? null,
                                'source'      => $article['source']['name'] ?? '',
                                'published'   => $article['publishedAt'] ?? '',
                            ])
                            ->filter(fn ($a) => !empty($a['title']) && !empty($a['url']))
                            ->values()
                            ->all();
                    }
                } catch (\Exception $e) {
                    // Fall through to demo data
                }

                return []; // empty = no results from API
            });

            // Only use API results if non-empty; otherwise fallback to demo
            if (!empty($articles)) {
                return $articles;
            }
        }

        // ── Demo data (static articles) ───────────────────
        return $this->demoArticles();
    }

    /**
     * Static demo articles for offline/development mode.
     */
    private function demoArticles(): array
    {
        return [
            [
                'title'       => '5 Hidden Gem Kuliner Kaki Lima di Jakarta yang Wajib Dicoba',
                'description' => 'Dari sate taichan sampai nasi uduk legendaris, ini dia rekomendasi street food Jakarta yang bikin nagih.',
                'url'         => '#',
                'image'       => null,
                'source'      => 'StreetFoodies Blog',
                'published'   => now()->subHours(2)->toIso8601String(),
            ],
            [
                'title'       => 'Bakso Bakar: Inovasi Kuliner yang Lagi Viral di Bandung',
                'description' => 'Bakso yang dibakar dengan bumbu rempah khas, disajikan dengan sambal pedas — wajib dicoba saat ke Bandung!',
                'url'         => '#',
                'image'       => null,
                'source'      => 'StreetFoodies Blog',
                'published'   => now()->subHours(5)->toIso8601String(),
            ],
            [
                'title'       => 'Tips Memulai Bisnis Kuliner Kaki Lima untuk Pemula',
                'description' => 'Modal kecil bukan halangan! Simak tips sukses membangun bisnis street food dari nol.',
                'url'         => '#',
                'image'       => null,
                'source'      => 'StreetFoodies Blog',
                'published'   => now()->subDay()->toIso8601String(),
            ],
            [
                'title'       => 'Kuliner Malam Surabaya: Wisata Street Food yang Tak Pernah Sepi',
                'description' => 'Rujak cingur, sate klopo, lontong balap — Surabaya punya segudang kuliner malam yang sayang dilewatkan.',
                'url'         => '#',
                'image'       => null,
                'source'      => 'StreetFoodies Blog',
                'published'   => now()->subDay()->toIso8601String(),
            ],
            [
                'title'       => 'Mengenal Rendang: Dari Dapur Minang ke Pengakuan Dunia',
                'description' => 'Rendang dinobatkan sebagai makanan terenak di dunia. Bagaimana sejarah dan filosofi di baliknya?',
                'url'         => '#',
                'image'       => null,
                'source'      => 'StreetFoodies Blog',
                'published'   => now()->subDays(2)->toIso8601String(),
            ],
            [
                'title'       => 'Sate Padang vs Sate Madura: Mana yang Lebih Enak?',
                'description' => 'Dua varian sate legendaris ini punya penggemar masing-masing. Yuk kenali perbedaannya!',
                'url'         => '#',
                'image'       => null,
                'source'      => 'StreetFoodies Blog',
                'published'   => now()->subDays(2)->toIso8601String(),
            ],
            [
                'title'       => 'Street Food Sehat? Ini Pilihan Menu Kaki Lima Rendah Kalori',
                'description' => 'Gak semua street food bikin gemuk. Ada banyak pilihan sehat seperti gado-gado, ketoprak, dan pecel.',
                'url'         => '#',
                'image'       => null,
                'source'      => 'StreetFoodies Blog',
                'published'   => now()->subDays(3)->toIso8601String(),
            ],
            [
                'title'       => 'Fenomena Gorengan: Kenapa Indonesia Tak Bisa Lepas dari Camilan Ini',
                'description' => 'Dari tahu isi sampai pisang goreng, gorengan adalah comfort food sejuta umat. Apa rahasianya?',
                'url'         => '#',
                'image'       => null,
                'source'      => 'StreetFoodies Blog',
                'published'   => now()->subDays(3)->toIso8601String(),
            ],
            [
                'title'       => '5 Rekomendasi Minuman Segar untuk Temani Street Food Favoritmu',
                'description' => 'Es teh manis, es jeruk, es kelapa muda — minuman sederhana yang selalu jadi teman sempurna street food.',
                'url'         => '#',
                'image'       => null,
                'source'      => 'StreetFoodies Blog',
                'published'   => now()->subDays(4)->toIso8601String(),
            ],
        ];
    }
}
