import React, { useContext, useState, useEffect } from 'react';
import UserList from './UserList';
import MessageList from './MessageList.js';
import MessageInput from './MessageInput';
import './css/DirectMessage.css';
import AuthContext from '../../Utils/AuthProvider.jsx';
import UserSearch from './UserSearch.js';

function DirectMessage() {
  const [selectedUser, setSelectedUser] = useState(null);
  const [messages, setMessages] = useState([]);
  const [users, setUsers] = useState([]);
  const { user } = useContext(AuthContext);
  const userid = user?.id;

  const getUserList = () => {
    if (!userid) {
      return;
    }
    fetch(`http://localhost:8080/users/${userid}/direct-message`, {
      headers: {
        "Authorization": "Bearer " + localStorage.getItem('authToken')
      },
    })
      .then(response => response.json())
      .then(json => setUsers(json.data))
      .catch(error => console.error('Error fetching users:', error));
  };

  useEffect(() => {
    getUserList();
  
    const handleSearchUser = async (event) => {
      const { keyword, searchUserId } = event.detail;
      const queryParams = new URLSearchParams({
        keyword,
        userId: searchUserId
      });
  
      fetch(`http://localhost:8080/users/search?${queryParams.toString()}`)
        .then(response => response.json())
        .then(json => {
          if (!json.data || json.data.length === 0) {
            // 検索結果が存在しない場合
            console.warn("User not found. Resetting selected user.");
            setSelectedUser(null); // 選択ユーザーをリセット
            setMessages([]); // メッセージリストも空にする
            return;
          }
  
          const searchedUserId = json.data[0].id;
          const existingConversation = users.find(user =>
            (user.sender.id === searchedUserId && user.receiver.id === userid) ||
            (user.receiver.id === searchedUserId && user.sender.id === userid)
          );
  
          if (existingConversation) {
            // 過去にDMした相手が検索された場合
            setSelectedUser({ sender: { id: userid }, receiver: { id: searchedUserId } });
            fetch(`http://localhost:8080/direct-message/${userid}/${searchedUserId}`, {
              headers: {
                "Authorization": "Bearer " + localStorage.getItem('authToken')
              }
            })
              .then(response => response.json())
              .then(json => setMessages(json.data))
              .catch(error => console.error('Error fetching messages:', error));
          } else {
            // 新しいユーザーとのDMを開始
            setSelectedUser({ sender: { id: userid }, receiver: { id: searchedUserId } });
            setMessages([]); // 新しいユーザーなのでメッセージは空に
          }
        })
        .catch(error => {
          console.error("Error fetching search results:", error);
          setSelectedUser(null); // エラーの場合も選択ユーザーをリセット
          setMessages([]); // メッセージリストを空に
        });
    };
  
    window.addEventListener('SearchUser', handleSearchUser);
  
    return () => {
      window.removeEventListener('SearchUser', handleSearchUser);
    };
  }, [userid, users]);
  

  const handleUserSelect = (selectedUser) => {
    setSelectedUser(selectedUser);
    const senderid = selectedUser.sender.id;
    const receiverid = selectedUser.receiver.id;
    fetch(`http://localhost:8080/direct-message/${userid}/${userid === receiverid ? senderid : receiverid}`, {
      headers: {
        "Authorization": "Bearer " + localStorage.getItem('authToken')
      }
    })
      .then(response => response.json())
      .then(json => setMessages(json.data))
      .catch(error => console.error('Error fetching messages:', error));
  };

  const handleSendMessage = (message) => {
    const receiverid = selectedUser.receiver.id;
    const senderid = selectedUser.sender.id;
     // メッセージが空白または空文字でないことを確認
    if (!message.trim()) {
      console.warn("Cannot send an empty message.");
      return; // 空のメッセージの場合は送信を中断
  }

    if(senderid != receiverid){
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
          setMessages(prevMessages => [...prevMessages, { sender: json.data.sender, receiver: json.data.receiver, message, sent_at: new Date().toISOString() }]);
          getUserList();
        })
        .catch(error => console.error('Error sending message:', error));
    }
  };

  return (
    <div className="direct-message-container">
      <UserList users={users} onUserSelect={handleUserSelect} />
      
        <div className="direct-chat-container">
          <MessageList messages={messages} currentUserid={userid} />
        {selectedUser &&(
          <MessageInput onSendMessage={handleSendMessage} />
         )}
          
        </div>
      
      <div className="direct-search-container">
        <UserSearch />
      </div>
    </div>
  );
}

export default DirectMessage;
