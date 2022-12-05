<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RenameAndAlterLocationsDocumentsTableToDocumentLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('locations_documents','document_locations');
        Schema::table('document_locations', function (Blueprint $table) {
            $table->dropForeign('locations_documents_id_d_foreign');
            $table->dropIndex('locations_documents_id_d_foreign');
            $table->renameColumn('id_d', 'document_id');
            $table->foreign('document_id')->references('id')->on('documents')->onDelete('cascade');
        });
        DB::statement("ALTER TABLE document_locations comment 'Расположение документов'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('document_locations','locations_documents');
        Schema::table('locations_documents', function (Blueprint $table) {
            $table->dropForeign('document_locations_document_id_foreign');
            $table->dropIndex('document_locations_document_id_foreign');
            $table->renameColumn('document_id', 'id_d');
            $table->foreign('id_d')->references('id')->on('documents')->onDelete('cascade');
        });
    }
}
