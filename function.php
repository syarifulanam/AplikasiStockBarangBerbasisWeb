<?php
session_start();

// Membuat koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "stockbarang");

// Menambahkan barang baru
if (isset($_POST['addnewbarang'])) {
    $namabarang = $_POST['namabarang'];
    $deskripsi = $_POST['deskripsi'];
    $stock = $_POST['stock'];

    // soal gambar
    $allowed_extension = array('png', 'jpg');
    $nama = $_FILES['file']['name']; //ngambil nama gambar
    $dot = explode('.', $nama);
    $ekstensi = strtolower(end($dot)); //ngambil ekstensinya
    $ukuran = $_FILES['file']['size']; //ngambil size filenya
    $file_tmp = $_FILES['file']['tmp_name']; //ngambil lokasi filenya

    //penamaan file -> enkripsi
    $image = md5(uniqid($nama, true) . time()) . '.' . $ekstensi; //menggabungkan nama file yg dienkripsi dgn ekstensinya 

    // validasi udah ada atau belum
    $cek = mysqli_query($conn, "select * from stock where namabarang='$namabarang'");
    $hitung = mysqli_num_rows($cek);

    if ($hitung < 1) {
        //jika belum ada

        //proses upload gambar
        if (in_array($ekstensi, $allowed_extension) == true) {
            //validasi ukuran filenya
            if ($ukuran < 15000000) {
                move_uploaded_file($file_tmp, 'images/' . $image);

                $addtotable = mysqli_query($conn, "INSERT INTO stock (namabarang, deskripsi, stock, image) VALUES ('$namabarang', '$deskripsi', '$stock', '$image')");
                if ($addtotable) {
                    header('location: index.php');
                    exit();
                } else {
                    echo 'Gagal';
                    header('location: index.php');
                    exit();
                }
            } else {
                //kalau filenya lebih dari 15mb
                echo '
                <script>
                alert("Ukuran terlalu besar");
                window.location.href="index.php";
                </script>
                ';
            }
        } else {
            //kalau filenya tidak png/jpg
            echo '
            <script>
            alert("File harus png/jpg");
            window.location.href="index.php";
            </script>
            ';
        }
    } else {
        // jika sudah ada
        echo '
            <script>
            alert("Nama barang sudah terdaftar");
            window.location.href="index.php";
            </script>
            ';
    }
};

//Menambahkan barang masuk
if (isset($_POST['barangmasuk'])) {
    $idb = $_POST['idbarang'];
    $keterangan = $_POST['keterangan'];
    $qty = $_POST['qty'];
    $tanggal = date('Y-m-d'); // Simpan tanggal hari ini


    $cekstocksekarang = mysqli_query($conn, "select * from stock where idbarang='$idb'");
    $ambildatanya = mysqli_fetch_array($cekstocksekarang);

    $stocksekarang = $ambildatanya['stock'];
    $tambahkanstocksekarangdenganquantity = $stocksekarang + $qty;

    $addtomasuk = mysqli_query($conn, "INSERT INTO masuk (idbarang, qty, keterangan, tanggal) VALUES ('$idb', '$qty', '$keterangan', '$tanggal')");
    $updatestockmasuk = mysqli_query($conn, "UPDATE stock SET stock = stock + $qty WHERE idbarang = '$idb'");

    if ($addtomasuk && $updatestockmasuk) {
        echo "<script>alert('Barang masuk berhasil ditambahkan!'); window.location.href='masuk.php';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan barang masuk!');</script>";
    }
}

//Menambahkan barang keluar
if (isset($_POST['addbarangkeluar'])) {
    $idb = $_POST['idbarang'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];

    $cekstocksekarang = mysqli_query($conn, "select * from stock where idbarang='$idb'");
    $ambildatanya = mysqli_fetch_array($cekstocksekarang);

    $stocksekarang = $ambildatanya['stock'];

    if ($stocksekarang >= $qty) {
        //Kalau barangnya cukup
        $tambahkanstocksekarangdenganquantity = $stocksekarang - $qty;

        $addtokeluar = mysqli_query($conn, "INSERT INTO keluar (idbarang, penerima, qty) values('$idb','$penerima', '$qty')");
        $updatestockkeluar = mysqli_query($conn, "UPDATE stock SET stock='$tambahkanstocksekarangdenganquantity' WHERE idbarang='$idb'");
        if ($addtokeluar && $updatestockkeluar) {
            header('location: keluar.php');
            exit();
        } else {
            echo 'gagal';
            header('location: keluar.php');
            exit();
        }
    } else {
        echo '<script>alert("Gagal menambahkan barang keluar"); window.location.href="keluar.php";</script>';
    }
}

// Update info barang
if (isset($_POST['updatebarang'])) {
    $idb = $_POST['idb'];
    $namabarang = $_POST['namabarang'];
    $deskripsi = $_POST['deskripsi'];

    // soal gambar
    $allowed_extension = array('png', 'jpg');
    $nama = $_FILES['file']['name']; //ngambil nama gambar
    $dot = explode('.', $nama);
    $ekstensi = strtolower(end($dot)); //ngambil ekstensinya
    $ukuran = $_FILES['file']['size']; //ngambil size filenya
    $file_tmp = $_FILES['file']['tmp_name']; //ngambil lokasi filenya

    //penamaan file -> enkripsi
    $image = md5(uniqid($nama, true) . time()) . '.' . $ekstensi; //menggabungkan nama file yg dienkripsi dgn ekstensinya 

    if ($ukuran == 0) {
        //jika tidak ingin upload
        $update = mysqli_query($conn, "update stock set namabarang='$namabarang', deskripsi='$deskripsi' where idbarang='$idb'");
        if ($update) {
            header('location: index.php');
        } else {
            echo 'gagal';
            header('location: index.php');
        }
    } else {
        //jika ingin
        if (move_uploaded_file($file_tmp, 'images/' . $image)) {
            $update = mysqli_query($conn, "update stock set namabarang='$namabarang', deskripsi='$deskripsi', image='$image' where idbarang='$idb'");
            if ($update) {
                header('location: index.php');
            } else {
                echo 'gagal';
                header('location: index.php');
            }
        } else {
            echo 'gagal';
            header('location: index.php');
        }

        //move_uploaded_file($file_tmp, 'images/' . $image);

    }
}

//Menghapus barang dari stock
if (isset($_POST['hapusbarang'])) {
    $idb = $_POST['idb']; //idbarang

    $gambar = mysqli_query($conn, "select * from stock where idbarang='$idb'");
    $get = mysqli_fetch_array($gambar);
    $img = 'images/' . $get['image'];
    unlink($img);

    $hapus = mysqli_query($conn, "delete from stock where idbarang='$idb'");
    if ($hapus) {
        header('location: index.php');
        exit();
    } else {
        echo 'Gagal';
        header('location: index.php');
        exit();
    }
}

//Mengubah data barang masuk
if (isset($_POST['updatebarangmasuk'])) {
    $idb = $_POST['idb'];
    $idm = $_POST['idm'];
    $deskripsi = $_POST['keterangan'];
    $qty = $_POST['qty'];

    $lihatstock = mysqli_query($conn, "select * from stock where idbarang='$idb'");
    $stocknya = mysqli_fetch_array($lihatstock);
    $stockskrg = $stocknya['stock'];

    $qtyskrg = mysqli_query($conn, "select * from masuk where idmasuk='$idm'");
    $qtynya = mysqli_fetch_array($qtyskrg);
    $qtyskrg = $qtynya['qty'];

    if ($qty > $qtyskrg) {
        $selisih = $qty - $qtyskrg;
        $kurangin = $stockskrg - $selisih;
        $kurangistocknya = mysqli_query($conn, "UPDATE stock SET stock='$kurangin' WHERE idbarang='$idb'");
        $updatenya = mysqli_query($conn, "UPDATE masuk SET qty='$qty', keterangan='$deskripsi' WHERE idmasuk='$idm'");
        if ($kurangistocknya && $updatenya) {
            header('location: masuk.php');
            exit();
        } else {
            echo 'Gagal';
            header('location: masuk.php');
            exit();
        }
    } else {
        $selisih = $qtyskrg - $qty;
        $kurangin = $stockskrg + $selisih;
        $kurangistocknya = mysqli_query($conn, "UPDATE stock SET stock='$kurangin' WHERE idbarang='$idb'");
        $updatenya = mysqli_query($conn, "UPDATE masuk SET qty='$qty', keterangan='$deskripsi' WHERE idmasuk='$idm'");
        if ($kurangistocknya && $updatenya) {
            header('location: masuk.php');
            exit();
        } else {
            echo 'Gagal';
            header('location: masuk.php');
            exit();
        }
    }
}

//Menghapus barang masuk
if (isset($_POST['hapusbarangmasuk'])) {
    $idb = $_POST['idb'];
    $qty = $_POST['qty'];
    $idm = $_POST['idm'];

    $getdatastock = mysqli_query($conn, "select * from stock where idbarang='$idb'");
    $data = mysqli_fetch_array($getdatastock);
    $stock = $data['stock'];

    $selisih = $stock - $qty;

    $update = mysqli_query($conn, "update stock set stock='$selisih' where idbarang='$idb'");
    $hapusdata = mysqli_query($conn, "delete from masuk where idmasuk='$idm'");

    if ($update && $hapusdata) {
        header('location: masuk.php');
    } else {
        header('location: masuk.php');
    }
}

// Mengubah data barang keluar
if (isset($_POST['updatebarangkeluar'])) {
    $idb = $_POST['idb'];
    $idk = $_POST['idk'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];

    // Ambil stok barang saat ini
    $lihatstock = mysqli_query($conn, "SELECT stock FROM stock WHERE idbarang='$idb'");
    $stocknya = mysqli_fetch_array($lihatstock);
    $stockskrg = $stocknya['stock'];

    // Ambil qty lama di tabel keluar
    $result = mysqli_query($conn, "SELECT qty FROM keluar WHERE idkeluar='$idk'");
    $qtynya = mysqli_fetch_array($result);
    $qtylama = $qtynya['qty'];

    if ($qty > $qtylama) {
        // Jika qty baru lebih besar, stok berkurang
        $selisih = $qty - $qtylama;
        $stokbaru = $stockskrg - $selisih;
    } else {
        // Jika qty baru lebih kecil, stok bertambah
        $selisih = $qtylama - $qty;
        $stokbaru = $stockskrg + $selisih;
    }

    // Update stok barang
    $kurangistocknya = mysqli_query($conn, "UPDATE stock SET stock='$stokbaru' WHERE idbarang='$idb'");

    // Update data barang keluar
    $updatenya = mysqli_query($conn, "UPDATE keluar SET qty='$qty', penerima='$penerima' WHERE idkeluar='$idk'");

    // Cek jika query gagal
    if (!$kurangistocknya || !$updatenya) {
        echo "Gagal update: " . mysqli_error($conn);
        exit();
    }

    // Redirect ke halaman keluar.php
    header('location: keluar.php');
    exit();
}

//Menghapus barang keluar
if (isset($_POST['hapusbarangkeluar'])) {
    $idb = $_POST['idb'];
    $qty = $_POST['qty'];
    $idk = $_POST['idk'];

    $getdatastock = mysqli_query($conn, "select * from stock where idbarang='$idb'");
    $data = mysqli_fetch_array($getdatastock);
    $stock = $data['stock'];

    $selisih = $stock + $qty;

    $update = mysqli_query($conn, "update stock set stock='$selisih' where idbarang='$idb'");
    $hapusdata = mysqli_query($conn, "delete from keluar where idkeluar='$idk'");

    if ($update && $hapusdata) {
        header('location: keluar.php');
    } else {
        header('location: keluar.php');
    }
}

//Menambahkan Admin Baru
if (isset($_POST['addadmin'])) {
    $email = $_POST['emailadmin'];
    $password = $_POST['password'];

    $queryinsert = mysqli_query($conn, "INSERT INTO login (email, password) VALUES ('$email', '$password')");

    if ($queryinsert) {
        // if berhasil
        header('location: admin.php');
    } else {
        // kalau gagal insert ke db
        header('location: admin.php');
    }
}


// Edit Data Admin
if (isset($_POST['updateadmin'])) {
    $emailbaru = $_POST['emailadmin'];
    $passwordbaru = $_POST['password'];
    $idnya = $_POST['id'];

    $queryupdate = mysqli_query($conn, "UPDATE login SET email='$emailbaru', password='$passwordbaru' WHERE iduser='$idnya'");

    if ($queryupdate) {
        header('location: admin.php');
    } else {
        echo "Gagal mengupdate admin: " . mysqli_error($conn);
    }
}

// Hapus Admin
if (isset($_POST['hapusadmin'])) {
    $id = $_POST['id']; // Pastikan id dikirim dari form

    // Query untuk menghapus admin
    $querydelete = mysqli_query($conn, "DELETE FROM login WHERE iduser = '$id'");

    if ($querydelete) {
        header('location: admin.php');
    } else {
        header('location: admin.php');
    }
}
