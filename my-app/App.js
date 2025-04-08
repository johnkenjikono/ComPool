import React from 'react';
import { View, Text, StyleSheet } from 'react-native';
import { NavigationContainer } from '@react-navigation/native';
import { createBottomTabNavigator } from '@react-navigation/bottom-tabs';
import {styles} from "./styles"

// Simple screens
function HomeScreen() {
  return (
    <View style={styles.screen}>
      <Text>The easiest way to manage shared money among friends. No stress. No confusion. Just seamless group finances.</Text>
    </View>
  );
}

function AboutScreen() {
  return (
    <View style={styles.screen}>
      <Text>This is the About Page.</Text>
    </View>
  );
}

function ContactScreen() {
  return (
    <View style={styles.screen}>
      <Text>Contact Us at: compool@example.com</Text>
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
