<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('commandes', function (Blueprint $table) {
             $table->enum('type_commande', ['emporter', 'table'])->default('emporter')->after('mode');
            
        });
    }

    public function down(): void
    {
        Schema::table('commandes', function (Blueprint $table) {
          
        });
    }
};
