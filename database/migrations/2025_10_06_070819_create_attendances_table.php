<?php

use App\Models\Event;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Event::class)->constrained()->onDelete('cascade');
            $table->string('attendee_name');
            $table->string('attendee_email')->nullable();
            $table->string('qr_token')->unique();
            $table->timestamp('used_at')->nullable();
            $table->string('checked_in_by')->nullable(); // Admin who checked them in
            $table->timestamps();

            $table->index(['event_id', 'qr_token']);
            $table->index(['event_id', 'used_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
