<?php

namespace App\Models;

use CodeIgniter\Model;

class WebPostModel extends Model
{
    protected $table            = 'web_posts';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['title', 'content', 'image', 'category', 'is_active'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getActivePosts()
    {
        return $this->where('is_active', 1)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }
}
