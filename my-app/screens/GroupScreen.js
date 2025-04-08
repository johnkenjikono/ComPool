import React, { useEffect, useState } from 'react';
import { View, Text, FlatList, ActivityIndicator } from 'react-native';
import { styles } from '../styles'; // Shared stylesheet for consistent design

// This screen fetches and displays all groups from the backend.
// It uses FlatList for efficient rendering of dynamic lists.
export default function GroupScreen({ username }) {
  const [groups, setGroups] = useState([]);         // Stores group data from the backend
  const [loading, setLoading] = useState(true);     // Used to show a loading spinner while fetching data

  // Runs once on component mount
  useEffect(() => {
    fetchGroups();
  }, []);

  // Fetches group data from REST API
  const fetchGroups = async () => {
    try {
      // NOTE: Replace with your actual IP if it changes
      const response = await fetch('http://172.21.198.34/index.php/group/list');
      const data = await response.json();
      setGroups(data); // Store group data in state
    } catch (error) {
      console.error('Error fetching groups:', error);
    } finally {
      setLoading(false); // Hide loading spinner after fetch completes
    }
  };

  // Renders each group card with basic info
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

      {/* Show spinner while loading */}
      {loading ? (
        <ActivityIndicator size="large" />
      ) : (
        // Renders all groups once loading is complete
        <FlatList
          data={groups}
          keyExtractor={(item) => item.id.toString()}
          renderItem={renderItem}
        />
      )}
    </View>
  );
}
