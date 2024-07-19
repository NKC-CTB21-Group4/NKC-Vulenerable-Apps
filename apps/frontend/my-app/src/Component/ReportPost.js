import React, { useState, useContext } from 'react';
import './ReportPost.css'; // CSSファイルを作成してインポート
import AuthContext from '../Utils/AuthProvider';

function ReportPost({ postid, onClose }) {
  const [reportReasons, setReportReasons] = useState([]);
  const [ids, setIds] = useState([]);
  const [additionalInfo, setAdditionalInfo] = useState(""); // テキストエリアの状態
  const { user } = useContext(AuthContext);
  const userid = user.id;

  const handleCheckboxChange = (event) => {
    const { value, checked, id } = event.target;
    const numericId = parseInt(id, 10); // IDを数値に変換

    if (checked) {
      setReportReasons((prevReasons) => [...prevReasons, value]);
      setIds((prevIds) => [...prevIds, numericId]);
    } else {
      setReportReasons((prevReasons) => prevReasons.filter((v) => v !== value));
      setIds((prevIds) => prevIds.filter((i) => i !== numericId));
    }
  };

  const handleInputChange = (event) => {
    setAdditionalInfo(event.target.value);
  };

  const handleSubmit = async (event) => {
    event.preventDefault();
    try {
      // 通報内容をサーバーに送信する処理
      const filteredIds = ids.filter(id => !isNaN(id)); // NaNを除外
      const response = await fetch(`http://localhost:8080/reports/${userid}/${postid}`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer ' + localStorage.getItem("authToken")
        },
        body: JSON.stringify({
          tag_ids: filteredIds,
          reason: additionalInfo // テキストエリアの内容も送信
        }),
      });

      if (response.ok) {
        alert('通報が送信されました');
        onClose();
      } else {
        alert('通報の送信に失敗しました');
      }
    } catch (error) {
      console.error('通報の送信エラー:', error);
      alert('通報の送信中にエラーが発生しました');
    }
  };

  return (
    <div className="report-modal-overlay">
      <div className="report-modal-content">
        <h2 className="report-modal-title">通報内容を選択してください</h2>
        <form className="report-form" onSubmit={handleSubmit}>
          <div className="report-checkbox-group">
            <div className="report-checkbox-item">
              <input
                type="checkbox"
                id="1"
                value="Discriminatory Posts"
                onChange={handleCheckboxChange}
              />
              <label htmlFor="1">差別的な発言</label>
            </div>
            <div className="report-checkbox-item">
              <input
                type="checkbox"
                id="2"
                value="Violent Speech"
                onChange={handleCheckboxChange}
              />
              <label htmlFor="2">暴力的な発言</label>
            </div>
            <div className="report-checkbox-item">
              <input
                type="checkbox"
                id="3"
                value="Spam"
                onChange={handleCheckboxChange}
              />
              <label htmlFor="3">スパム</label>
            </div>
            <div className="report-checkbox-item">
              <input
                type="checkbox"
                id="4"
                value="Suicide or Self-Harm"
                onChange={handleCheckboxChange}
              />
              <label htmlFor="4">自殺や自傷行為</label>
            </div>
          </div>
          <div className="report-textarea-group">
            <label htmlFor="additionalInfo" className="report-textarea-label">通報内容の詳細:</label>
            <textarea
              id="additionalInfo"
              value={additionalInfo}
              onChange={handleInputChange}
              rows="4"
              placeholder="ここに詳細を入力してください..."
              className="report-textarea"
            />
          </div>
          <div className="report-button-group">
            <button type="submit" className="report-submit-button">送信</button>
            <button type="button" className="report-cancel-button" onClick={onClose}>キャンセル</button>
          </div>
        </form>
      </div>
    </div>
  );
}

export default ReportPost;
