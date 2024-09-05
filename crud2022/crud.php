<?php
// Koneksi Database
$server = "localhost";
$user = "root";
$password = "";
$database = "dbcrud2022";

//buat koneksi
$koneksi = mysqli_connect($server, $user, $password, $database) or die(mysqli_error($koneksi));

//buat koneksi
$q = mysqli_query($koneksi, "SELECT kode FROM tbarangg order by kode desc limit 1");
$datax = mysqli_fetch_array($q);
if($datax){
  $no_terakhir = substr($datax['kode'], -3);
  $no = $no_terakhir + 1;

  if ($no > 0 and $no < 10) {
    $kode = "00".$no;
  }else if($no > 10 and $no < 100){
    $kode = "0".$no;
  }else if($no > 100){
    $kode = $no;
  }
}else{
  $kode = "001";
}

$tahun = date('Y');
$vkode = "INV-" . $tahun . $kode;
//INV-2022-001

//jika tombol simpan di klik
if (isset($_POST['bsimpan'])) {

  //pengujian apakah data akan di edit / disimpan baru
  if (isset($_GET['hal']) == "edit") {
    //data akan di edit
    $edit = mysqli_query($koneksi, "UPDATE tbarangg SET
                                           nama = '$_POST[tnama]',
                                           asal = '$_POST[tasal]',
                                           jumlah = '$_POST[tjumlah]',
                                           satuan = '$_POST[tsatuan]',
                                           tanggal_diterima = '$_POST[tanggal_diterima]'
                                    WHERE id_barang = '$_GET[id]'
                                   ");

    //uji jika edit data sukses
    if ($edit) {
      echo "<script>
            alert('Data Berhasil Diedit');
            document.location= 'index.php';
          </script>";
    } else {
      echo "<script>
            alert('Data Gagal Diedit');
            document.location= 'index.php';
          </script>";
    }
  } else {
    //data akan disimpan baru
    $simpan = mysqli_query($koneksi, " INSERT INTO tbarangg (kode, nama, asal, jumlah, satuan, tanggal_diterima)
                                        VALUE ( '$_POST[tkode]',
                                                '$_POST[tnama]',
                                                '$_POST[tasal]',
                                                '$_POST[tjumlah]',
                                                '$_POST[tsatuan]',
                                                '$_POST[tanggal_diterima]')
                                              ");
    //uji jika simpan data sukses
    if ($simpan) {
      echo "<script>
            alert('Data Berhasil Disimpan!');
            document.location= 'index.php';
          </script>";
    } else {
      echo "<script>
            alert('Data Gagal Disimpan!');
            document.location= 'index.php';
          </script>";
    }
  }
}


//deklarasi variabel untuk menampung data yang akan di edit

$vnama = "";
$vasal = "";
$vjumlah = "";
$vsatuan = "";
$vtanggal_diterima = "";


//pengujian jika tombol edit / hapus di klik
if (isset($_GET['hal'])) {

  //pengujian jika edit data
  if ($_GET['hal'] == 'edit') {
    //TAMPILKAN DATA YANG AKAN DI EDIT
    $tampil = mysqli_query($koneksi, "SELECT * FROM tbarangg WHERE id_barang='$_GET[id]'");
    $data = mysqli_fetch_array($tampil);
    if ($data) {
      //jika data ditemukan maka data ditampung ke variabel
      $vkode = $data['kode'];
      $vnama = $data['nama'];
      $vasal = $data['asal'];
      $vjumlah = $data['jumlah'];
      $vsatuan = $data['satuan'];
      $vtanggal_diterima = $data['tanggal_diterima'];
    }
  } else if ($_GET['hal'] == "hapus") {
    //HAPUS DATA
    $hapus = mysqli_query($koneksi, "DELETE FROM tbarangg WHERE id_barang='$_GET[id]'");
    //uji jika hapus data sukses
    if ($hapus) {
      echo "<script>
        alert('Data Berhasil Dihapus');
        document.location= 'index.php';
        </script>";
    } else {
      echo "<script>
        alert('Data Gagal Dihapus');
        document.location= 'index.php';
        </script>";
    }
  }
}

?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Crud PHP + MySQL + Bootstrap 5</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
  <!-- Awal Container -->
  <div class="container">
    <h3 class="text-center">Data Inventaris</h3>
    <h3 class="text-center">Kelas XII RPL 1</h3>

    <!-- Awal Row -->
    <div class="row">
      <!-- Awal col -->
      <div class="col-md-8 mx-auto">
        <!-- Awal Card -->
        <div class="card">
          <div class="card-header bg-info text-light">
            Form Input Data Barang
          </div>
          <div class="card-body">
            <!-- Awal Form -->
            <form method="POST">
              <div class="mb-3">
                <label class="form-label">Kode Barang</label>
                <input type="text" name="tkode" value="<?= $vkode ?>" class="form-control" placeholder="Masukkan kode barang">
              </div>

              <div class="mb-3">
                <label class="form-label">Nama Barang</label>
                <input type="text" name="tnama" value="<?= $vnama ?>" class="form-control" placeholder="Masukkan nama barang">
              </div>

              <div class="mb-3">
                <label class="form-label">Asal Barang</label>
                <select class="form-select" name="tasal">
                  <option value="<?= $vasal ?>"><?= $vasal ?></option>
                  <option value="Pembelian">Pembelian</option>
                  <option value="Curian">Curian</option>
                  <option value="Sumbangan">Sumbangan</option>
                  <option value="Bantuan">Bantuan</option>
                </select>
              </div>

              <div class="row">
                <div class="col">
                  <div class="mb-3">
                    <label class="form-label">Jumlah</label>
                    <input type="number" name="tjumlah" value="<?= $vjumlah ?>" class="form-control" placeholder="Masukkan jumlah barang">
                  </div>
                </div>

                <div class="col">
                  <div class="mb-3">
                    <label class="form-label">Satuan</label>
                    <select class="form-select" name="tsatuan">
                      <option value="<?= $vsatuan ?>"><?= $vsatuan ?></option>
                      <option value="Unit">Unit</option>
                      <option value="Pcs">Pcs</option>
                      <option value="Kotak">Kotak</option>
                      <option value="Box">Box</option>
                    </select>
                  </div>
                </div>

                <div class="col">
                  <div class="mb-3">
                    <label class="form-label">Tanggal diterima</label>
                    <input type="date" name="tanggal_diterima" value="<?= $vtanggal_diterima ?>" class="form-control" placeholder="Masukkan jumlah barang">
                  </div>
                </div>

                <div class="text-center">
                  <hr>
                  <button class="btn btn-primary" name="bsimpan" type="submit">Simpan</button>
                  <button class="btn btn-danger" name="bkosongkan" type="riset">Kosongkan</button>
                  <a href="index.php" class="btn btn-warning">Logout</a>
                </div>
              </div>



            </form>
            <!-- Akhir Form -->
          </div>
          <div class="card-footer bg-info">

          </div>
        </div>
        <!-- Akhir Card -->
      </div>
      <!-- Akhir Row -->
    </div>
    <!-- Akhir Row -->

    <!-- Awal Card -->
    <div class="card mt-3">
      <div class="card-header bg-info text-light">
        Data Barang
      </div>
      <div class="card-body">
        <div class="col-md-6 mx-auto">
          <form method="POST">
            <div class="input-group mb-3">
              <input type="text" name="tcari" value="<?= @$_POST['tcari']; ?>" class="form-control" placeholder="Masukkan kata kunci!">
              <button class="btn btn-primary" name="bcari" type="cari">Cari</button>
              <button class="btn btn-danger" name="breset" type="submit">Reset</button>
            </div>
          </form>
        </div>
        <table class="table table-striped table-hover table-bordered">
          <tr>
            <th>No.</th>
            <th>Kode Barang</th>
            <th>Nama Barang</th>
            <th>Asal Barang</th>
            <th>Jumlah</th>
            <th>Sembarang</th>
            <th>Tanggal Diterima</th>
            <th>Aksi</th>
          </tr>

          <?php
          //persiapan menampilkan data
          $no = 1;

          //untuk pencarian data
          //jika tombol cari di klik
          if(isset($_POST['bcari'])) {
            //tampilkan data yang dicari
            $keyword = $_POST['tcari'];
            $q = "SELECT * FROM tbarangg WHERE kode like '%$keyword%' or nama like '%$keyword%' order by id_barang desc ";
          } else {
            $q = "SELECT * FROM tbarangg order by id_barang desc";
          }

          $tampil = mysqli_query($koneksi, $q);
          while ($data = mysqli_fetch_array($tampil)) :
          ?>
            <tr>
              <td><?= $no++ ?></td>
              <td><?= $data['kode'] ?></td>
              <td><?= $data['nama'] ?></td>
              <td><?= $data['asal'] ?></td>
              <td><?= $data['jumlah'] ?> <?= $data['satuan'] ?></td>
              <td>Sembarang</td>
              <td><?= $data['tanggal_diterima'] ?></td>
              <td>
                <a href="index.php?hal=edit&id=<?= $data['id_barang'] ?>" class="btn btn-warning">Edit</a>

                <a href="index.php?hal=hapus&id=<?= $data['id_barang'] ?>" class="btn btn-danger" onclick="return confirm('Apakah anda yakin akan hapus data ini?')">Hapus</a>
              </td>
            </tr>
          <?php endwhile; ?>
        </table>
      </div>
      <div class="card-footer bg-info">

      </div>
    </div>
    <!-- Akhir Card -->

  </div>
  <!-- Akhir Container -->



  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>