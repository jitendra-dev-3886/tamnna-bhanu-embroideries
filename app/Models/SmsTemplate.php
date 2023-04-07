<?php

namespace App\Models;

use App\Traits\CreatedbyUpdatedby;
use App\Traits\Scopes;
use App\Traits\UploadTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class SmsTemplate extends Model
{
    use SoftDeletes, Scopes, HasFactory, UploadTrait, CreatedbyUpdatedby;

    /**
     * @var array
     */
    protected $fillable = ['id', 'type', 'label', 'message', 'status', 'created_by', 'updated_by'];

    /**
     * Lightweight response variable
     *
     * @var array
     */
    public $light = ['id', 'label'];

    /**
     * @var array
     */
    public $sortable = ['created_at', 'id', 'message'];

    /**
     * @var array
     */
    public $foreign_sortable = [];

    /**
     * @var array
     */
    public $foreign_table = [];

    /**
     * @var array
     */
    public $foreign_key = [];

    /**
     * @var array
     */
    public $foreign_method = [];

    /**
     * @var array
     */
    public $type_sortable = ['type', 'status'];

    /**
     * @var array
     */
    public $type_enum = [
        ['constants.sms_template.type_enum.0', 'constants.sms_template.type_enum.1'],
        ['constants.sms_template.status_enum.0', 'constants.sms_template.status_enum.1']
    ];

    /**
     * @var array
     */
    public $type_enum_text = [
        ['constants.sms_template.type.0', 'constants.sms_template.type.1'],
        ['constants.sms_template.status.0', 'constants.sms_template.status.1']
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'      => 'string',
        'type'    => 'string',
        'message' => 'string',
        'status'  => 'string'
    ];
}
