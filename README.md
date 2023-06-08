# Registration Preschools API Extension

This is a WordPress plugin that extends the Registration Preschools API with custom post types and custom fields.

## Installation

1. Download the plugin files and extract them.
2. Upload the plugin folder to the `/wp-content/plugins/` directory.
3. Activate the plugin through the 'Plugins' menu in WordPress.

## Usage

1. Schools can be added by simply following the same steps usually used for publishing normal blog posts.
2. There is also an import/export page available for importing and exporting schools' information in bulk using a CSV file.
3. The database folder contains imported sql file which can be deleted after importing. 

### Custom Post Type

The plugin registers a custom post type called "phila-preschools" because "preschool-registration" was too long to be used as a custom post type name. However, the permalink slug of the post type is "preschool-registration". You can create and manage preschool registrations using this post type.

### Custom Fields

The plugin adds custom fields to the "phila-preschools" post type. The following custom fields are available:

- Preschool Name
- Address
- Registration Time
- Location Accepting Registrations
- Weekday Timings (for each weekday)

### REST API Endpoints

The plugin adds the following REST API endpoints:

- **GET** `/wp/v2/preschool-registration/{id}` - Retrieves the details of a specific preschool registration by ID. Example response:
  ```json
  {
    "id": 1,
    "preschool_name": "ABC Preschool",
    "address": "123 Main St, City",
    "registration_time": "10:00 AM - 12:00 PM",
    "location_accepting_registrations": "Main Location",
    "weekday_timings": {
      "monday": "8:00 AM - 4:00 PM",
      "tuesday": "8:00 AM - 4:00 PM",
      "wednesday": "8:00 AM - 4:00 PM",
      "thursday": "8:00 AM - 4:00 PM",
      "friday": "8:00 AM - 4:00 PM",
      "saturday": "",
      "sunday": ""
    }
  }

- **GET** `/wp/v2/preschool-registration` - Retrieves a list of all preschool registrations. Example response:
[
  {
    "id": 1,
    "preschool_name": "ABC Preschool",
    "address": "123 Main St, City",
    "registration_time": "10:00 AM - 12:00 PM",
    "location_accepting_registrations": "Main Location",
    "weekday_timings": {
      "monday": "8:00 AM - 4:00 PM",
      "tuesday": "8:00 AM - 4:00 PM",
      "wednesday": "8:00 AM - 4:00 PM",
      "thursday": "8:00 AM - 4:00 PM",
      "friday": "8:00 AM - 4:00 PM",
      "saturday": "",
      "sunday": ""
    }
  },
  {
    "id": 2,
    "preschool_name": "XYZ Preschool",
    "address": "456 Elm St, City",
    "registration_time": "9:00 AM - 1:00 PM",
    "location_accepting_registrations": "Branch Location",
    "weekday_timings": {
      "monday": "9:00 AM - 3:00 PM",
      "tuesday": "9:00 AM - 3:00 PM",
      "wednesday": "9:00 AM - 3:00 PM",
      "thursday": "9:00 AM - 3:00 PM",
      "friday": "9:00 AM - 3:00 PM",
      "saturday": "",
      "sunday": ""
    }
  }
]

### License
This project is licensed under the GNU General Public License (GPL) License - (its just a dummy line)