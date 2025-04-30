import React, { useEffect, useState } from 'react';
import { View, Text, FlatList, TextInput, Button } from 'react-native';
import { BASE_URL } from '../config';
import AsyncStorage from '@react-native-async-storage/async-storage';
import { styles } from '../styles';
import { useNavigation } from '@react-navigation/native';


const GroupChatScreen = ({ route }) => {
  const { groupId, groupName } = route.params;
  const [username, setUsername] = useState('');
  const [messages, setMessages] = useState([]);
  const [newMessage, setNewMessage] = useState('');
  const navigation = useNavigation();


  useEffect(() => {
    loadUsername();
    fetchMessages();
    const interval = setInterval(fetchMessages, 3000);
    return () => clearInterval(interval);
  }, []);

  const loadUsername = async () => {
    const savedUser = await AsyncStorage.getItem('username');
    if (savedUser) setUsername(savedUser);
  };

  const fetchMessages = async () => {
    try {
      const res = await fetch(`${BASE_URL}/chat/list?group_id=${groupId}`);
      const data = await res.json();
      if (Array.isArray(data)) setMessages(data);
    } catch (error) {
      console.error("Fetch error:", error);
    }
  };

  const sendMessage = async () => {
    if (!newMessage.trim()) return;
    try {
      const res = await fetch(`${BASE_URL}/chat/send`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          group_id: groupId,
          username,
          content: newMessage,
        }),
      });
      const data = await res.json();
      if (data.success) {
        setNewMessage('');
        fetchMessages();
      }
    } catch (error) {
      console.error("Send error:", error);
    }
  };

  return (
    <View style={[styles.screen, { paddingHorizontal: 10 }]}>
      <View style={{ flexDirection: 'row', alignItems: 'center', marginBottom: 10 }}>
        <Text style={[styles.title, { flex: 1, textAlign: 'center' }]}>{groupName}</Text>
      </View>
    
      <FlatList
        data={messages}
        keyExtractor={(item) => item.id.toString()}
        contentContainerStyle={{ flexGrow: 1 }}
        renderItem={({ item }) => (
          <Text style={{ marginBottom: 5 }}>
            <Text style={{ fontWeight: 'bold' }}>{item.username}: </Text>
            {item.content}
          </Text>
        )}
      />

      <View style={{ flexDirection: 'row', alignItems: 'center', paddingTop: 10 }}>
        <TextInput
          value={newMessage}
          onChangeText={setNewMessage}
          placeholder="Type a message..."
          style={{
            flex: 1,
            borderWidth: 1,
            borderColor: '#ccc',
            borderRadius: 5,
            paddingHorizontal: 10,
            paddingVertical: 8,
            marginBottom: 5,
            marginRight: 10,
            fontSize: 16
          }}
        />
        <View style={{ height: 40, justifyContent: 'center' }}>
          <Button title="Send" onPress={sendMessage} />
        </View>
      </View>

    </View>
  );
};

export default GroupChatScreen;
