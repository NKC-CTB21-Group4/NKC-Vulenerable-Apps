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
    const matches = input.matchAll(/(?:authorId:(\d+))?\s*(?:authorName:([^\s]+))?\s*(?:dateFrom:([^\s]+))?\s*(?:dateTo:([^\s]+))?\s*(?:onlyFromFollowedUser:([^\s]+))?/g);
    for(const match of matches){
      if(match[1] && !advancedSearchParams.authorId)advancedSearchParams.authorId = match[1];
      if(match[2] && !advancedSearchParams.authorName)advancedSearchParams.authorName = match[2];
      if(match[3] && !advancedSearchParams.dateFrom)advancedSearchParams.dateFrom = match[3];
      if(match[4] && !advancedSearchParams.dateTo)advancedSearchParams.dateTo = match[4];
      if(match[5] && !advancedSearchParams.onlyFromFollowedUser)advancedSearchParams.onlyFromFollowedUser = match[5];
    }
      keyword = keyword
      .replace(/authorId:\d+|authorName:[^\s]+|dateFrom:[^\s]+|dateTo:[^\s]+|onlyFromFollowedUser:[^\s]+/g, '')
      .trim(); // 残りの部分をキーワードとして扱う

    // 検索パラメータをカスタムイベントで送信
    const event = new CustomEvent('SearchPost', {
      detail: {
        keyword: keyword || '',
        authorId: advancedSearchParams.authorId || '',
        authorName: advancedSearchParams.authorName || '',
        dateFrom: advancedSearchParams.dateFrom || '',
        dateTo: advancedSearchParams.dateTo || '',
        onlyFromFollowedUser: advancedSearchParams.onlyFromFollowedUser || ''
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
