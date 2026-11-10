namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InquiryItem extends Model
{
    public $timestamps = false; // Tanpa timestamps[cite: 1]

    protected $fillable = [
        'inquiry_id', 'flower_id', 'flower_name', 
        'price', 'qty', 'subtotal'
    ];

    public function inquiry(): BelongsTo
    {
        return $this->belongsTo(Inquiry::class);
    }

    // Relasi ke flower tetap ada (bisa includeTrashed karena flower pakai soft delete)[cite: 1]
    public function flower(): BelongsTo
    {
        return $this->belongsTo(Flower::class)->withTrashed(); 
    }
}