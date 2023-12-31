<?php

// Needs error handlings, better return schema
class DynoDB
{
    private $servername = "127.0.0.1";
    private $username = "root";
    private $password = "";
    private $dbname = "dynoDB";
    private $conn;

    public function connect()
    {
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        if ($this->conn->connect_error) {
            return false;
        }
        return true;
    }

    function runQuery(string $sql){
        $tmp = array();
        $data = array();

        $this->conn->multi_query($sql);

        do {
            if ($result = $this->conn->store_result()) {
                while ($tmp = $result->fetch_assoc())
                    if ($tmp)
                        $data[] = $tmp;
                $result->free();
            }
        } while ($this->conn->next_result());

        return $data;
    }

    function get_all_data()
    {
        if(!$this->conn)
            return false;

        $sql = 'CALL get_all_data()';
        return $this->runQuery($sql);
    }

    function get_data($id)
    {
        if(!$this->conn)
            return false;

        $sql = "CALL get_data($id)";
        return $this->runQuery($sql);
    }

    private function getVersionFields(int $version) {
        $sql = "SELECT field, type FROM fields 
                INNER JOIN version_fields
                ON fields.id=field_id 
                AND 1=version_id;";
        return $this->runQuery($sql);
    }
    
    // CAUTION possible version duplication
    function add_new_versioned_data(array $data) {
        $sql = "";
        $sqlInsertData = "INSERT INTO data(version_id, value) VALUES (@version_id, COLUMN_CREATE(";

        $sql .= "INSERT INTO versions VALUES ();
                 SET @version_id = LAST_INSERT_ID();\n";

        foreach ($data as $key => $value) {
            $type = gettype($value) == 'string' ? 'char' : (gettype($value) == 'boolean' ? 'bool' : gettype($value));

            $sql .= "INSERT INTO fields(field, type)
                         SELECT '$key', '$type'
                         WHERE NOT EXISTS (SELECT id FROM fields WHERE field='$key' AND type='$type');
                     SET @new_field_id = (SELECT id FROM fields WHERE field='$key' AND type='$type');
                     INSERT INTO version_fields(version_id, field_id)
                         VALUES (@version_id, @new_field_id);\n";

            $sqlInsertData .= "'$key', '$value' as $type,";
        }
        $sqlInsertData = rtrim($sqlInsertData, ',') . "));";
        $sql .= $sqlInsertData . " SELECT LAST_INSERT_ID() as id, @version_id as version;";

        return $this->runQuery($sql);
        
    }

    function add_data(int $version, array $data) {
        $sql = "";
        $sqlInsertData = "INSERT INTO data(version_id, value) VALUES ($version, COLUMN_CREATE(";

        foreach ($data as $key => $value) {
            $type = gettype($value) == 'string' ? 'char' : gettype($value);
            $sqlInsertData .= "'$key', '$value' as $type,";
        }

        $sqlInsertData = rtrim($sqlInsertData, ',') . "));";
        $sql .= $sqlInsertData . " SELECT LAST_INSERT_ID() as id";

        return $this->runQuery($sql);
    }

    function update_data(int $data_id, array $newData) {
        $sql = "UPDATE data SET value=COLUMN_CREATE(";
        foreach ($newData as $key => $value) {
            $type = gettype($value) == 'string' ? 'char' : gettype($value);
            $sql .= "'$key', '$value' as $type,";
        }

        $sql = rtrim($sql, ",") . ") WHERE id=$data_id;";

        return $this->runQuery($sql);
    }

    function delete_data($id)
    {
        if(!$this->conn)
            return false;

        $sql = "CALL delete_data($id)";
        return $this->runQuery($sql);
    }

    function close() {
        $this->conn->close();
    }
}

?>