const BASE_URL = 'http:/172.21.198.34/index.php'; // replace with your actual IP

// Checking user exists
export async function checkUserExists(username) {
  try {
    const response = await fetch(`${BASE_URL}/user/list?username=${encodeURIComponent(username)}`);
    if (!response.ok) {
      throw new Error('Network response was not ok');
    }

    const data = await response.json();
    return Array.isArray(data) && data.length > 0;
  } catch (error) {
    console.error('API error:', error);
    throw error;
  }
}
