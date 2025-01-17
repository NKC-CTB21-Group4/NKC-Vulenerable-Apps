import React, { useState, useEffect, useContext } from 'react';
import { useFetchTags, reportPosts } from './api/report';
import './css/ReportPost.css'; // CSSファイルを作成してインポート
import AuthContext from '../Utils/AuthProvider';

function ReportPost({ postid, onClose }) {
  const [reportOptions, setReportOptions] = useState([]); // APIから取得する通報内容
  const [selectedIds, setSelectedIds] = useState([]); // 選択されたID
  const [additionalInfo, setAdditionalInfo] = useState(''); // テキストエリアの状態
  const [errorMessage, setErrorMessage] = useState(''); // エラーメッセージの状態
  const { user } = useContext(AuthContext);
  const userid = user.id;
  const { data, error } = useFetchTags('http://localhost:8080/tags');

  useEffect(() => {
    if (data) {
      setReportOptions(data.data);
    }
    if (error) {
      console.log('エラーが発生しました', error);
    }
  }, [data]);

  const handleCheckboxChange = (event) => {
    const { checked, id } = event.target;
    const numericId = parseInt(id, 10); // IDを数値に変換

    if (checked) {
      setSelectedIds((prevIds) => [...prevIds, numericId]);
    } else {
      setSelectedIds((prevIds) => prevIds.filter((i) => i !== numericId));
    }
  };

  const handleInputChange = (event) => {
    setAdditionalInfo(event.target.value);
  };

  const handleSubmit = async (event) => {
    event.preventDefault();

    // タグが選択されていない場合はエラーメッセージを表示して中断
    if (selectedIds.length === 0) {
      setErrorMessage('タグを選択してください。');
      return;
    }

    try {
      const url = `http://localhost:8080/reports/${userid}/${postid}`;
      const response = await reportPosts(url, selectedIds, additionalInfo);
      console.log(response);
      if (response) {
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
        {errorMessage && <p className="error-message">{errorMessage}</p>}
        <form className="report-form" onSubmit={handleSubmit}>
          <div className="report-checkbox-group">
            {reportOptions.map((option) => (
              <div key={option.id} className="report-checkbox-item">
                <input
                  type="checkbox"
                  id={option.id}
                  value={option.name}
                  onChange={handleCheckboxChange}
                />
                <label htmlFor={option.id}>{option.name}</label>
              </div>
            ))}
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
