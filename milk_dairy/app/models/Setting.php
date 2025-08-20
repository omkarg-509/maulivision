<?php
require_once '../core/Database.php';

class Setting extends Database
{
    public function getOrCreate($user_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM setting WHERE user_id = ? LIMIT 1");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $setting = $result->fetch_assoc();
        if ($setting) {
            return $setting;
        } else {
            $this->create($user_id, 0);
            return [ 'user_id' => $user_id, 'dark_mode' => 0 ];
        }
    }
    public function create($user_id, $dark_mode)
    {
        $stmt = $this->db->prepare("INSERT INTO setting (user_id, dark_mode) VALUES (?, ?)");
        $stmt->bind_param("ii", $user_id, $dark_mode);
        return $stmt->execute();
    }
    public function updateDarkMode($user_id, $dark_mode)
    {
        $stmt = $this->db->prepare("UPDATE setting SET dark_mode = ? WHERE user_id = ?");
        $stmt->bind_param("ii", $dark_mode, $user_id);
        return $stmt->execute();
    }
}
