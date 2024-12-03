import React from 'react';
import './css/PrivateButton.css';

const PrivateButton = ({  onUserPrivateClick,isPrivated }) => {
  return (
    <input
      type="button"
      className="Private-button"
      onClick={ onUserPrivateClick}
      value={isPrivated ? "公開する" : "非公開にする"}
    >
   
    </input>
  );
};

export default PrivateButton;
