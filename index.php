<?php

// 1. Membuat Interface MetodePembayaran
interface MetodePembayaran {
    public function prosesBayar(float $jumlah, string $keterangan): bool;
    public function getNamaMetode(): string;
}

// Fungsi helper untuk memformat mata uang ke format Indonesia (contoh: Rp 500.000)
function formatRupiah(float $angka): string {
    return "Rp " . number_format($angka, 0, ',', '.');
}

// 2 & 3. Class TransferBank (Biaya Admin 0% dan menerima parameter nomor rekening)
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

        echo "=== Informasi Pembayaran (" . $this->getNamaMetode() . ") ===<br>";
        echo "Keterangan          : " . htmlspecialchars($keterangan) . "<br>";
        echo "Nomor Rekening      : " . htmlspecialchars($this->nomorRekening) . "<br>";
        echo "Jumlah Tagihan      : " . formatRupiah($jumlah) . "<br>";
        echo "Biaya Admin (0%)    : " . formatRupiah($biayaAdmin) . "<br>";
        echo "Total Pembayaran    : " . formatRupiah($total) . "<br>";
        echo "Status              : Berhasil<br><br>";

        return true;
    }
}

// 2 & 3. Class QRIS (Biaya Admin 0.7%, tidak perlu parameter tambahan)
class QRIS implements MetodePembayaran {
    public function getNamaMetode(): string {
        return "QRIS";
    }

    public function prosesBayar(float $jumlah, string $keterangan): bool {
        $biayaAdmin = $jumlah * 0.007; // 0.7%
        $total = $jumlah + $biayaAdmin;

        echo "=== Informasi Pembayaran (" . $this->getNamaMetode() . ") ===<br>";
        echo "Keterangan          : " . htmlspecialchars($keterangan) . "<br>";
        echo "Jumlah Tagihan      : " . formatRupiah($jumlah) . "<br>";
        echo "Biaya Admin (0.7%)  : " . formatRupiah($biayaAdmin) . "<br>";
        echo "Total Pembayaran    : " . formatRupiah($total) . "<br>";
        echo "Status              : Berhasil<br><br>";

        return true;
    }
}

// 2 & 3. Class KartuKredit (Biaya Admin 2.5%, menerima parameter nomor kartu dan menampilkan 4 digit terakhir)
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

        echo "=== Informasi Pembayaran (" . $this->getNamaMetode() . ") ===<br>";
        echo "Keterangan          : " . htmlspecialchars($keterangan) . "<br>";
        echo "Nomor Kartu         : " . $this->getMaskedNomorKartu() . "<br>";
        echo "Jumlah Tagihan      : " . formatRupiah($jumlah) . "<br>";
        echo "Biaya Admin (2.5%)  : " . formatRupiah($biayaAdmin) . "<br>";
        echo "Total Pembayaran    : " . formatRupiah($total) . "<br>";
        echo "Status              : Berhasil<br><br>";

        return true;
    }
}

// ==========================================
// PENGUJIAN / CONTOH IMPLEMENTASI
// ==========================================

$daftarPembayaran = [
    new TransferBank("8830123456"),
    new QRIS(),
    new KartuKredit("4532888899991234")
];

$jumlahTagihan = 500000;
$keteranganTransaksi = "Pembayaran Layanan Premium";

echo "<h2>Simulasi Metode Pembayaran</h2>";
foreach ($daftarPembayaran as $metode) {
    $metode->prosesBayar($jumlahTagihan, $keteranganTransaksi);
}

?>
