// screens/RegisterScreen.js
import React, { useState } from 'react';
import { View, Text, TextInput, Button, Alert } from 'react-native';
import AsyncStorage from '@react-native-async-storage/async-storage';
import { styles } from '../styles';
import { BASE_URL } from '../config';

export default function RegisterScreen({ setIsLoggedIn, setUsername }) {
  const [usernameInput, setUsernameInput] = useState('');
  const [passwordInput, setPasswordInput] = useState('');

  const handleRegister = async () => {
    if (!usernameInput || !passwordInput) {
      Alert.alert('Missing Info', 'Both fields are required.');
      return;
    }

    try {
      const response = await fetch(`${BASE_URL}/user/create`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ username: usernameInput, password: passwordInput }),
      });

      const result = await response.json();

      if (result.success) {
        Alert.alert('Registration Successful', 'Welcome to ComPool!');
        await AsyncStorage.setItem('user', usernameInput);
        setUsername(usernameInput);
        setIsLoggedIn(true);
      } else {
        Alert.alert('Registration Failed', result.message || 'Try a different username.');
      }
    } catch (error) {
      console.error('Registration error:', error);
      Alert.alert('Error', 'Could not connect to the server.');
    }
  };

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Register</Text>
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
        value={passwordInput}
        onChangeText={setPasswordInput}
      />
      <Button title="Register" onPress={handleRegister} />
    </View>
  );
}
