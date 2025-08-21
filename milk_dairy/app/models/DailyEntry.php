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
        $stmt = $this->db->prepare("INSERT INTO daily_entries (vid,cid,milktype,milkliter) VALUES (?,?,?,?)");
        $stmt->bind_param("iisd", $data['vid'], $data['cid'], $data['milktype'], $data['milkliter']);
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
}