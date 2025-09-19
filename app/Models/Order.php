<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_id', 'user_id', 'code', 'total', 'status',
        'amount_paid', 'amount_due', 'payment_method', 'payment_status'
    ];

    public function transactions() { return $this->hasMany(Transaction::class); }

    // Trạng thái đơn hàng chuẩn
    const STATUS_DRAFT = 'draft';
    const STATUS_NEW = 'new';
    const STATUS_PROCESSING = 'processing';
    const STATUS_DELIVERING = 'delivering';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_COMPLETED = 'completed';
    const STATUS_RETURNED = 'returned';

    public static function statusOptions()
    {
        return [
            self::STATUS_DRAFT => 'Nháp',
            self::STATUS_NEW => 'Tạo mới',
            self::STATUS_PROCESSING => 'Đang xử lý',
            self::STATUS_DELIVERING => 'Đang giao hàng',
            self::STATUS_DELIVERED => 'Đã giao hàng',
            self::STATUS_COMPLETED => 'Đã hoàn thiện',
            self::STATUS_RETURNED => 'Hoàn trả',
        ];
    }

    public function customer() { return $this->belongsTo(Customer::class); }
    public function user() { return $this->belongsTo(User::class); }
    public function items() { return $this->hasMany(OrderItem::class); }

    public function getPaymentStatusTextAttribute()
    {
        if ($this->status === self::STATUS_COMPLETED) {
            return 'Đã hoàn thành';
        }
        $paid = $this->transactions()->where('type', 'payment')->sum('amount') - $this->transactions()->where('type', 'refund')->sum('amount');
        if ($paid >= $this->total) {
            return 'Đã thanh toán đủ';
        } elseif ($paid > 0) {
            return 'Thanh toán một phần';
        } else {
            return 'Chưa thanh toán';
        }
    }
    public function isPaid() {
        $paid = $this->transactions()->where('type', 'payment')->sum('amount') - $this->transactions()->where('type', 'refund')->sum('amount');
        return $paid >= $this->total;
    }
    public function isPartialPaid() {
        $paid = $this->transactions()->where('type', 'payment')->sum('amount') - $this->transactions()->where('type', 'refund')->sum('amount');
        return $paid > 0 && $paid < $this->total;
    }
    public function isUnpaid() {
        $paid = $this->transactions()->where('type', 'payment')->sum('amount') - $this->transactions()->where('type', 'refund')->sum('amount');
        return $paid <= 0;
    }
}
