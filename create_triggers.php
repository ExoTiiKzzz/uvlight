<?php
require './lib/includes/defines.inc.php';
$conn->query("
                        CREATE TABLE IF NOT EXISTS `logs` (
                            `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
                            `query` text NOT NULL,
                            `datetime` datetime NOT NULL DEFAULT current_timestamp(),
                            `ip` varchar(255) NOT NULL
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
$sql = $conn->query('SHOW TABLES');
$string =
    '
        FOR EACH ROW
        BEGIN
            DECLARE original_query TEXT;
            DECLARE ip_address VARCHAR(255);
            SET original_query = (SELECT info FROM INFORMATION_SCHEMA.PROCESSLIST WHERE id = CONNECTION_ID());
            SET ip_address = (select SUBSTRING_INDEX(host,":",1) as "ip" from information_schema.processlist WHERE ID=connection_id());
            INSERT INTO `logs`(`query`, `ip`) VALUES (original_query, ip_address);
        END;
    ';
foreach($sql->fetchAll(PDO::FETCH_ASSOC) as $table) {
    $tablename = reset($table);
    if($tablename != "logs"){
        $conn->query("CREATE OR REPLACE TRIGGER insert_log_$tablename BEFORE INSERT ON $tablename $string");
        $conn->query("CREATE OR REPLACE TRIGGER update_log_$tablename BEFORE UPDATE ON $tablename $string");
        $conn->query("CREATE OR REPLACE TRIGGER delete_log_$tablename BEFORE DELETE ON $tablename $string");
    }
}