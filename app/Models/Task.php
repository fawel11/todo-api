<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['title','description','author_id','status'];

    /**

     * The owner of this delicious recipe

     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo

     */

    // Task.php (Task model)

    public function setAuthorIdAttribute()
    {
        $this->attributes['author_id'] = auth()->id();
    }


    public function author(){
        return $this->belongsTo(User::class, 'author_id');
    }
}
