import React, { useState } from 'react';
import { View, Text } from 'react-native';
import { NavigationContainer } from '@react-navigation/native';
import { createBottomTabNavigator } from '@react-navigation/bottom-tabs';
import { styles } from './styles';

import HomeScreen from './screens/HomeScreen';
import AboutScreen from './screens/AboutScreen';
import ContactUsScreen from './screens/ContactUsScreen';
import LoginScreen from './screens/LoginScreen';
// import GroupScreen from './screens/GroupScreen'; // coming next!

const Tab = createBottomTabNavigator();

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
        <Tab.Screen name="Contact" component={ContactUsScreen} />
        <Tab.Screen name="Login">
          {() => <LoginScreen setIsLoggedIn={setIsLoggedIn} setUsername={setUsername} />}
        </Tab.Screen>

        {isLoggedIn && (
          <Tab.Screen name="Groups">
            {() => <GroupScreen username={username} />}
          </Tab.Screen>
        )}
      </Tab.Navigator>
    </NavigationContainer>
  );
}
