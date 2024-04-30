<?php

class Database
{
    private $host = "127.0.0.1";
    private $username = "root";
    private $password = "password";
    private $database = "kuliah";
    private $conn;

    public function __construct()
    {
        try {
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->database", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Connected successfully\n";
        } catch(PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    public function getConnection()
    {
        return $this->conn;
    }
}

class Book
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create($bookData)
    {
        $query = "INSERT INTO books (title, author) VALUES (:title, :author)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":title", $bookData['title']);
        $stmt->bindParam(":author", $bookData['author']);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function read($bookId)
    {
        $query = "SELECT * FROM books WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":id", $bookId);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($bookId, $newBookData)
    {
        $query = "UPDATE books SET title = :title, author = :author WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":id", $bookId);
        $stmt->bindParam(":title", $newBookData['title']);
        $stmt->bindParam(":author", $newBookData['author']);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete($bookId)
    {
        $query = "DELETE FROM books WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":id", $bookId);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}

// Contoh penggunaan
$database = new Database();
$db = $database->getConnection();

$bookManager = new Book($db);

// Create
$bookData1 = ['title' => 'Laskar Pelangi', 'author' => 'Budi'];
$bookManager->create($bookData1);

$bookData2 = ['title' => 'Jejak Petualang', 'author' => 'Ichal'];
$bookManager->create($bookData2);

// Read
$bookIdToRead = 1;
$bookRead = $bookManager->read($bookIdToRead);
if ($bookRead) {
    echo "Book with ID $bookIdToRead found:\n";
    print_r($bookRead);
} else {
    echo "Book with ID $bookIdToRead not found.\n";
}

// Update
$bookIdToUpdate = 2;
$newBookData = ['title' => 'New Book 2 Title', 'author' => 'New Author 2'];
if ($bookManager->update($bookIdToUpdate, $newBookData)) {
    echo "Book with ID $bookIdToUpdate updated successfully.\n";
} else {
    echo "Book with ID $bookIdToUpdate not found.\n";
}

// Delete
$bookIdToDelete = 2;
if ($bookManager->delete($bookIdToDelete)) {
    echo "Book with ID $bookIdToDelete deleted successfully.\n";
} else {
    echo "Book with ID $bookIdToDelete not found.\n";
}

?>
