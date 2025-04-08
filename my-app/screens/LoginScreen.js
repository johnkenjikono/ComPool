import React, { useState } from 'react';
import { View, Text, TextInput, Button, Alert } from 'react-native';
import { checkUserExists } from '../api/userApi'; // REST API function to verify user
import { styles } from '../styles'; // Shared styling for consistency

// LoginScreen handles user login logic and UI.
// Props:
// - setIsLoggedIn: updates the logged-in state in parent
// - setUsername: stores the logged-in user's name
export default function LoginScreen({ setIsLoggedIn, setUsername }) {
  const [usernameInput, setUsernameInput] = useState(''); // Input field for username
  const [password, setPassword] = useState(''); // Input field for password (not used in validation yet)

  // Called when user presses the "Log In" button
  const handleLogin = async () => {
    try {
      // Check if the user exists in the database
      const exists = await checkUserExists(usernameInput);

      if (exists) {
        // If found, show success alert and update state
        Alert.alert('Login Successful', `Welcome ${usernameInput}`);
        setUsername(usernameInput);
        setIsLoggedIn(true);
      } else {
        // Otherwise, show error alert
        Alert.alert('Login Failed', 'User not found');
      }
    } catch (error) {
      // Handle network or backend errors
      console.error('Login error:', error);
      Alert.alert('Error', 'Could not connect to the server');
    }
  };

  return (
    <View style={styles.container}>

      {/* Username input field */}
      <TextInput
        style={styles.input}
        placeholder="Username"
        value={usernameInput}
        onChangeText={setUsernameInput}
      />

      {/* Password input field (hidden text) */}
      <TextInput
        style={styles.input}
        placeholder="Password"
        secureTextEntry
        value={password}
        onChangeText={setPassword}
      />

      {/* Login button */}
      <Button title="Log In" onPress={handleLogin} />
    </View>
  );
}
