<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HttpLog extends Model
{
    use HasFactory;

    protected $table = 'http_log';

    protected $fillable = [
        'id_token',
        'ip_address',
        'user_agent',
        'request_method',
        'request_uri',
        'request_body',
        'request_headers',
        'response_headers',
        'status_code',
        'response_size',
        'response_time',
    ];
}
