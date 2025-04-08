import React, { useState } from 'react';
import { View, Text, TextInput, Button, Alert } from 'react-native';
import { checkUserExists } from '../api/userApi';
import { styles } from '../styles';

export default function LoginScreen({ setIsLoggedIn, setUsername }) {
  const [usernameInput, setUsernameInput] = useState('');
  const [password, setPassword] = useState('');

  const handleLogin = async () => {
    try {
      const exists = await checkUserExists(usernameInput);
      if (exists) {
        Alert.alert('Login Successful', `Welcome ${usernameInput}`);
        setUsername(usernameInput);
        setIsLoggedIn(true);
      } else {
        Alert.alert('Login Failed', 'User not found');
      }
    } catch (error) {
      console.error('Login error:', error);
      Alert.alert('Error', 'Could not connect to the server');
    }
  };

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Login</Text>
      <TextInput
        style={styles.input}
        placeholder="Username"
        value={usernameInput}
        onChangeText={setUsernameInput}
      />
      <TextInput
        style={styles.input}
        placeholder="Password"
        secureTextEntry
        value={password}
        onChangeText={setPassword}
      />
      <Button title="Log In" onPress={handleLogin} />
    </View>
  );
}