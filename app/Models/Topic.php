<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Topic extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'body', 'category_id', 'excerpt', 'slug',
    ];

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 不同的排序，使用不同的数据读取逻辑
     * @param $query
     * @param $order
     */
    public function scopeWithOrder($query, $order)
    {
        switch ($order) {
            case 'recent':
                $query->recent();
                break;

            default:
                $query->recentReplied();
                break;
        }
    }

    /**
     * 当话题有新回复时，我们将编写逻辑来更新话题模型的 reply_count 属性，
     * 此时会自动触发框架对数据模型 updated_at 时间戳的更新
     * @param $query
     * @return mixed
     */
    public function scopeRecentReplied($query)
    {
        return $query->orderBy('updated_at', 'desc');
    }

    /**
     * 按照创建时间排序
     * @param $query
     * @return mixed
     */
    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}
