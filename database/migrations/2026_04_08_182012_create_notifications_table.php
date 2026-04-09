<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // penerima notif
            $table->foreignId('sender_id')->nullable()->constrained('users')->onDelete('cascade'); // pengirim (jika ada)
            $table->string('type'); // like, comment, connection_request, connection_accepted, new_post, event_reminder
            $table->morphs('notifiable'); // untuk polymorphic (post_id, comment_id, connection_id, event_id)
            $table->text('message');
            $table->text('action_url')->nullable(); // link yang akan dituju
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'is_read']);
            $table->index(['user_id', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('notifications');
    }
};