<?php
include "koneksi.php";

$nama_menu = isset($_POST['nama_menu']) ? htmlentities($_POST['nama_menu']) : "";
$Keterangan = isset($_POST['keterangan']) ? htmlentities($_POST['keterangan']) : "";
$kategori_menu = isset($_POST['kategori']) ? htmlentities($_POST['kategori']) : "";
$harga = isset($_POST['harga']) ? htmlentities($_POST['harga']) : "";
$stok = isset($_POST['stok']) ? htmlentities($_POST['stok']) : "";


$kode_rand = rand(10000,99999)."-";
$target_dir = "../Assets/img/".$kode_rand;
$target_file = $target_dir.basename($_FILES['foto']['name']);
$imageType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

if (!empty($_POST['input_menu_validate'])) {
//cek apakah gambar atau bukan
$cek = getimagesize(($_FILES['foto']['tmp_name']));
if($cek === false){
    $message = "Ini bukan file gambar";
    $statusUpload = 0;
}else{
    $statusUpload = 1;
    if(file_exists($target_file)){
        $message ="Maaf, File yang di masukan telah Ada";
        $statusUpload = 0;
    }else{
        if($_FILES['foto']['size']>500000){//500kb
        $message ="File Foto yang diupload terlalu Besar";
        $statusUpload = 0;
        }else{
            if($imageType != "jpg" && $imageType !="png" && $imageType !="jpeg" && $imageType !="gif"){
                $message ="Maaf, hanya memiliki gambar dengan format JPG,PNG,JPEG,GIF";
                $statusUpload = 0;
            }
        }
    }
}

if($statusUpload == 0){
 $message = '<script>alert("'.$message.',Gambar tidak dapat diupload"); 
                window.location="../menu"</script>';
}else{
    $select = mysqli_query($koneksi,"SELECT * FROM tb_daftar_menu WHERE nama_menu= '$nama_menu'");
    if(mysqli_num_rows($select)>0){
        $message = '<script>alert("Nama menu yang di masukkan telah ada"); 
        window.location="../menu"</script>';
    }else{
        if(move_uploaded_file($_FILES['foto']['tmp_name'], $target_file)){
        $query = mysqli_query($koneksi,"INSERT INTO tb_daftar_menu (foto,nama_menu,keterangan,kategori,harga,stok) values ('" . $kode_rand . $_FILES['foto']['name']."','$nama_menu','$Keterangan','$kategori_menu','$harga','$stok')");
        if ($query) {
            $message = '<script>alert("Data Berhasil Dimasukkan"); 
            window.location="../menu"</script>';
        }else{
            $message = '<script>alert("Data Gagal Dimasukkan"); 
            window.location="../menu"</script>';
        }
      }else{
        $message = '<script>alert("Maaf, terjadi kesalahan file tidak dapat diupload"); 
            window.location="../menu"</script>';
      }
    }
}
 
}
    echo $message;

// $message = "";
// }
  // Check if the username already exists
    // $check_query = mysqli_query($conn, "SELECT * FROM tb_user WHERE username = '$username'");
    // if (mysqli_num_rows($check_query) > 0) {
    //     $message = '<script>alert("Username already exists. Please choose a different one.")</script>';
    // } else {
    // Insert new user if username is unique