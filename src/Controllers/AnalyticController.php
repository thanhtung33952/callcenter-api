<?php
namespace Controllers;
use DateTime;
class AnalyticController
{
    private $db;

    // constructor receives container instance
    public function __construct($db) {
        $this->db = $db;
    }

    public function getTotalChatManageByFaq($request, $response, $args) {
        $date = new DateTime();
        $dateNow = $date->format('Y-m-d');
        $from_date = isset($_GET['from_date']) ? $_GET['from_date'] : 0; //FORMAT YYYY-MM-DD
        $to_date = isset($_GET['to_date']) ? $_GET['to_date'] : $dateNow; //FORMAT YYYY-MM-DD
        if (empty($_GET['to_date']) || $_GET['to_date'] == 'null') {
            $to_date = $dateNow;
        }
        try{
            $sql = "SELECT Faq.parent_id AS faq2_id, Faq.faq_id AS faq1_id, Faq.title AS faq_title, IFNULL(ChatManage.total, 0) AS total   
                    FROM (SELECT * FROM Faq WHERE parent_id = 0 AND is_deleted = 0) AS Faq 
                    LEFT JOIN (SELECT faq1_id, count(id) AS total 
                                FROM ChatManage 
                                WHERE DATE(start) BETWEEN :from_date  AND :to_date
                                GROUP BY faq1_id 
                                HAVING count(id)>0) AS ChatManage 
                        ON ChatManage.faq1_id = Faq.faq_id
                    ORDER BY position ";
    
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam("from_date", $from_date);
            $stmt->bindParam("to_date", $to_date);
            $stmt->execute();
            $result = $stmt->fetchAll();

            foreach($result as $key => $value){
                $parent_id = $value["faq1_id"];

                $sql = "SELECT Faq.parent_id AS faq1_id, Faq.faq_id AS faq2_id, Faq.title AS faq_title, IFNULL(ChatManage.total, 0) AS total   
                FROM (SELECT * FROM Faq WHERE parent_id=:parent_id AND is_deleted = 0) AS Faq 
                LEFT JOIN (SELECT faq1_id, faq2_id, count(id) AS total 
                      FROM ChatManage 
                      WHERE faq1_id=:parent_id AND DATE(start) BETWEEN :from_date  AND :to_date
                      GROUP BY faq1_id, faq2_id 
                      HAVING count(id)>0) AS ChatManage 
                    ON ChatManage.faq2_id = Faq.faq_id AND ChatManage.faq1_id = Faq.parent_id
                ORDER BY position ";

                $stmt = $this->db->prepare($sql);
                $stmt->bindParam("from_date", $from_date);
                $stmt->bindParam("to_date", $to_date);
                $stmt->bindParam("parent_id", $parent_id);
                $stmt->execute();
                $data = $stmt->fetchAll();
                $result[$key]["sub"] = $data;

                foreach($data as $key1 => $value1){
                    $parent_id = $value1["faq2_id"];

                    $sql = "SELECT Faq.parent_id AS faq1_id, Faq.faq_id AS faq2_id, Faq.title AS faq_title, IFNULL(ChatManage.total, 0) AS total   
                    FROM (SELECT * FROM Faq WHERE parent_id=:parent_id AND is_deleted = 0) AS Faq 
                    LEFT JOIN (SELECT faq2_id, faq3_id, count(id) AS total 
                        FROM ChatManage 
                        WHERE faq2_id=:parent_id AND DATE(start) BETWEEN :from_date  AND :to_date
                        GROUP BY faq2_id, faq3_id 
                        HAVING count(id)>0) AS ChatManage 
                        ON ChatManage.faq3_id = Faq.faq_id AND ChatManage.faq2_id = Faq.parent_id
                    ORDER BY position ";

                    $stmt = $this->db->prepare($sql);
                    $stmt->bindParam("from_date", $from_date);
                    $stmt->bindParam("to_date", $to_date);
                    $stmt->bindParam("parent_id", $parent_id);
                    $stmt->execute();
                    $data1 = $stmt->fetchAll();
                    $result[$key]["sub"][$key1]["sub"] = $data1;
                }
            }

            $sql = "SELECT 0 AS faq1_id, 0 AS faq2_id, 'すべて' AS faq_title, ChatManage.total   
                    FROM (SELECT count(id) AS total 
                          FROM ChatManage 
                          WHERE DATE(start) BETWEEN :from_date AND :to_date) AS ChatManage 
                    ";
    
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam("from_date", $from_date);
            $stmt->bindParam("to_date", $to_date);
            $stmt->execute();
            $data = $stmt->fetchObject();
            array_push($result, $data);
            return $response->withJson($result);
        }
        // any errors from the above database queries will be catched
        catch (PDOException $e){
            // roll back transaction
            //return $response->write($e);
            return $response->withStatus(500);
            // log any errors to file
            ExceptionErrorHandler($e);
            exit;
        }
    }

    //Get ChatManage by userId and staff id
    public function getTotalChatManageByDate($request, $response, $args) {
        $input = $request->getParsedBody();
        $date = new DateTime();
        $dateNow = $date->format('Y-m-d');
        $from_date = isset($_GET['from_date']) ? $_GET['from_date'] : 0; //FORMAT YYYY-MM-DD
        $to_date = isset($_GET['to_date']) ? $_GET['to_date'] : $dateNow; //FORMAT YYYY-MM-DD
        if (empty($_GET['to_date']) || $_GET['to_date'] == 'null') {
            $to_date = $dateNow;
        }
        $faq1_id = isset($_GET['faq1_id']) ? $_GET['faq1_id'] : 0;
        $faq2_id = isset($_GET['faq2_id']) ? $_GET['faq2_id'] : 0;
        
        if($dateNow == $from_date || $from_date==$to_date){
            //get DATA
            $sql = "SELECT HOUR(start) AS time, count(id) AS total 
                    FROM (SELECT * 
                          FROM ChatManage
                          WHERE (faq1_id=:faq1_id AND faq2_id=:faq2_id ) OR 
                                (faq2_id=:faq1_id AND faq3_id=:faq2_id ) OR 
                                (faq1_id=:faq1_id AND '0'=:faq2_id ) OR 
                                ('0'=:faq1_id AND '0'=:faq2_id)) AS ChatManage 
                    WHERE DATE(start) BETWEEN  :from_date  AND :to_date
                    GROUP BY HOUR(start) 
                    ORDER BY HOUR(start)
                    ";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam("from_date", $from_date);
            $stmt->bindParam("to_date", $to_date);
            $stmt->bindParam("faq1_id", $faq1_id);
            $stmt->bindParam("faq2_id", $faq2_id);
            $stmt->execute();
            $result = $stmt->fetchAll();

            return $response->withJson($result);
        }
        //get DATA
        $sql = "SELECT DATE(start) AS date, count(id) AS total 
                FROM (SELECT * 
                          FROM ChatManage
                          WHERE (faq1_id=:faq1_id AND faq2_id=:faq2_id) OR 
                                (faq2_id=:faq1_id AND faq3_id=:faq2_id) OR 
                                ('0'=:faq2_id AND faq1_id=:faq1_id) OR 
                                ('0'=:faq1_id AND '0'=:faq2_id)) AS ChatManage 
                WHERE DATE(start) BETWEEN :from_date AND :to_date
                GROUP BY DATE(start) 
                ORDER BY DATE(start)
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam("from_date", $from_date);
        $stmt->bindParam("to_date", $to_date);
        $stmt->bindParam("faq1_id", $faq1_id);
        $stmt->bindParam("faq2_id", $faq2_id);
        $stmt->execute();
        $result = $stmt->fetchAll();
        
        return $response->withJson($result);
    }
}