### TÀI LIỆU VỀ SERVER SOCKET.IO (SYSTEM CHAT REALTIME)

**SERVER SOCKET:** https://jiban.earth:6960

##### 1. Login dành cho Admin (staff)
socket.emit("login", (data, fn));

##### 2. Lắng nghe login thành công của Admin (staff)
socket.on("loginSuccess");

##### 1. Gửi tin nhắn
socket.emit("newMessage", (data, fn));

##### 1. Gửi tin nhắn
socket.emit("newMessage", (data, fn));

##### 1. Lắng nghe tin nhắn đến
socket.on("newMessage");

##### 1. Admin lắng nghe danh sách admin đang online (staff List Online)
socket.on("staffListOnline");

##### 1. Admin lắng nghe danh sách admin đang online (staff List Online)
socket.on("staffListOnline");


##### 1. Admin lắng nghe danh sách các yêu cầu tự đang đợi từ user (requests)
this.socket.on("requesttotal");

##### 1. Admin lắng nghe danh sách các cuộc trò chuyện đang diễn ra (chatting)
this.socket.on("chattingtotal");

##### 1. Admin lắng nghe kết thúc chat từ user chat(endChat)
this.socket.on("endChat");

##### 1. Logout, ngắt kết nối của admin (staff)
socket.disconnect();
