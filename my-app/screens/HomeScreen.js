import React from 'react';
import { View, Text, Image, StyleSheet, ScrollView } from 'react-native';
import { styles } from "../styles"; // Shared app-wide styling

// HomeScreen is the landing page for the app.
// It introduces the app, how it works, and its benefits.
export default function HomeScreen() {
  return (
    <ScrollView contentContainerStyle={styles.container}>
      {/* Logo and App Slogan */}
      <View style={styles.logoContainer}>
        <Image source={require('../assets/Logo3.png')} style={styles.logo} />
        <Text style={styles.slogan}>Pool Money and Compete!</Text>
      </View>

      {/* App Overview */}
      <Text style={styles.subtitle}>
        The easiest way to manage shared money among friends. No stress. No
        confusion. Just seamless group finances.
      </Text>

      {/* Main promotional image */}
      <Image
        source={require('../assets/gfmi-tokenization.jpg-removebg-preview.png')}
        style={styles.mainImage}
      />

      {/* Step-by-step Explanation */}
      <Text style={styles.sectionTitle}>How It Works</Text>
      <View style={styles.stepsContainer}>
        {/* Step 1 */}
        <View style={styles.step}>
          <Image source={require('../assets/teamwork.png')} style={styles.stepIcon} />
          <Text style={styles.stepTitle}>Step 1</Text>
          <Text>Create a Pool</Text>
        </View>

        {/* Step 2 */}
        <View style={styles.step}>
          <Image source={require('../assets/add-group.png')} style={styles.stepIcon} />
          <Text style={styles.stepTitle}>Step 2</Text>
          <Text>Invite Friends</Text>
        </View>

        {/* Step 3 */}
        <View style={styles.step}>
          <Image source={require('../assets/soccer-player.png')} style={styles.stepIcon} />
          <Text style={styles.stepTitle}>Step 3</Text>
          <Text>Contribute & Compete</Text>
        </View>
      </View>

      {/* Feature List */}
      <Text style={styles.sectionTitle}>Why Choose ComPool?</Text>
      <View style={styles.bulletList}>
        <Text>✅ Secure Money Pooling</Text>
        <Text>✅ Track Contributions in Real-Time</Text>
        <Text>✅ Set Rules for Payouts</Text>
        <Text>✅ Compete & Earn Rewards</Text>
        <Text>✅ No Hidden Fees</Text>
        <Text>✅ Free for Android, iPhone, and Web</Text>
      </View>

      {/* Footer with credit */}
      <View style={styles.footer}>
        <Text>© 2025 ComPool. All rights reserved.</Text>
        <Text>
          This app was designed and published as part of COMP 333 at Wesleyan.
        </Text>
      </View>
    </ScrollView>
  );
}
