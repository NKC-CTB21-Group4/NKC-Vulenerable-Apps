import React, { useContext, useState, useEffect } from 'react';
import UserList from './UserList';
import MessageList from './MessageList.js';
import MessageInput from './MessageInput';
import './DirectMessage.css';
import AuthContext from '../Utils/AuthProvider.jsx';

function DirectMessage() {
  const [selectedUser, setSelectedUser] = useState(null);
  const [messages, setMessages] = useState([]);
  const [users, setUsers] = useState([]);
  const {user} = useContext(AuthContext);
  const userid = user?.id;

  useEffect(() => {
    // ユーザーリストを取得
    if(!userid){
        return;
    }
    fetch(`http://localhost:8080/users/${userid}/direct-message`,{
        headers:{
            "Authorization": "Bearer " + localStorage.getItem('authToken')
        },
    })
      .then(response => response.json())
      .then(json => setUsers(json.data))
      .catch(error => console.error('Error fetching users:', error));
  }, [userid]);

  const handleUserSelect = (selectedUser) => {
    setSelectedUser(selectedUser);
    const senderid = selectedUser.sender.id;
    const receiverid = selectedUser.receiver.id;
    fetch(`http://localhost:8080/direct-message/${userid}/${userid === receiverid ? senderid : receiverid}`,{
        headers:{
            "Authorization": "Bearer " + localStorage.getItem('authToken')
        }
    })
      .then(response => response.json())
      .then(json => setMessages(json.data))
      .catch(error => console.error('Error fetching messages:', error));
      console.log(messages);
  };

  const handleSendMessage = (message) => {
    const receiverid = selectedUser.receiver.id;
    const senderid = selectedUser.sender.id;
    fetch(`http://localhost:8080/direct-message/${userid}/${userid === receiverid ? senderid : receiverid}`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          "Authorization": "Bearer " + localStorage.getItem('authToken')
        },
        body: JSON.stringify({ message }),
      })
      .then(response => response.json())
      .then(json => {
        setMessages(prevMessages => [...prevMessages, { sender: json.data.sender, receiver:json.data.receiver, message, sent_at: new Date().toISOString() }]);
      })
      .catch(error => console.error('Error sending message:', error));
  };

  return (
    <div className="direct-message-container">
      <UserList users={users} onUserSelect={handleUserSelect} />
      {selectedUser && (
        <div className="direct-chat-container">
          <MessageList messages={messages} currentUserid={userid}/>
          <MessageInput onSendMessage={handleSendMessage} />
        </div>
      )}
    </div>
  );
}

export default DirectMessage;
