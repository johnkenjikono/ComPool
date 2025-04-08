import React, { useState } from 'react';
import { View, Text } from 'react-native';
import { NavigationContainer } from '@react-navigation/native';
import { createBottomTabNavigator } from '@react-navigation/bottom-tabs';
import { createNativeStackNavigator } from '@react-navigation/native-stack';

import { styles } from './styles'; // Shared global styles

// Screens
import HomeScreen from './screens/HomeScreen';
import AboutScreen from './screens/AboutScreen';
import LoginScreen from './screens/LoginScreen';
import RegisterScreen from './screens/RegisterScreen';
// import ContactUsScreen from './screens/ContactUsScreen'; removed contact screen will be used later
import GroupScreen from './screens/GroupScreen';
import CreateGroupScreen from './screens/CreateGroupScreen';

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
    </GroupStack.Navigator>
  );
}

export default function App() {
  const [isLoggedIn, setIsLoggedIn] = useState(false);
  const [username, setUsername] = useState('');

  return (
    <NavigationContainer>
      <View style={styles.header}>
        <Text style={styles.title}>ComPool</Text>
        <Text style={styles.slogan}>Pool Money and Compete!</Text>
      </View>

      <Tab.Navigator>
        <Tab.Screen name="Home" component={HomeScreen} />
        <Tab.Screen name="About" component={AboutScreen} />

        {!isLoggedIn && (
          <>
            <Tab.Screen name="Login">
              {() => <LoginScreen setIsLoggedIn={setIsLoggedIn} setUsername={setUsername} />}
            </Tab.Screen>
            <Tab.Screen name="Register">
              {() => <RegisterScreen setIsLoggedIn={setIsLoggedIn} setUsername={setUsername} />}
            </Tab.Screen>
          </>
        )}

        {isLoggedIn && (
          <Tab.Screen name="Groups">
            {() => <GroupStackScreen username={username} />}
          </Tab.Screen>
        )}
      </Tab.Navigator>
    </NavigationContainer>
  );
}
