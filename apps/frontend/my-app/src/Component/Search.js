import React, { useState } from 'react';
import './css/Search.css';
import tegakisearch from '../images/tegakisearch.png';
import tegakiclear from '../images/tegakiclear.png'; // クリアアイコンのパスをインポート

function Search() {
  const [searchTerm, setSearchTerm] = useState(''); // 検索語句を管理する状態

  const handleChange = (event) => {
    const value = event.target.value;
    setSearchTerm(value);
  };

  const handleClear = () => {
    setSearchTerm(''); // インプット内の文字を空にする
  };

  const handleKeyDown = (event) => {
    if (event.key === 'Enter') {
      processSearchInput(searchTerm);
    }
  };

  const processSearchInput = (input) => {
    const advancedSearchParams = {};
    let keyword = input.trim(); // キーワードをトリムして取り出す

    // 特定のパラメータ形式に基づいて分割・解析
    if (input.includes('authorId:')) {
      const match = input.match(/authorId:(\d+)/);
      if (match) {
        advancedSearchParams.authorId = match[1];
        keyword = keyword.replace(match[0], '').trim(); // 残りの部分をキーワードとして扱う
      }
    }
    if (input.includes('authorName:')) {
      const match = input.match(/authorName:([^\s]+)/);
      if (match) {
        advancedSearchParams.authorName = match[1];
        keyword = keyword.replace(match[0], '').trim(); // 残りの部分をキーワードとして扱う
      }
    }
    if (input.includes('dateFrom:')) {
      const match = input.match(/dateFrom:([^\s]+)/);
      if (match) {
        advancedSearchParams.dateFrom = match[1];
        keyword = keyword.replace(match[0], '').trim();
      }
    }
    if (input.includes('dateTo:')) {
      const match = input.match(/dateTo:([^\s]+)/);
      if (match) {
        advancedSearchParams.dateTo = match[1];
        keyword = keyword.replace(match[0], '').trim();
      }
    }

    // 検索パラメータをカスタムイベントで送信
    const event = new CustomEvent('SearchPost', {
      detail: {
        keyword: keyword || '',
        authorId: advancedSearchParams.authorId || '',
        authorName: advancedSearchParams.authorName || '',
        dateFrom: advancedSearchParams.dateFrom || '',
        dateTo: advancedSearchParams.dateTo || ''
      }
    });
    window.dispatchEvent(event); // カスタムイベントを発火
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
        onKeyDown={handleKeyDown} // エンターキーの押下を検出
      />
      <button className="clear-button" onClick={handleClear}>
        <img src={tegakiclear} alt="Clear Icon" />
      </button>
    </div>
  );
}

export default Search;
