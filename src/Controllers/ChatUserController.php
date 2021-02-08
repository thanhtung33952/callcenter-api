<?php
namespace Controllers;
use DateTime;
class ChatUserController
{
    private $db;

    // constructor receives container instance
    public function __construct($db) {
        $this->db = $db;
    }

    // login Chat User
    public function loginChatUser($request, $response) {  
        
        $input = $request->getParsedBody();
        $email = $input['email'];
        $password = $input['password'];
        
        $this->db->beginTransaction();
        try {
            //get info exist
            $sql = "SELECT ChatUser.id, email, family_name, Company.name AS company_name, created 
                    FROM ChatUser 
                    LEFT JOIN Company 
                      ON ChatUser.company_id = Company.id 
                    WHERE email=:email AND password=:password AND no_login = 0";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam("email", $email);
            $stmt->bindParam("password", MD5($password));
            $stmt->execute();
            $result = $stmt->fetchObject();
            if($stmt->rowCount()>0){
                
                return $response->withJson($result);
            }
            $input['id'] = -1;// ko tồn tại

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

    public function getAllChatUser($request, $response, $args) {
        try{
            $sql = "SELECT id, email, family_name, given_name, company_id, created 
                    FROM ChatUser 
                    ORDER BY id ";
            
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

    //Get ChatUser by userId
    public function getChatUserById($request, $response, $args) {
        $id = $args['id'];
        
        //get user
        $sql = "SELECT ChatUser.id, email, family_name, given_name, company_id, Company.name AS company_name, created 
                FROM ChatUser 
                INNER JOIN Company 
                    ON ChatUser.company_id = Company.id
                WHERE ChatUser.id=:id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $result = $stmt->fetchObject();
        
        return $response->withJson($result);
    }

    // Add a new user
    public function addNewChatUser($request, $response) {  
        
        $input = $request->getParsedBody();
        $email = isset($input['email']) ? $input['email'] : '';
        $family_name = $input['family_name'];
        //$given_name = $input['given_name'];
        $given_name = " ";
        $company_name = isset($input['company_name']) ? $input['company_name'] : '';
        $password = isset($input['password']) ? $input['password'] : '';
        
        $this->db->beginTransaction();
        try {
            //case pass = null
            if ($password==''){
                $no_login = 1;

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

                $sql = "INSERT INTO ChatUser (email, family_name, given_name, company_id, no_login, password) 
                        VALUES (:email, :family_name, :given_name, :company_id, :no_login, null)";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam("email", $email);
                $stmt->bindParam("family_name", $family_name);
                $stmt->bindParam("given_name", $given_name);
                $stmt->bindParam("company_id", $company_id);
                $stmt->bindParam("no_login", $no_login);

                $stmt->execute();
                $input['id'] = $this->db->lastInsertId();
                $this->db->commit();
                return $response->withJson($input);
            }

            //get info exist
            $sql = "SELECT id 
                    FROM ChatUser 
                    WHERE email=:email AND password=:password AND no_login = 0";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam("email", $email);
            $stmt->bindParam("password", MD5($password));
            $stmt->execute();
            $result = $stmt->fetchObject();
            if($stmt->rowCount()>0){
                $input['id'] = -1; // đã tồn tại
                return $response->withJson($input);
            }

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

            $sql = "INSERT INTO ChatUser (email, family_name, given_name, company_id, password) 
                        VALUES (:email, :family_name, :given_name, :company_id, :password)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam("email", $email);
            $stmt->bindParam("family_name", $family_name);
            $stmt->bindParam("given_name", $given_name);
            $stmt->bindParam("company_id", $company_id);
            $stmt->bindParam("password", MD5($password));
    
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

    // Update user with Id
    public function updateChatUserWithId($request, $response, $args) {
        $input = $request->getParsedBody();
        $id = $input['id'];
        $email = $input['email'];
        $family_name = $input['family_name'];
        $given_name = ' ';
        $company_id = $input['company_id'];
        $password = $input['password'];
    
        $this->db->beginTransaction();
        try {
            $sql = "UPDATE ChatUser SET email=:email, family_name=:family_name, given_name=:given_name, company_id=:company_id, password=:password 
                    WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam("email", $email);
            $stmt->bindParam("family_name", $family_name);
            $stmt->bindParam("given_name", $given_name);
            $stmt->bindParam("company_id", $company_id);
            $stmt->bindParam("password", MD5($password));
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

    // delete a Chatuser with Id
    public function deleteChatUserWithId($request, $response, $args) {
        $this->db->beginTransaction();
        try {
            $stmt = $this->db->prepare("DELETE FROM ChatUser WHERE id=:id");
            $stmt->bindParam("id", $args['id']);
            $result = $stmt->execute();
            
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
}