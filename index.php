<?php

// 1. Membuat Interface MetodePembayaran
interface MetodePembayaran {
    public function prosesBayar(float $jumlah, string $keterangan): bool;
    public function getNamaMetode(): string;
}

// Fungsi helper untuk format mata uang Rupiah
function formatRupiah(float $angka): string {
    return "Rp " . number_format($angka, 0, ',', '.');
}

// 2 & 3. Class TransferBank (Biaya Admin 0%)
class TransferBank implements MetodePembayaran {
    private string $nomorRekening;

    public function __construct(string $nomorRekening) {
        $this->nomorRekening = $nomorRekening;
    }

    public function getNamaMetode(): string {
        return "Transfer Bank";
    }

    public function prosesBayar(float $jumlah, string $keterangan): bool {
        $biayaAdmin = 0; // 0%
        $total = $jumlah + $biayaAdmin;

        echo "=== Informasi Pembayaran (" . $this->getNamaMetode() . ") ===\n";
        echo "Keterangan          : " . $keterangan . "\n";
        echo "Nomor Rekening      : " . $this->nomorRekening . "\n";
        echo "Jumlah Tagihan      : " . formatRupiah($jumlah) . "\n";
        echo "Biaya Admin (0%)    : " . formatRupiah($biayaAdmin) . "\n";
        echo "Total Pembayaran    : " . formatRupiah($total) . "\n";
        echo "Status              : Berhasil\n\n";

        return true;
    }
}

// 2 & 3. Class QRIS (Biaya Admin 0.7%)
class QRIS implements MetodePembayaran {
    public function getNamaMetode(): string {
        return "QRIS";
    }

    public function prosesBayar(float $jumlah, string $keterangan): bool {
        $biayaAdmin = $jumlah * 0.007; // 0.7%
        $total = $jumlah + $biayaAdmin;

        echo "=== Informasi Pembayaran (" . $this->getNamaMetode() . ") ===\n";
        echo "Keterangan          : " . $keterangan . "\n";
        echo "Jumlah Tagihan      : " . formatRupiah($jumlah) . "\n";
        echo "Biaya Admin (0.7%)  : " . formatRupiah($biayaAdmin) . "\n";
        echo "Total Pembayaran    : " . formatRupiah($total) . "\n";
        echo "Status              : Berhasil\n\n";

        return true;
    }
}

// 2 & 3. Class KartuKredit (Biaya Admin 2.5%, tampilkan 4 digit terakhir)
class KartuKredit implements MetodePembayaran {
    private string $nomorKartu;

    public function __construct(string $nomorKartu) {
        $this->nomorKartu = $nomorKartu;
    }

    public function getNamaMetode(): string {
        return "Kartu Kredit";
    }

    private function getMaskedNomorKartu(): string {
        $panjang = strlen($this->nomorKartu);
        if ($panjang <= 4) {
            return $this->nomorKartu;
        }
        $empatDigitTerakhir = substr($this->nomorKartu, -4);
        return "****-****-****-" . $empatDigitTerakhir;
    }

    public function prosesBayar(float $jumlah, string $keterangan): bool {
        $biayaAdmin = $jumlah * 0.025; // 2.5%
        $total = $jumlah + $biayaAdmin;

        echo "=== Informasi Pembayaran (" . $this->getNamaMetode() . ") ===\n";
        echo "Keterangan          : " . $keterangan . "\n";
        echo "Nomor Kartu         : " . $this->getMaskedNomorKartu() . "\n";
        echo "Jumlah Tagihan      : " . formatRupiah($jumlah) . "\n";
        echo "Biaya Admin (2.5%)  : " . formatRupiah($biayaAdmin) . "\n";
        echo "Total Pembayaran    : " . formatRupiah($total) . "\n";
        echo "Status              : Berhasil\n\n";

        return true;
    }
}

// ==========================================
// CONTOH PENGGUNAAN (TESTING)
// ==========================================

$daftarPembayaran = [
    new TransferBank("1234567890"),
    new QRIS(),
    new KartuKredit("4532123456789012")
];

$jumlahTagihan = 500000;
$keteranganTransaksi = "Pembelian Tas Selempang";

foreach ($daftarPembayaran as $metode) {
    $metode->prosesBayar($jumlahTagihan, $keteranganTransaksi);
}

?>
