<?php
$sql_pria = $mysqli->query("SELECT * FROM tabel_kependudukan WHERE JK='1'");
$sql_wanita = $mysqli->query("SELECT * FROM tabel_kependudukan WHERE JK='2'");
$sql_total = $mysqli->query("SELECT * FROM tabel_kependudukan");

$sql_belum_sekolah = $mysqli->query("SELECT * FROM tabel_kependudukan JOIN tabel_pendidikan ON tabel_kependudukan.NIK = tabel_pendidikan.NIK WHERE tabel_pendidikan.PENDIDIKAN_TERAKHIR='Tidak Sekolah'");
$sql_tidak_tamat_sd = $mysqli->query("SELECT * FROM tabel_kependudukan JOIN tabel_pendidikan ON tabel_kependudukan.NIK = tabel_pendidikan.NIK WHERE tabel_pendidikan.PENDIDIKAN_TERAKHIR='Tidak Tamat SD'");
$sql_sd = $mysqli->query("SELECT * FROM tabel_kependudukan JOIN tabel_pendidikan ON tabel_kependudukan.NIK = tabel_pendidikan.NIK WHERE tabel_pendidikan.PENDIDIKAN_TERAKHIR='SD dan Sederajat'");
$sql_smp = $mysqli->query("SELECT * FROM tabel_kependudukan JOIN tabel_pendidikan ON tabel_kependudukan.NIK = tabel_pendidikan.NIK WHERE tabel_pendidikan.PENDIDIKAN_TERAKHIR='SMP dan Sederajat'");
$sql_sma = $mysqli->query("SELECT * FROM tabel_kependudukan JOIN tabel_pendidikan ON tabel_kependudukan.NIK = tabel_pendidikan.NIK WHERE tabel_pendidikan.PENDIDIKAN_TERAKHIR='SMA dan Sederajat'");
$sql_diploma = $mysqli->query("SELECT * FROM tabel_kependudukan JOIN tabel_pendidikan ON tabel_kependudukan.NIK = tabel_pendidikan.NIK WHERE tabel_pendidikan.PENDIDIKAN_TERAKHIR='Diploma 1-3'");
$sql_s1 = $mysqli->query("SELECT * FROM tabel_kependudukan JOIN tabel_pendidikan ON tabel_kependudukan.NIK = tabel_pendidikan.NIK WHERE tabel_pendidikan.PENDIDIKAN_TERAKHIR='S1 dan Sederajat'");
$sql_s2 = $mysqli->query("SELECT * FROM tabel_kependudukan JOIN tabel_pendidikan ON tabel_kependudukan.NIK = tabel_pendidikan.NIK WHERE tabel_pendidikan.PENDIDIKAN_TERAKHIR='S2 dan Sederajat'");
$sql_s3 = $mysqli->query("SELECT * FROM tabel_kependudukan JOIN tabel_pendidikan ON tabel_kependudukan.NIK = tabel_pendidikan.NIK WHERE tabel_pendidikan.PENDIDIKAN_TERAKHIR='S3 dan Sederajat'");
// $total_ds1 = mysqli_num_rows($sql_diploma) + mysqli_num_rows($sql_s1);
$sql_count_pekerjaan = $mysqli->query("SELECT pekerjaan, count(pekerjaan) AS jumlah FROM tabel_pekerjaan
WHERE NOT pekerjaan='--Pilih Pekerjaan--'
GROUP BY pekerjaan ;");

$sql_count_pendidikan = $mysqli->query("SELECT pendidikan_terakhir, count(pendidikan_terakhir) AS jumlah FROM tabel_pendidikan
WHERE NOT pendidikan_terakhir='--Pilih Pendidikan--'
GROUP BY pendidikan_terakhir ;");

$sql_count_jenis_kelamin = $mysqli->query("SELECT 
COALESCE(
    CASE 
        WHEN jk = 1 THEN 'Laki-Laki' 
        WHEN jk = 2 THEN 'Perempuan' 
    END,
    'Total'
) AS jenis_kelamin, 
COUNT(*) AS jumlah_penduduk
FROM 
tabel_kependudukan 
GROUP BY 
jk 
WITH ROLLUP;");

$sql_count_umur = $mysqli->query("SELECT 'Bayi' AS kategori_umur, COUNT(*) AS jumlah FROM tabel_kependudukan WHERE TAHUN BETWEEN 0 AND 4
UNION ALL
SELECT 'Anak' AS kategori_umur, COUNT(*) AS jumlah FROM tabel_kependudukan WHERE TAHUN BETWEEN 5 AND 11
UNION ALL
SELECT 'Remaja' AS kategori_umur, COUNT(*) AS jumlah FROM tabel_kependudukan WHERE TAHUN BETWEEN 12 AND 25
UNION ALL
SELECT 'Dewasa' AS kategori_umur, COUNT(*) AS jumlah FROM tabel_kependudukan WHERE TAHUN BETWEEN 26 AND 45
UNION ALL
SELECT 'Lansia' AS kategori_umur, COUNT(*) AS jumlah FROM tabel_kependudukan WHERE TAHUN > 45");

$sql_count_agama = $mysqli->query("SELECT AGAMA, COUNT(*) AS jumlah FROM tabel_kependudukan WHERE AGAMA IN ('islam', 'kristen', 'katolik', 'budha', 'hindu', 'khonghucu') GROUP BY AGAMA");

$sql_count_dusun = $mysqli->query("SELECT b.dusun, COUNT(*) AS jumlah FROM tabel_kependudukan a
LEFT JOIN tabel_dusun b ON a.DSN = b.id
GROUP BY b.dusun");
?>



<!-- ======= Hero Section ======= -->
<section id="hero" class="d-flex align-items-center">
    <div class="container">
        <div class="row gy-4">
            <div class="col-lg-6 order-2 order-lg-1 d-flex flex-column justify-content-center">
                <h1>Sistem Bantuan Sosial Desa Perning</h1>
                <p style="text-align: justify;">
                    Sistem Informasi Bantuan Sosial Desa Perning atau bisa disingkat SIBSDP merupakan suatu sistem yang dapat mengolah data kependudukan yang berada di <?= $row_profil->nama_desa; ?> menjadi Data Klasifikasi kependudukan dan Data Klasifikasi Bantuan.
                </p>
                <div>
                    <!-- tombol Lihat Daftar Penerima bantuan -->
                    <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal" class="btn-get-started scrollto">Lihat Daftar Penerima Bantuan</a>
                </div>
            </div>
            <div class="col-lg-6 order-1 order-lg-2 hero-img">
                <img src="<?= $base_url; ?>asset_user/img/test 1@4x-8.png" class="img-fluid animated" alt="">
            </div>
        </div>

        <!-- modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Pencarian Lebih Lengkap</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="filter" method="GET">
                        <div class="modal-body">
                            <!-- Dusun -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="pencarian_cek" class="col-form-label"><b>Pencarian Berdasarkan ?</b></label><br>
                                        <div class="form-check-inline">
                                            <label class="form-check-label">
                                                <input type="radio" name="pencarian" class="form-check-input" value="rekomendasi">
                                                Rekomendasi Penerima Bantuan
                                            </label>
                                        </div>
                                        <div class="form-check-inline">
                                            <label class="form-check-label">
                                                <input type="radio" name="pencarian" class="form-check-input" value="penerima">
                                                Penerima Bantuan
                                            </label>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <!-- Tipe bantuan -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="dusun" class="col-form-label"><b>Dusun</b></label>
                                        <select id="dusun" name="dusun" class="form-control">
                                            <option value="" hidden>Pilih Dusun</option>
                                            <?php
                                            $result_dusun = $mysqli->query("SELECT * FROM tabel_dusun");
                                            while ($rows_dusun = $result_dusun->fetch_object()) {
                                                echo "
                                                    <option value='$rows_dusun->id'>$rows_dusun->dusun</option>
                                                ";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="jenis_bantuan" class="col-form-label"><b>Jenis Bantuan</b></label>
                                        <select id="jenis_bantuan" name="jenis_bantuan" class="form-control pencek">
                                            <option value="" hidden>--Pilih Jenis Bantuan--</option>
                                            <option value="BPNT">Bantuan Sembako (BPNT)</option>
                                            <option value="PKH">Bantuan PKH</option>
                                            <option value="BST">Bantuan Sosial Tunai (BST)</option>
                                            <option value="BLT">Bantuan Langsung Tunai Dana Desa (BLT-Dana Desa)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" name="terapkan" value="filter_data" class="btn text-light" style="background-color: #042165;">Terapkan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- akhir modal -->

    </div>

</section><!-- End Hero -->

<main id="main">

    <!-- ======= About Section ======= -->
    <section id="about" class="about">
        <div class="container">



            <div class="footer-newsletter">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-6 text-center">
                            <h2>Cari Informasi Penerima Bantuan</h2>

                            <!-- pencarian -->
                            <form class="d-flex custom-search" action="search" method="GET">
                                <input class="form-control me-2" type="number" name="nik" placeholder="Masukan NIK Kepala Keluarga" aria-label="Search" required>
                                <!-- Tombol cari -->
                                <button class="btn text-light me-2" type="submit" style="background-color: #042165;">Cari</button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-2 d-flex align-items-center justify-content-center about-img">
                <img src="<?= $base_url; ?>asset_user/img/pencarian.png" class="img-fluid" alt="" data-aos="zoom-in">
            </div>
            <div class="text-center">
                <h6>Cari Informasi Penerima Bantuan</h6>
                <p>Untuk mengecek siapa saja yang menerima bantuan, Anda dapat memulai dengan Memasukkan NIK dari kepala keluarga yang ingin dicari.</p>
            </div>
        </div>

        <div class="row justify-content-center mt-5">
            <div class="col-lg-2 d-flex align-items-center justify-content-center about-img pb-3">
                <img src="<?= $base_url; ?>asset_user/img/Artboard.png" style="width: 300px !important;" alt="" data-aos="zoom-in">
            </div>
            <div class="row align-items-center justify-content-center">
                <div class="col-lg-7 text-center">
                    <h2 style="font-size: 1.5rem;">Klasifikasi Bantuan</h2>
                    <p>
                        Dalam pengklasifikasian bantuan, sistem ini menggunakan kriteria - kriteria yang berdasarkan keputusan
                        Menteri Sosial Republik Indonesia Nomor : 146 / HUK / 2013 tentang penetapan kriteria dan Pendataan Fakir
                        Miskin dan Orang Tidak Mampu terdiri atas 14 (empat belas) kriteria kemiskinan.

                    </p>
                </div>
            </div>
        </div>
    </section><!-- End About Section -->

    <!-- ======= Kependudukan ======= -->

    <!-- demo grafipenduduk -->
    <section id="Demografi" class="services section-bg">
        <div class="container" data-aos="fade-up">

            <div class="section-title">
                <h2>Infografis Kependudukan</h2>
                <p>Demografi Penduduk </p>
            </div>

            <div class="row justify-content-center">
                <canvas id="chart-jk"></canvas>
            </div>

        </div>
    </section>
    <!-- akhir Demografi Penduduk -->

    <!-- Pendidikan -->
    <section id="Pendidikan" class="services section-bg">
        <div class="container" data-aos="fade-up">

            <div class="section-title">
                <h2>Infografis Kependudukan</h2>
                <p>Pendidikan</p>
            </div>

            <div class="row justify-content-center">
                <canvas id="chart-pendidikan"></canvas>
            </div>

        </div>
    </section>
    <!-- Akhir pendidikan -->



    <!-- Pekerjaan -->

    <section id="Pekerjaan" class="services section-bg">
        <div class="container" data-aos="fade-up">

            <div class="section-title">
                <h2>Infografis Kependudukan</h2>
                <p>Pekerjaan</p>
            </div>

            <?php
            $sql_blmbekerja = $mysqli->query("SELECT * FROM tabel_kependudukan JOIN tabel_pekerjaan ON tabel_kependudukan.NIK = tabel_pekerjaan.NIK WHERE tabel_pekerjaan.PEKERJAAN='Tidak Bekerja'");
            $sql_petani = $mysqli->query("SELECT * FROM tabel_kependudukan JOIN tabel_pekerjaan ON tabel_kependudukan.NIK = tabel_pekerjaan.NIK WHERE tabel_pekerjaan.PEKERJAAN='Petani'");
            $sql_buruh_tani = $mysqli->query("SELECT * FROM tabel_kependudukan JOIN tabel_pekerjaan ON tabel_kependudukan.NIK = tabel_pekerjaan.NIK WHERE tabel_pekerjaan.PEKERJAAN='Buruh Tani'");
            $sql_buruh_kebun = $mysqli->query("SELECT * FROM tabel_kependudukan JOIN tabel_pekerjaan ON tabel_kependudukan.NIK = tabel_pekerjaan.NIK WHERE tabel_pekerjaan.PEKERJAAN='Buruh Perkebunan'");
            $sql_buruh_bangunan = $mysqli->query("SELECT * FROM tabel_kependudukan JOIN tabel_pekerjaan ON tabel_kependudukan.NIK = tabel_pekerjaan.NIK WHERE tabel_pekerjaan.PEKERJAAN='Buruh Bangunan'");
            $sql_nelayan = $mysqli->query("SELECT * FROM tabel_kependudukan JOIN tabel_pekerjaan ON tabel_kependudukan.NIK = tabel_pekerjaan.NIK WHERE tabel_pekerjaan.PEKERJAAN='Nelayan'");
            $sql_guru = $mysqli->query("SELECT * FROM tabel_kependudukan JOIN tabel_pekerjaan ON tabel_kependudukan.NIK = tabel_pekerjaan.NIK WHERE tabel_pekerjaan.PEKERJAAN='Guru'");
            $sql_pedagang_kecil = $mysqli->query("SELECT * FROM tabel_kependudukan JOIN tabel_pekerjaan ON tabel_kependudukan.NIK = tabel_pekerjaan.NIK WHERE tabel_pekerjaan.PEKERJAAN='Pedagang Kecil'");
            $sql_pedagang_besar = $mysqli->query("SELECT * FROM tabel_kependudukan JOIN tabel_pekerjaan ON tabel_kependudukan.NIK = tabel_pekerjaan.NIK WHERE tabel_pekerjaan.PEKERJAAN='Pedagang Besar'");
            $sql_industri = $mysqli->query("SELECT * FROM tabel_kependudukan JOIN tabel_pekerjaan ON tabel_kependudukan.NIK = tabel_pekerjaan.NIK WHERE tabel_pekerjaan.PEKERJAAN='Pengolahan/Industri'");
            $sql_pns = $mysqli->query("SELECT * FROM tabel_kependudukan JOIN tabel_pekerjaan ON tabel_kependudukan.NIK = tabel_pekerjaan.NIK WHERE tabel_pekerjaan.PEKERJAAN='PNS'");
            $sql_pensiun = $mysqli->query("SELECT * FROM tabel_kependudukan JOIN tabel_pekerjaan ON tabel_kependudukan.NIK = tabel_pekerjaan.NIK WHERE tabel_pekerjaan.PEKERJAAN='Pensiunan'");
            $sql_perdesa = $mysqli->query("SELECT * FROM tabel_kependudukan JOIN tabel_pekerjaan ON tabel_kependudukan.NIK = tabel_pekerjaan.NIK WHERE tabel_pekerjaan.PEKERJAAN='Perangkat Desa'");
            $sql_tki = $mysqli->query("SELECT * FROM tabel_kependudukan JOIN tabel_pekerjaan ON tabel_kependudukan.NIK = tabel_pekerjaan.NIK WHERE tabel_pekerjaan.PEKERJAAN='TKI'");
            ?>
            <div class="row justify-content-center">
                <canvas id="chart-pekerjaan"></canvas>
            </div>

        </div>
    </section>

    <!-- Akhir Pekerjaan -->


    <!-- Kelompok umur -->

    <section id="Kelompok" class="services section-bg">
        <div class="container" data-aos="fade-up">

            <div class="section-title">
                <h2>Infografis Kependudukan</h2>
                <p>Kelompok Umur</p>
            </div>

            <?php
            $sql_umur_bayi = $mysqli->query("SELECT * FROM tabel_kependudukan WHERE TAHUN BETWEEN 0 AND 4");
            $sql_umur_anak = $mysqli->query("SELECT * FROM tabel_kependudukan WHERE TAHUN BETWEEN 5 AND 11");
            $sql_umur_remaja = $mysqli->query("SELECT * FROM tabel_kependudukan WHERE TAHUN BETWEEN 12 AND 25");
            $sql_umur_dewasa = $mysqli->query("SELECT * FROM tabel_kependudukan WHERE TAHUN BETWEEN 26 AND 45");
            $sql_umur_lansia = $mysqli->query("SELECT * FROM tabel_kependudukan WHERE TAHUN > 45");
            ?>

            <div class="row justify-content-center">
                <canvas id="chart-umur"></canvas>
            </div>

        </div>
    </section>

    <!-- end kelopok umur   -->


    <!-- Agama -->
    <section id="Agama" class="services section-bg">
        <div class="container" data-aos="fade-up">

            <div class="section-title">
                <h2>Infografis Kependudukan</h2>
                <p>Agama</p>
            </div>

            <?php
            $sql_islam = $mysqli->query("SELECT * FROM tabel_kependudukan WHERE AGAMA='islam'");
            $sql_kristen = $mysqli->query("SELECT * FROM tabel_kependudukan WHERE AGAMA='kristen'");
            $sql_katolik = $mysqli->query("SELECT * FROM tabel_kependudukan WHERE AGAMA='katolik'");
            $sql_budha = $mysqli->query("SELECT * FROM tabel_kependudukan WHERE AGAMA='budha'");
            $sql_hindu = $mysqli->query("SELECT * FROM tabel_kependudukan WHERE AGAMA='hindu'");
            $sql_khonghucu = $mysqli->query("SELECT * FROM tabel_kependudukan WHERE AGAMA='khonghucu'");
            ?>

            <div class="row justify-content-center">
                <canvas id="chart-agama"></canvas>
            </div>

        </div>
    </section>

    <!-- end agama -->


    <!-- Dusun -->

    <section id="Dusun" class="services section-bg">
        <div class="container" data-aos="fade-up">

            <div class="section-title">
                <h2>Infografis Kependudukan</h2>
                <p>Dusun</p>
            </div>

            <div class="row justify-content-center">
                <canvas id="chart-dusun"></canvas>
            </div>

        </div>
    </section>

    <!-- end dusun -->

    <!-- End Kependudukan -->


    <!-- ======= Contact Us Section ======= -->
    <section id="contact" class="contact">
        <div class="container" data-aos="fade-up">

            <div class="section-title">
                <h2>Hubungi Kami</h2>
                <p>Hubungi kami untuk memulai</p>
            </div>

            <div class="row">
                <div class="col-lg-12" data-aos="fade-up" data-aos-delay="100">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="info">
                                <div class="address">
                                    <i class="bi bi-geo-alt"></i>
                                    <h4>Lokasi:</h4>
                                    <p><?= $row_profil->alamat; ?></p>
                                </div>

                                <div class="email">
                                    <i class="bi bi-envelope"></i>
                                    <h4>Email:</h4>
                                    <p><?= $row_profil->email; ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="info">
                                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d9887.829379943943!2d112.4845825!3d-7.407482!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e780efb93e960f3%3A0x4200f8a855cdf07d!2sPerning%2C%20Jetis%2C%20Mojokerto%20Regency%2C%20East%20Java!5e0!3m2!1sen!2sid!4v1621074426231!5m2!1sen!2sid" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </section><!-- End Contact Us Section -->
    <?php
    $data_count_pekerjaan = array();
    while ($row = mysqli_fetch_array($sql_count_pekerjaan)) {
        $data_count_pekerjaan[$row['pekerjaan']] = $row['jumlah'];
    }
    ?>

    <?php
    $data_count_pendidikan = array();
    while ($row = mysqli_fetch_array($sql_count_pendidikan)) {
        $data_count_pendidikan[$row['pendidikan_terakhir']] = $row['jumlah'];
    }
    ?>

    <?php
    $data_count_jk = array();
    while ($row = mysqli_fetch_array($sql_count_jenis_kelamin)) {
        $data_count_jk[$row['jenis_kelamin']] = $row['jumlah_penduduk'];
    }

    // print_r($data_count_jk);
    ?>

    <?php
    $data_count_umur = array();
    while ($row = mysqli_fetch_array($sql_count_umur)) {
        $data_count_umur[$row['kategori_umur']] = $row['jumlah'];
    }

    ?>

    <?php
    $data_count_agama = array();
    while ($row = mysqli_fetch_array($sql_count_agama)) {
        $data_count_agama[$row['AGAMA']] = $row['jumlah'];
    }
    ?>

    <?php
    $data_count_dusun = array();
    while ($row = mysqli_fetch_array($sql_count_dusun)) {
        $data_count_dusun[$row['dusun']] = $row['jumlah'];
    }
    print_r($data_count_dusun)
    ?>
</main><!-- End #main -->
<script>
    var dataPekerjaan = {
        labels: [<?php foreach ($data_count_pekerjaan as $key => $value) {
                        echo '"' . $key . '",';
                    } ?>],
        datasets: [{
            label: "Jumlah yang bekerja",
            data: [<?php foreach ($data_count_pekerjaan as $key => $value) {
                        echo '"' . $value . '",';
                    } ?>],
            backgroundColor: "rgba(54, 162, 235, 0.5)",
            borderColor: "rgb(54, 162, 235)",
            borderWidth: 1,
        }, ],
    };

    var optionsPekerjaan = {
        responsive: true,
        maintainAspectRatio: false,
        legend: {
            display: false,
        },
        plugins: {
            title: {
                display: true,
                text: "Grafik Data Pekerjaan",
            },
        },
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true,
                },
            }, ],
        },
    };

    var ctxPekerjaan = document.getElementById("chart-pekerjaan").getContext("2d");
    var chartPekerjaan = new Chart(ctxPekerjaan, {
        type: "bar",
        data: dataPekerjaan,
        options: optionsPekerjaan,
    });

    var dataPendidikan = {
        labels: [<?php foreach ($data_count_pendidikan as $key => $value) {
                        echo '"' . $key . '",';
                    } ?>],
        datasets: [{
            label: "Jumlah yang menempuh pendidikan",
            data: [<?php foreach ($data_count_pendidikan as $key => $value) {
                        echo '"' . $value . '",';
                    } ?>],
            backgroundColor: "rgba(54, 162, 235, 0.5)",
            borderColor: "rgb(54, 162, 235)",
            borderWidth: 1,
        }, ],
    };

    var optionsPendidikan = {
        responsive: true,
        maintainAspectRatio: false,
        legend: {
            display: false,
        },
        plugins: {
            title: {
                display: true,
                text: "Grafik Data Pendidikan",
            },
        },
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true,
                },
            }, ],
        },
    };

    var ctxPendidikan = document.getElementById("chart-pendidikan").getContext("2d");
    var chartPendidikan = new Chart(ctxPendidikan, {
        type: "bar",
        data: dataPendidikan,
        options: optionsPendidikan,
    });

    var dataJk = {
        labels: [<?php foreach ($data_count_jk as $key => $value) {
                        echo '"' . $key . '",';
                    } ?>],
        datasets: [{
            label: "Jumlah",
            data: [<?php foreach ($data_count_jk as $key => $value) {
                        echo '"' . $value . '",';
                    } ?>],
            backgroundColor: "rgba(54, 162, 235, 0.5)",
            borderColor: "rgb(54, 162, 235)",
            borderWidth: 1,
        }, ],
    };

    var optionsJk = {
        responsive: true,
        maintainAspectRatio: false,
        legend: {
            display: false,
        },
        plugins: {
            title: {
                display: true,
                text: "Grafik Data Jenis Kelamin",
            },
        },
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true,
                },
            }, ],
        },
    };

    var ctxJk = document.getElementById("chart-jk").getContext("2d");
    var chartJk = new Chart(ctxJk, {
        type: "bar",
        data: dataJk,
        options: optionsJk,
    });

    var dataUmur = {
        labels: [<?php foreach ($data_count_umur as $key => $value) {
                        echo '"' . $key . '",';
                    } ?>],
        datasets: [{
            label: "Jumlah",
            data: [<?php foreach ($data_count_umur as $key => $value) {
                        echo '"' . $value . '",';
                    } ?>],
            backgroundColor: "rgba(54, 162, 235, 0.5)",
            borderColor: "rgb(54, 162, 235)",
            borderWidth: 1,
        }, ],
    };

    var optionsUmur = {
        responsive: true,
        maintainAspectRatio: false,
        legend: {
            display: false,
        },
        plugins: {
            title: {
                display: true,
                text: "Grafik Data Umur",
            },
        },
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true,
                },
            }, ],
        },
    };

    var ctxUmur = document.getElementById("chart-umur").getContext("2d");
    var chartUmur = new Chart(ctxUmur, {
        type: "bar",
        data: dataUmur,
        options: optionsUmur,
    });


    var dataAgama = {
        labels: [<?php foreach ($data_count_agama as $key => $value) {
                        echo '"' . $key . '",';
                    } ?>],
        datasets: [{
            label: "Jumlah",
            data: [<?php foreach ($data_count_agama as $key => $value) {
                        echo '"' . $value . '",';
                    } ?>],
            backgroundColor: "rgba(54, 162, 235, 0.5)",
            borderColor: "rgb(54, 162, 235)",
            borderWidth: 1,
        }, ],
    };

    var optionsAgama = {
        responsive: true,
        maintainAspectRatio: false,
        legend: {
            display: false,
        },
        plugins: {
            title: {
                display: true,
                text: "Grafik Data Agama",
            },
        },
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true,
                },
            }, ],
        },
    };

    var ctxAgama = document.getElementById("chart-agama").getContext("2d");
    var chartAgama = new Chart(ctxAgama, {
        type: "bar",
        data: dataAgama,
        options: optionsAgama,
    });

    var dataDusun = {
        labels: [<?php foreach ($data_count_dusun as $key => $value) {
                        echo '"' . $key . '",';
                    } ?>],
        datasets: [{
            label: "Jumlah",
            data: [<?php foreach ($data_count_dusun as $key => $value) {
                        echo '"' . $value . '",';
                    } ?>],
            backgroundColor: "rgba(54, 162, 235, 0.5)",
            borderColor: "rgb(54, 162, 235)",
            borderWidth: 1,
        }, ],
    };

    var optionsDusun = {
        responsive: true,
        maintainAspectRatio: false,
        legend: {
            display: false,
        },
        plugins: {
            title: {
                display: true,
                text: "Grafik Data Dusun",
            },
        },
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true,
                },
            }, ],
        },
    };

    var ctxDusun = document.getElementById("chart-dusun").getContext("2d");
    var chartDusun = new Chart(ctxDusun, {
        type: "bar",
        data: dataDusun,
        options: optionsDusun,
    });
</script>