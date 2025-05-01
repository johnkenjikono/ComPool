import { OPENAI_API_KEY } from '../config';
import React, { useState } from 'react';
import { Alert } from 'react-native';
import {
  View,
  Text,
  TextInput,
  Button,
  ScrollView,
  KeyboardAvoidingView,
  Platform,
} from 'react-native';
import { styles } from '../styles';

export default function HelpDeskScreen() {
  const [input, setInput] = useState('');
  const [chat, setChat] = useState([]);

  const sendToGPT = async () => {
    const newMessage = { role: 'user', content: input };
    const updatedChat = [...chat, newMessage];
    setChat(updatedChat);
    setInput('');

    try {
      const response = await fetch('https://api.openai.com/v1/chat/completions', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          Authorization: `Bearer ${OPENAI_API_KEY}`,
        },
        body: JSON.stringify({
          model: 'gpt-3.5-turbo',
          messages: updatedChat,
        }),
      });

    const data = await response.json();
    const reply = data.choices?.[0]?.message;

    if (reply && reply.role && reply.content) {
    setChat([...updatedChat, reply]);
    } else {
    console.warn("Invalid reply from OpenAI:", data);
    Alert.alert('Error', 'Invalid response from AI. Try again later.');
    }

    } catch (error) {
      console.error('Error:', error);
    }
  };

  return (
    <KeyboardAvoidingView
      style={{ flex: 1 }}
      behavior={Platform.OS === 'ios' ? 'padding' : undefined}
    >
      <View style={{ flex: 1, padding: 10 }}>
        <ScrollView style={{ flex: 1 }} contentContainerStyle={{ paddingBottom: 80 }}>
          {chat.map((msg, i) => (
            <Text key={i} style={{ marginVertical: 5 }}>
              <Text style={{ fontWeight: 'bold' }}>{msg.role === 'user' ? 'You: ' : 'HelpBot: '}</Text>
              {msg.content}
            </Text>
          ))}
        </ScrollView>

        <TextInput
          style={[styles.input, { marginBottom: 8 }]}
          value={input}
          onChangeText={setInput}
          placeholder="Ask for help..."
        />
        <Button title="Send" onPress={sendToGPT} />
      </View>
    </KeyboardAvoidingView>
  );
}
