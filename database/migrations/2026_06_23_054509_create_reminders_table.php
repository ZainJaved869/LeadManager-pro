<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->nullableMorphs('remindable'); // task, lead, etc.
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamp('remind_at');
            $table->timestamp('sent_at')->nullable();
            $table->string('type')->default('email'); // email, in_app, both
            $table->boolean('is_sent')->default(false);
            $table->timestamps();

            $table->index(['is_sent', 'remind_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('reminders');
    }
};