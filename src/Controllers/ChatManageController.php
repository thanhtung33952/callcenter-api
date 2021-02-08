<?php
namespace Controllers;
use DateTime;
class ChatManageController
{
    private $db;

    // constructor receives container instance
    public function __construct($db) {
        $this->db = $db;
    }

    public function getAllChatManage($request, $response, $args) {
        $missedcall = isset($_GET['missedcall']) ? $_GET['missedcall'] : 0;
        try{
            $sql = "SELECT ChatManage.id, user_id, ChatUser.family_name, 
                           CASE WHEN staff_id IS NULL THEN 1 ELSE 0 END AS missed_call, 
                           Faq1.title AS faq1_title, Faq2.title AS faq2_title, 
                           Faq3.title AS faq3_title, start 
                    FROM (SELECT * FROM ChatManage 
                            WHERE user_id IS NOT NULL ";
            if($missedcall==1){
                $sql .= "AND staff_id IS NULL ";
            }
            $sql .= ") ChatManage 
                    INNER JOIN ChatUser 
                        ON user_id = ChatUser.id
                    INNER JOIN Faq AS Faq1 
                        ON faq1_id = Faq1.faq_id 
                    INNER JOIN Faq AS Faq2 
                        ON faq2_id = Faq2.faq_id 
                    LEFT JOIN Faq AS Faq3 
                        ON faq3_id = Faq3.faq_id 
                    ORDER BY ChatManage.start desc ";
            
            $limit = isset($_GET['limit']) ? $_GET['limit'] : 0;
            $offset= isset($_GET['offset']) ? $_GET['offset'] : 0;
            if($limit>0){
                $sql .= "LIMIT $limit OFFSET $offset ";
            }
    
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll();
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

    //Get ChatManage by Id
    public function getChatManageById($request, $response, $args) {
        $id = $args['id'];
        
        //get user
        $sql = "SELECT ChatManage.id, user_id, user_name, user_email, company_name, staff_id, staff_name, 
                       faq1_id, faq2_id, faq3_id, faq1_title, faq2_title, faq3_title, start, end  
                FROM (SELECT * FROM ChatManage WHERE ChatManage.id=:id) AS ChatManage 
                LEFT JOIN (SELECT id, family_name AS user_name, email AS user_email, company_id FROM ChatUser) AS ChatUser 
                    ON user_id = ChatUser.id
                LEFT JOIN (SELECT id, name AS company_name FROM Company) AS Company 
                    ON ChatUser.company_id = Company.id
                LEFT JOIN (SELECT id, family_name AS staff_name FROM CentersStaff) AS CentersStaff 
                    ON staff_id = CentersStaff.id
                LEFT JOIN (SELECT faq_id, title AS faq1_title FROM Faq) AS Faq1 
                    ON faq1_id = Faq1.faq_id 
                LEFT JOIN (SELECT faq_id, title AS faq2_title FROM Faq) AS Faq2 
                    ON faq2_id = Faq2.faq_id 
                LEFT JOIN (SELECT faq_id, title AS faq3_title FROM Faq) AS Faq3 
                    ON faq3_id = Faq3.faq_id 
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $result = $stmt->fetchObject();
        
        return $response->withJson($result);
    }

    //Get ChatManage by userId and staff id
    public function getChatManageByUserIdStaffId($request, $response, $args) {
        $input = $request->getParsedBody();
        $user_id = $input['user_id'];
        $staff_id = $input['staff_id'];
        
        //get DATA
        $sql = "SELECT ChatManage.id, user_id, user_name, user_email, company_name, staff_id, staff_name, 
                       faq1_id, faq2_id, faq3_id, faq1_title, faq2_title, faq3_title, start, end  
                FROM (SELECT * FROM ChatManage 
                      WHERE user_id=:user_id AND staff_id=:staff_id  
                      ORDER BY id desc 
                      LIMIT 1
                ) AS ChatManage 
                LEFT JOIN (SELECT id, family_name AS user_name, email AS user_email, company_id FROM ChatUser) AS ChatUser 
                    ON user_id = ChatUser.id
                LEFT JOIN (SELECT id, name AS company_name FROM Company) AS Company 
                    ON ChatUser.company_id = Company.id
                LEFT JOIN (SELECT id, family_name AS staff_name FROM CentersStaff) AS CentersStaff 
                    ON staff_id = CentersStaff.id
                LEFT JOIN (SELECT faq_id, title AS faq1_title FROM Faq) AS Faq1 
                    ON faq1_id = Faq1.faq_id 
                LEFT JOIN (SELECT faq_id, title AS faq2_title FROM Faq) AS Faq2 
                    ON faq2_id = Faq2.faq_id 
                LEFT JOIN (SELECT faq_id, title AS faq3_title FROM Faq) AS Faq3 
                    ON faq3_id = Faq3.faq_id 
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam("user_id", $user_id);
        $stmt->bindParam("staff_id", $staff_id);
        $stmt->execute();
        $result = $stmt->fetchObject();
        
        return $response->withJson($result);
    }

    //Get List ChatManage by staff id
    public function getChatManagesByStaffId($request, $response, $args) {
        $staff_id = $args['id'];
        
        //get DATA
        $sql = "SELECT ContactManage.contact_id, ChatManage.id AS manage_id, ChatManage.user_id, user_name, 
                       user_email, company_name, staff_id, staff_name, ChatManage.faq1_id, ChatManage.faq2_id, ChatManage.faq3_id, faq1_title, faq2_title, faq3_title, start, end, IFNULL(not_seen,0) AS not_seen 
                FROM (SELECT * FROM ChatManage 
                      WHERE staff_id=:staff_id  
                      ORDER BY id desc 
                ) AS ChatManage 
                LEFT JOIN (SELECT id, family_name AS user_name, email AS user_email, company_id FROM ChatUser) AS ChatUser 
                    ON user_id = ChatUser.id
                LEFT JOIN (SELECT id, name AS company_name FROM Company) AS Company 
                    ON ChatUser.company_id = Company.id
                LEFT JOIN (SELECT id, family_name AS staff_name FROM CentersStaff) AS CentersStaff 
                    ON staff_id = CentersStaff.id
                LEFT JOIN (SELECT faq_id, title AS faq1_title FROM Faq) AS Faq1 
                    ON faq1_id = Faq1.faq_id 
                LEFT JOIN (SELECT faq_id, title AS faq2_title FROM Faq) AS Faq2 
                    ON faq2_id = Faq2.faq_id 
                LEFT JOIN (SELECT faq_id, title AS faq3_title FROM Faq) AS Faq3 
                    ON faq3_id = Faq3.faq_id 
                LEFT JOIN (SELECT chat_id, COUNT(not_seen) not_seen 
                            FROM Chat
                            WHERE writer_id = '1' AND not_seen = '1'
                            GROUP BY chat_id) Chat
                    ON ChatManage.id = Chat.chat_id 
                LEFT JOIN ContactManage 
                    ON ContactManage.user_id=ChatManage.user_id AND ContactManage.faq1_id=ChatManage.faq1_id  AND ContactManage.faq2_id=ChatManage.faq2_id AND ContactManage.faq3_id=ChatManage.faq3_id 
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam("staff_id", $staff_id);
        $stmt->execute();
        $result = $stmt->fetchAll();
        
        return $response->withJson($result);
    }

    // Add a new ChatManage
    public function addNewChatManage($request, $response) {
        $input = $request->getParsedBody();
        $user_id = isset($input['user_id']) ? $input['user_id'] : NULL;
        $staff_id = isset($input['staff_id']) ? $input['staff_id'] : NULL;
        $faq1_id = $input['faq1_id'];
        $faq2_id = $input['faq2_id'];
        $faq3_id = $input['faq3_id'];
        
        $this->db->beginTransaction();
        try {
            
            $sql = "INSERT INTO ChatManage (user_id, staff_id, faq1_id, faq2_id, faq3_id) 
                        VALUES (:user_id, :staff_id, :faq1_id, :faq2_id, :faq3_id)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam("user_id", $user_id);
            $stmt->bindParam("staff_id", $staff_id);
            $stmt->bindParam("faq1_id", $faq1_id);
            $stmt->bindParam("faq2_id", $faq2_id);
            $stmt->bindParam("faq3_id", $faq3_id);
    
            $stmt->execute();
            $input['id'] = $this->db->lastInsertId();
    
            $this->db->commit();

            return $response->withJson($input);
        }               
        // any errors from the above database queries will be catched
        catch (PDOException $e){
            // roll back transaction
            $this->db->rollback();
            //return $response->write($e);
            return $response->withStatus(500);
            // log any errors to file
            ExceptionErrorHandler($e);
            exit;
        }
    }

    // Update user with userId
    public function updateChatManageWithId($request, $response, $args) {
        $input = $request->getParsedBody();
        $id = $args['id'];
        $user_id = $input['user_id'];
        $staff_id = isset($input['staff_id']) ? $input['staff_id'] : NULL;
        $faq1_id = $input['faq1_id'];
        $faq2_id = $input['faq2_id'];
        $faq3_id = $input['faq3_id'];
    
        $this->db->beginTransaction();
        try {
            // check exist
            $sql = "SELECT id 
                    FROM ChatManage 
                    WHERE user_id=:user_id AND staff_id=:staff_id AND faq1_id=:faq1_id AND faq2_id=:faq2_id AND faq3_id=:faq3_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam("user_id", $user_id);
            $stmt->bindParam("staff_id", $staff_id);
            $stmt->bindParam("faq1_id", $faq1_id);
            $stmt->bindParam("faq2_id", $faq2_id);
            $stmt->bindParam("faq3_id", $faq3_id);
            $stmt->execute();
            if($stmt->rowCount()>0){
                $id = $stmt->fetchColumn();
                $stmt = $this->db->prepare("DELETE FROM ChatManage WHERE user_id=:user_id AND staff_id IS NULL AND 
                                                        faq1_id=:faq1_id AND faq2_id=:faq2_id AND faq3_id=:faq3_id");
                $stmt->bindParam("user_id", $user_id);
                $stmt->bindParam("faq1_id", $faq1_id);
                $stmt->bindParam("faq2_id", $faq2_id);
                $stmt->bindParam("faq3_id", $faq3_id);
                $result = $stmt->execute();
            } else{
                $sql = "UPDATE ChatManage SET user_id=:user_id, staff_id=:staff_id, faq1_id=:faq1_id, faq2_id=:faq2_id, faq3_id=:faq3_id ";
                if ($user_id != NULL && $staff_id == NULL){
                    $sql .= ", start = NOW() ";
                }
                $sql .= "WHERE id = :id";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam("user_id", $user_id);
                $stmt->bindParam("staff_id", $staff_id);
                $stmt->bindParam("faq1_id", $faq1_id);
                $stmt->bindParam("faq2_id", $faq2_id);
                $stmt->bindParam("faq3_id", $faq3_id);
                $stmt->bindParam("id", $id);
                $stmt->execute();

                // check exist
                $sql = "SELECT contact_id 
                        FROM ContactManage 
                        WHERE user_id=:user_id AND faq1_id=:faq1_id AND faq2_id=:faq2_id AND faq3_id=:faq3_id";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam("user_id", $user_id);
                $stmt->bindParam("faq1_id", $faq1_id);
                $stmt->bindParam("faq2_id", $faq2_id);
                $stmt->bindParam("faq3_id", $faq3_id);
                $stmt->execute();
                if(!($stmt->rowCount()>0)){
                    //insert ContactManage
                    $sql = "INSERT INTO ContactManage (user_id, faq1_id, faq2_id, faq3_id) 
                                VALUES (:user_id, :faq1_id, :faq2_id, :faq3_id)";
                    $stmt = $this->db->prepare($sql);
                    $stmt->bindParam("user_id", $user_id);
                    $stmt->bindParam("faq1_id", $faq1_id);
                    $stmt->bindParam("faq2_id", $faq2_id);
                    $stmt->bindParam("faq3_id", $faq3_id);
                    $stmt->execute();
                }
            }

            //get DATA
            $sql = "SELECT ContactManage.contact_id, ChatManage.id AS manage_id, ChatManage.user_id, user_name, user_email, company_name, staff_id, staff_name, ChatManage.faq1_id, ChatManage.faq2_id, ChatManage.faq3_id, faq1_title, faq2_title, faq3_title, start, end  
                    FROM (SELECT * FROM ChatManage 
                        WHERE id=:id 
                    ) AS ChatManage 
                    LEFT JOIN (SELECT id, family_name AS user_name, email AS user_email, company_id FROM ChatUser) AS ChatUser 
                        ON user_id = ChatUser.id
                    LEFT JOIN (SELECT id, name AS company_name FROM Company) AS Company 
                        ON ChatUser.company_id = Company.id
                    LEFT JOIN (SELECT id, family_name AS staff_name FROM CentersStaff) AS CentersStaff 
                        ON staff_id = CentersStaff.id
                    LEFT JOIN (SELECT faq_id, title AS faq1_title FROM Faq) AS Faq1 
                        ON faq1_id = Faq1.faq_id 
                    LEFT JOIN (SELECT faq_id, title AS faq2_title FROM Faq) AS Faq2 
                        ON faq2_id = Faq2.faq_id 
                    LEFT JOIN (SELECT faq_id, title AS faq3_title FROM Faq) AS Faq3 
                        ON faq3_id = Faq3.faq_id 
                    LEFT JOIN (SELECT * FROM ContactManage 
                               WHERE user_id=:user_id AND faq1_id=:faq1_id AND faq2_id=:faq2_id AND faq3_id=:faq3_id) AS ContactManage
                        ON ContactManage.user_id=ChatManage.user_id AND ContactManage.faq1_id=ChatManage.faq1_id AND ContactManage.faq2_id=ChatManage.faq2_id AND ContactManage.faq3_id=ChatManage.faq3_id
                    ";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam("id", $id);
            $stmt->bindParam("user_id", $user_id);
            $stmt->bindParam("faq1_id", $faq1_id);
            $stmt->bindParam("faq2_id", $faq2_id);
            $stmt->bindParam("faq3_id", $faq3_id);
            $stmt->execute();
            $result = $stmt->fetchObject();

            $this->db->commit();
            return $response->withJson($result);
        }               
        // any errors from the above database queries will be catched
        catch (PDOException $e){
            // roll back transaction
            $this->db->rollback();
            //return $response->write($e);
            return $response->withStatus(500);
            // log any errors to file
            ExceptionErrorHandler($e);
            exit;
        }
    }

    // Update user with userId
    public function updateEndChatManageWithId($request, $response, $args) {
        $input = $request->getParsedBody();
        $id = $args['id'];
    
        $this->db->beginTransaction();
        try {
            $sql = "UPDATE ChatManage SET end = NOW() 
                    WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam("id", $id);
            $stmt->execute();            
            
            $this->db->commit();
            return $response->withJson($input);
        }               
        // any errors from the above database queries will be catched
        catch (PDOException $e){
            // roll back transaction
            $this->db->rollback();
            //return $response->write($e);
            return $response->withStatus(500);
            // log any errors to file
            ExceptionErrorHandler($e);
            exit;
        }
    }

    // delete a user with Id
    public function deleteChatManageWithId($request, $response, $args) {
        $this->db->beginTransaction();
        try {
            $sql = "SELECT user_id, faq1_id, faq2_id, faq3_id 
                    FROM ChatManage 
                    WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam("id", $args['id']);
            $stmt->execute();
            if($stmt->rowCount()>0){
                $result = $stmt->fetchObject();
                $user_id = $result->user_id;
                $faq1_id = $result->faq1_id;
                $faq2_id = $result->faq2_id;
                $faq3_id = $result->faq3_id;

                $stmt = $this->db->prepare("DELETE Chat 
                                            FROM ChatManage 
                                            INNER JOIN Chat 
                                                ON  Chat.chat_id=ChatManage.id 
                                            WHERE user_id=:user_id AND faq1_id=:faq1_id AND faq2_id=:faq2_id AND faq3_id=:faq3_id");
                $stmt->bindParam("user_id", $user_id);
                $stmt->bindParam("faq1_id", $faq1_id);
                $stmt->bindParam("faq2_id", $faq2_id);
                $stmt->bindParam("faq3_id", $faq3_id);
                $result = $stmt->execute();

                $stmt = $this->db->prepare("DELETE FROM ChatManage 
                                            WHERE user_id=:user_id AND faq1_id=:faq1_id AND faq2_id=:faq2_id AND faq3_id=:faq3_id");
                $stmt->bindParam("user_id", $user_id);
                $stmt->bindParam("faq1_id", $faq1_id);
                $stmt->bindParam("faq2_id", $faq2_id);
                $stmt->bindParam("faq3_id", $faq3_id);
                $result = $stmt->execute();
                
            }
            $this->db->commit();
            return $response->withStatus(200);
        }               
        // any errors from the above database queries will be catched
        catch (PDOException $e){
            // roll back transaction
            $this->db->rollback();
            //return $response->write($e);
            return $response->withStatus(500);
            // log any errors to file
            ExceptionErrorHandler($e);
            exit;
        }
    }

    // add New Client Request
    public function addNewClientRequest($request, $response) {
        $input = $request->getParsedBody();
        $family_name = $input['family_name'];
        //$given_name = $input['given_name'];
        $given_name = " ";
        $company_name = $input['company_name'];
        $no_login = 1;
        $email = $input['email'];
        $faq1_id = $input['faq1_id'];
        $faq2_id = $input['faq2_id'];
        $faq3_id = $input['faq3_id'];
        $staff_id = isset($input['staff_id']) ? $input['staff_id'] : NULL;
        
        $this->db->beginTransaction();
        try {
            
            //get company id
            $sql = "SELECT id 
                    FROM Company WHERE name=:company_name";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam("company_name", $company_name);
            $stmt->execute();
            $result = $stmt->fetchObject();
            if($stmt->rowCount()>0){
                $company_id = $result->id;
            } else{
                //insert company
                $sql = "INSERT INTO Company (name) 
                            VALUES (:company_name)";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam("company_name", $company_name);

                $stmt->execute();
                $company_id = $this->db->lastInsertId();
            }

            $sql = "INSERT INTO ChatUser (email, family_name, given_name, company_id, no_login) 
                        VALUES (:email, :family_name, :given_name, :company_id, :no_login)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam("email", $email);
            $stmt->bindParam("family_name", $family_name);
            $stmt->bindParam("given_name", $given_name);
            $stmt->bindParam("company_id", $company_id);
            $stmt->bindParam("no_login", $no_login);

            $stmt->execute();
            $user_id = $this->db->lastInsertId();
            

            $result = new \stdClass();
            $result->user_id = $user_id;

            $sql = "INSERT INTO ChatManage (user_id, staff_id, faq1_id, faq2_id, faq3_id) 
                        VALUES (:user_id, :staff_id, :faq1_id, :faq2_id, :faq3_id)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam("user_id", $user_id);
            $stmt->bindParam("staff_id", $staff_id);
            $stmt->bindParam("faq1_id", $faq1_id);
            $stmt->bindParam("faq2_id", $faq2_id);
            $stmt->bindParam("faq3_id", $faq3_id);

            $stmt->execute();
            $result->manager_id = $this->db->lastInsertId();
            
            $this->db->commit();
            
            return $response->withJson($result);
        }               
        // any errors from the above database queries will be catched
        catch (PDOException $e){
            // roll back transaction
            $this->db->rollback();
            //return $response->write($e);
            return $response->withStatus(500);
            // log any errors to file
            ExceptionErrorHandler($e);
            exit;
        }
    }

    //Total user chat 
    public function getListUserChatWithStaff($request, $response, $args) {
        $staff_id = $args['id'];
        
        $sql = "SELECT DISTINCT user_id 
                FROM ChatManage 
                WHERE staff_id = :staff_id
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam("staff_id", $staff_id);
        $stmt->execute();
        $result = $stmt->fetchAll();
        
        return $response->withJson($result);
    }
}