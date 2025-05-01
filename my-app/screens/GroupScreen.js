import React, { useEffect, useState, useCallback } from 'react';
import { View, Text, FlatList, ActivityIndicator, Button, Alert } from 'react-native';
import { useNavigation, useFocusEffect } from '@react-navigation/native';
import { styles } from '../styles';
import { BASE_URL } from '../config';

export default function GroupScreen({ username }) {
  const [groups, setGroups] = useState([]);
  const [loading, setLoading] = useState(true);
  const [balance, setBalance] = useState(null); // ✅ NEW STATE
  const navigation = useNavigation();

  // ✅ Fetch balance on mount
  useEffect(() => {
    const fetchBalance = async () => {
      try {
        const res = await fetch(`${BASE_URL}/user/balance?username=${username}`);
        const data = await res.json();
        setBalance(data.balance);
      } catch (err) {
        console.error('Error fetching balance:', err);
        setBalance(null);
      }
    };
    fetchBalance();
  }, []);

  useFocusEffect(
    useCallback(() => {
      fetchGroups();
      fetchBalance();
    }, [])
  );

  const handleDetails = (group) => {
    navigation.navigate('CreateGroupScreen', {
      editMode: true,
      group,
    });
  };

  const fetchBalance = async () => {
    try {
      const res = await fetch(`${BASE_URL}/user/balance?username=${username}`);
      const data = await res.json();
      setBalance(data.balance);
    } catch (err) {
      console.error('Error fetching balance:', err);
    }
  };

  const fetchGroups = async () => {
    try {
      const response = await fetch(`${BASE_URL}/group/list`);
      const data = await response.json();
  
      // Only include groups where current user is a member
      const filtered = data.filter(group =>
        group.members.split(',').includes(username)
      );
  
      setGroups(filtered);
    } catch (error) {
      console.error('Error fetching groups:', error);
    } finally {
      setLoading(false);
    }
  };
  
  const renderItem = ({ item }) => {
    const isMember = item.members.split(',').includes(username);
    return (
      <View style={styles.card}>
        <Text style={styles.title}>{item.group_name}</Text>
        <Text style={styles.description}>Creator: {item.username}</Text>
        <Text style={styles.description}>Size: {item.group_size}</Text>
        <Text style={styles.description}>Members: {item.members}</Text>
  
        {isMember && (
          <View style={{ flexDirection: 'row', justifyContent: 'space-between', marginTop: 10 }}>
            <View style={{ flex: 1, marginRight: 5 }}>
              <Button title="Details" onPress={() => handleDetails(item)} />
            </View>
            <View style={{ flex: 1 }}>
              <Button
                title="Group Chat"
                onPress={() => navigation.navigate('GroupChat', { groupId: item.id, groupName: item.group_name })}
              />
            </View>
          </View>
        )}
      </View>
    );
  };
  

  return (
    <View style={styles.container}>
      {/* ✅ USER BALANCE DISPLAY */}
      <Text style={styles.title}>
      My Balance: {balance !== null ? `$${Number(balance).toFixed(2)}` : 'Loading...'}
      </Text>


      {/* Add Create Group button at the top */}
      <View style={{ marginVertical: 10 }}>
        <Button
          title="Create New Group"
          onPress={() => navigation.navigate('CreateGroupScreen')}
        />
      </View>

      {loading ? (
        <ActivityIndicator size="large" />
      ) : (
        <FlatList
          data={groups}
          keyExtractor={(item) => item.id.toString()}
          renderItem={renderItem}
          contentContainerStyle={{ paddingBottom: 100 }}
        />
      )}
    </View>
  );
}
