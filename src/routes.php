<?php

use Slim\Http\Request;
use Slim\Http\Response;

//*************** Begin API ChatTheme *********************
//Get all chattheme
$app->get('/chatthemes', 'ChatThemeController:getAllChatTheme');

//Get chattheme by Id
$app->get('/chattheme/[{id}]', 'ChatThemeController:getChatThemeById' );

// Add a new chattheme
$app->post('/chattheme', 'ChatThemeController:addNewChatTheme' );

// Update chattheme with Id
$app->put('/chattheme/[{id}]', 'ChatThemeController:updateChatThemeWithId' );

// delete a chattheme with Id
$app->delete('/chattheme/[{id}]', 'ChatThemeController:deleteChatThemeWithId' );
//*************** End API ChatTheme *********************

 
//*************** Begin API ChatUser *********************
//Get all chatuser
$app->get('/chatusers', 'ChatUserController:getAllChatUser');

//Get chatuser by Id
$app->get('/chatuser/[{id}]', 'ChatUserController:getChatUserById' );

// Add a new chatuser
$app->post('/chatuser', 'ChatUserController:addNewChatUser' );

// Update chatuser with Id
$app->put('/chatuser/[{id}]', 'ChatUserController:updateChatUserWithId' );

// delete a chatuser with Id
$app->delete('/chatuser/[{id}]', 'ChatUserController:deleteChatUserWithId' );

// API login chat user
$app->post('/loginchatuser', 'ChatUserController:loginChatUser');
//*************** End API ChatUser *********************


//*************** Begin API Company *********************
//Get all company
$app->get('/companies', 'CompanyController:getAllCompany');

//Get company by Id
$app->get('/company/[{id}]', 'CompanyController:getCompanyById' );

// Add a new company
$app->post('/company', 'CompanyController:addNewCompany' );

// Update company with Id
$app->put('/company/[{id}]', 'CompanyController:updateCompanyWithId' );

// delete a company with Id
$app->delete('/company/[{id}]', 'CompanyController:deleteCompanyWithId' );
//*************** End API Company *********************


//*************** Begin API CentersStaff *********************
//Get all centersstaff
$app->get('/centersstaffs', 'CentersStaffController:getAllCentersStaff');

//Get centersstaff by Id
$app->get('/centersstaff/[{id}]', 'CentersStaffController:getCentersStaffById' );

// Add a new centersstaff
$app->post('/centersstaff', 'CentersStaffController:addNewCentersStaff' );

// Update centersstaff with Id
$app->put('/centersstaff/[{id}]', 'CentersStaffController:updateCentersStaffWithId' );

// delete a centersstaff with Id
$app->delete('/centersstaff/[{id}]', 'CentersStaffController:deleteCentersStaffWithId' );

// API login
$app->post('/login', 'CentersStaffController:login');
//*************** End API CentersStaff *********************


//*************** Begin API Chat *********************
// //Get all chat
// $app->get('/chats', 'ChatController:getAllChat');

//Get chat by Id
$app->get('/chat/[{id}]', 'ChatController:getChatByChatId' );

//Get chat by faq and user
$app->get('/chatsbyfaq', 'ChatController:getChatByFaqIdAndUserId' );

// Add a new chat
$app->post('/chat', 'ChatController:addNewChat' );

// Update chat with Id
$app->put('/chat/[{id}]', 'ChatController:updateChatWithId' );

// delete a chat with Id
$app->delete('/chat/[{id}]', 'ChatController:deleteChatWithId' );

// Update seen chat with Id
$app->put('/updateseenchat/[{id}]', 'ChatController:updateSeenChatWithChatId' );
//*************** End API Chat *********************


//*************** Begin API ChatManage *********************
//Get all chatmanage
$app->get('/chatmanages', 'ChatManageController:getAllChatManage');

//Get chatmanage by Id
$app->get('/chatmanage/[{id}]', 'ChatManageController:getChatManageById' );

//Get list user chat with staff id
$app->get('/listuserschatwithstaff/[{id}]', 'ChatManageController:getListUserChatWithStaff' );

//Get chatmanages by staff id
$app->get('/chatmanagesbystaffid/[{id}]', 'ChatManageController:getChatManagesByStaffId' );

// Add a new chatmanage
$app->post('/chatmanage', 'ChatManageController:addNewChatManage' );

// GET chatmanage BY user id and staff id
$app->post('/getchatmanage', 'ChatManageController:getChatManageByUserIdStaffId' );

// Update chatmanage with Id
$app->put('/chatmanage/[{id}]', 'ChatManageController:updateChatManageWithId' );

// Update end chatmanage with Id
$app->put('/endchatmanage/[{id}]', 'ChatManageController:updateEndChatManageWithId' );

// delete a chatmanage with Id
$app->delete('/chatmanage/[{id}]', 'ChatManageController:deleteChatManageWithId' );
//*************** End API ChatManage *********************


//*************** Begin API      *********************
// Get history chat by user
$app->post('/chathistorybyuser', 'ChatHistoryController:getHistoryChatByUser' );

// Get history chat by company
$app->post('/chathistorybycompany', 'ChatHistoryController:getHistoryChatByCompany' );

// Get list user chat by faq and staff
$app->post('/listchatuserbyfaqandstaff', 'ChatHistoryController:getListChatUserByFaqAndStaff' );

// Get history chat latest
$app->post('/chathistorylatest', 'ChatHistoryController:getHistoryChatLatest' );

// Get history chat by faq
$app->post('/chathistorybyfaq', 'ChatHistoryController:getHistoryChatByFaq' );

// Get history chat by company
$app->post('/chathistorybykeysearch/[{name}]', 'ChatHistoryController:getHistoryChatByKeySearch' );
//*************** End API ChatHistory *********************

// Add a new client request
$app->post('/clientrequest', 'ChatManageController:addNewClientRequest' );


//*************** Begin API FixedPhraseManage *********************
//Get FixedPhrase by Id
$app->get('/fixedphrases/[{id}]', 'FixedPhraseManageController:getFixedPhraseByStaffId' );

// Add a new fixedphrase
$app->post('/fixedphrase', 'FixedPhraseManageController:addNewFixedPhrase' );

// Update fixedphrase with Id
$app->put('/fixedphrase/[{id}]', 'FixedPhraseManageController:updateFixedPhraseWithId' );

// delete a fixedphrase with Id
$app->delete('/fixedphrase/[{id}]', 'FixedPhraseManageController:deleteFixedPhraseWithId' );
//*************** End API FixedPhraseManage *********************

//*************** Begin API Analytic *********************
//Get total chat manage by faq
$app->get('/totalchatmanagebyfaq', 'AnalyticController:getTotalChatManageByFaq' );

//Get total chat manage by faq
$app->get('/totalchatmanagebydate', 'AnalyticController:getTotalChatManageByDate' );
//*************** End API Analytic *********************

//*************** Begin API Faq *********************
//Get FaqParent
$app->get('/faqparents', 'FaqController:getFaqParent' );

//Get FixedPhrase by Id
$app->get('/faqsbyparent/[{id}]', 'FaqController:getAllFaqByParent' );

//Get faq by Id
$app->get('/faq/[{id}]', 'FaqController:getFaq' );
//*************** End API Faq *********************