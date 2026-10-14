<?php

namespace App\Models;

use CodeIgniter\Model;

class AttendeeModel extends Model
{
    protected $table            = 'attendees';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $allowedFields = [
    'name',
    'email',
    'mobile',
    'company',
    'designation',
    'city',
    'country',
    'session_preference',
    'consent',
    'password_hash',
];

    protected $useTimestamps = false;
}
