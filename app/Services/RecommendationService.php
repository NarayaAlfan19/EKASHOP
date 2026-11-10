namespace App\Services;

use App\Models\Flower;
use Illuminate\Support\Collection;

class RecommendationService
{
    /**
     * Hitung rekomendasi berdasarkan preferensi (FR-023 - FR-030).
     */
    public function getRecommendations(array $prefs, int $limit = 6): Collection
    {
        // 1. Ambil bunga active & ketersediaan cabang (eager loading untuk performa)
        $query = Flower::with(['occasions', 'types', 'colors', 'tags', 'branches'])
                       ->where('status', 'active');

        if (!empty($prefs['branch_id'])) {
            $query->whereHas('branches', function ($q) use ($prefs) {
                $q->where('branches.id', $prefs['branch_id'])
                  ->where('branch_flower.is_available', true);
            });
        } else {
            // Jika tidak pilih cabang, minimal harus tersedia di satu cabang aktif
            $query->whereHas('branches', function ($q) {
                $q->where('branch_flower.is_available', true);
            });
        }

        $flowers = $query->get();

        // 2. Cek apakah pengguna men-submit form kosong
        $isPrefEmpty = empty($prefs['occasion_id']) && empty($prefs['type_ids']) && 
                       empty($prefs['color_ids']) && empty($prefs['budget']) && empty($prefs['tag_ids']);
                       
        if ($isPrefEmpty || $flowers->isEmpty()) {
            return $this->getFallback($flowers, $limit);
        }

        // 3. Hitung skor tiap bunga
        $scoredFlowers = $flowers->map(function ($flower) use ($prefs) {
            $score = $this->calculateScore($flower, $prefs);
            $flower->match_score = $score;
            return $flower;
        })->filter(function ($flower) {
            return $flower->match_score > 0; // Buang skor 0
        })->sortByDesc('match_score')->values();

        // 4. Fallback jika tidak ada yang cocok sama sekali
        if ($scoredFlowers->isEmpty()) {
            return $this->getFallback($flowers, $limit);
        }

        return $scoredFlowers->take($limit);
    }

    private function calculateScore(Flower $flower, array $prefs): float
    {
        // Bobot dasar sesuai konfigurasi PRD
        $weights = [
            'occasion' => 0.30,
            'type' => 0.20,
            'color' => 0.20,
            'budget' => 0.15,
            'tag' => 0.15
        ];

        $totalWeight = 0;
        $earnedScore = 0;

        // Acara (Pilihan Tunggal)
        if (!empty($prefs['occasion_id'])) {
            $totalWeight += $weights['occasion'];
            if ($flower->occasions->contains('id', $prefs['occasion_id'])) {
                $earnedScore += $weights['occasion'] * 1;
            }
        }

        // Jenis Bunga (Pilihan Ganda)
        if (!empty($prefs['type_ids'])) {
            $totalWeight += $weights['type'];
            $matchCount = $flower->types->whereIn('id', $prefs['type_ids'])->count();
            $earnedScore += $weights['type'] * ($matchCount / count($prefs['type_ids']));
        }

        // Warna (Pilihan Ganda)
        if (!empty($prefs['color_ids'])) {
            $totalWeight += $weights['color'];
            $matchCount = $flower->colors->whereIn('id', $prefs['color_ids'])->count();
            $earnedScore += $weights['color'] * ($matchCount / count($prefs['color_ids']));
        }

        // Tag / Suasana (Pilihan Ganda)
        if (!empty($prefs['tag_ids'])) {
            $totalWeight += $weights['tag'];
            $matchCount = $flower->tags->whereIn('id', $prefs['tag_ids'])->count();
            $earnedScore += $weights['tag'] * ($matchCount / count($prefs['tag_ids']));
        }

        // Budget (Rentang Harga Efektif)
        if (!empty($prefs['budget'])) {
            $totalWeight += $weights['budget'];
            $price = $flower->discount_price ?? $flower->price;
            
            $min = $prefs['budget']['min'];
            $max = $prefs['budget']['max'];

            if ($price >= $min && $price <= $max) {
                $earnedScore += $weights['budget'] * 1;
            } else {
                // Beri nilai 0.5 jika di luar rentang dengan selisih maksimal 20%
                $marginMin = $min - ($min * 0.20);
                $marginMax = $max + ($max * 0.20);
                
                if ($price >= $marginMin && $price <= $marginMax) {
                    $earnedScore += $weights['budget'] * 0.5;
                }
            }
        }

        if ($totalWeight == 0) return 0;

        // Normalisasi bobot agar skor maksimal selalu 100%
        return round(($earnedScore / $totalWeight) * 100, 2);
    }

    private function getFallback(Collection $flowers, int $limit)
    {
        // Fallback: urutkan berdasarkan yang paling populer atau terbaru
        return $flowers->sortByDesc('created_at')->take($limit);
    }
}