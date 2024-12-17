import React, { useEffect, useContext } from "react";
import { Link } from "react-router-dom";
import "../css/ThreePointLeaderMenu.css";
import AuthContext from "../../Utils/AuthProvider";

const MenuContent = ({ isOpen }) => {
  const { user } = useContext(AuthContext); // useContextをトップレベルで呼び出す
  const userid = user?.id;

  useEffect(() => {
    // User削除モーダルのイベントリスナーを追加
    const userDeleteBtn = document.querySelector("#user-delete-btn");
    const userDeleteDialog = document.querySelector("#user-delete-dialog");

    const openDeleteDialog = () => {
      if (userDeleteDialog) {
        userDeleteDialog.showModal();
      }
    };

    if (userDeleteBtn) {
      userDeleteBtn.addEventListener("click", openDeleteDialog);
    }

    // Email, Password変更モーダルのイベントリスナーを追加
    const userChangeBtn = document.querySelector("#user-change-btn");
    const userChangeDialog = document.querySelector("#user-change-dialog");

    const openChangeDialog = () => {
      if (userChangeDialog) {
        userChangeDialog.showModal();
      }
    };

    if (userChangeBtn) {
      userChangeBtn.addEventListener("click", openChangeDialog);
    }

    return () => {
      if (userDeleteBtn) {
        userDeleteBtn.removeEventListener("click", openDeleteDialog);
      }
      if (userChangeBtn) {
        userChangeBtn.removeEventListener("click", openChangeDialog);
      }
    };
  }, []);

  return (
    <nav className={`nav-menu ${isOpen ? "open" : ""}`}>
      <ul>
          <li>
            <Link to="#" id="user-change-btn">
              Email,Password変更
            </Link>
          </li>
        {userid &&(
        <li>
          <Link to="#" id="user-delete-btn">
            User削除
          </Link>
        </li>
        )}
      </ul>
    </nav>
  );
};

export default MenuContent;
