<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEwentTestTables extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('customer_id')->nullable();
            $table->string('name');
            $table->string('mobile')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('pincode')->nullable();
            $table->string('profile_image')->nullable();
            $table->string('driving_license')->nullable();
            $table->integer('driving_license_status')->default(0)->comment('0:Pending, 1:uploaded, 2:verified, 3:cancelled');
            $table->string('govt_id_card')->nullable();
            $table->integer('govt_id_card_status')->default(0)->comment('0:Pending, 1:uploaded, 2:verified, 3:cancelled');
            $table->string('cancelled_cheque')->nullable();
            $table->integer('cancelled_cheque_status')->default(0)->comment('0:Pending, 1:uploaded, 2:verified, 3:cancelled');
            $table->string('current_address_proof')->nullable();
            $table->integer('current_address_proof_status')->default(0)->comment('0:Pending, 1:uploaded, 2:verified, 3:cancelled');
            $table->tinyInteger('status')->default(1);
            $table->string('remember_token', 100)->nullable();
            $table->timestamps();
        });

        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('role')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
        Schema::create('sub_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->string('title');
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->integer('stock_qty')->default(0);
            $table->tinyInteger('stock')->default(1)->comment('1: Stock in, 0:Stock Out');
            $table->string('title');
            $table->string('product_sku')->nullable();
            $table->integer('position')->nullable();
            $table->string('types')->nullable();
            $table->text('short_desc')->nullable();
            $table->longText('long_desc')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('sub_category_id')->nullable();
            $table->string('image')->nullable();
            $table->tinyInteger('is_selling')->default(0)->comment('0:inactive, 1:active');
            $table->decimal('base_price', 10, 2)->nullable();
            $table->decimal('display_price', 10, 2)->nullable();
            $table->tinyInteger('is_rent')->default(0)->comment('0:inactive, 1:active');
            $table->decimal('per_rent_price', 10, 2)->nullable();
            $table->string('rent_duration')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keyword')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('is_bestseller')->default(0);
            $table->tinyInteger('is_new_arrival')->default(1);
            $table->tinyInteger('is_featured')->default(0);
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('sub_category_id')->references('id')->on('sub_categories')->onDelete('cascade');
        });

        Schema::create('admin_ratings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('admin_id');
            $table->decimal('rating', 2, 1);
            $table->text('comments')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('admin_id')->references('id')->on('admins');
        });

        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('image');
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->mediumText('value');
            $table->integer('expiration');
        });

        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->string('owner');
            $table->integer('expiration');
        });

        

        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at');
        });

        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->text('question')->nullable();
            $table->text('answer')->nullable();
            $table->timestamps();
        });

        Schema::create('galleries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->string('image');
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products');
        });

        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('queue');
            $table->longText('payload');
            $table->tinyInteger('attempts')->unsigned();
            $table->unsignedInteger('reserved_at')->nullable();
            $table->unsignedInteger('available_at');
            $table->unsignedInteger('created_at');
        });

        Schema::create('job_batches', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->integer('total_jobs');
            $table->integer('pending_jobs');
            $table->integer('failed_jobs');
            $table->longText('failed_job_ids');
            $table->mediumText('options')->nullable();
            $table->integer('cancelled_at')->nullable();
            $table->unsignedInteger('created_at');
            $table->unsignedInteger('finished_at')->nullable();
        });


        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->string('coupon_code')->unique();
            $table->enum('discount_type', ['flat', 'percentage']);
            $table->decimal('discount_value', 10, 2);
            $table->decimal('minimum_order_amount', 10, 2)->nullable();
            $table->decimal('maximum_discount', 10, 2)->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->integer('usage_limit')->unsigned()->nullable();
            $table->integer('usage_per_user')->unsigned()->nullable();
            $table->enum('status', ['active', 'inactive', 'expired'])->default('inactive');
            $table->timestamps();
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->enum('order_type', ['Rent', 'Sell']);
            $table->string('order_number');
            $table->decimal('total_price', 10, 2);
            $table->decimal('discount_amount', 10, 2)->default(0.00);
            $table->decimal('final_amount', 10, 2)->default(0.00);
            $table->integer('quantity')->unsigned();
            $table->enum('status', ['Ready to pickup', 'completed', 'cancelled', 'returned'])->default('Ready to pickup');
            $table->unsignedBigInteger('offer_id')->nullable();
            $table->enum('payment_type', ['Card', 'COD', 'PhonePay', 'GooglePay', 'UPI', 'NetBanking', 'Wallet'])->default('COD');
            $table->enum('payment_mode', ['Online', 'Offline'])->default('Online');
            $table->enum('payment_status', ['pending', 'failed', 'cancelled'])->default('pending');
            $table->text('shipping_address')->nullable();
            $table->integer('rent_duration')->unsigned();
            $table->dateTime('rent_start_date');
            $table->dateTime('rent_end_date');
            $table->enum('rent_status', ['Ready to pickup	', 'ongoing', 'completed', 'late']);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('offer_id')->references('id')->on('offers')->nullable()->onUpdate('cascade');
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('quantity')->unsigned();
            $table->decimal('price', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });

        Schema::create('order_item_returns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_item_id');
            $table->dateTime('return_date');
            $table->enum('return_status', ['on_time', 'late', 'damaged', 'good_condition'])->default('on_time');
            $table->text('return_condition')->nullable();
            $table->decimal('refund_amount', 10, 2)->nullable();
            $table->timestamps();

            $table->foreign('order_item_id')->references('id')->on('order_items')->onDelete('cascade');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('mobile')->nullable();
            $table->string('otp')->nullable();
            $table->string('email')->nullable();
            $table->string('token')->nullable();
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->enum('payment_method', ['credit_card', 'debit_card', 'paypal', 'stripe', 'cash', 'bank_transfer']);
            $table->enum('payment_status', ['pending', 'completed', 'failed', 'refunded'])->default('pending');
            $table->string('transaction_id')->nullable();
            $table->decimal('amount', 10, 2);
            $table->char('currency', 3)->default('USD');
            $table->dateTime('payment_date')->nullable();
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('personal_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('tokenable_type');
            $table->unsignedBigInteger('tokenable_id');
            $table->string('name');
            $table->string('token', 64);
            $table->text('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->unique(['tokenable_type', 'tokenable_id']);
        });

        Schema::create('policies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->timestamps();
        });

       

        Schema::create('product_features', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->string('title');
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products');
        });

        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->string('image');
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products');
        });

        Schema::create('product_reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('user_id');
            $table->decimal('rating', 2, 1);
            $table->text('review')->nullable();
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::create('product_types', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->tinyInteger('status')->default(1);
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->unsigned();
            $table->primary('id');

            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::create('user_addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('type');
            $table->string('street_address');
            $table->string('city');
            $table->string('country');
            $table->string('pincode');
            $table->tinyInteger('is_default')->default(0);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->decimal('balance', 10, 2)->default(0.00);
            $table->timestamps();
        });

        Schema::create('why_ewent', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('image');
            $table->tinyInteger('status')->default(1);
            $table->integer('position')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('why_ewent');
        Schema::dropIfExists('wallets');
        Schema::dropIfExists('user_addresses');
        Schema::dropIfExists('users');
        Schema::dropIfExists('sub_categories');
        Schema::dropIfExists('stock');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('product_types');
        Schema::dropIfExists('product_reviews');
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('product_features');
        Schema::dropIfExists('products');
        Schema::dropIfExists('policies');
        Schema::dropIfExists('personal_access_tokens');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('order_item_returns');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('offers');
        Schema::dropIfExists('migrations');
        Schema::dropIfExists('job_batches');
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('galleries');
        Schema::dropIfExists('faqs');
        Schema::dropIfExists('failed_jobs');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('cache_locks');
        Schema::dropIfExists('cache');
        Schema::dropIfExists('banners');
        Schema::dropIfExists('admin_ratings');
        Schema::dropIfExists('admins');
    }
}