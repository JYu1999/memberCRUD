<?php

class Member
{

    public $id;
    public $points;

    private $conn;
    private $table = 'members';

    // Constructor with DB

    public function __construct($db)
    {
        $this->conn = $db;
    }


    public function read_single() {
        // Create query
        $query = 'SELECT m.id as member_id, m.first_name, m.last_name, p.id as point_id, p.name, p.point, mp.created_at
                FROM members m
                JOIN member_point mp ON m.id = mp.member_id
                JOIN points p ON mp.point_id = p.id
                WHERE m.id = ?
                ORDER BY mp.created_at';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Bind ID
        $stmt->bindParam(1, $this->id);

        $stmt->execute();


        // Set properties
        $this->points = array();

        // Fetch the first row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Continue fetching rows until no more rows are left
        while ($row) {
            array_push($this->points, $row);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
        }
    }

    public function create() {
        // Create query
        $query = 'INSERT INTO members (account, password, first_name, last_name, gender, birthday, city)
              VALUES (:account, :password, :first_name, :last_name, :gender, :birthday, :city)';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->account = htmlspecialchars(strip_tags($this->account));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->first_name = htmlspecialchars(strip_tags($this->first_name));
        $this->last_name = htmlspecialchars(strip_tags($this->last_name));
        $this->gender = htmlspecialchars(strip_tags($this->gender));
        $this->birthday = htmlspecialchars(strip_tags($this->birthday));
        $this->city = htmlspecialchars(strip_tags($this->city));

        // Bind data
        $stmt->bindParam(':account', $this->account);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':first_name', $this->first_name);
        $stmt->bindParam(':last_name', $this->last_name);
        $stmt->bindParam(':gender', $this->gender);
        $stmt->bindParam(':birthday', $this->birthday);
        $stmt->bindParam(':city', $this->city);

        // Execute query
        if($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            // Insert member points record
            $query = 'INSERT INTO member_point (member_id, point_id, created_at, updated_at)
                  VALUES (:member_id, :point_id, NOW(), NOW())';

            $stmt = $this->conn->prepare($query);

            // Bind member points data
            $stmt->bindParam(':member_id', $this->id);
            $stmt->bindParam(':point_id', $this->point_id);
            // Execute the statement to insert the member points record
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }

        }else{
            return false;
        }


    }

    public function delete() {
        // Delete member points records
        $query = 'DELETE FROM member_point WHERE member_id = :member_id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':member_id', $this->id);

        if ($stmt->execute()) {
            // Delete member
            $query = 'DELETE FROM members WHERE id = :id';
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $this->id);

            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function update() {
        $update_fields = "";
        $params = [];

        if (!empty($this->account)) {
            $update_fields .= "account = ?, ";
            array_push($params, $this->account);
        }
        if (!empty($this->password)) {
            $update_fields .= "password = ?, ";
            array_push($params, $this->password);
        }
        if (!empty($this->first_name)) {
            $update_fields .= "first_name = ?, ";
            array_push($params, $this->first_name);
        }
        if (!empty($this->last_name)) {
            $update_fields .= "last_name = ?, ";
            array_push($params, $this->last_name);
        }
        if (isset($this->gender)) {
            $update_fields .= "gender = ?, ";
            array_push($params, $this->gender);
        }
        if (!empty($this->birthday)) {
            $update_fields .= "birthday = ?, ";
            array_push($params, $this->birthday);
        }
        if (!empty($this->city)) {
            $update_fields .= "city = ?, ";
            array_push($params, $this->city);
        }

        // Remove the trailing comma and space
        $update_fields = rtrim($update_fields, ', ');

        if (!empty($update_fields)) {
            $query = "UPDATE " . $this->table . " SET " . $update_fields . " WHERE id = ?";
            array_push($params, $this->id);

            $stmt = $this->conn->prepare($query);

            for ($i = 1; $i <= count($params); $i++) {
                $stmt->bindParam($i, $params[$i - 1]);
            }

            if ($stmt->execute()) {
                return true;
            }
        }

        return false;
    }



}