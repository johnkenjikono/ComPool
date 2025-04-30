import { BASE_URL } from '../config';

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

export async function loginUser(username, password) {
  const response = await fetch(`${BASE_URL}/user/login`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ username, password }),
  });

  const data = await response.json();
  return data.success;
}
