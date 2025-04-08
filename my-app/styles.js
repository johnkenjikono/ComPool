import { StyleSheet } from "react-native";

export const styles = StyleSheet.create({
  // Top banner shown on every screen (title + slogan)
  header: {
    paddingTop: 50,
    paddingBottom: 20,
    backgroundColor: '#1e90ff',
    alignItems: 'center',
  },

  // Used for slogan text in header or homepage
  slogan: {
    fontSize: 20,
    fontWeight: 'bold',
    textAlign: 'center',
    color: '#f0f8ff',
  },

  // Full-screen layout centered vertically and horizontally
  screen: {
    flex: 1,
    alignItems: 'center',
    justifyContent: 'center',
  },

  // General container for scroll views or full-page screens
  container: {
    padding: 20,
    backgroundColor: '#fff',
  },

  // Homepage logo area
  logoContainer: {
    alignItems: 'center',
    marginBottom: 20,
  },

  // App logo image
  logo: {
    width: 200,
    height: 100,
    resizeMode: 'contain',
  },

  // Subtitle used under headers or logos
  subtitle: {
    fontSize: 16,
    marginVertical: 20,
    textAlign: 'center',
  },

  // Large feature or banner image
  mainImage: {
    width: '100%',
    height: 200,
    resizeMode: 'contain',
    marginBottom: 30,
  },

  // Section headers like "How It Works" or "Why ComPool"
  sectionTitle: {
    fontSize: 22,
    fontWeight: 'bold',
    marginVertical: 15,
  },

  // Container for multi-step layouts on homepage
  stepsContainer: {
    flexDirection: 'row',
    justifyContent: 'space-around',
    marginBottom: 30,
  },

  // Each step box in the "How It Works" section
  step: {
    alignItems: 'center',
    width: '30%',
  },

  // Icons used inside each step
  stepIcon: {
    width: 60,
    height: 60,
    resizeMode: 'contain',
  },

  // Label for each step
  stepTitle: {
    fontWeight: 'bold',
    marginTop: 5,
  },

  // List of bullet-point benefits on homepage
  bulletList: {
    paddingLeft: 10,
    marginBottom: 30,
  },

  // Page titles (used for login, group screen, etc.)
  title: {
    fontSize: 26,
    fontWeight: 'bold',
    marginBottom: 20,
    textAlign: 'center',
  },

  // About screen team member container
  member: {
    marginBottom: 30,
    alignItems: 'center',
  },

  // Team member profile picture
  photo: {
    width: 200,
    height: 200,
    borderRadius: 100,
    marginBottom: 10,
  },

  // Team member name
  name: {
    fontSize: 18,
    fontWeight: '600',
    marginBottom: 5,
  },

  // Text description or paragraph
  description: {
    fontSize: 16,
    marginBottom: 20,
    textAlign: 'center',
    paddingHorizontal: 10,
  },

  // Footer for About/Contact/Home screens
  footer: {
    marginTop: 40,
    borderTopWidth: 1,
    borderTopColor: '#ccc',
    paddingTop: 15,
    alignItems: 'center',
  },

  // Small footer note text
  footerNote: {
    fontSize: 12,
    textAlign: 'center',
    marginTop: 5,
    color: '#555',
  },

  // Form container (used in Contact + Login)
  form: {
    marginBottom: 20,
  },

  // Label above form input fields
  label: {
    fontSize: 16,
    marginBottom: 5,
  },

  // Text input style (used for form fields)
  input: {
    borderWidth: 1,
    borderColor: '#ccc',
    padding: 10,
    marginBottom: 15,
    borderRadius: 5,
  },

  // Multiline textarea field (e.g., message box)
  textArea: {
    borderWidth: 1,
    borderColor: '#ccc',
    padding: 10,
    marginBottom: 15,
    borderRadius: 5,
    textAlignVertical: 'top', // ensures cursor starts at top in Android
  },

  // Card used to display each group on the group list screen
  card: {
    padding: 16,
    borderWidth: 1,
    borderColor: '#ddd',
    borderRadius: 6,
    marginBottom: 12,
    backgroundColor: '#f9f9f9',
  }
});
