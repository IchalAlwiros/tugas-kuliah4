<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                    <form method='post' action='".htmlspecialchars($_SERVER["PHP_SELF"])."'>
                        <input type='hidden' name='book_id' value='".$book['id']."'>
                        <button type='submit' name='update'>Update</button>
                        <button type='submit' name='delete'>Delete</button>
                    </form>
                  </td>";
            echo "</tr>";
        }
        ?>
    </table>

    <h2>Tambah Buku Baru</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label>Judul:</label><br>
        <input type="text" name="title"><br>
        <label>Penulis:</label><br>
        <input type="text" name="author"><br><br>
        <button type="submit" name="create">Tambah Buku</button>
    </form>
</body>
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
        } else {
            echo "Gagal menambahkan buku.";
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
        } else {
            echo "Gagal mengupdate buku.";
        }
    } elseif (isset($_POST['delete'])) {
        // Proses untuk menghapus buku
        $bookId = $_POST['book_id'];
        if ($bookManager->delete($bookId)) {
            echo "Buku berhasil dihapus.";
        } else {
            echo "Gagal menghapus buku.";
        }
    }
}
?>



