import React, { useState,useEffect } from 'react';
import './css/Search.css';
import tegakisearch from '../images/tegakisearch.png';
import tegakiclear from '../images/tegakiclear.png'; // クリアアイコンのパスをインポート

function Search({ setSearchKeyword,searchKeyword }) {
  const [searchTerm, setSearchTerm] = useState(''); // 検索語句を管理する状態

  
  useEffect(() => {
    if (searchKeyword === '') {
      setSearchTerm('');
    }
  }, [searchKeyword]);
  
  const handleChange = (event) => {
    const value = event.target.value;
    setSearchTerm(value);
  };

  const handleClear = () => {
    setSearchTerm(''); // インプット内の文字を空にする
    setSearchKeyword(''); // 検索キーワードをリセット
  };

  const handleKeyPress = (event) => {
    if (event.key === 'Enter') {
      setSearchKeyword(searchTerm);
    }
  };

  return (
    <div className="search-container">
      <img className="search-icon" src={tegakisearch} alt="Search Icon" />
      <input
        className="search-post"
        type="text"
        placeholder="検索"
        value={searchTerm} // インプットの値をsearchTermにバインド
        onChange={handleChange}
        onKeyPress={handleKeyPress} // エンターキーの押下を検出
      />
      <button className="clear-button" onClick={handleClear}>
        <img src={tegakiclear} alt="Clear Icon" />
      </button>
    </div>
  );
}

export default Search;
