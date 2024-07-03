<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->enum('priority', ['High', 'Low']);
            $table->enum('status', ['todo', 'in_progress', 'done']);
            $table->date('implementation_date');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse'down' migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
