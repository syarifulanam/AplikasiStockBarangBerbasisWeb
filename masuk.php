<?php
require 'function.php';
require 'cek.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Barang Masuk</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="index.php">Store Lab</a>
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
                        <a class="nav-link" href="admin.php">
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
                    <h1 class="mt-4">Barang Masuk</h1>

                    <div class="card mb-4">
                        <div class="card-header">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal">
                                Tambah Barang
                            </button>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped table-hover table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>IDM</th>
                                        <th>IDB</th>
                                        <th>Tanggal</th>
                                        <th>Nama Barang</th>
                                        <th>Jumlah</th>
                                        <th>Keterangan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                    $ambilsemuadatastock = mysqli_query($conn, "SELECT * FROM masuk m, stock s where s.idbarang = m.idbarang");
                                    while ($data = mysqli_fetch_array($ambilsemuadatastock)) {
                                        $idb = $data['idbarang'];
                                        $idm = $data['idmasuk'];
                                        $tanggal = $data['tanggal'];
                                        $namabarang = $data['namabarang'];
                                        $qty = $data['qty'];
                                        $keterangan = $data['keterangan'];

                                    ?>
                                        <tr>
                                            <td><?= $idm; ?></td>
                                            <td><?= $idb; ?></td>
                                            <td><?= $tanggal; ?></td>
                                            <td><?= $namabarang; ?></td>
                                            <td><?= $qty; ?></td>
                                            <td><?= $keterangan; ?></td>
                                            <td>
                                                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#edit<?= $idm; ?>">Edit</button>
                                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delete<?= $idm; ?>">Delete</button>
                                            </td>
                                        </tr>

                                        <!-- Edit Modal -->
                                        <div class="modal fade" id="edit<?= $idm; ?>">
                                            <div class="modal-dialog">
                                                <div class="modal-content">

                                                    <!-- Modal Header -->
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Edit Barang</h4>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>

                                                    <!-- Modal body -->
                                                    <form method="post">
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="form-label">ID MASUK = <?= $idm; ?></label>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Nama Barang [IDB: <?= $idb; ?>]</label>
                                                                <input type="text" value="<?= $namabarang; ?>" class="form-control" disabled>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Keterangan</label>
                                                                <input type="text" name="deskripsi" value="<?= $keterangan; ?>" class="form-control" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Quantity</label>
                                                                <input type="text" name="deskripsi" value="<?= $qty; ?>" class="form-control" required>
                                                            </div>
                                                            <br />
                                                            <input type="hidden" name="idb" value="<?= $idb; ?>">
                                                            <input type="hidden" name="idm" value="<?= $idm; ?>">
                                                            <button type="submit" class="btn btn-primary" name="updatebarangmasuk">Submit</button>
                                                            <br />
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Delete Modal -->
                                        <div class="modal fade" id="delete<?= $idm; ?>">
                                            <div class="modal-dialog">
                                                <div class="modal-content">

                                                    <!-- Modal Header -->
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Hapus Barang</h4>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>

                                                    <!-- Modal body -->
                                                    <form method="post">
                                                        <div class="modal-body">
                                                            Apakah Anda yakin ingin menghapus <?= $namabarang; ?>
                                                            <input type="hidden" name="idb" value="<?= $idb; ?>">
                                                            <input type="hidden" name="qty" value="<?= $qty; ?>">
                                                            <input type="hidden" name="idm" value="<?= $idm; ?>">
                                                            <br />
                                                            <br />
                                                            <button type="submit" class="btn btn-danger" name="hapusbarangmasuk">Hapus</button> 
                                                            <br />
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

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
        <!-- The Modal -->
        <div class="modal fade" id="myModal">
            <div class="modal-dialog">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Tambah Barang Masuk</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <!-- Modal body -->
                    <form method="post">
                        <div class="modal-body">

                            <select name="idbarang" class="form-control">
                                <?php
                                $ambilsemuadatanya = mysqli_query($conn, "SELECT * FROM stock");
                                while ($fetcharry = mysqli_fetch_array($ambilsemuadatanya)) {
                                    $namabarangnya = $fetcharry['namabarang'];
                                    $idbarangnya = $fetcharry['idbarang'];
                                ?>
                                    <option value="<?= $idbarangnya; ?>">
                                        <?= $namabarangnya; ?>
                                    </option>
                                <?php
                                }
                                ?>
                            </select>
                            <br />
                            <input type="number" name="qty" class="form-control" placeholder="Quantity barang" required>
                            <br />
                            <input type="text" name="penerima" class="form-control" placeholder="Penerima barang" required>
                            <br />
                            <button type="submit" class="btn btn-primary" name="barangmasuk">Submit</button>
                            <br />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script> -->
    <!-- <script src="assets/demo/chart-area-demo.js"></script> -->
    <!-- <script src="assets/demo/chart-bar-demo.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
</body>

</html>