import React, { useEffect, useRef } from 'react';
import './css/MessageList.css';

function MessageList({ messages, currentUserid }) {
  const messageEndRef = useRef(null);

  const scrollToBottom = () => {
    messageEndRef.current?.scrollIntoView({ behavior: 'smooth' });
  };

  useEffect(() => {
    scrollToBottom();
  }, [messages]);

  return (
    <div className="direct-message-list">
      {messages.map((message, index) => (
        <div
          key={index}
          className={`direct-message ${message.sender_id === currentUserid ? 'sent' : 'received'}`}
        >
          <strong>{message.sender_id === currentUserid ? '' : `User ${message.sender_id}`}</strong> {message.message}
          <em className="timestamp">{new Date(message.sent_at).toLocaleString()}</em>
        </div>
      ))}
      <div ref={messageEndRef} />
    </div>
  );
}

export default MessageList;
