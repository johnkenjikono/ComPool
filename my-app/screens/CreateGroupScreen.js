import React, { useEffect, useState } from 'react';
import { View, Text, TextInput, Button, Alert, KeyboardAvoidingView, Platform } from 'react-native';
import { useNavigation, useRoute } from '@react-navigation/native';
import DropDownPicker from 'react-native-dropdown-picker';
import { styles } from '../styles';

export default function CreateGroupScreen({ username }) {
  const navigation = useNavigation();
  const route = useRoute();
  const isEdit = route.params?.editMode || false;
  const groupData = route.params?.group || null;

  // Form state
  const [groupName, setGroupName] = useState(groupData?.group_name || '');
  const [groupSize, setGroupSize] = useState(groupData?.group_size?.toString() || '');

  // Dropdown state
  const [allUsers, setAllUsers] = useState([]);
  const [selectedMembers, setSelectedMembers] = useState([]);
  const [open, setOpen] = useState(false);
  const [dropdownItems, setDropdownItems] = useState([]);

  // Populate users and initial selected members if editing
  useEffect(() => {
    const fetchUsers = async () => {
      try {
        const response = await fetch('http://172.21.198.34/index.php/user/list');
        const users = await response.json();
        const otherUsers = users.filter(user => user.username !== username);
        setDropdownItems(otherUsers.map(user => ({ label: user.username, value: user.username })));
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

  // Create or update group handler
  const handleSubmit = async () => {
    const size = parseInt(groupSize);

    if (!groupName || isNaN(size) || size < 1) {
      Alert.alert('Invalid Input', 'Please enter a valid group name and numeric group size.');
      return;
    }

    if (selectedMembers.length !== size - 1) {
      Alert.alert('Invalid Member Selection', `You must select exactly ${size - 1} other members.`);
      return;
    }

    const fullMembers = [username, ...selectedMembers];
    const payload = {
      group_name: groupName,
      group_size: size,
      username,
      members: fullMembers
    };

    const url = isEdit
      ? `http://172.21.198.34/index.php/group/update?id=${groupData.id}`
      : 'http://172.21.198.34/index.php/group/create';

    try {
      const response = await fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      });
      const result = await response.json();
      if (result.success) {
        Alert.alert('Success', isEdit ? 'Group updated successfully.' : 'Group created successfully.');
        navigation.goBack();
      } else {
        Alert.alert('Error', 'Operation failed.');
      }
    } catch (error) {
      console.error('Submit error:', error);
      Alert.alert('Error', 'Something went wrong.');
    }
  };

  return (
    <KeyboardAvoidingView behavior={Platform.OS === 'ios' ? 'padding' : 'height'} style={styles.container}>
      <Text style={styles.label}>Group Name:</Text>
      <TextInput
        style={styles.input}
        value={groupName}
        onChangeText={setGroupName}
        placeholder="Enter group name"
        editable={!isEdit}  // disables editing if in edit mode
      />

      <Text style={styles.label}>Group Size:</Text>
      <TextInput
        style={styles.input}
        value={groupSize}
        onChangeText={setGroupSize}
        placeholder="Enter total group size"
        keyboardType="numeric"
      />

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

      <View style={{ flexDirection: 'row', justifyContent: 'space-between' }}>
        <View style={{ flex: 1, marginRight: 8 }}>
          <Button title="Back" onPress={() => navigation.goBack()} />
        </View>
        <View style={{ flex: 1 }}>
          <Button title={isEdit ? 'Update Group' : 'Create Group'} onPress={handleSubmit} />
        </View>
      </View>
    </KeyboardAvoidingView>
  );
}
