<?php
    $jsonURL = "./data/buku.json"; // Mengambil URL File JSON dibuat
    $dataBuku = array(); // Mendefiniskan variabel dataBuku dengan nilai Array kosong
    $dataJson = file_get_contents($jsonURL); // Mengambil data dari file JSON yang sudah dibuat sebelumnya
    $dataBuku = json_decode($dataJson, true); // Mendecode data JSON dan merubahnya menjadi array assosiatif

    function getDataBook(){ // fungsi untuk mengambil data buku
        global $jsonURL; // Mengambil URL file JSON dari variabel Global
        $dataBuku = file_get_contents($jsonURL); // mengambil data dari file data.json
        $dataBukuArray = json_decode($dataBuku, true); // mengubah atau mendecode nilai data.json menjadi array assosiatif pada php
        usort($dataBukuArray, function($a, $b) { // mensortir secara ASC dari nilai maskapai atau nama maskapai
            return strcmp($a['judulBuku'], $b['judulBuku']);
        });
        return $dataBukuArray; // mengembalikan data yang sudah disortir
    }


    function postDataBook($data) { // fungsi untuk menyimpan data dari fom ke file data.json dengan menerima 1 parameter yaitu data yang ingin ditambahkan
        $jsonData = json_encode($data, JSON_PRETTY_PRINT); // mengubah data yang ingin ditambahkan dari array assosiatif menjadi json format
        file_put_contents('./data/buku.json', $jsonData); // menambahkan atau mengupdate atau memperbaharui file yang ditambahkan ke file data.json
    }


    function hitungTotalHarga($hargaBuku, $pajak){ // Fungsi untuk menghitung total harga buku dengan menerima 2 parameter yaitu harga buku dan pajak
        $totalPajak = $hargaBuku * $pajak / 100; // Menghitung total pajak
        $totalHarga = $hargaBuku + $totalPajak; // Menghitung total harga buku
        return (int)$totalHarga; // Mengembalikan nilai total harga buku dengan tipe integer ke luar fungsi tersebut
    }

    if(isset($_POST['submit'])){ // Apabila button submit di tekan (Pada tambah data)
        $judulBuku = $_POST['judulBuku']; // Membuat variabel judul buku dengan nilai dari form dengan inputan yang memiliki name judul buku
        $namaPenulis = $_POST['namaPenulis']; // Membuat variabel nama penulis dengan nilai dari form dengan inputan yang memiliki name nama penulis
        $tahunTerbit = $_POST['tahunTerbit']; // Membuat variabel tahun terbit dengan nilai dari form dengan inputan yang memiliki name tahun terbit
        $harga = (int)$_POST['harga']; // Membuat variabel harga dengan nilai dari form dengan inputan yang memiliki name harga kemudian value tersebut di konversi dari string menjadi integer
        $pajak = (int)$_POST['pajak']; // Membuat variabel pajak dengan nilai dari form dengan inputan yang memiliki name pajak kemudian value tersebut di konversi dari string menjadi integer

        $totalHarga = hitungTotalHarga($harga, $pajak); // Menghitung total harga buku dengan menggunakan fungsi hitungTotalHarga yang sudah dibuat dengan mengirimkan data (parameter) yaitu harga dan pajak

        // Membuat blueprint atau struktur data (array assosiatif) yang ingin ditambahkan dengan beberapa key dan value
        $newBook = [
            "judulBuku" => $judulBuku,
            "namaPenulis" => $namaPenulis,
            "tahunTerbit" => $tahunTerbit,
            "harga" => $harga,
            "pajak" => $pajak,
            "totalHarga" => $totalHarga
        ];

        // menambahkan atau menggabungkan data penerbangan yang sudah ada dengan new rute yang baru dibuat
        array_push($dataBuku, $newBook);

        // menyimpan data yang sudah dibuat
        postDataBook($dataBuku);
    }

    if(isset($_POST['delete'])){ // Apabila button yang memiliki name 'delete' di klik
        $namaBuku = $_POST['delete']; // Mengambil nama buku dari button yang diklik
        foreach($dataBuku as $key => $buku){ // Melakukan perulangan dari setiap data buku yang dimiliki (Data ini merujuk pada Buku.json)
            if($namaBuku == $buku['judulBuku']){ // Apabila data buku sama dengan nama buku yang dicari
                unset($dataBuku[$key]); // Hapus data buku yang dicari
                postDataBook($dataBuku); // Mempost data menggunakan fungsi yang sudah dibuat yaitu postDataBook dengan mengirimkan 1 parameter yaitu data buku yang sekarang sudah dihapus dari nama buku yang dicari
            }
        }
    }
?>



<!Doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Tugas Besar | Aditya Bayu Aji</title>
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
        <link href="./library/css/bootstrap.min.css" rel="stylesheet">
        <link href="./library/css/style.css" rel="stylesheet">
    </head>
    <body style="background-color:#202124; color:#ffffff; font-family: 'Open Sans', sans-serif;">

    <!-- NAVIGASI -->
    <nav class="navbar mt-3">
        <div class="container">
            <a class="navbar-brand text-light" href="#">
                <img src="./data/images/logo.png" alt="Logo" width="30" height="26" class="d-inline-block align-text-top">
                <span class="navbar-brand mb-0 text-white fw-bold">Arsip Buku</span>
            </a>
        </div>
    </nav>

    <!-- HEADER -->
    <div class="container my-5 pt-5">
        <div class="row">
            <div class="col-6">
                <h1 class="fw-semibold mb-3">Website Arsip Buku</h1>
                <p class="fw-medium mb-5">Banyak referensi buku dapat ditemukan, lihat sekarang dan apakah ada yang anda lewatkan?</p>
                <button class="btn btn-primary px-4 fw-semibold mt-5 py-2 rounded rounded-3">Daftar Buku</button>
            </div>
            <div class="col-6 text-center pt-5">
                <img src="./data/images/book.png" alt="Buku" width="60%">
            </div>
        </div>
    </div>

    <!-- BODY -->
    <div class="container mt-5 pt-5">
        <div class="row my-3">
            <div class="col">
                <h1 class="mb-4">Data Arsip Buku</h1>
            </div>
            <div class="col text-end">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahDataBuku">
                    Tambah Data Buku
                </button>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr class="text-center">
                            <th scope="col">No</th>
                            <th scope="col">Judul Buku</th>
                            <th scope="col">Nama Penulis</th>
                            <th scope="col">Tahun Terbit</th>
                            <th scope="col">Harga Buku</th>
                            <th scope="col">Pajak (%)</th>
                            <th scope="col">Total Harga</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if(count(getDataBook()) > 0){
                                $index = 1;
                                foreach(getDataBook() as $key => $value){
                                    echo "<tr>";
                                    echo "<th scope='row'>$index</th>";
                                    echo "<td class='col-5'>" . $value['judulBuku'] . "</td>";
                                    echo "<td class='col-2'>" . $value['namaPenulis'] . "</td>";
                                    echo "<td class='col-1'>" . $value['tahunTerbit'] . "</td>";
                                    echo "<td class='col-1'>" . $value['harga'] . "</td>";
                                    echo "<td class='col-1'>" . $value['pajak'] . "</td>";
                                    echo "<td class='col-1'>" . $value['totalHarga'] . "</td>";
                                    echo "<td class='col-1'>";
                                    echo "<div class='d-flex gap-1'>";
                                    echo "<form method='post'>";
                                    echo "<button type='submit' name='delete' value='" . $value['judulBuku'] . "' class='btn btn-danger btn-sm'>Hapus</button>";
                                    echo "</form>";
                                    echo "</div>";
                                    echo "</td>";
                                    echo "</tr>";
                                    $index += 1;
                                }
                            }else{
                                echo "";
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>


        <!-- Modal Tambah Buku -->
        <div class="modal fade" id="tambahDataBuku" tabindex="-1" aria-labelledby="tambahDataBukuLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg text-black">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5 fw-semibold" id="tambahDataBukuLabel">Tambah Data Buku</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="post">
                        <div class="modal-body">
                            <div class="mb-3 row">
                                <div class="col-3">
                                    <label for="judulBuku" class="form-label">Judul Buku</label>
                                </div>
                                <div class="col-9">
                                    <input type="text" class="form-control" name="judulBuku" id="judulBuku" placeholder="A Brief History of Time" required>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <div class="col-3">
                                    <label for="namaPenulis" class="form-label">Nama Penulis</label>
                                </div>
                                <div class="col-9">
                                    <input type="text" class="form-control" name="namaPenulis" id="namaPenulis" placeholder="Stephen Hawking" required>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <div class="col-3">
                                    <label for="tahunTerbit" class="form-label">Tahun Terbit</label>
                                </div>
                                <div class="col-9">
                                    <input type="number" class="form-control" name="tahunTerbit" id="tahunTerbit" placeholder="2000" required>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <div class="col-3">
                                    <label for="harga" class="form-label">Harga Buku</label>
                                </div>
                                <div class="col-9">
                                    <input type="number" class="form-control" name="harga" id="harga" placeholder="100000" required>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <div class="col-3">
                                    <label for="pajak" class="form-label">Pajak</label>
                                </div>
                                <div class="col-8">
                                    <input type="number" class="form-control" name="pajak" id="pajak" placeholder="20" required>
                                </div>
                                <div class="col-1">
                                    <p class="fs-4">%</p>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="reset" class="btn btn-secondary">
                            <input type="submit" class="btn btn-primary" name="submit">
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="container mt-5 py-5">
            <div class="row">
                <div class="col">&copy;Copyright 2023. Alright Reserved.</div>
                <div class="col text-end">Aditya Bayu Aji.</div>
            </div>
        </div>
    
    <script src="./library/js/bootstrap.bundle.min.js"></script>
    </body>
</html>