import React, { useState } from 'react';
import { View, Text } from 'react-native';
import { NavigationContainer } from '@react-navigation/native';
import { createBottomTabNavigator } from '@react-navigation/bottom-tabs';
import { styles } from './styles'; // Shared global styles

// Importing all screens
import HomeScreen from './screens/HomeScreen';
import AboutScreen from './screens/AboutScreen';
import ContactUsScreen from './screens/ContactUsScreen';
import LoginScreen from './screens/LoginScreen';
import GroupScreen from './screens/GroupScreen';

// Create a bottom tab navigator instance
const Tab = createBottomTabNavigator();

// Root App component
export default function App() {
  const [isLoggedIn, setIsLoggedIn] = useState(false); // Tracks login state
  const [username, setUsername] = useState('');        // Stores current logged-in user's name

  return (
    <NavigationContainer>
      {/* Static app header shown across all screens */}
      <View style={styles.header}>
        <Text style={styles.title}>ComPool</Text>
        <Text style={styles.slogan}>Pool Money and Compete!</Text>
      </View>

      {/* Bottom tab navigation */}
      <Tab.Navigator>
        {/* These screens are always visible */}
        <Tab.Screen name="Home" component={HomeScreen} />
        <Tab.Screen name="About" component={AboutScreen} />
        <Tab.Screen name="Contact" component={ContactUsScreen} />

        {/* Login screen passes state setters to update login status */}
        <Tab.Screen name="Login">
          {() => <LoginScreen setIsLoggedIn={setIsLoggedIn} setUsername={setUsername} />}
        </Tab.Screen>

        {/* Conditionally show the Groups tab only if the user is logged in */}
        {isLoggedIn && (
          <Tab.Screen name="Groups">
            {() => <GroupScreen username={username} />}
          </Tab.Screen>
        )}
      </Tab.Navigator>
    </NavigationContainer>
  );
}
