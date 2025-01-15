const getAuthHeaders = () => {
    const token = localStorage.getItem('authToken');
    return token ? { 'Authorization': `Bearer ${token}` } : {};
  };

  export async function createUser(apiEndpoint,newUser) {
    try {
        const response = await fetch(apiEndpoint, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify(newUser),
        });
    
        const responseData = await response.json();
    
        if (!response.ok) {
          throw new Error(responseData.message || 'ユーザーの作成に失敗しました');
        }
    
        return responseData;
      } catch (error) {
        console.error('エラーが発生しました:', error.message);
        throw error;
      }
  }

  export async function createAdminUser(apiEndpoint,newUser) {
    try {
        const response = await fetch(apiEndpoint, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            ...getAuthHeaders(),
          },
          body: JSON.stringify(newUser),
        });
    
        const responseData = await response.json();
    
        if (!response.ok) {
          throw new Error(responseData.message || 'ユーザーの作成に失敗しました');
        }
    
        return responseData;
      } catch (error) {
        console.error('エラーが発生しました:', error.message);
        throw error;
      }
  }

  export async function deleteUser(apiEndpoint) {
    try {
      const response = await fetch(apiEndpoint, {
        method: 'DELETE',
        headers: {
          ...getAuthHeaders(),
        },
      });
  
      if (!response.ok) {
        throw new Error('ユーザーの削除に失敗しました');
      }
      return response;
    } catch (error) {
      console.error('Error:', error);
      throw error;
    }
  }

  export async function getAuth(apiEndpoint,email,password) {
    try {
        const response = await fetch(apiEndpoint, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            email,
            password,
          }),
        });
    
        const responseData = await response.json();
    
        if (!response.ok) {
          throw new Error(responseData.message || '認証に失敗しました');
        }
    
        return responseData;
      } catch (error) {
        console.error('エラーが発生しました:', error.message);
        throw error;
      }
  }

  export async function updateUser(apiEndpoint,newEmail,email,newPassword,password) {
    try{
      const response = await fetch(apiEndpoint, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer ' + localStorage.getItem('authToken')
        },
        body: JSON.stringify({
          email: newEmail || email, // 新しいメールがなければ現在のメールを使用
          password: newPassword || password // 新しいパスワードがなければ現在のパスワードを使用
        }),
      });

      const responseData = response.json;

      if(!response.ok){
        throw new Error(responseData.message || 'ユーザー情報のアップデートに失敗しました。');
      }

      return responseData;

    }catch(error){
      console.error('エラーが発生しました。',error.message);
      throw error;
    }
  }