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
            Schema::create('peminjamans', function (Blueprint $table) {
                $table->id();

                $table->foreignId('user_id')
                    ->constrained()
                    ->cascadeOnDelete();

                $table->foreignId('alat_id')
                    ->constrained('alats')
                    ->cascadeOnDelete();

                $table->date('tanggal_pinjam');
                $table->date('tanggal_jatuh_tempo');
                $table->date('tanggal_kembali')->nullable();

                $table->enum('status', ['pending', 'dipinjam', 'dikembalikan'])
                    ->default('pending');


                $table->integer('denda')->default(0);

                $table->timestamps();
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('peminjamans');
        }
    };
