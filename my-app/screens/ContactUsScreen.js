import React from 'react';
import { View, Text, TextInput, Button, Alert, StyleSheet, ScrollView } from 'react-native';
import { styles } from "../styles"; // Shared styles across the app

// ContactUs screen includes a basic form for feedback or support inquiries.
// NOTE: This is a frontend-only mockup — the backend isn't wired up to process these messages yet.
const ContactUs = () => {
  // Displays a placeholder alert when the user hits "Send Message"
  const handleSubmit = (event) => {
    event.preventDefault();
    Alert.alert('Backend is not set up yet!');
  };

  return (
    <ScrollView style={styles.container}>
      {/* Page title and intro description */}
      <Text style={styles.title}>Contact Us</Text>
      <Text style={styles.description}>
        Have questions, feedback, or need support? Reach out to us, and we'll get back to you as soon as possible!
      </Text>

      {/* Contact Form */}
      <View style={styles.form}>
        {/* Name field */}
        <Text style={styles.label}>Name:</Text>
        <TextInput style={styles.input} placeholder="Your Name" />

        {/* Email field */}
        <Text style={styles.label}>Email:</Text>
        <TextInput style={styles.input} placeholder="Your Email" keyboardType="email-address" />

        {/* Subject field */}
        <Text style={styles.label}>Subject:</Text>
        <TextInput style={styles.input} placeholder="Subject" />

        {/* Message text area */}
        <Text style={styles.label}>Message:</Text>
        <TextInput
          style={styles.textArea}
          placeholder="Your Message"
          multiline
          numberOfLines={4}
        />

        {/* Submit button (non-functional for now) */}
        <Button title="Send Message" onPress={handleSubmit} />
      </View>

      {/* Footer */}
      <View style={styles.footer}>
        <Text>© 2025 ComPool. All rights reserved.</Text>
        <Text>
          This site was designed and published as part of the COMP 333 Software Engineering class at Wesleyan University. This is an exercise.
        </Text>
      </View>
    </ScrollView>
  );
};

export default ContactUs;