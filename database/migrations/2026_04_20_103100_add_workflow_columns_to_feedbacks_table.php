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
        Schema::table('feedbacks', function (Blueprint $table) {
            $table->string('workflow_status', 40)->default('pending_faculty')->after('submitted_at');
            $table->timestamp('approved_by_faculty_at')->nullable()->after('workflow_status');
            $table->timestamp('approved_by_principal_at')->nullable()->after('approved_by_faculty_at');
            $table->timestamp('approved_by_admin_at')->nullable()->after('approved_by_principal_at');
            $table->timestamp('rejected_at')->nullable()->after('approved_by_admin_at');
            $table->foreignId('rejected_by_user_id')->nullable()->after('rejected_at')->constrained('users')->nullOnDelete();
            $table->text('status_note')->nullable()->after('rejected_by_user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('feedbacks', function (Blueprint $table) {
            $table->dropConstrainedForeignId('rejected_by_user_id');
            $table->dropColumn([
                'workflow_status',
                'approved_by_faculty_at',
                'approved_by_principal_at',
                'approved_by_admin_at',
                'rejected_at',
                'status_note',
            ]);
        });
    }
};
