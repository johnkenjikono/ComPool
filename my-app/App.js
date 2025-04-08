import React from 'react';
import { View, Text, StyleSheet } from 'react-native';
import { NavigationContainer } from '@react-navigation/native';
import { createBottomTabNavigator } from '@react-navigation/bottom-tabs';
import {styles} from "./styles"
import AboutScreen from './AboutScreen';
import HomeScreen from './HomeScreen';


function ContactScreen() {
  return (
    <View style={styles.screen}>
      <Text>Contact Us</Text>
    </View>
  );
}

// Tab Navigator
const Tab = createBottomTabNavigator();

export default function App() {
  return (
    <NavigationContainer>
      <View style={styles.header}>
        <Text style={styles.title}>ComPool</Text>
        <Text style={styles.slogan}>Pool Money and Compete!</Text>
      </View>

      <Tab.Navigator>
        <Tab.Screen name="Home" component={HomeScreen} />
        <Tab.Screen name="About" component={AboutScreen} />
        <Tab.Screen name="Contact Us" component={ContactScreen} />
      </Tab.Navigator>
    </NavigationContainer>
  );
}
