<?php
require '../backend/function.php';
require '../backend/cek.php';

//Dapatkan ID barang yang dipassing di halaman sebelumnya
$idbarang = $_GET['id']; //Get ID barang
//Get informasi barang berdasarkan database
$get = mysqli_query($conn, "select * from stock where idbarang='$idbarang'");
$fetch = mysqli_fetch_assoc($get);
//Set variable
$namabarang = $fetch['namabarang'];
$deskripsi = $fetch['deskripsi'];
$stock = $fetch['stock'];

//cek ada gambar atau tidak
$gambar = $fetch['image']; //ambil gambar
if (empty($gambar)) {
    $img = '<img src="images/default.jpg" class="zoomable">';
} else {
    $img = '<img src="images/' . $gambar . '" class="zoomable">';
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Stock - Detail Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <style>
        .zoomable {
            width: 200px;
            height: 200px;
        }

        .zoomable:hover {
            transform: scale(2);
            transition: 0.3s ease;
        }

        a {
            text-decoration: none;
            color: black;
        }
    </style>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="index.php">Stock@</a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <a class="nav-link" href="index.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Stock Barang
                        </a>
                        <a class="nav-link" href="masuk.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Barang Masuk
                        </a>
                        <a class="nav-link" href="keluar.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Barang Keluar
                        </a>
                        <a class="nav-link" href="keluar.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Kelola Admin
                        </a>
                        <a class="nav-link" href="logout.php">
                            Logout
                        </a>
                    </div>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Detail Barang</h1>
                    <div class="card mb-4">
                        <div class="card-header">
                            <h2><?= $namabarang; ?></h2>
                            <?= $img; ?>
                        </div>
                        <div class="card-body">

                            <div class="row">
                                <div class="col-md-3">Deskripsi</div>
                                <div class="col-md-9">: <?= $deskripsi; ?></div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">Stock</div>
                                <div class="col-md-9">: <?= $stock; ?></div>
                            </div>

                            <br></br>
                            <hr>

                            <h3>Barang Masuk</h3>
                            <table class="table table-striped table-hover table-bordered" id="barangmasuk" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>ID Barang</th>
                                        <th>Tanggal</th>
                                        <th>keterangan</th>
                                        <th>Quantity</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                    $ambildatamasuk = mysqli_query($conn, "SELECT * FROM masuk where idbarang='$idbarang'");
                                    $i = 1;

                                    while ($fetch = mysqli_fetch_assoc($ambildatamasuk)) {                                        $tanggal = $fetch['tanggal'];
                                        $keterangan = $fetch['keterangan'];
                                        $quantity = $fetch['qty'];

                                    ?>
                                        <tr>
                                            <td><?= $i++; ?></td>
                                            <td><?= $idbarang; ?></td>
                                            <td><?= $tanggal; ?></td>
                                            <td><?= $keterangan; ?></td>
                                            <td><?= $quantity; ?></td>
                                        </tr>

                                    <?php
                                    };


                                    ?>

                                </tbody>
                            </table>


                            <br></br>

                            <h3>Barang Keluar</h3>
                            <table class="table table-striped table-hover table-bordered" id="barangkeluar" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>ID Barang</th>
                                        <th>Tanggal</th>
                                        <th>Penerima</th>
                                        <th>Quantity</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                    $ambildatakeluar = mysqli_query($conn, "SELECT * FROM keluar where idbarang='$idbarang'");
                                    $i = 1;

                                    while ($fetch = mysqli_fetch_assoc($ambildatakeluar)) {
                                        $tanggal = $fetch['tanggal'];
                                        $penerima = $fetch['penerima'];
                                        $quantity = $fetch['qty'];

                                    ?>
                                        <tr>
                                            <td><?= $i++; ?></td>
                                            <td><?= $idbarang; ?></td>
                                            <td><?= $tanggal; ?></td>
                                            <td><?= $penerima; ?></td>
                                            <td><?= $quantity; ?></td>
                                        </tr>

                                    <?php
                                    };


                                    ?>

                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </main>
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; Your Website 2025</div>
                        <div>
                            <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script src="js/scripts.js"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script> -->
    <!-- <script src="assets/demo/chart-area-demo.js"></script> -->
    <!-- <script src="assets/demo/chart-bar-demo.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>



</html>