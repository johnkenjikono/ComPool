import React, { useEffect, useState } from 'react';
import { View, Text, TextInput, Button, Alert, KeyboardAvoidingView, Platform } from 'react-native';
import { useNavigation, useRoute } from '@react-navigation/native';
import DropDownPicker from 'react-native-dropdown-picker';
import { styles } from '../styles';
import { BASE_URL } from '../config';

export default function CreateGroupScreen({ username }) {
  const navigation = useNavigation();
  const route = useRoute();

  // Check if we are editing an existing group (passed via route params)
  const isEdit = route.params?.editMode || false;
  const groupData = route.params?.group || null;

  // State for form fields
  const [groupName, setGroupName] = useState(groupData?.group_name || '');
  const [groupSize, setGroupSize] = useState(groupData?.group_size?.toString() || '');

  // State for dropdown (selecting members)
  const [allUsers, setAllUsers] = useState([]); // all users except current
  const [selectedMembers, setSelectedMembers] = useState([]); // selected in dropdown
  const [open, setOpen] = useState(false); // dropdown open/closed
  const [dropdownItems, setDropdownItems] = useState([]); // dropdown choices

  /**
   * Fetch all users from the backend, excluding the current user.
   * If in edit mode, pre-select the current group members (excluding self).
   */
  useEffect(() => {
    const fetchUsers = async () => {
      try {
        const response = await fetch(`${BASE_URL}/user/list`); // Replace IP
        const users = await response.json();
        const otherUsers = users.filter(user => user.username !== username);
        setDropdownItems(otherUsers.map(user => ({
          label: user.username,
          value: user.username
        })));
        setAllUsers(otherUsers);

        if (groupData?.members) {
          const nonCreatorMembers = groupData.members.split(',').filter(name => name !== username);
          setSelectedMembers(nonCreatorMembers);
        }
      } catch (error) {
        console.error('Failed to fetch users:', error);
      }
    };
    fetchUsers();
  }, []);

  /**
   * Handles both group creation and update.
   * Validates input fields, then sends payload to backend.
   */
  const handleSubmit = async () => {
    const size = parseInt(groupSize);

    // Basic field validation
    if (!groupName || isNaN(size) || size < 1) {
      Alert.alert('Invalid Input', 'Please enter a valid group name and numeric group size.');
      return;
    }

    // Ensure the number of selected members matches size - 1 (since creator is always included)
    if (selectedMembers.length !== size - 1) {
      Alert.alert('Invalid Member Selection', `You must select exactly ${size - 1} other members.`);
      return;
    }

    // Always include current user as a member
    const fullMembers = [username, ...selectedMembers];

    const payload = {
      group_name: groupName,
      group_size: size,
      username,
      members: fullMembers
    };

    // Choose endpoint based on mode
    const url = isEdit
        ? `${BASE_URL}/group/update?id=${groupData.id}`
        : `${BASE_URL}/group/create`;


    try {
      const response = await fetch(url, {
        method: 'POST', // Note: using POST even for updates to match your backend
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      });

      const result = await response.json();

      if (result.success) {
        Alert.alert('Success', isEdit ? 'Group updated successfully.' : 'Group created successfully.');
        navigation.goBack(); // Return to group screen after success
      } else {
        Alert.alert('Error', 'Operation failed.');
      }
    } catch (error) {
      console.error('Submit error:', error);
      Alert.alert('Error', 'Something went wrong.');
    }
  };

  return (
    <KeyboardAvoidingView
      behavior={Platform.OS === 'ios' ? 'padding' : 'height'}
      style={styles.container}
    >
      {/* Group Name Input */}
      <Text style={styles.label}>Group Name:</Text>
      <TextInput
        style={styles.input}
        value={groupName}
        onChangeText={setGroupName}
        placeholder="Enter group name"
      />

      {/* Group Size Input */}
      <Text style={styles.label}>Group Size:</Text>
      <TextInput
        style={styles.input}
        value={groupSize}
        onChangeText={setGroupSize}
        placeholder="Enter total group size"
        keyboardType="numeric"
      />

      {/* Dropdown for members (not including self) */}
      <Text style={styles.label}>Select {groupSize ? parseInt(groupSize) - 1 : 0} Members:</Text>
      <DropDownPicker
        open={open}
        value={selectedMembers}
        items={dropdownItems}
        setOpen={setOpen}
        setValue={setSelectedMembers}
        setItems={setDropdownItems}
        multiple={true}
        min={0}
        max={groupSize ? parseInt(groupSize) - 1 : 0}
        placeholder="Select group members"
        style={{ marginBottom: 20 }}
      />

      {/* Back and Submit Buttons */}
      <View style={{ flexDirection: 'row', justifyContent: 'space-between' }}>
        <View style={{ flex: 1, marginRight: 8 }}>
          <Button title="Back" onPress={() => navigation.goBack()} />
        </View>
        <View style={{ flex: 1 }}>
          <Button
            title={isEdit ? 'Update Group' : 'Create Group'}
            onPress={handleSubmit}
          />
        </View>
      </View>
    </KeyboardAvoidingView>
  );
}
