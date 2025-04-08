import React from 'react';
import { View, Text, TextInput, Button, Alert, StyleSheet, ScrollView, Image } from 'react-native';
import {styles} from "./styles"

const ContactUs = () => {
  const handleSubmit = (event) => {
    event.preventDefault();
    Alert.alert('Backend is not set up yet!');
  };

  return (
    <ScrollView style={styles.container}>

      {/* Contact Us Form */}
      <Text style={styles.title}>Contact Us</Text>
      <Text style={styles.description}>
        Have questions, feedback, or need support? Reach out to us, and we'll get back to you as soon as possible!
      </Text>

      <View style={styles.form}>
        <Text style={styles.label}>Name:</Text>
        <TextInput style={styles.input} placeholder="Your Name" />

        <Text style={styles.label}>Email:</Text>
        <TextInput style={styles.input} placeholder="Your Email" keyboardType="email-address" />

        <Text style={styles.label}>Subject:</Text>
        <TextInput style={styles.input} placeholder="Subject" />

        <Text style={styles.label}>Message:</Text>
        <TextInput
          style={styles.textArea}
          placeholder="Your Message"
          multiline
          numberOfLines={4}
        />

        <Button title="Send Message" onPress={handleSubmit} />
      </View>

      {/* Footer */}
      <View style={styles.footer}>
        <Text>&copy; 2025 ComPool. All rights reserved.</Text>
        <Text>This site was designed and published as part of the COMP 333 Software Engineering class at Wesleyan University. This is an exercise.</Text>
      </View>
    </ScrollView>
  );
};


export default ContactUs;
``