import React from 'react';
import './MessageList.css';

function MessageList({ messages, currentUserid }) {
  return (
    <div className="message-list">
      {messages.map((message, index) => (
        <div
          key={index}
          className={`message ${message.sender_id === currentUserid ? 'sent' : 'received'}`}
        >
          <strong>{message.sender_id === currentUserid ? 'Me' : `User ${message.sender_id}`}:</strong> {message.message}
          <em className="timestamp">{new Date(message.sent_at).toLocaleString()}</em>
        </div>
      ))}
    </div>
  );
}

export default MessageList;
