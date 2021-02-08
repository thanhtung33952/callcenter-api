<?php
namespace Controllers;
use DateTime;
class CentersStaffController
{
    private $db;

    // constructor receives container instance
    public function __construct($db) {
        $this->db = $db;
    }

    public function getAllCentersStaff($request, $response, $args) {
        try{
            $sql = "SELECT id, email, family_name, given_name 
                    FROM CentersStaff 
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

    //Get CentersStaff by userId
    public function getCentersStaffById($request, $response, $args) {
        $id = $args['id'];
        
        //get user
        $sql = "SELECT id, email, family_name, given_name 
                FROM CentersStaff WHERE id=:id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $result = $stmt->fetchObject();
        
        return $response->withJson($result);
    }

    // Add a new user
    public function addNewCentersStaff($request, $response) {  
        
        $input = $request->getParsedBody();
        $email = $input['email'];
        $family_name = $input['family_name'];
        $given_name = $input['given_name'];
        
        $this->db->beginTransaction();
        try {
            $sql = "INSERT INTO CentersStaff (email, family_name, given_name) 
                        VALUES (:email, :family_name, :given_name)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam("email", $email);
            $stmt->bindParam("family_name", $family_name);
            $stmt->bindParam("given_name", $given_name);
    
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
    public function updateCentersStaffWithId($request, $response, $args) {
        $input = $request->getParsedBody();
        $id = $input['id'];
        $email = $input['email'];
        $family_name = $input['family_name'];
        $given_name = $input['given_name'];
    
        $this->db->beginTransaction();
        try {
            $sql = "UPDATE CentersStaff SET email=:email, family_name=:family_name, given_name=:given_name 
                    WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam("email", $email);
            $stmt->bindParam("family_name", $family_name);
            $stmt->bindParam("given_name", $given_name);
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

    // delete a CentersStaff with Id
    public function deleteCentersStaffWithId($request, $response, $args) {
        $this->db->beginTransaction();
        try {
            $stmt = $this->db->prepare("DELETE FROM CentersStaff WHERE id=:id");
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

    //login
    public function login($request, $response) {
        $input = $request->getParsedBody();
        $email = $input["email"];
        $password = $input["password"];
        //check email   
        $sql = "SELECT id, email, family_name, given_name FROM CentersStaff WHERE email =:email AND password=:password";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam("email", $email);
            $stmt->bindParam("password", MD5($password));
            $stmt->execute();
            $result = $stmt->fetchObject();
            
            return $response->withJson($result);
        } catch(PDOException $e) {
            return $response->withStatus(500);
        }
    }
}