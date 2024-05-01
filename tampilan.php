<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <title>CRUD Buku</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Daftar Buku</h2>
    <button type="button" class="btn btn-info" id="tambahBuku">Tambah Buku</button><br>
    <table>
        <tr>
            <th>ID</th>
            <th>Judul</th>
            <th>Penulis</th>
            <th>Tindakan</th>
        </tr>
        <?php
        // Include file yang berisi class Book dan Database
        include_once 'tugas4.php';

        // Buat objek Database
        $database = new Database;
        $db = $database->getConnection();

        // Buat objek Book
        $bookManager = new Book($db);

        // Ambil semua data buku dari database
        $books = $bookManager->getAll();

        // Tampilkan data buku dalam tabel
        foreach ($books as $book) {
            echo "<tr>";
            echo "<td>".$book['id']."</td>";
            echo "<td>".$book['title']."</td>";
            echo "<td>".$book['author']."</td>";
            echo "<td>
                    <button name='update' class='updateBuku btn btn-primary' data-id='".$book['id']."' data-title='".$book['title']."' data-author='".$book['author']."'>Update</button>
                    <form method='post' action='".htmlspecialchars($_SERVER["PHP_SELF"])."'>
                        <input type='hidden' name='book_id' value='".$book['id']."'>
                        <button type='submit' name='delete' class='btn btn-danger'>Delete</button>
                    </form>
                  </td>";
            echo "</tr>";
        }
        ?>
    </table>
</body>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <div class="modal" id="modalTambahBuku" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title"><b>Tambah Buku Baru</b></h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Judul</label>
                                <input type="text" name="title" class="form-control" />
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Penulis</label>
                                <input type="text" name="author" class="form-control" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="create" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>
</form>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <div class="modal" id="modalUbahBuku" tabindex="-1">
        <input type="hidden" name="book_id" id="book_id" />
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title"><b>Ubah Buku</b></h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Judul</label>
                                <input type="text" name="title" id="title" class="form-control" />
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Penulis</label>
                                <input type="text" name="author" id="author" class="form-control" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="update" class="btn btn-primary">Edit</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function(){
        $("#tambahBuku").click(function(){
            $("#modalTambahBuku").modal();
        });

        $(".updateBuku").click(function () {
            var id = $(this).attr('data-id');
            var title = $(this).attr('data-title');
            var author = $(this).attr('data-author');
            $("#book_id").val(id);
            $("#title").val(title);
            $("#author").val(author);
            $('#modalUbahBuku').modal('show');
        });
    });
</script>

</html>


<?php
// Include file yang berisi class Book dan Database
include_once 'tugas4.php';

// Buat objek Database
$database = new Database();
$db = $database->getConnection();

// Buat objek Book
$bookManager = new Book($db);

// Proses form jika tombol submit ditekan
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['create'])) {
        // Proses untuk membuat buku baru
        $bookData = [
            'title' => $_POST['title'],
            'author' => $_POST['author']
        ];
        if ($bookManager->create($bookData)) {
            echo "Buku berhasil ditambahkan.";
            echo "<script>window.location = 'tampilan.php';</script>";
        } else {
            echo "Gagal menambahkan buku.";
            echo "<script>window.location = 'tampilan.php';</script>";
        }
    } elseif (isset($_POST['update'])) {
        // Proses untuk mengupdate buku
        $bookId = $_POST['book_id'];
        $newBookData = [
            'title' => $_POST['title'],
            'author' => $_POST['author']
        ];
        if ($bookManager->update($bookId, $newBookData)) {
            echo "Buku berhasil diupdate.";
            echo "<script>window.location = 'tampilan.php';</script>";
        } else {
            echo "Gagal mengupdate buku.";
            echo "<script>window.location = 'tampilan.php';</script>";
        }
    } elseif (isset($_POST['delete'])) {
        // Proses untuk menghapus buku
        $bookId = $_POST['book_id'];
        if ($bookManager->delete($bookId)) {
            echo "Buku berhasil dihapus.";
            echo "<script>window.location = 'tampilan.php';</script>";
        } else {
            echo "Gagal menghapus buku.";
            echo "<script>window.location = 'tampilan.php';</script>";
        }
    }
}
?>



