import React, { useContext, useState, useEffect } from 'react';
import { Navigate } from 'react-router-dom';
import { useFetchUsers, useFetchMessage, sendMessage, searchUsers } from '../api/directMessage.js';
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
  const userid = user.id;

  const { data: userData, error: userError, mutate: userMutate } = useFetchUsers(
    `http://localhost:8080/users/${userid}/direct-message`
  );

  const { data: messageData, error: messageError, mutate: messageMutate } = useFetchMessage(
    selectedUser
      ? `http://localhost:8080/direct-message/${userid}/${
          userid === selectedUser.receiver.id ? selectedUser.sender.id : selectedUser.receiver.id
        }`
      : null
  );

  useEffect(() => {
    if (userData?.data) {
      setUsers(userData.data);
    }
  }, [userData]);

  useEffect(() => {
    if (messageData?.data) {
      setMessages(messageData.data);
    }
  }, [messageData]);

  useEffect(() => {
    const handleSearchUser = async (event) => {
      const { keyword, searchUserId, onlyFromFollowedUser } = event.detail;
      const queryParams = new URLSearchParams({ keyword, userId: searchUserId, onlyFromFollowedUser });
      const url = `http://localhost:8080/users/search?${queryParams.toString()}`;

      try {
        const response = await searchUsers(url);
        if (!response.data || response.data.length === 0) {
          console.warn("User not found. Resetting selected user.");
          setSearchResults([]);
          setSelectedUser(null);
          setMessages([]);
          return;
        }
        setSearchResults(response.data);
      } catch (error) {
        console.error("Error fetching search results:", error);
        setSearchResults([]);
        setSelectedUser(null);
        setMessages([]);
      }
    };

    window.addEventListener('SearchUser', handleSearchUser);

    return () => {
      window.removeEventListener('SearchUser', handleSearchUser);
    };
  }, [userid]);

  const handleUserSelect = (user) => {
    setSelectedUser(user);
  };

  const handleSendMessage = async (message) => {
    if (!message.trim()) {
      console.warn("Cannot send an empty message.");
      return;
    }

    const receiverId = selectedUser.receiver.id;
    const senderId = selectedUser.sender.id;
    const url = `http://localhost:8080/direct-message/${userid}/${
      userid === receiverId ? senderId : receiverId
    }`;

    try {
      const response = await sendMessage(url, message);
      setMessages((prevMessages) => [
        ...prevMessages,
        {
          sender: response.data.sender,
          receiver: response.data.receiver,
          message,
          sent_at: new Date().toISOString(),
        },
      ]);
      userMutate();
    } catch (error) {
      console.error("Error sending message:", error);
    }
  };

  const handleSearchResultClick = (user) => {
    setSelectedUser({ sender: { id: userid }, receiver: { id: user.id } });
    setSearchResults([]);
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
