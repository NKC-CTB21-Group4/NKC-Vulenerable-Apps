import React, {useState}from 'react'
import './css/UserSearch.css'
import tegakisearch from '../../images/tegakisearch.png';
import tegakiclear from '../../images/tegakiclear.png'; // クリアアイコンのパスをインポート

function UserSearch() {
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

        const matches = input.matchAll(/(?:userId:(\d+))?\s*(?:onlyFromFollowedUser:([^\s]+))?/g);
        for(const match of matches) {
          if(match[1] && !advancedSearchParams.userId)advancedSearchParams.userId = match[1];
          if(match[2] && !advancedSearchParams.onlyFromFollowedUser)advancedSearchParams.onlyFromFollowedUser = match[2];
          keyword = keyword
          .replace(/userId:\d+|onlyFromFollowedUser:[^\s]+/g, '')
          .trim(); // 残りの部分をキーワードとして扱う
        }

      const event = new CustomEvent('SearchUser',{
        detail:{
            keyword:keyword || '',
            searchUserId: advancedSearchParams.userId || '',
            onlyFromFollowedUser: advancedSearchParams.onlyFromFollowedUser || ''
        }
      });
      window.dispatchEvent(event);
    }

    return (
    <div className="direct-message-search-container">
      <img className="direct-message-search-icon" src={tegakisearch} alt="Search Icon" />
      <input
        className="direct-message-search-user"
        type="text"
        placeholder="検索"
        value={searchTerm} // インプットの値をsearchTermにバインド
        onChange={handleChange}
        onKeyDown={handleKeyDown} // エンターキーの押下を検出
      />
      <button className="direct-message-clear-button" onClick={handleClear}>
        <img src={tegakiclear} alt="Clear Icon" />
      </button>
    </div>
    )
}

export default UserSearch