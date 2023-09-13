<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataSetDetail extends Model
{
    protected $primaryKey = 'id'; // Specify the primary key field
    public $incrementing = false; // Disable auto-incrementing for the primary key


    protected $fillable = [
        'id',
        'name',
        'compulsoryFieldsCompleteOnly',
        'formType',
        'dimensionItemType',
        'dimensionItem',
        'periodType',
        'expiryDays',
        'dataEntryFormId',
        'categoryComboId',
    ];

    public function dataElementDetails()
    {
        return $this->hasMany(DataSetElementDetail::class, 'dataSetId');
    }
}