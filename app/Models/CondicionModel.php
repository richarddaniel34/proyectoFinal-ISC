<?php
namespace App\Models;
use CodeIgniter\Model;

class CondicionModel extends Model
{
    protected $table = 'condicion';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['nombre'];
}

?>
