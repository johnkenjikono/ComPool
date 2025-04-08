import React, { useEffect, useState } from 'react';
import { View, Text, FlatList, ActivityIndicator, Button, Alert } from 'react-native';
import { useNavigation } from '@react-navigation/native';
import { styles } from '../styles';
import { BASE_URL } from '../config'; // Centralized base URL
import { useFocusEffect } from '@react-navigation/native';
import { useCallback } from 'react';

export default function GroupScreen({ username }) {
  const [groups, setGroups] = useState([]);
  const [loading, setLoading] = useState(true);
  const navigation = useNavigation();

  useFocusEffect(
    useCallback(() => {
      fetchGroups();
    }, [])
  );
  

  const fetchGroups = async () => {
    try {
      const response = await fetch(`${BASE_URL}/group/list`);
      const data = await response.json();
      setGroups(data);
    } catch (error) {
      console.error('Error fetching groups:', error);
    } finally {
      setLoading(false);
    }
  };

  const confirmDelete = (groupId) => {
    Alert.alert(
      'Delete Group',
      'Are you sure you want to delete this group?',
      [
        { text: 'Cancel', style: 'cancel' },
        {
          text: 'Delete',
          style: 'destructive',
          onPress: () => handleDelete(groupId),
        },
      ]
    );
  };

  const handleDelete = async (groupId) => {
    try {
      const response = await fetch(`${BASE_URL}/group/delete?id=${groupId}`, {
        method: 'DELETE',
      });
      const result = await response.json();
      if (result.success) {
        Alert.alert('Group deleted');
        fetchGroups(); // refresh list
      } else {
        Alert.alert('Error', 'Could not delete group.');
      }
    } catch (error) {
      console.error('Delete error:', error);
      Alert.alert('Error', 'Failed to delete group.');
    }
  };

  const handleEdit = (group) => {
    navigation.navigate('CreateGroup', {
      editMode: true,
      group,
    });
  };

  const renderItem = ({ item }) => {
    const isCreator = item.username === username;
    return (
      <View style={styles.card}>
        <Text style={styles.title}>{item.group_name}</Text>
        <Text style={styles.description}>Creator: {item.username}</Text>
        <Text style={styles.description}>Size: {item.group_size}</Text>
        <Text style={styles.description}>Members: {item.members}</Text>

        {isCreator && (
          <View style={{ flexDirection: 'row', justifyContent: 'space-between', marginTop: 10 }}>
            <View style={{ flex: 1, marginRight: 5 }}>
              <Button title="Edit Group" onPress={() => handleEdit(item)} />
            </View>
            <View style={{ flex: 1 }}>
              <Button title="Delete Group" color="red" onPress={() => confirmDelete(item.id)} />
            </View>
          </View>
        )}
      </View>
    );
  };

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Groups</Text>
  
      {/* Add Create Group button at the top */}
      <View style={{ marginVertical: 10 }}>
        <Button
          title="Create New Group"
          onPress={() => navigation.navigate('CreateGroup')}
        />
      </View>
  
      {loading ? (
        <ActivityIndicator size="large" />
      ) : (
        <FlatList
          data={groups}
          keyExtractor={(item) => item.id.toString()}
          renderItem={renderItem}
        />
      )}
    </View>
  );
  
}
