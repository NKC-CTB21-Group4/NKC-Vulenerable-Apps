import { useState, useCallback } from 'react';

const useForceRender = () => {
  const [, setTick] = useState(0);
  
  // 状態を強制的に更新し再レンダリングをトリガーする
  const forceRender = useCallback(() => {
    setTick(tick => tick + 1);
  }, []);

  return forceRender;
};

export default useForceRender;
