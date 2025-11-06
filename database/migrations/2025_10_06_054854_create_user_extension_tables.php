<?php
// database/migrations/2024_01_01_000003_create_user_extensions_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nisn')->unique();
            $table->foreignId('class_id')->constrained('classes', 'class_id');
            $table->boolean('is_admin_class')->default(false);
            $table->timestamps();
        });

        Schema::create('user_teachers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nip')->unique();
            $table->boolean('is_pkwu')->default(false);
            $table->boolean('is_wali_kelas')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_teachers');
        Schema::dropIfExists('user_students');
    }
};