<?php
namespace Controllers;
use DateTime;
class FixedPhraseManageController
{
    private $db;

    // constructor receives container instance
    public function __construct($db) {
        $this->db = $db;
    }

    //Get Chat by ChatId
    public function getFixedPhraseByStaffId($request, $response, $args) {
        $staff_id = $args['id'];
        $sql = "SELECT id, staff_id, title, content, created 
                FROM FixedPhraseManage 
                WHERE staff_id = :staff_id 
                ORDER BY id ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam("staff_id", $staff_id);
        $stmt->execute();
        $result = $stmt->fetchAll();
        
        return $response->withJson($result);
    }

    // Add a new FixedPhrase
    public function addNewFixedPhrase($request, $response) {
        $input = $request->getParsedBody();
        $staff_id = $input['staff_id'];
        $title = $input['title'];
        $content = $input['content'];
        
        $this->db->beginTransaction();
        try {
            $sql = "INSERT INTO FixedPhraseManage (staff_id, title, content) 
                        VALUES (:staff_id, :title, :content)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam("staff_id", $staff_id);
            $stmt->bindParam("title", $title);
            $stmt->bindParam("content", $content);
    
            $stmt->execute();
            $input['id'] = $this->db->lastInsertId();
            
            $sql = "SELECT created 
                    FROM FixedPhraseManage 
                    WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam("id", $input['id']);
            $stmt->execute();
            $input['created'] = $stmt->fetchColumn();
            
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

    // Update chat with FixedPhrase Id
    public function updateFixedPhraseWithId($request, $response, $args) {
        $input = $request->getParsedBody();
        $id = $args['id'];
        $staff_id = $input['staff_id'];
        $title = $input['title'];
        $content = $input['content'];
    
        $this->db->beginTransaction();
        try {
            $sql = "UPDATE FixedPhraseManage SET title=:title, content=:content, created=NOW() 
                    WHERE id=:id AND staff_id=:staff_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam("title", $title);
            $stmt->bindParam("content", $content);
            $stmt->bindParam("staff_id", $staff_id);
            $stmt->bindParam("id", $id);
            $stmt->execute();
            
            $sql = "SELECT created 
                    FROM FixedPhraseManage 
                    WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam("id", $id);
            $stmt->execute();
            $input['created'] = $stmt->fetchColumn();

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

    // delete a FixedPhrase with FixedPhraseId
    public function deleteFixedPhraseWithId($request, $response, $args) {
        $this->db->beginTransaction();
        try {
            $stmt = $this->db->prepare("DELETE FROM FixedPhraseManage WHERE id=:id");
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