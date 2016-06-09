<?php

class Connection {

    protected static $db;

    private function __construct() {
        global $wgBracketContestDbType,
            $wgBracketContestDbServer,
            $wgBracketContestDbName,
            $wgBracketContestDbUser,
            $wgBracketContestDbPassword;
        
        try {
            self::$db = new PDO( $wgBracketContestDbType . ':host=' . $wgBracketContestDbServer. ';dbname=' . $wgBracketContestDbName,
                $wgBracketContestDbUser, $wgBracketContestDbPassword);
            self::$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        }
        catch (PDOException $e) {
            // echo "Connection Error: " . $e->getMessage();
        }

    }

    public static function getConnection() {
        if (!self::$db) {
            new Connection();
        }
        return self::$db;
    }
}
?>