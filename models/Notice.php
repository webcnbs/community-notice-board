<?php
// models/Notice.php
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/AuditLog.php';

class Notice {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->pdo();
    }

    //  Create a new notice
    public function create(array $data) {
        $stmt = $this->pdo->prepare("
            INSERT INTO notices (title, content, category_id, priority, user_id, expiry_date)
            VALUES (:title, :content, :category_id, :priority, :user_id, :expiry_date)
        ");
        $stmt->execute($data);
        return $this->pdo->lastInsertId();
    }

    //  Update an existing notice
    public function update(int $id, array $data) {
        $stmt = $this->pdo->prepare("
            UPDATE notices 
            SET title=:title, content=:content, category_id=:category_id,
                priority=:priority, expiry_date=:expiry_date 
            WHERE notice_id=:id
        ");
        $data['id'] = $id;
        $stmt->execute($data);
    }

    //  Delete a notice and its related data
    public function delete(int $id) {
        // Delete related bookmarks
        $this->pdo->prepare("DELETE FROM bookmarks WHERE notice_id = ?")->execute([$id]);

        // Delete related comments
        $this->pdo->prepare("DELETE FROM comments WHERE notice_id = ?")->execute([$id]);

        // Delete the notice itself
        $this->pdo->prepare("DELETE FROM notices WHERE notice_id = ?")->execute([$id]);

        // Record audit log
        $log = new AuditLog();
        $userId = $_SESSION['user']['user_id'] ?? null;
        $log->record($userId, 'delete_notice', "Deleted notice ID $id");
    }

    //  Find a single notice by ID
    public function find(int $id) {
        $stmt = $this->pdo->prepare("
            SELECT n.*, c.name AS category_name 
            FROM notices n
            JOIN categories c ON n.category_id = c.category_id 
            WHERE notice_id=?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // List notices with filters, pagination, and search
    public function list(array $filters, int $limit = 10, int $offset = 0) {
        $where = []; 
        $params = [];

        if (!empty($filters['category_id'])) {
            $where[] = 'n.category_id = :category_id';
            $params[':category_id'] = (int)$filters['category_id'];
        }

        if (!empty($filters['priority'])) {
            $where[] = 'n.priority = :priority';
            $params[':priority'] = $filters['priority'];
        }

        if (!empty($filters['q']) && strlen($filters['q']) >= 1) {
            $where[] = 'n.title LIKE :q1'; 
            $params[':q1'] = '%' . $filters['q'] . '%'; // Added % at start too for better search
        }
        if (!empty($filters['active_only'])) {
            $where[] = '(n.expiry_date IS NULL OR n.expiry_date >= CURDATE())';
        }

        
        $sql = "SELECT n.notice_id, n.title, n.priority, n.expiry_date, n.created_at, c.name AS category
        FROM notices n 
        LEFT JOIN categories c ON n.category_id = c.category_id"; // Changed JOIN to LEFT JOIN

        if ($where) {
            $sql .= " WHERE " . implode(' AND ', $where);
        }

        $sql .= " ORDER BY n.created_at DESC LIMIT :limit OFFSET :offset";

        $stmt = $this->pdo->prepare($sql);

        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val, is_int($val) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }

        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //  Count notices matching filters
    public function count(array $filters) {
    $where = []; 
    $params = [];

    if (!empty($filters['category_id'])) {
        $where[] = 'category_id = :category_id';
        $params[':category_id'] = (int)$filters['category_id'];
    }

    if (!empty($filters['priority'])) {
        $where[] = 'priority = :priority';
        $params[':priority'] = $filters['priority'];
    }

    if (!empty($filters['q']) && strlen($filters['q']) >= 1) {
        $where[] = 'title LIKE :q1'; 
        $params[':q1'] = '%' . $filters['q'] . '%';
    }

    if (!empty($filters['active_only'])) {
        $where[] = '(expiry_date IS NULL OR expiry_date >= CURDATE())';
    }

    $sql = "SELECT COUNT(*) FROM notices"; 
    if ($where) {
        $sql .= " WHERE " . implode(' AND ', $where);
    }

    $stmt = $this->pdo->prepare($sql);
    foreach ($params as $key => $val) {
        $stmt->bindValue($key, $val, is_int($val) ? PDO::PARAM_INT : PDO::PARAM_STR);
    }
    $stmt->execute();
    return (int)$stmt->fetchColumn();
}

    // Increment view count
    public function incrementViews(int $id) {
        $this->pdo->prepare("UPDATE notices SET views = views + 1 WHERE notice_id=?")->execute([$id]);
    }

    //  Fetch all notices (for admin dashboard or manage-notices.php)
    public function all() {
        $stmt = $this->pdo->query("
            SELECT n.*, c.name AS category_name, u.username AS created_by 
            FROM notices n
            LEFT JOIN categories c ON n.category_id = c.category_id
            LEFT JOIN users u ON n.user_id = u.user_id
            ORDER BY n.created_at DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}