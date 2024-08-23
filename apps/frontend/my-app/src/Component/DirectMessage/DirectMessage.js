import React, { useContext, useState, useEffect } from 'react';
import UserList from './UserList';
import MessageList from './MessageList.js';
import MessageInput from './MessageInput';
import './css/DirectMessage.css';
import AuthContext from '../../Utils/AuthProvider.jsx';
import { useFetchUsers, useFetchMessage, sendMessage } from '../../api/directmessage.js';

function DirectMessage() {
  const [selectedUser, setSelectedUser] = useState(null);
  const [messages, setMessages] = useState([]);
  const [users, setUsers] = useState([]);
  const { user } = useContext(AuthContext);
  const userid = user?.id;

  // ユーザーリストを取得
  const { data: userData, error: userError } = useFetchUsers(userid ? `http://localhost:8080/users/${userid}/direct-message` : null);

  // メッセージ取得用の SWR フック
  const { data: messageData, error: messageError, mutate } = useFetchMessage(
    selectedUser
      ? `http://localhost:8080/direct-message/${userid}/${userid === selectedUser.receiver.id ? selectedUser.sender.id : selectedUser.receiver.id}`
      : null,
    { needsAuth: true }
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

  if (userError) return <div>Failed to load</div>;
  if (!userData) return <div>Loading...</div>;

  const handleUserSelect = (user) => {
    setSelectedUser(user);
    // メッセージがすでにロードされている場合は更新
    if (messageData?.data) {
      setMessages(messageData.data);
    }
  };

  const handleSendMessage = async (message) => {
    try {
      const response = await sendMessage(`http://localhost:8080/direct-message/${userid}/${userid === selectedUser.receiver.id ? selectedUser.sender.id : selectedUser.receiver.id}`,message)
      if (response.statusCode == !200) throw new Error(response.message || 'メッセージ送信に失敗しました');
      // メッセージリストの更新
      mutate(); // キャッシュを再検証して最新データを取得する
    } catch (error) {
      console.error('Error sending message:', error);
    }
  };

  return (
    <div className="direct-message-container">
      <UserList users={users} onUserSelect={handleUserSelect} />
      {selectedUser && (
        <div className="direct-chat-container">
          <MessageList messages={messages} currentUserid={userid} />
          <MessageInput onSendMessage={handleSendMessage} />
        </div>
      )}
    </div>
  );
}

export default DirectMessage;
