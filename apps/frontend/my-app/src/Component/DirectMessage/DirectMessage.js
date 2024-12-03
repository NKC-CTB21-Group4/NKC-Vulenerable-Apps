import React, { useContext, useState, useEffect } from 'react';
import UserList from './UserList';
import MessageList from './MessageList.js';
import MessageInput from './MessageInput';
import './css/DirectMessage.css';
import AuthContext from '../../Utils/AuthProvider.jsx';
import UserSearch from './UserSearch.js';
import PullDownUserList from './PullDownUserList.js';

function DirectMessage() {
  const [selectedUser, setSelectedUser] = useState(null);
  const [messages, setMessages] = useState([]);
  const [users, setUsers] = useState([]);
  const [searchResults, setSearchResults] = useState([]);
  const { user } = useContext(AuthContext);
  const userid = user?.id;

  const getUserList = () => {
    if (!userid) return;

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
      const { keyword, searchUserId,onlyFromFollowedUser } = event.detail;
      const queryParams = new URLSearchParams({ 
        keyword, 
        userId: searchUserId,
        onlyFromFollowedUser
      });

      fetch(`http://localhost:8080/users/search?${queryParams.toString()}`, {
        headers: {
          'Authorization': `Bearer ${localStorage.getItem('authToken')}`,
        }
      })
        .then(response => response.json())
        .then(json => {
          if (!json.data || json.data.length === 0) {
            console.warn("User not found. Resetting selected user.");
            setSearchResults([]); // 検索結果をリセット
            setSelectedUser(null);
            setMessages([]);
            return;
          }

          setSearchResults(json.data); // 検索結果を保存
        })
        .catch(error => {
          console.error("Error fetching search results:", error);
          setSearchResults([]);
          setSelectedUser(null);
          setMessages([]);
        });
    };

    window.addEventListener('SearchUser', handleSearchUser);

    return () => {
      window.removeEventListener('SearchUser', handleSearchUser);
    };
  }, [userid]);

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
    if (!message.trim()) {
      console.warn("Cannot send an empty message.");
      return;
    }

    const receiverid = selectedUser.receiver.id;
    const senderid = selectedUser.sender.id;

    if (senderid !== receiverid) {
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
          setMessages(prevMessages => [
            ...prevMessages,
            { sender: json.data.sender, receiver: json.data.receiver, message, sent_at: new Date().toISOString() }
          ]);
          getUserList();
        })
        .catch(error => console.error('Error sending message:', error));
    }
  };

  const handleSearchResultClick = (user) => {
    setSelectedUser({ sender: { id: userid }, receiver: { id: user.id } });
    setSearchResults([]); // プルダウンリストを非表示にする
    handleUserSelect({ sender: { id: userid }, receiver: { id: user.id } });
  };

  return (
    <div className="direct-message-container">
      <UserList users={users} onUserSelect={handleUserSelect} />
      <div className="direct-chat-container">
        <MessageList messages={messages} currentUserid={userid} />
        {selectedUser && <MessageInput onSendMessage={handleSendMessage} />}
      </div>
      <div className="direct-search-container">
        <UserSearch />
        {searchResults.length > 0 && (
          <PullDownUserList users={searchResults} onUserClick={handleSearchResultClick} />
        )}
      </div>
    </div>
  );
}

export default DirectMessage;
