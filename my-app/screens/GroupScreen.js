import React, { useEffect, useState, useCallback } from 'react';
import { View, Text, FlatList, ActivityIndicator, Button, Alert } from 'react-native';
import { useNavigation, useFocusEffect } from '@react-navigation/native';
import { styles } from '../styles';
import { BASE_URL } from '../config';

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

  const handleDetails = (group) => {
    navigation.navigate('CreateGroupScreen', {
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
          <View style={{ marginTop: 10 }}>
            <Button title="Details" onPress={() => handleDetails(item)} />
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
        />
      )}
    </View>
  );
}
