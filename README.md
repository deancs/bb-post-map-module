# Beaver Builder Posts Map Module

### Overview
Display PIPs on a Google Map based on the geo-location of a posts custom field, clicking on the PIP
displays a pop-up with title and featured images plus a call-to-action to view the post

### Configuration

Install as a standard Wordpress Plugin

### Implementation

Enable the BB Module through site Settings \ Page Builder in WP Admin

### Known Limitations and bugs

- Currently only ACF GoogleMap field type is supported for specifying the posts geolocation
- Map may not render after editing the settings in the PageBuilder, although once published the refreshed page displays correctly
- The overall architecture for passing the list of posts geo-locations to the frontend is not as elegant as I would like but it works


