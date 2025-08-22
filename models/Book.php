<?php
// models/Book.php
require_once __DIR__ . '/../config/db.php';
class Book {
    // Lấy tất cả sách, kèm tên danh mục
    public static function all() {
        global $conn;
        $sql = "SELECT books.*, categories.name AS category_name FROM books LEFT JOIN categories ON books.category_id = categories.id ORDER BY books.id DESC";
        $result = $conn->query($sql);
        $books = $result->fetch_all(MYSQLI_ASSOC);
        foreach ($books as &$book) {
            $book['is_available'] = self::isAvailable($book['id']);
        }
        return $books;
    }
    // Tìm sách theo id
    public static function find($id) {
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM books WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    // Tìm sách kèm tên danh mục
    public static function findWithCategory($id) {
        global $conn;
        $stmt = $conn->prepare("SELECT b.*, c.name as category_name FROM books b LEFT JOIN categories c ON b.category_id = c.id WHERE b.id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $book = $result->fetch_assoc();
        if ($book) {
            $book['is_available'] = self::isAvailable($book['id']);
        }
        return $book;
    }
    // Thêm sách mới
    public static function create($title, $author, $category_id, $description, $published_year, $image = null, $content = null, $quantity = 1) {
        global $conn;
        $sql = "INSERT INTO books (title, author, category_id, description, published_year, image, content, quantity) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $cat_id = !empty($category_id) ? intval($category_id) : null;
        $pub_year = !empty($published_year) ? intval($published_year) : null;
        $stmt->bind_param("ssissssi", $title, $author, $cat_id, $description, $pub_year, $image, $content, $quantity);
        return $stmt->execute();
    }
    // Sửa sách
    public static function update($id, $title, $author, $category_id, $description, $published_year, $image = null, $content = null, $quantity = 1) {
        global $conn;
        $cat_id = !empty($category_id) ? intval($category_id) : null;
        $pub_year = !empty($published_year) ? intval($published_year) : null;
        if ($image !== null) {
            $sql = "UPDATE books SET title = ?, author = ?, category_id = ?, description = ?, published_year = ?, image = ?, content = ?, quantity = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssissssii", $title, $author, $cat_id, $description, $pub_year, $image, $content, $quantity, $id);
        } else {
            $sql = "UPDATE books SET title = ?, author = ?, category_id = ?, description = ?, published_year = ?, content = ?, quantity = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssisssii", $title, $author, $cat_id, $description, $pub_year, $content, $quantity, $id);
        }
        return $stmt->execute();
    }
    // Xóa sách
    public static function delete($id) {
        global $conn;
        $stmt = $conn->prepare("DELETE FROM books WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
    // Tìm kiếm sách
    public static function search($keyword) {
        global $conn;
        $keyword = "%" . $conn->real_escape_string($keyword) . "%";
        $sql = "SELECT books.*, categories.name AS category_name FROM books LEFT JOIN categories ON books.category_id = categories.id WHERE books.title LIKE ? OR books.author LIKE ? OR books.published_year LIKE ? ORDER BY books.id DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $keyword, $keyword, $keyword);
        $stmt->execute();
        $result = $stmt->get_result();
        $books = $result->fetch_all(MYSQLI_ASSOC);
        foreach ($books as &$book) {
            $book['is_available'] = self::isAvailable($book['id']);
        }
        return $books;
    }
    // Lọc sách theo danh mục
    public static function filterByCategory($category_id) {
        global $conn;
        $cat_id = !empty($category_id) ? intval($category_id) : null;
        $stmt = $conn->prepare("SELECT books.*, categories.name AS category_name FROM books LEFT JOIN categories ON books.category_id = categories.id WHERE books.category_id = ? ORDER BY books.id DESC");
        $stmt->bind_param("i", $cat_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $books = $result->fetch_all(MYSQLI_ASSOC);
        foreach ($books as &$book) {
            $book['is_available'] = self::isAvailable($book['id']);
        }
        return $books;
    }
    // Phân trang sách
    public static function getPaged($offset, $limit, $search = '', $category_id = null, $sort = '', $author = '') {
        global $conn;
        $where = "1";
        $params = [];
        $types = '';
        if ($search !== '') {
            $where .= " AND (books.title LIKE ? OR books.author LIKE ? OR books.published_year LIKE ?)";
            $searchVal = "%$search%";
            $params[] = $searchVal; $params[] = $searchVal; $params[] = $searchVal;
            $types .= 'sss';
        }
        if ($category_id) {
            $where .= " AND books.category_id = ?";
            $params[] = intval($category_id);
            $types .= 'i';
        }
        $order = "books.id DESC";
        if ($sort === 'title_asc') {
            $order = "books.title ASC";
        } elseif ($sort === 'title_desc') {
            $order = "books.title DESC";
        } elseif ($sort === 'year_asc') {
            $order = "books.published_year ASC";
        } elseif ($sort === 'year_desc') {
            $order = "books.published_year DESC";
        }
        // Truy vấn lấy sách phân trang
        $sql = "SELECT books.*, categories.name AS category_name FROM books LEFT JOIN categories ON books.category_id = categories.id WHERE $where ORDER BY $order LIMIT ?, ?";
        $params_page = $params;
        $types_page = $types;
        $params_page[] = $offset;
        $params_page[] = $limit;
        $types_page .= 'ii';
        $stmt = $conn->prepare($sql);
        $stmt->bind_param($types_page, ...$params_page);
        $stmt->execute();
        $result = $stmt->get_result();
        $books = $result->fetch_all(MYSQLI_ASSOC);
        foreach ($books as &$book) {
            $book['is_available'] = self::isAvailable($book['id']);
        }
        // Truy vấn tổng số sách phù hợp
        $sql_count = "SELECT COUNT(*) as total FROM books LEFT JOIN categories ON books.category_id = categories.id WHERE $where";
        $stmt_count = $conn->prepare($sql_count);
        if (!empty($params)) {
            $stmt_count->bind_param($types, ...$params);
        }
        $stmt_count->execute();
        $result_count = $stmt_count->get_result();
        $total = $result_count->fetch_assoc()['total'];
        return ['books' => $books, 'total' => $total];
    }
    // Kiểm tra sách có sẵn để mượn không (dựa vào quantity)
    public static function isAvailable($book_id) {
        global $conn;
        $stmt = $conn->prepare("SELECT quantity FROM books WHERE id = ?");
        $stmt->bind_param("i", $book_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return ($row && $row['quantity'] > 0);
    }
    // Giảm số lượng sách khi mượn
    public static function decreaseQuantity($book_id, $amount = 1) {
        global $conn;
        $stmt = $conn->prepare("UPDATE books SET quantity = quantity - ? WHERE id = ? AND quantity >= ?");
        $stmt->bind_param("iii", $amount, $book_id, $amount);
        return $stmt->execute();
    }
}
