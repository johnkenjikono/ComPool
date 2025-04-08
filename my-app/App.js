import React, { useState } from 'react';
import { View, Text } from 'react-native';
import { NavigationContainer } from '@react-navigation/native';
import { createBottomTabNavigator } from '@react-navigation/bottom-tabs';
import { createNativeStackNavigator } from '@react-navigation/native-stack';

import { styles } from './styles'; // Shared global styles

// Screens
import HomeScreen from './screens/HomeScreen';
import AboutScreen from './screens/AboutScreen';
import ContactUsScreen from './screens/ContactUsScreen';
import LoginScreen from './screens/LoginScreen';
import GroupScreen from './screens/GroupScreen';
import CreateGroupScreen from './screens/CreateGroupScreen';

// Bottom Tab Navigator
const Tab = createBottomTabNavigator();

// Stack Navigator for Groups tab (allows deeper navigation)
const GroupStack = createNativeStackNavigator();

function GroupStackScreen({ username }) {
  return (
    <GroupStack.Navigator screenOptions={{ headerShown: false }}>
      <GroupStack.Screen name="Groups">
        {() => <GroupScreen username={username} />}
      </GroupStack.Screen>
      <GroupStack.Screen name="CreateGroup">
        {() => <CreateGroupScreen username={username} />}
      </GroupStack.Screen>
    </GroupStack.Navigator>
  );
}


// App Root
export default function App() {
  const [isLoggedIn, setIsLoggedIn] = useState(false); // Whether user is logged in
  const [username, setUsername] = useState('');        // Store current user's name

  return (
    <NavigationContainer>
      {/* Static app header shown on all tabs */}
      <View style={styles.header}>
        <Text style={styles.title}>ComPool</Text>
        <Text style={styles.slogan}>Pool Money and Compete!</Text>
      </View>

      {/* Tab Navigation */}
      <Tab.Navigator>
        {/* Public tabs available to everyone */}
        <Tab.Screen name="Home" component={HomeScreen} />
        <Tab.Screen name="About" component={AboutScreen} />
        <Tab.Screen name="Contact" component={ContactUsScreen} />
        <Tab.Screen name="Login">
          {() => <LoginScreen setIsLoggedIn={setIsLoggedIn} setUsername={setUsername} />}
        </Tab.Screen>

        {/* Groups tab only appears when logged in */}
        {isLoggedIn && (
          <Tab.Screen name="Groups">
            {() => <GroupStackScreen username={username} />}
          </Tab.Screen>
        )}
      </Tab.Navigator>
    </NavigationContainer>
  );
}
