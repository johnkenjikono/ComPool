import React from 'react';
import { View, Text, Image, StyleSheet, ScrollView } from 'react-native';
import {styles} from "../styles"

export default function AboutScreen() {
  return (
    <ScrollView contentContainerStyle={styles.container}>
      <Text style={styles.title}>Meet the Team!</Text>

      <View style={styles.member}>
        <Image
          style={styles.photo}
          source={{ uri: 'https://media.licdn.com/dms/image/v2/D4E03AQFJE-vFS-8E_g/profile-displayphoto-shrink_400_400/profile-displayphoto-shrink_400_400/0/1724359423958?e=1745452800&v=beta&t=PDEVVDLWLpEM7oaAOgTgmXYEdkdgTLj2KccO338GG8A' }}
        />
        <Text style={styles.name}>Cory Reavy - Co-founder</Text>
        <Text style={styles.description}>
          First through the breach is Cory Reavy, a striking young savant from Mamaroneck, New York...
        </Text>
      </View>

      <View style={styles.member}>
        <Image
          style={styles.photo}
          source={{ uri: 'https://media.licdn.com/dms/image/v2/D4E03AQE22aFt5bm_qw/profile-displayphoto-shrink_400_400/profile-displayphoto-shrink_400_400/0/1698604488440?e=1745452800&v=beta&t=UnFcZlSU0ji6QwxMp6gBTwsIDsXtJWrg7FszpaTCQSk' }}
        />
        <Text style={styles.name}>Pierce - Co-founder</Text>
        <Text style={styles.description}>
          Hailing from Rye, New York, Pierce Buckner Wolfson enjoys eating, working out, and wearing his glasses...
        </Text>
      </View>

      <View style={styles.member}>
        <Image
          style={styles.photo}
          source={{ uri: 'https://media.licdn.com/dms/image/v2/D4E03AQHHAiydJds5zw/profile-displayphoto-shrink_400_400/profile-displayphoto-shrink_400_400/0/1685411749125?e=1745452800&v=beta&t=KUK8oPr7QaOc8BSdU09sF9CzJQE9WOQ8TijXpt_j-VA' }}
        />
        <Text style={styles.name}>Kenji - Senior Intern</Text>
        <Text style={styles.description}>
          Kenji Kono also grew up in Mamaroneck, New York... Management congratulates Kenji on his promotion.
        </Text>
      </View>

      <View style={styles.footer}>
        <Text>© 2025 ComPool. All rights reserved.</Text>
        <Text style={styles.footerNote}>
          This site was designed as part of COMP 333 at Wesleyan University.
        </Text>
      </View>
    </ScrollView>
  );
}
