<?php
// database/migrations/2024_01_01_000001_create_class_grades_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('class_grades', function (Blueprint $table) {
            $table->id(); // akan jadi 'id' bukan 'type_id'
            $table->string('name'); // X, XI, XII
            $table->timestamps();
        });

        Schema::create('class_majors', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Ilmu Pengetahuan Alam
            $table->string('short_name'); // IPA
            $table->timestamps();
        });

        Schema::create('class_sections', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // A, B, C, etc.
            $table->timestamps();
        });

        Schema::create('classes', function (Blueprint $table) {
            $table->id('class_id');
            $table->foreignId('grade_id')->constrained('class_grades');
            $table->foreignId('major_id')->constrained('class_majors');
            $table->foreignId('section_id')->constrained('class_sections');
            $table->foreignId('teacher_id')->constrained('users');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_sections');
        Schema::dropIfExists('class_majors');
        Schema::dropIfExists('class_grades');
        Schema::dropIfExists('classes');
    }
};