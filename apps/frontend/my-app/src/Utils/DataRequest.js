// utils/ContentFetcher.js
import React, { useEffect, useState } from 'react';
import Content from '../Component/Content';

function DataRequest() {
  const [contentData, setContentData] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    async function fetchData() {
      try {
        const response = await fetch("http://localhost:8080/challenges/api/diary");
        
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const json = await response.json();
        console.log(json.data);
        console.log("Fetched data:", json);
        
        if (json && json.data && Array.isArray(json.data)) {
          setContentData(json.data);
        } else {
          throw new Error("Invalid JSON structure");
        }
        
        setLoading(false);
      } catch (error) {
        console.error("Error fetching content:", error);
        setLoading(false);
      }
    }

    fetchData();
  }, []);

  if (loading) {
    return <div>Loading...</div>;
  }
  return <Content contentData={contentData} />;
}

export default DataRequest;
