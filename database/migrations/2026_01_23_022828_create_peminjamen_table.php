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

                // Relasi
                $table->foreignId('user_id')
                    ->constrained()
                    ->cascadeOnDelete();

                $table->foreignId('alat_id')
                    ->constrained('alats')
                    ->cascadeOnDelete();

                // Data Peminjaman
                $table->integer('jumlah')->default(1);

                $table->date('tanggal_pinjam');
                $table->date('tanggal_jatuh_tempo');
                $table->date('tanggal_kembali')->nullable();

                // Status lengkap
                $table->enum('status', [
                    'pending',
                    'dipinjam',
                    'menunggu_pemeriksaan',
                    'dikembalikan',
                    'hilang',
                    'ditolak'
                ])->default('pending');

                // Keterlambatan & Denda
                $table->integer('hari_telat')->default(0);
                $table->integer('denda')->default(0);
                $table->text('alasan_denda')->nullable();

                // Foto
                $table->string('foto_peminjam')->nullable();
                $table->string('foto_kondisi')->nullable();

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
