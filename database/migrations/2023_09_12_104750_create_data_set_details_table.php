<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataSetDetailsTable extends Migration
{
    public function up()
    {
        Schema::create('data_set_details', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name')->nullable();
            $table->boolean('compulsoryFieldsCompleteOnly')->nullable();
            $table->string('formType')->nullable();
            $table->string('dimensionItemType')->nullable();
            $table->string('dimensionItem')->nullable();
            $table->string('periodType')->nullable();
            $table->integer('expiryDays')->nullable();
            $table->string('dataEntryFormId')->nullable();
            $table->string('categoryComboId')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('data_set_details');
    }
}