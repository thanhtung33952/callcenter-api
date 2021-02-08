<?php
namespace Controllers;

class CompanyController
{
    private $db;

    // constructor receives container instance
    public function __construct($db) {
        $this->db = $db;
    }

    public function getAllCompany($request, $response, $args) {
        try{
            $sql = "SELECT id, name  
                    FROM Company 
                    ORDER BY name ";
            
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

    //Get company by companyId
    public function getCompanyById($request, $response, $args) {
        $id = $args['id'];
        
        //get company
        $sql = "SELECT id, name 
                FROM Company WHERE id=:id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $result = $stmt->fetchObject();
        
        return $response->withJson($result);
    }

    // Add a new company
    public function addNewCompany($request, $response) {
        $input = $request->getParsedBody();
        $name = $input['name'];
    
        $this->db->beginTransaction();
        try {
            //check exist
            $sql = "SELECT name 
                    FROM Company 
                    WHERE name=:name";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam("name", $name);
            $stmt->execute();
            $result = $stmt->fetch();
            if($stmt->rowCount()>0){
                $input['id'] = -1;
                return $response->withJson($input);
            }

            //insert company
            $sql = "INSERT INTO Company (name) 
                        VALUES (:name)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam("name", $name);
    
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

    // Update company with companyId
    public function updateCompanyWithId($request, $response, $args) {
        $input = $request->getParsedBody();
        $id = $args['id'];
        $name = $input['name'];
    
        $this->db->beginTransaction();
        try {
            $sql = "UPDATE Company SET name=:name 
                    WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam("name", $name);
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

    // delete a company with companyId
    public function deleteCompanyWithId($request, $response, $args) {
        $this->db->beginTransaction();
        try {
            $stmt = $this->db->prepare("DELETE FROM Company WHERE id=:id");
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