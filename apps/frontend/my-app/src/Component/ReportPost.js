import React, { useState, useEffect, useContext } from 'react';
import './css/ReportPost.css';
import AuthContext from '../Utils/AuthProvider';
import { useFetchTags, reportPosts } from '../api/report';

function ReportPost({ postid, onClose }) {
  const [reportOptions, setReportOptions] = useState([]); // 通報内容の選択肢
  const [selectedIds, setSelectedIds] = useState([]); // 選択された通報内容のID
  const [additionalInfo, setAdditionalInfo] = useState(''); // テキストエリアの入力内容
  const { user } = useContext(AuthContext);
  const userid = user.id;

  // タグの取得
  const { data: fetchedTags, error } = useFetchTags('http://localhost:8080/tags');

  useEffect(() => {
    if (fetchedTags && !error) {
      setReportOptions(fetchedTags.data); // データが存在する場合にのみセット
    } else if (error) {
      console.error('通報内容の取得中にエラーが発生しました', error);
    }
  }, [fetchedTags, error]);

  // チェックボックスの状態変更
  const handleCheckboxChange = (event) => {
    const { checked, id } = event.target;
    const numericId = parseInt(id, 10);

    setSelectedIds((prevIds) =>
      checked ? [...prevIds, numericId] : prevIds.filter((i) => i !== numericId)
    );
  };

  // テキストエリアの入力処理
  const handleInputChange = (event) => {
    setAdditionalInfo(event.target.value);
  };

  // 通報の送信処理
  const handleSubmit = async (event) => {
    event.preventDefault();
    try {
      const response = await reportPosts(
        `http://localhost:8080/reports/${userid}/${postid}`,
        selectedIds,
        additionalInfo
      );
      console.log(response);
      if (response.statusCode == 201) {
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
            <label htmlFor="additionalInfo" className="report-textarea-label">
              通報内容の詳細:
            </label>
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
            <button type="submit" className="report-submit-button">
              送信
            </button>
            <button type="button" className="report-cancel-button" onClick={onClose}>
              キャンセル
            </button>
          </div>
        </form>
      </div>
    </div>
  );
}

export default ReportPost;
