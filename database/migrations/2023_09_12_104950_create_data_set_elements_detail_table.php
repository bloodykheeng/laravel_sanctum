<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataSetElementsDetailTable extends Migration
{
    public function up()
    {
        Schema::create('data_set_elements_detail', function (Blueprint $table) {
            $table->string('id')->primary(); // Primary key, fillable and manually set
            $table->string('code')->nullable();
            $table->string('name')->nullable();
            $table->string('aggregationType')->nullable();
            $table->text('description')->nullable();
            $table->string('valueType')->nullable();
            $table->string('dimensionItem')->nullable();
            $table->string('dimensionItemType')->nullable();
            $table->string('categoryComboId')->nullable();
            $table->string('dataSetId');
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('dataSetId')->references('id')->on('data_set_details')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('data_set_elements_detail');
    }
}