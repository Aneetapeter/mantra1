<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // Enhanced date/time support
            $table->datetime('start_datetime')->nullable()->after('date');
            $table->datetime('end_datetime')->nullable()->after('start_datetime');

            // Event details
            $table->text('description')->nullable()->after('type');
            $table->string('color', 7)->nullable()->after('description'); // hex color

            // Reminders
            $table->enum('reminder', ['none', 'at_time', '5min', '10min', '1hour', '1day'])->default('none')->after('color');

            // Recurrence
            $table->boolean('is_recurring')->default(false)->after('reminder');
            $table->enum('recurrence_rule', ['daily', 'weekly', 'monthly', 'yearly'])->nullable()->after('is_recurring');
            $table->unsignedBigInteger('parent_event_id')->nullable()->after('recurrence_rule');

            // Status & Priority
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])->default('pending')->after('parent_event_id');
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium')->after('status');

            // Foreign key for recurring events
            $table->foreign('parent_event_id')->references('id')->on('events')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign(['parent_event_id']);
            $table->dropColumn([
                'start_datetime',
                'end_datetime',
                'description',
                'color',
                'reminder',
                'is_recurring',
                'recurrence_rule',
                'parent_event_id',
                'status',
                'priority'
            ]);
        });
    }
};
