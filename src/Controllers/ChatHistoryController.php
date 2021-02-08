<?php
namespace Controllers;
use DateTime;
class ChatHistoryController
{
    private $db;

    // constructor receives container instance
    public function __construct($db) {
        $this->db = $db;
    }

    //Get Chat by ChatId
    public function getHistoryChatByUser($request, $response, $args) {
        $input = $request->getParsedBody();
        $email = $input['email'];
        $family_name = $input['family_name'];
        //$given_name = $input['given_name'];
        $company_name = $input['company_name'];

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
            // trả về []
            return $response->withJson(array());
        }

        $sql = "SELECT ChatManage.id, user_id, email, family_name, company_id, staff_id, faq1_id, faq2_id, faq3_id, start, end  
                FROM (SELECT id, email, family_name, company_id FROM ChatUser 
                WHERE email=:email AND family_name=:family_name AND company_id=:company_id
                      ) AS ChatUser 
                INNER JOIN (SELECT id, user_id, staff_id, faq1_id, faq2_id, faq3_id, start, end 
                            FROM ChatManage
                            WHERE staff_id IS NOT NULL) AS ChatManage 
                  ON ChatUser.id = ChatManage.user_id  
                ORDER BY ChatManage.start DESC 
                LIMIT 3";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam("email", $email);
        $stmt->bindParam("family_name", $family_name);
        $stmt->bindParam("company_id", $company_id);
        $stmt->execute();
        $result = $stmt->fetchAll();
        
        return $response->withJson($result);
    }


    //Get Chat by ChatId
    public function getHistoryChatByCompany($request, $response, $args) {
        $input = $request->getParsedBody();
        $company_name = $input['company_name'];

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
            // trả về []
            return $response->withJson(array());
        }

        $sql = "SELECT ChatManage.id, user_id, email, family_name, company_id, staff_id, faq1_id, faq2_id, faq3_id, start, end  
                FROM (SELECT id, email, family_name, company_id FROM ChatUser 
                      WHERE company_id=:company_id
                      ) AS ChatUser 
                INNER JOIN (SELECT id, user_id, staff_id, faq1_id, faq2_id, faq3_id, start, end 
                            FROM ChatManage 
                            WHERE staff_id IS NOT NULL
                            ) AS ChatManage 
                  ON ChatUser.id = ChatManage.user_id 
                ORDER BY ChatManage.start DESC 
                LIMIT 12";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam("company_id", $company_id);
        $stmt->execute();
        $result = $stmt->fetchAll();
        
        return $response->withJson($result);
    }


    //get History Chat By Key Search
    public function getHistoryChatByKeySearch($request, $response, $args) {
        $keySearch= isset($args['name'])? $args['name'] : "";
        //{ companyData: [], userData: [], categoryData: [], contentData: [] }
        
        //search name company
        //get company id
        $sql = "SELECT id, name 
                FROM Company WHERE name=:company_name";
        $stmt = $this->db->prepare($sql);
        //$query = "%".$keySearch."%";
        $stmt->bindParam("company_name", $keySearch);
        $stmt->execute();

        $results = $stmt->fetchObject();
        if($stmt->rowCount()>0){
            $company_id = $results->id;
            $company_name = $results->name;

            $sql = "SELECT ChatManage.id AS id_chat, '$company_name' AS company_name, 
                           family_name AS user_name, faq1_title, faq2_title, faq3_title, start AS start_date, end AS end_date 
                    FROM (SELECT id, email, family_name, company_id 
                          FROM ChatUser 
                          WHERE company_id=:company_id 
                        ) AS ChatUser 
                    INNER JOIN (SELECT id, user_id, staff_id, faq1_id, faq2_id, faq3_id, start, end 
                                FROM ChatManage 
                                WHERE staff_id IS NOT NULL
                                ) AS ChatManage 
                    ON ChatUser.id = ChatManage.user_id 
                    INNER JOIN (SELECT faq_id, title AS faq1_title FROM Faq) AS Faq1 
                        ON ChatManage.faq1_id = Faq1.faq_id 
                    INNER JOIN (SELECT faq_id, title AS faq2_title FROM Faq) AS Faq2 
                        ON ChatManage.faq2_id = Faq2.faq_id 
                    LEFT JOIN (SELECT faq_id, title AS faq3_title FROM Faq) AS Faq3 
                        ON ChatManage.faq3_id = Faq3.faq_id 
                    ORDER BY ChatManage.start DESC 
                    LIMIT 12";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam("company_id", $company_id);
            $stmt->execute();
            $result->companyData = $stmt->fetchAll();
        } else{
            // trả về []
            $result->companyData = array();
        }

        //search name user
        $sql = "SELECT ChatManage.id AS id_chat, 
                       company_name, family_name AS user_name, faq1_title, faq2_title, faq3_title, start AS start_date, end AS end_date 
                FROM (SELECT id, email, family_name, company_id 
                      FROM ChatUser 
                      WHERE family_name=:family_name 
                      ) AS ChatUser 
                INNER JOIN (SELECT id, user_id, staff_id, faq1_id, faq2_id, faq3_id, start, end 
                            FROM ChatManage 
                            WHERE staff_id IS NOT NULL
                            ) AS ChatManage 
                  ON ChatUser.id = ChatManage.user_id 
                INNER JOIN (SELECT id, name AS company_name FROM Company) AS Company 
                  ON ChatUser.company_id = Company.id 
                INNER JOIN (SELECT faq_id, title AS faq1_title FROM Faq) AS Faq1 
                    ON ChatManage.faq1_id = Faq1.faq_id 
                INNER JOIN (SELECT faq_id, title AS faq2_title FROM Faq) AS Faq2 
                    ON ChatManage.faq2_id = Faq2.faq_id 
                LEFT JOIN (SELECT faq_id, title AS faq3_title FROM Faq) AS Faq3 
                    ON ChatManage.faq3_id = Faq3.faq_id 
                ORDER BY ChatManage.start DESC 
                LIMIT 3";
        $stmt = $this->db->prepare($sql);
        //$query = "%".$keySearch."%";
        $stmt->bindParam("family_name", $keySearch);
        $stmt->execute();
        $result->userData = $stmt->fetchAll();


        //search name faq name 
        //get faq id
        $sql = "SELECT faq_id, title 
                FROM Faq WHERE title=:faq_title";
        $stmt = $this->db->prepare($sql);
        //$query = "%".$keySearch."%";
        $stmt->bindParam("faq_title", $keySearch);
        $stmt->execute();

        $results = $stmt->fetchObject();
        if($stmt->rowCount()>0){
            $faq_id = $results->faq_id;
            $faq_title = $results->title;

        $sql = "SELECT ChatManage.id AS id_chat, 
                       company_name, family_name AS user_name, '$faq_title' AS faq2_title, start AS start_date, end AS end_date 
                FROM (SELECT id, user_id, staff_id, faq1_id, faq2_id, faq3_id, start, end 
                      FROM ChatManage 
                      WHERE staff_id IS NOT NULL AND (faq1_id=:faq_id OR faq2_id=:faq_id OR faq3_id=:faq_id) 
                      ORDER BY ChatManage.start DESC 
                      LIMIT 124 
                    ) AS ChatManage 
                INNER JOIN (SELECT id, email, family_name, company_id 
                          FROM ChatUser 
                            ) AS ChatUser 
                  ON ChatManage.user_id = ChatUser.id 
                INNER JOIN (SELECT id, name AS company_name FROM Company) AS Company 
                  ON ChatUser.company_id = Company.id 
                
                ORDER BY ChatManage.start DESC 
                LIMIT 124";
        $stmt = $this->db->prepare($sql);
        //$query = "%".$keySearch."%";
        $stmt->bindParam("faq_id", $faq_id);
        $stmt->execute();
        $result->categoryData = $stmt->fetchAll();
        } else{
            // trả về []
            $result->categoryData = array();
        }

        //search name content
        $sql = "SELECT ChatManage.id AS id_chat, 
                        company_name, family_name AS user_name, faq1_title, faq2_title, faq3_title, start AS start_date, end AS end_date  
                FROM (
                    SELECT chat_id 
                    FROM Chat 
                    WHERE text LIKE :text 
                    GROUP BY chat_id 
                ) AS Chat
                INNER JOIN (SELECT id, user_id, staff_id, faq1_id, faq2_id, faq3_id, start, end 
                            FROM ChatManage 
                            WHERE staff_id IS NOT NULL
                            ) AS ChatManage 
                    ON Chat.chat_id = ChatManage.id 
                INNER JOIN (SELECT id, email, family_name, company_id 
                          FROM ChatUser 
                            ) AS ChatUser 
                  ON ChatManage.user_id = ChatUser.id 
                INNER JOIN (SELECT id, name AS company_name FROM Company) AS Company 
                  ON ChatUser.company_id = Company.id 
                INNER JOIN (SELECT faq_id, title AS faq1_title FROM Faq) AS Faq1 
                        ON ChatManage.faq1_id = Faq1.faq_id 
                INNER JOIN (SELECT faq_id, title AS faq2_title FROM Faq) AS Faq2 
                        ON ChatManage.faq2_id = Faq2.faq_id 
                LEFT JOIN (SELECT faq_id, title AS faq3_title FROM Faq) AS Faq3 
                        ON ChatManage.faq3_id = Faq3.faq_id 
                ORDER BY ChatManage.start DESC ";
        $stmt = $this->db->prepare($sql);
        $query = "%".$keySearch."%";
        $stmt->bindParam("text", $query);
        $stmt->execute();
        $result->contentData = $stmt->fetchAll();

        return $response->withJson($result);

    }

    //Get ChatUser by faq and staff
    public function getListChatUserByFaqAndStaff($request, $response, $args) {
        $input = $request->getParsedBody();
        $staff_id = $input['staff_id'];
        $faq1_id = $input['faq1_id'];
        $faq2_id = $input['faq2_id'];
        $faq3_id = $input['faq3_id'];
        
        //get user
        $sql = "SELECT ChatUser.id, email, family_name, company_id, Company.name AS company_name 
                FROM (SELECT user_id 
                      FROM ChatManage  
                      WHERE staff_id=:staff_id AND faq1_id=:faq1_id AND faq2_id=:faq2_id AND faq3_id=:faq3_id 
                      GROUP BY user_id, staff_id, faq1_id, faq2_id, faq3_id) AS ChatManage 
                INNER JOIN ChatUser 
                    ON ChatUser.id = ChatManage.user_id 
                INNER JOIN Company 
                    ON ChatUser.company_id = Company.id 
                ORDER BY family_name";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam("staff_id", $staff_id);
        $stmt->bindParam("faq1_id", $faq1_id);
        $stmt->bindParam("faq2_id", $faq2_id);
        $stmt->bindParam("faq3_id", $faq3_id);
        $stmt->execute();
        $result = $stmt->fetchAll();
        
        return $response->withJson($result);
    }

    //Get Chat latest
    public function getHistoryChatLatest($request, $response, $args) {
        $input = $request->getParsedBody();
        $user_id = $input['user_id'];
        $staff_id = $input['staff_id'];
        $faq1_id = $input['faq1_id'];
        $faq2_id = $input['faq2_id'];
        $faq3_id = $input['faq3_id'];

        $sql = "SELECT ChatManage.id, user_id, staff_id, faq1_id, faq2_id, faq3_id, start, end  
                FROM ChatManage 
                WHERE user_id=:user_id AND staff_id=:staff_id AND faq1_id=:faq1_id AND faq2_id=:faq2_id AND faq3_id=:faq3_id 
                ORDER BY end DESC 
                LIMIT 1 
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam("user_id", $user_id);
        $stmt->bindParam("staff_id", $staff_id);
        $stmt->bindParam("faq1_id", $faq1_id);
        $stmt->bindParam("faq2_id", $faq2_id);
        $stmt->bindParam("faq3_id", $faq3_id);
        $stmt->execute();
        $result = $stmt->fetchObject();
        
        return $response->withJson($result);
    }

    //Get Chat by faq
    public function getHistoryChatByFaq($request, $response, $args) {
        $input = $request->getParsedBody();
        $staff_id = $input['staff_id'];
        $faq1_id = $input['faq1_id'];
        $faq2_id = $input['faq2_id'];
        $faq3_id = $input['faq3_id'];

        $sql = "SELECT ChatManage.id, user_id, email, family_name, company_id, staff_id, faq1_id, faq2_id, faq3_id, start, end  
                FROM (  SELECT ChatManage.id, ChatManage.user_id, ChatManage.staff_id, ChatManage.faq1_id, ChatManage.faq2_id, ChatManage.faq3_id, ChatManage.start, ChatManage.end 
                        FROM ChatManage 
                        INNER JOIN (SELECT user_id, staff_id, faq1_id, faq2_id, faq3_id, MAX(end) AS maxEnd 
                        FROM ChatManage 
                        WHERE staff_id=:staff_id AND faq1_id=:faq1_id AND faq2_id=:faq2_id AND faq3_id=:faq3_id
                        GROUP BY user_id, staff_id, faq1_id, faq2_id, faq3_id) groupChatManage
                            ON ChatManage.user_id = groupChatManage.user_id AND
                                ChatManage.staff_id = groupChatManage.staff_id AND
                                ChatManage.faq1_id = groupChatManage.faq1_id AND
                                ChatManage.faq2_id = groupChatManage.faq2_id AND 
                                ChatManage.faq3_id = groupChatManage.faq3_id AND
                                ChatManage.end = groupChatManage.maxEnd 
                    ) AS ChatManage 

                INNER JOIN ChatUser 
                  ON ChatUser.id = ChatManage.user_id 
                ORDER BY ChatManage.start DESC 
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam("staff_id", $staff_id);
        $stmt->bindParam("faq1_id", $faq1_id);
        $stmt->bindParam("faq2_id", $faq2_id);
        $stmt->bindParam("faq3_id", $faq3_id);
        $stmt->execute();
        $result = $stmt->fetchAll();
        
        return $response->withJson($result);
    }
}