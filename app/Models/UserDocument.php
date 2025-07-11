<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'document_type',
        'file_path'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Set the storage disk/folder for uploaded documents
    public static function storeDocument($file)
    {
        // Store in 'public/sample_documents' and return the path
        return $file->store('sample_documents', 'public');
    }
}
