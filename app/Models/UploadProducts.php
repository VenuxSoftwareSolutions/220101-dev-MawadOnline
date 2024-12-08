<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UploadProducts extends Model
{
    use HasFactory;


     // Add the fillable property to allow mass assignment
     protected $fillable = [
        'id_product',       // The product ID
        'path',             // The path of the image
        'extension',        // The file extension
        'document_name',    // The name of the document (optional)
        'type',             // The type of file (e.g., 'image')
    ];
}
