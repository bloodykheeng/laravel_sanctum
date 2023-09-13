<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataSetElementDetail extends Model
{
    protected $table = 'data_set_elements_detail';

    protected $primaryKey = 'id';

    public $incrementing = false;


    protected $fillable = [
        'id',
        'code',
        'name',
        'aggregationType',
        'description',
        'valueType',
        'dimensionItem',
        'dimensionItemType',
        'categoryComboId',
        'dataSetId',
    ];

    // Define relationships here if needed
    public function dataSet()
    {
        return $this->belongsTo(DataSetDetail::class, 'dataSetId');
    }
}