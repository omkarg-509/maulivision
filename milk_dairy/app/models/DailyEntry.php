<?php 
require_once '../core/Database.php';

class DailyEntry extends Database
{

     
    public function getAll($vid = null)
    {
        if ($vid !== null) {
            $stmt = $this->db->prepare("SELECT daily_entries.*, customers.name AS customer_name FROM daily_entries JOIN customers ON daily_entries.cid = customers.id WHERE daily_entries.vid = ? ORDER BY daily_entries.id DESC");
            $stmt->bind_param("i", $vid);
            $stmt->execute();
            $result = $stmt->get_result();
            $entries = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
            return $entries;
        } else {
            $result = $this->db->query("SELECT daily_entries.*, customers.name AS customer_name FROM daily_entries JOIN customers ON daily_entries.cid = customers.id ORDER BY daily_entries.id DESC");
            return $result->fetch_all(MYSQLI_ASSOC);
        }
    }

    public function insert($data)
    {
        // Ensure created_at is set and in MySQL DATETIME format (Y-m-d H:i:s).
        $created_at = null;
        if (!empty($data['created_at'])) {
            // frontend may send datetime-local like "2025-08-22T08:30" -> convert to "2025-08-22 08:30:00"
            $created_at = str_replace('T', ' ', $data['created_at']);
            if (strlen($created_at) === 16) {
                $created_at .= ':00';
            }
        } else {
            $created_at = date('Y-m-d H:i:s');
        }

        $stmt = $this->db->prepare("INSERT INTO daily_entries (vid,cid,milktype,milkliter,selected_date,created_at) VALUES (?,?,?,?,?,?)");
        $stmt->bind_param("iisds", $data['vid'], $data['cid'], $data['milktype'], $data['milkliter'],$data['entrydate'], $created_at);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }


    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM daily_entries WHERE id = ?");
        $stmt->bind_param("i", $id);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    public function getByBillId($bill_id)
    {
        $stmt = $this->db->prepare("SELECT daily_entries.*, customers.name AS customer_name FROM daily_entries JOIN customers ON daily_entries.cid = customers.id WHERE customers.bill_id = ?");
        $stmt->bind_param("s", $bill_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $entry = $result->fetch_assoc();
        $stmt->close();
        return $entry;
    }

    public function getEntriesByDateRange($customerId, $startDate, $endDate, $vendorId)
    {
        $stmt = $this->db->prepare("
            SELECT 
                DATE(created_at) as date,
                milktype,
                SUM(milkliter) as liter
            FROM daily_entries 
            WHERE cid = ? 
                AND vid = ? 
                AND DATE(created_at) BETWEEN ? AND ?
            GROUP BY DATE(created_at), milktype
            ORDER BY DATE(created_at), milktype
        ");
        $stmt->bind_param("iiss", $customerId, $vendorId, $startDate, $endDate);
        $stmt->execute();
        $result = $stmt->get_result();
        $entries = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $entries;
    }
    
    /**
     * Get aggregated entries (cow & buffalo liters) per customer per day in a date range for a vendor
     */
    public function getEntriesByDateRangeAll($vendorId, $startDate, $endDate)
    {
        $stmt = $this->db->prepare("SELECT 
                DATE(de.created_at) as date,
                c.name AS customer_name,
                SUM(CASE WHEN de.milktype='cow' THEN de.milkliter ELSE 0 END) AS cow_liter,
                SUM(CASE WHEN de.milktype='buffalo' THEN de.milkliter ELSE 0 END) AS buffalo_liter
            FROM daily_entries de
            JOIN customers c ON de.cid = c.id
            WHERE de.vid = ? AND DATE(de.created_at) BETWEEN ? AND ?
            GROUP BY DATE(de.created_at), c.name
            ORDER BY DATE(de.created_at) DESC, c.name ASC");
        $stmt->bind_param("iss", $vendorId, $startDate, $endDate);
        $stmt->execute();
        $result = $stmt->get_result();
        $entries = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $entries;
    }

    /**
     * Search customers (by name/bill/mobile) within vendor and return totals + last date
     * Optional date window filter on entries.
     */
    public function searchSummaries($vendorId, $term, $startDate = null, $endDate = null)
    {
        $like = "%{$term}%";

        // Build dynamic query for optional date filter
        $sql = "SELECT 
                    c.id,
                    c.name,
                    c.bill_id,
                    c.mobile,
                    COALESCE(SUM(CASE WHEN de.milktype='cow' THEN de.milkliter END),0) AS cow_liters,
                    COALESCE(SUM(CASE WHEN de.milktype='buffalo' THEN de.milkliter END),0) AS buffalo_liters,
                    COALESCE(SUM(de.milkliter),0) AS total_liters,
                    MAX(DATE(de.created_at)) AS last_date
                FROM customers c
                LEFT JOIN daily_entries de 
                    ON de.cid = c.id AND de.vid = ?";

        $params = [$vendorId];
        $types = 'i';

        if ($startDate && $endDate) {
            $sql .= " AND DATE(de.created_at) BETWEEN ? AND ?";
            $params[] = $startDate; $params[] = $endDate; $types .= 'ss';
        }

        $sql .= " WHERE c.vid = ? AND c.d_status = 0 AND (c.name LIKE ? OR c.bill_id LIKE ? OR c.mobile LIKE ?) ";
        $params[] = $vendorId; $types .= 'i';
        $params[] = $like; $params[] = $like; $params[] = $like; $types .= 'sss';

    // Order: non-null last_date first (DESC), nulls last, then name
    $sql .= " GROUP BY c.id, c.name, c.bill_id, c.mobile ORDER BY (last_date IS NULL) ASC, last_date DESC, c.name ASC";

        $stmt = $this->db->prepare($sql);

        // Bind params dynamically
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }
}