var express = require('express');
// var https = require('https');
var https = require('http');
var fs = require('fs');
var options = {
    cert: fs.readFileSync('/home/ubuntu/SSL/db79fdc20564ee23.crt'),
    key: fs.readFileSync('/home/ubuntu/SSL/private.key')
};
var app = express();
var _findIndex = require('lodash/findIndex') // npm install lodash --save
//var server = https.Server(options, app);
var server = https.createServer(app);
var port = (process.env.OPENSHIFT_NODEJS_PORT || process.env.PORT || 6969);
var io = require('socket.io')(server);
server.listen(port, () => console.log('Server running in port ' + port));

var staffOnline = []; //danh sách staff dang online
var requests = []; //danh sách các yêu cầu đang đơi
var chatting = []; //danh sách các cuộc trò chuyện đang diễn ra

io.on('connection', function(socket) {
    console.log(socket.id + ': connected');
    
    //lắng nghe khi người dùng thoát
    socket.on('disconnect', function() {
        console.log(socket.id + ': disconnected')
        $index = _findIndex(staffOnline, ['id', socket.id]);
        if($index>=0){
            staffOnline.splice($index, 1);
            io.sockets.emit('staffListOnline', staffOnline);
        }
        
        $index = _findIndex(requests, ['id', socket.id]);
        if($index>=0){
            requests.splice($index, 1);
            io.sockets.emit('requesttotal', requests);
        }

        $index = _findIndex(chatting, ['userSocketId', socket.id]);
        if($index>=0){
            var item = chatting[$index];
            chatting.splice($index, 1);
            io.sockets.emit('chattingtotal', chatting);
            // gửi thông báo cho staff
            if(item.staffSocketId !== null && typeof item.staffSocketId !== "undefined"){
            	try {
                    io.sockets.connected[item.staffSocketId].emit('endChat', socket.id);
                }
                catch (e) {
                    console.log("staff no online");
                }
            }
        }

        // $index = _findIndex(chatting, ['staffSocketId', socket.id]);
        // if($index>=0){
        //     console.log('staff disconnected')
        //     chatting.splice($index, 1);
        //     io.sockets.emit('chattingtotal', chatting);
        // }


    })
    
    // //lắng nghe khi có người gửi tin nhắn
    // socket.on('newMessage', data => {
    //     //gửi lại tin nhắn cho tất cả các user dang online
    //     io.sockets.emit('newMessage', {
    //         data: data.data,
    //         user: data.user
    //     });
    // })

    //lắng nghe khi có người gửi tin nhắn
    //data: receiverInfo->id
    //                  ->name
    //      senderInfo  ->id
    //                  ->name
    //      writer_id   (1: user, 0: staff) //writer_id là mã xác nhận ai là người nhắn
    //      text

    socket.on('newMessage', (data, fn) => {
        // gửi cho các staff
        staffOnline.find(function(itemStaff, i){
            try {
                    io.sockets.connected[itemStaff.id].emit('newMessage', data);
            }
            catch (e) {
                    console.log("staff no online");
            }
        })
        chatting.find(function(item, i){
            // trường hợp user gửi cho user
            if(item.staffId === data.receiverInfo.id && item.userId === data.senderInfo.id && data.writer_id==1){
                try {
                    io.sockets.connected[item.userSocketId].emit('newMessage', data);
                }
                catch (e) {
                    console.log("user no online");
                }
            }
            // trường hợp staff gửi cho user
            if(item.userId === data.receiverInfo.id && item.staffId === data.senderInfo.id && data.writer_id==0){
                try {
                    io.sockets.connected[item.userSocketId].emit('newMessage', data);
                }
                catch (e) {
                    console.log("user no online");
                }
            }
        });
    	
    	//Sau ki xử lý xong gọi callback function fn
        if(fn !== null && typeof fn !== "undefined"){
    	    fn();
        }
    })
    
    socket.on('endChat', (data, fn) => {//data = id
        //trường hợp staff nhấn end chat
        $index = _findIndex(chatting, ['staffSocketId', socket.id]);
        if($index>=0){
            //gửi thông báo tới user
            io.sockets.connected[chatting[$index].userSocketId].emit('endChat', data);
            //disconnect client
            io.sockets.connected[chatting[$index].userSocketId].disconnect();
            console.log("client disconnect");
            chatting.splice($index, 1);
        }

        //trường hợp user nhấn end chat
        $index = _findIndex(chatting, ['userSocketId', socket.id]);
        if($index>=0){
            //gửi thông báo tới user
            //io.sockets.connected[chatting[$index].staffSocketId].emit('endChat', data);
            //disconnect client
            io.sockets.connected[chatting[$index].userSocketId].disconnect();
            console.log("client disconnect");
            chatting.splice($index, 1);
        }

        //Sau ki xử lý xong gọi callback function fn
        if(fn !== null && typeof fn !== "undefined"){
    	    fn();
        }
    })

	// //lắng nghe khi có người chuẩn bị gửi tin nhắn
    // socket.on('typing', data => {
    //     if(data.userChat.userId!==""){
    //         staffOnline.find(function(item, i){
    //             if(item.userId === data.userChat.userId){
    //                 io.sockets.connected[item.id].emit('typing', {
    //                     user: data.userInfo
    //                 });
    //             }
    //         });
    //     }
    // })

    //lắng nghe yêu cầu từ phía người yêu cầu
    socket.on('request', (data, fn) => {
        console.log(requests);
        requests.push({
            id: socket.id,
            userInfo: data
        })
        socket.emit('staffListOnline', staffOnline);
        //cap nhat lại danh sach request
        console.log("Cập nhật danh sach yeu cau: ");
        console.log(requests);
        io.sockets.emit('requesttotal', requests);
    	
    	//Sau ki xử lý xong gọi callback function fn
        if(fn !== null && typeof fn !== "undefined"){
    	    fn();
        }
    })


    //danh sách yêu cầu
    socket.on('requesttotal', (data, fn) => {
        console.log("Danh sach yeu cau: ");
        console.log(requests);
        socket.emit('requesttotal', requests);
    	
    	//Sau ki xử lý xong gọi callback function fn
        if(fn !== null && typeof fn !== "undefined"){
    	    fn();
        }
    })

    //danh sách đang trò chuyện
    socket.on('chattingtotal', (data, fn) => {
        console.log("Danh sach đang chat: ");
        console.log(chatting);
        socket.emit('chattingtotal', chatting);
    	
    	//Sau ki xử lý xong gọi callback function fn
        if(fn !== null && typeof fn !== "undefined"){
    	    fn();
        }
    })

    //danh sách staff đang online
    socket.on('staffListOnline', (data, fn) => {
        console.log("Danh sach staff đang online: ");
        console.log(staffOnline);
        socket.emit('staffListOnline', staffOnline);
    	
    	//Sau ki xử lý xong gọi callback function fn
        if(fn !== null && typeof fn !== "undefined"){
    	    fn();
        }
    })

    //lắng nghe event nhân viên chọn người yêu cầu và chấp nhận chat
    socket.on('acceptRequest', (data, fn) => {
        var userSocketId = "";

        userSocketId = requests.find(x => x.userInfo.userId === data.userId).id;
        console.log("userSocketId");
        console.log(userSocketId);
        requests = requests.filter(function(returnableObjects){
            if(returnableObjects.userInfo.userId == data.userId){
                userSocketId = returnableObjects.id;
            }
            return returnableObjects.userInfo.userId != data.userId;
        });
        
        // myArray.findIndex(e => e.userInfo.userId === data.userId);

        // requests.splice(requests.findIndex(e => e.userInfo.userId === data.userId),1);
        if(userSocketId=="") return;

        chatting.push({
            staffId: data.staffId,
            userId: data.userId,
            staffSocketId: socket.id,
            userSocketId: userSocketId
        })
        console.log("Trả về tư vấn viên chấp nhận chat");

        io.sockets.connected[userSocketId].emit('responseRequest', data);

        io.sockets.emit('requesttotal', requests);
        io.sockets.emit('chattingtotal', chatting);
        console.log("Cập nhật danh sách đang chat:");
        console.log(chatting);
    	
    	//Sau ki xử lý xong gọi callback function fn
        if(fn !== null && typeof fn !== "undefined"){
    	    fn();
        }
    })

    //lắng nghe khi có nhân viên login
    socket.on('login', (data, fn) => {
        // console.log(staffOnline);
        $index = _findIndex(staffOnline, ['id', socket.id]);
        if($index>=0){
            socket.emit('loginFail');
            console.log(item.id);
            return;
        }
        // nếu chưa tồn tại thì gửi socket login thành công
        console.log('loginSuccess: '+ socket.id);
        socket.emit('loginSuccess', data);
        staffOnline.push({
            id: socket.id,
            staffInfo: data
        })
        io.sockets.emit('staffListOnline', staffOnline);// gửi danh sách user dang online
        io.sockets.emit('requesttotal', requests);// gửi danh sách request
        io.sockets.emit('chattingtotal', chatting);
        
        //kiểm tra có đang connect chat trước đó không
        $index = _findIndex(chatting, ['staffId', data.staffId]);
        if($index>=0){
            chatting[$index].staffSocketId = socket.id;
            socket.emit('reload', chatting[$index].userId);
        }

        //Sau ki xử lý xong gọi callback function fn
        if(fn !== null && typeof fn !== "undefined"){
    	    fn();
        }
    })

});

app.get('/', (req, res) => {
    res.send("Home page. Server running okay.");
})
