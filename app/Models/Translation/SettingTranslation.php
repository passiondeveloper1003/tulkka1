<?php

namespace App\Models\Translation;

use Illuminate\Database\Eloquent\Model;

class SettingTranslation extends Model
{
    protected $table = 'setting_translations';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];
}
