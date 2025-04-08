import React, { useEffect, useState } from 'react';
import { View, Text, FlatList, ActivityIndicator } from 'react-native';
import { styles } from '../styles';

export default function GroupScreen({ username }) {
  const [groups, setGroups] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetchGroups();
  }, []);

  const fetchGroups = async () => {
    try {
      const response = await fetch('http:/172.21.198.34/index.php/group/list'); // replace with your actual IP
      const data = await response.json();
      setGroups(data);
    } catch (error) {
      console.error('Error fetching groups:', error);
    } finally {
      setLoading(false);
    }
  };

  const renderItem = ({ item }) => (
    <View style={styles.card}>
      <Text style={styles.title}>{item.group_name}</Text>
      <Text style={styles.description}>Creator: {item.username}</Text>
      <Text style={styles.description}>Size: {item.group_size}</Text>
      <Text style={styles.description}>Members: {item.members}</Text>
    </View>
  );

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Groups</Text>
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
