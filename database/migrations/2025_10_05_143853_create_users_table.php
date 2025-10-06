<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('users')) {
            return;
        }

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('users')) {
            return;
        }

        $columns = Schema::getColumnListing('users');
        sort($columns);

        if ($columns === ['created_at', 'id', 'updated_at']) {
            Schema::drop('users');
        }
    }
};
