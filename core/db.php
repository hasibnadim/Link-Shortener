<?php

namespace DB {
    class DBCLS
    {
        private $conn = null;
        public function __construct()
        {
            $config = include('core/config.php');
            $srv = explode(':', $config['dbservername']);

            $this->conn = new \mysqli(
                $srv[0],
                $config["dbusername"],
                $config["dbpassword"],
                $config['dbname'],
                (int)$srv[1]
            );
            if ($this->conn->connect_error) {
                die("Connection failed: " . $this->conn->connect_error);
            }
        }
        function __destruct()
        {
            $this->conn->close();
        }
        public function query(string $q, array $params = [])
        {
            $tmp=$this->conn->prepare($q);
            $tmp->execute($params);
            return $tmp;
        }
    }
};
