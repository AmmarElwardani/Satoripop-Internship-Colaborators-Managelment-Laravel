<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('firstName');
            $table->string('lastName');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('position',['web Developer', 'frontEnd Developer', 'backEnd Developer', 'mobile Developer', 'IT', 'network Security', 'sales'])->nullable();
            $table->string('address')->nullable();
            $table->string('birthDate')->nullable();
            $table->string('phone')->nullable()->unique();
            $table->string('civilState')->nullable();
            $table->boolean('gender');
            $table->string('nationality')->nullable();
            $table->string('nCin')->nullable();
            $table->string('nPassport')->nullable();
            $table->string('nCnss')->nullable();
            $table->string('school')->nullable();
            $table->text('history')->nullable();
            $table->string('experienceLevel')->nullable();
            $table->string('source')->nullable();
            $table->string('hiringDate')->default(now());
            $table->string('endOfContractDate')->nullable();
            $table->string('contractType')->nullable();
            $table->foreignId('supervisorId')->nullable()->constrained('users')->onDelete('cascade');
            $table->rememberToken();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {   Schema::disableForeignKeyConstraints();
        
        Schema::dropIfExists('users');
        Schema::enableForeignKeyConstraints();
    }
}
