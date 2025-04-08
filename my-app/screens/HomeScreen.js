import React from 'react';
import { View, Text, Image, StyleSheet, ScrollView } from 'react-native';
import {styles} from "../styles"

export default function HomeScreen() {
  return (
    <ScrollView contentContainerStyle={styles.container}>
      {/* Logo & Slogan */}
      <View style={styles.logoContainer}>
        <Image source={require('../assets/Logo3.png')} style={styles.logo} />
        <Text style={styles.slogan}>Pool Money and Compete!</Text>
      </View>

      {/* Description */}
      <Text style={styles.subtitle}>
        The easiest way to manage shared money among friends. No stress. No
        confusion. Just seamless group finances.
      </Text>
      <Image
        source={require('../assets/gfmi-tokenization.jpg-removebg-preview.png')}
        style={styles.mainImage}
      />

      {/* How It Works */}
      <Text style={styles.sectionTitle}>How It Works</Text>
      <View style={styles.stepsContainer}>
        <View style={styles.step}>
          <Image source={require('../assets/teamwork.png')} style={styles.stepIcon} />
          <Text style={styles.stepTitle}>Step 1</Text>
          <Text>Create a Pool</Text>
        </View>
        <View style={styles.step}>
          <Image source={require('../assets/add-group.png')} style={styles.stepIcon} />
          <Text style={styles.stepTitle}>Step 2</Text>
          <Text>Invite Friends</Text>
        </View>
        <View style={styles.step}>
          <Image source={require('../assets/soccer-player.png')} style={styles.stepIcon} />
          <Text style={styles.stepTitle}>Step 3</Text>
          <Text>Contribute & Compete</Text>
        </View>
      </View>

      {/* Features */}
      <Text style={styles.sectionTitle}>Why Choose ComPool?</Text>
      <View style={styles.bulletList}>
        <Text>✅ Secure Money Pooling</Text>
        <Text>✅ Track Contributions in Real-Time</Text>
        <Text>✅ Set Rules for Payouts</Text>
        <Text>✅ Compete & Earn Rewards</Text>
        <Text>✅ No Hidden Fees</Text>
        <Text>✅ Free for Android, iPhone, and Web</Text>
      </View>

      {/* Footer */}
      <View style={styles.footer}>
        <Text>© 2025 ComPool. All rights reserved.</Text>
        <Text>
          This app was designed and published as part of COMP 333 at Wesleyan.
        </Text>
      </View>
    </ScrollView>
  );
}
