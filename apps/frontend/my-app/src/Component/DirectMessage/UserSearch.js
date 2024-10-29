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

      if (input.includes('userId:')) {
        const match = input.match(/userId:(\d+)/);
        if (match) {
          advancedSearchParams.userId = match[1];
          keyword = keyword.replace(match[0], '').trim(); // 残りの部分をキーワードとして扱う
        }
      }

      const event = new CustomEvent('SearchUser',{
        detail:{
            keyword:keyword || '',
            userId:advancedSearchParams.userId || ''
        }
      });
      window.dispatchEvent(event);
    }

    return (
    <div className="direct-message-search-container">
      <img className="search-icon" src={tegakisearch} alt="Search Icon" />
      <input
        className="search-user"
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
    )
}

export default UserSearch