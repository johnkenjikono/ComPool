import React, { useState, useEffect } from 'react';
import { View, Text, Button } from 'react-native';
import AsyncStorage from '@react-native-async-storage/async-storage';
import { NavigationContainer } from '@react-navigation/native';
import { createBottomTabNavigator } from '@react-navigation/bottom-tabs';
import { createNativeStackNavigator } from '@react-navigation/native-stack';

import { styles } from './styles';

// Screens
import HomeScreen from './screens/HomeScreen';
import AboutScreen from './screens/AboutScreen';
import LoginScreen from './screens/LoginScreen';
import RegisterScreen from './screens/RegisterScreen';
import GroupScreen from './screens/GroupScreen';
import CreateGroupScreen from './screens/CreateGroupScreen';
import GroupChatScreen from './screens/GroupChatScreen';



const Tab = createBottomTabNavigator();
const GroupStack = createNativeStackNavigator();

function GroupStackScreen({ username }) {
  return (
    <GroupStack.Navigator screenOptions={{ headerShown: false }}>
      <GroupStack.Screen name="GroupsList">
        {() => <GroupScreen username={username} />}
      </GroupStack.Screen>
      <GroupStack.Screen name="CreateGroupScreen">
        {() => <CreateGroupScreen username={username} />}
      </GroupStack.Screen>
      <GroupStack.Screen name="GroupChat" component={GroupChatScreen} />
    </GroupStack.Navigator>
  );
}

export default function App() {
  const [isLoggedIn, setIsLoggedIn] = useState(false);
  const [username, setUsername] = useState('');

  // Load login state from AsyncStorage on app start
  useEffect(() => {
    const loadLogin = async () => {
      const savedUsername = await AsyncStorage.getItem('username');
      if (savedUsername) {
        setUsername(savedUsername);
        setIsLoggedIn(true);
      }
    };
    loadLogin();
  }, []);

  // Login handler
  const handleLogin = async (user) => {
    await AsyncStorage.setItem('username', user);
    setUsername(user);
    setIsLoggedIn(true);
  };

  // Logout handler
  const handleLogout = async () => {
    await AsyncStorage.removeItem('username');
    setUsername('');
    setIsLoggedIn(false);
  };

  return (
    <NavigationContainer>
      {/* App-wide custom header */}
      <View style={styles.header}>
        <Text style={styles.title}>ComPool</Text>
        <Text style={styles.slogan}>Pool Money and Compete!</Text>
        {isLoggedIn && (
          <Text style={[styles.slogan, { fontSize: 12, marginTop: 4, marginBottom: -12 }]}>Welcome, {username}</Text>
        )}
      </View>

      {/* Tab navigation */}
      <Tab.Navigator>
        <Tab.Screen name="Home" component={HomeScreen} />
        <Tab.Screen name="About" component={AboutScreen} />

        {!isLoggedIn && (
          <>
            <Tab.Screen name="Login">
              {() => <LoginScreen setIsLoggedIn={setIsLoggedIn} setUsername={handleLogin} />}
            </Tab.Screen>
            <Tab.Screen name="Register">
              {() => <RegisterScreen setIsLoggedIn={setIsLoggedIn} setUsername={handleLogin} />}
            </Tab.Screen>
          </>
        )}

        {isLoggedIn && (
          <>
            <Tab.Screen name="Groups">
              {() => <GroupStackScreen username={username} />}
            </Tab.Screen>
            <Tab.Screen name="Logout">
              {() => (
                <View style={styles.screen}>
                  <Text style={styles.title}>Are you sure you want to log out?</Text>
                  <Button title="Log Out" onPress={handleLogout} />
                </View>
              )}
            </Tab.Screen>
          </>
        )}
      </Tab.Navigator>
    </NavigationContainer>
  );
}
